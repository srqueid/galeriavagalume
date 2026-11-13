<?php

namespace Hostinger\AiAssistant\Mcp\Abilities\Tools\Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    die;
}

class UpdateWidgetImage extends BaseElementorTool {
    public function register(): void {
        $this->register_ability(
            'hostinger-ai/elementor-update-widget-image',
            array(
                'label'               => __( 'Update Elementor Widget Image', 'hostinger-ai-assistant' ),
                'description'         => 'Update the image of an Elementor image widget. Use when asked to replace a picture on an Elementor page. IMPORTANT: only use an image_url that is publicly reachable and returns HTTP 200 - never a placeholder or hallucinated URL, as this causes broken (404) images. Do not invent image URLs. Never hotlink an external image straight into a widget, because external URLs can expire and break. To use an image from the web, first sideload it with `hostinger-ai/media-upload-from-url`, then pass the returned attachment id as image_id and its url as image_url. To reuse an existing image, find it with `hostinger-ai/media-search` or `hostinger-ai/media-list` and pass its image_id. Needs `post_id` from `hostinger-ai/elementor-list-pages` and `widget_id` from `hostinger-ai/elementor-get-page-structure`.',
                'category'            => $this->category,
                'input_schema'        => array(
                    'type'       => 'object',
                    'properties' => array(
                        'post_id'   => array(
                            'type'        => 'integer',
                            'description' => 'The ID of the post/page containing the widget',
                        ),
                        'widget_id' => array(
                            'type'        => 'string',
                            'description' => 'The unique ID of the image widget to update',
                        ),
                        'image_url' => array(
                            'type'        => 'string',
                            'description' => 'The new image URL. Must be a real, currently reachable URL that returns HTTP 200 (not a 404, placeholder, or made-up URL). Use a WordPress media library URL - sideload external images with `hostinger-ai/media-upload-from-url` first to avoid broken images.',
                        ),
                        'image_id'  => array(
                            'type'        => 'integer',
                            'description' => 'Optional but recommended: WordPress media library attachment ID (from `hostinger-ai/media-search`, `hostinger-ai/media-list` or `hostinger-ai/media-upload-from-url`). Using a media library image is the most reliable way to avoid 404 images. When provided, it should match image_url.',
                        ),
                        'alt_text'  => array(
                            'type'        => 'string',
                            'description' => 'Optional: Image alt text for accessibility',
                        ),
                    ),
                    'required'   => array( 'post_id', 'widget_id', 'image_url' ),
                ),
                'permission_callback' => function () {
                    return current_user_can( 'manage_options' );
                },
                'meta'                => array(
                    'show_in_rest' => true,
                    'mcp'          => array(
                        'public' => true,
                        'type'   => $this->type,
                    ),
                    'annotations'  => array(
                        'readOnlyHint'    => false,
                        'destructiveHint' => false,
                        'idempotentHint'  => true,
                        'openWorldHint'   => false,
                    ),
                ),
            )
        );
    }

    public function execute( array $input ): array {
        $post_id   = $input['post_id'];
        $widget_id = $input['widget_id'];
        $image_url = $input['image_url'];
        $image_id  = $input['image_id'] ?? null;
        $alt_text  = $input['alt_text'] ?? null;

        $result = $this->get_elementor_data( $post_id );
        if ( isset( $result['error_code'] ) ) {
            return $result;
        }

        $elementor_data = $result['data'];

        $updated = false;
        $old_url = '';

        $this->update_widget_in_tree(
            $elementor_data,
            $widget_id,
            function ( &$widget ) use ( $image_url, $image_id, $alt_text, &$updated, &$old_url ) {
                $widget_type = $widget['widgetType'] ?? '';
                if ( $widget_type !== 'image' ) {
                    return array(
                        'error_code' => 'NOT_IMAGE_WIDGET',
                        'message'    => "Widget is type '{$widget_type}', not 'image'",
                    );
                }

                $old_url = $widget['settings']['image']['url'] ?? '';

                if ( ! isset( $widget['settings']['image'] ) ) {
                    $widget['settings']['image'] = array();
                }

                $widget['settings']['image']['url'] = $image_url;

                if ( $image_id !== null ) {
                    $widget['settings']['image']['id'] = $image_id;
                }

                if ( $alt_text !== null ) {
                    $widget['settings']['image_alt'] = $alt_text;
                }

                $updated = true;

                return null;
            }
        );

        if ( ! $updated ) {
            return array(
                'success'    => false,
                'error_code' => 'WIDGET_NOT_FOUND',
                /* translators: %s: widget id  */
                'message'    => __( 'Widget with ID %s not found', 'hostinger-ai-assistant' ),
            );
        }

        $save_result = $this->save_elementor_data( $post_id, $elementor_data );
        if ( ! $save_result['success'] ) {
            return $save_result;
        }

        return array(
            'success'   => true,
            'widget_id' => $widget_id,
            'old_url'   => $old_url,
            'new_url'   => $image_url,
        );
    }
}
