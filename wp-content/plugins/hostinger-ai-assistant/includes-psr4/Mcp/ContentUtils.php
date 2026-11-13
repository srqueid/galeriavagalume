<?php

namespace Hostinger\AiAssistant\Mcp;

use Alley\WP\Block_Converter\Block;
use Alley\WP\Block_Converter\Block_Converter;
use DOMNode;
use Exception;
use WP_HTML_Tag_Processor;

if ( ! defined( 'ABSPATH' ) ) {
    die;
}

class ContentUtils {
    public const CONTENT_FORMAT_HINT = 'Send HTML or block markup; HTML is converted to blocks.';

    public static function maybe_convert_to_blocks( string $content, string $post_type ): string {
        if ( empty( $content ) || ! use_block_editor_for_post_type( $post_type ) || self::is_block_content( $content ) ) {
            return $content;
        }

        $normalize_image_block = array( self::class, 'normalize_image_block' );
        add_filter( 'wp_block_converter_block', $normalize_image_block, 10, 2 );

        try {
            return ( new Block_Converter( $content ) )->convert();
        } catch ( Exception $exception ) {
            error_log( 'MCP Block conversion error: ' . $exception->getMessage() );

            return $content;
        } finally {
            remove_filter( 'wp_block_converter_block', $normalize_image_block );
        }
    }

    /**
     * The converter keeps the <img> as sent, so attributes like width fail the editor's block
     * validation, and it drops the image of a <p> holding only an <img>. Rebuild the markup the
     * core/image save function produces.
     */
    public static function normalize_image_block( ?Block $block, DOMNode $node ): ?Block {
        if ( ! $block || $block->block_name !== 'image' ) {
            return $block;
        }

        $html = str_contains( (string) $block->content, '<img' ) ? (string) $block->content : (string) $node->C14N();
        $tags = new WP_HTML_Tag_Processor( $html );
        $href = null;

        while ( $tags->next_tag( array( 'tag_closers' => 'visit' ) ) && $tags->get_tag() !== 'IMG' ) {
            if ( $tags->get_tag() === 'A' ) {
                $href = $tags->is_tag_closer() ? null : $tags->get_attribute( 'href' );
            }
        }

        if ( $tags->get_tag() !== 'IMG' ) {
            return $block;
        }

        $attributes = array();
        $image_html = sprintf( '<img src="%s" alt="%s"', esc_url( (string) $tags->get_attribute( 'src' ) ), esc_attr( (string) $tags->get_attribute( 'alt' ) ) );

        if ( preg_match( '/\bwp-image-(\d+)\b/', (string) $tags->get_attribute( 'class' ), $matches ) ) {
            $attributes['id'] = (int) $matches[1];
            $image_html      .= ' class="wp-image-' . $attributes['id'] . '"';
        }

        $image_html .= '/>';

        if ( is_string( $href ) && $href !== '' ) {
            $attributes['linkDestination'] = 'custom';
            $image_html                    = sprintf( '<a href="%s">%s</a>', esc_url( $href ), $image_html );
        }

        if ( preg_match( '#<figcaption[^>]*>(.*?)</figcaption>#s', $html, $caption ) ) {
            $image_html .= '<figcaption class="wp-element-caption">' . trim( $caption[1] ) . '</figcaption>';
        }

        return new Block( 'image', $attributes, '<figure class="wp-block-image">' . $image_html . '</figure>' );
    }

    private static function is_block_content( string $content ): bool {
        return str_starts_with( trim( $content ), '<!-- wp:' );
    }
}
