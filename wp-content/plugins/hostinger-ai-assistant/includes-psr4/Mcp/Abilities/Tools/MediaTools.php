<?php

namespace Hostinger\AiAssistant\Mcp\Abilities\Tools;

use WP_REST_Attachments_Controller;
use WP_Error;
use WP_Query;

if ( ! defined( 'ABSPATH' ) ) {
    die;
}

class MediaTools extends RestEndpointTool {
    public function register(): void {
        $this->register_operations(
            array(
                'list'   => array(
                    'tool_name'   => 'hostinger-ai/media-list',
                    'label'       => __( 'List Media', 'hostinger-ai-assistant' ),
                    'description' => 'List media library items with pagination and filtering by type or date. Use when you need an attachment ID and want to browse the library; to find an item by words use `hostinger-ai/media-search`.',
                    'meta'        => array(
                        'annotations' => array(
                            'readOnlyHint'  => true,
                            'openWorldHint' => false,
                        ),
                    ),
                ),
                'get'    => array(
                    'tool_name'   => 'hostinger-ai/media-get',
                    'label'       => __( 'Get Media', 'hostinger-ai-assistant' ),
                    'description' => 'Get one media library item by ID with its URL, sizes and metadata. Use when you need the details of a known attachment. Needs `id` from `hostinger-ai/media-search` or `hostinger-ai/media-list`.',
                    'meta'        => array(
                        'annotations' => array(
                            'readOnlyHint'  => true,
                            'openWorldHint' => false,
                        ),
                    ),
                ),
                'update' => array(
                    'tool_name'   => 'hostinger-ai/media-update',
                    'label'       => __( 'Update Media', 'hostinger-ai-assistant' ),
                    'description' => 'Update the metadata of a media library item, such as its title, alt text or caption; only the fields you pass change. Use when asked to fix the alt text, title or caption of an image or file. Needs `id` from `hostinger-ai/media-search` or `hostinger-ai/media-list`.',
                    'meta'        => array(
                        'annotations' => array(
                            'readOnlyHint'    => false,
                            'destructiveHint' => false,
                            'idempotentHint'  => true,
                            'openWorldHint'   => false,
                        ),
                    ),
                ),
                'delete' => array(
                    'tool_name'   => 'hostinger-ai/media-delete',
                    'label'       => __( 'Delete Media', 'hostinger-ai-assistant' ),
                    'description' => 'Permanently delete a media library item and its file; this cannot be undone. Use when asked to remove an image or file. Needs `id` from `hostinger-ai/media-search` or `hostinger-ai/media-list`.',
                    'meta'        => array(
                        'annotations' => array(
                            'readOnlyHint'    => false,
                            'destructiveHint' => true,
                            'idempotentHint'  => true,
                            'openWorldHint'   => false,
                        ),
                    ),
                ),
            ),
            WP_REST_Attachments_Controller::class,
            '/wp/v2/media',
            'attachment'
        );

        $this->register_search_tool();
        $this->register_get_file_tool();
    }

    private function register_search_tool(): void {
        wp_register_ability(
            'hostinger-ai/media-search',
            array(
                'label'               => __( 'Search Media', 'hostinger-ai-assistant' ),
                'description'         => 'Search media library items by title, caption or description, with filtering. Use when you need the attachment ID of an existing image or file, for example for a featured image.',
                'category'            => 'hostinger-ai-assistant',
                'input_schema'        => array(
                    'type'       => 'object',
                    'properties' => array(
                        'search'     => array(
                            'type'        => 'string',
                            'description' => 'Search term to look for in media titles, captions, and descriptions',
                        ),
                        'media_type' => array(
                            'type'        => 'string',
                            'description' => 'Filter by media type (image, video, audio, application)',
                        ),
                        'mime_type'  => array(
                            'type'        => 'string',
                            'description' => 'Filter by MIME type (e.g., image/jpeg, video/mp4)',
                        ),
                        'author'     => array(
                            'type'        => 'integer',
                            'description' => 'Filter by author ID',
                        ),
                        'parent'     => array(
                            'type'        => 'integer',
                            'description' => 'Filter by parent post ID',
                        ),
                        'page'       => array(
                            'type'        => 'integer',
                            'description' => 'Page number for pagination',
                            'default'     => 1,
                        ),
                        'per_page'   => array(
                            'type'        => 'integer',
                            'description' => 'Number of items per page',
                            'default'     => 10,
                        ),
                    ),
                ),
                'execute_callback'    => array( $this, 'search_media' ),
                'permission_callback' => function () {
                    return current_user_can( 'upload_files' );
                },
                'meta'                => array(
                    'show_in_rest' => true,
                    'mcp'          => array(
                        'public' => true,
                        'type'   => 'tool',
                    ),
                    'annotations'  => array(
                        'readOnlyHint'  => true,
                        'openWorldHint' => false,
                    ),
                ),
            )
        );
    }

    private function register_get_file_tool(): void {
        wp_register_ability(
            'hostinger-ai/media-get-file',
            array(
                'label'               => __( 'Get Media File', 'hostinger-ai-assistant' ),
                'description'         => 'Get the file content (blob) of a media library item. Use when you need to read or inspect the file itself, not just its metadata. Needs `id` from `hostinger-ai/media-search` or `hostinger-ai/media-list`.',
                'category'            => 'hostinger-ai-assistant',
                'input_schema'        => array(
                    'type'       => 'object',
                    'properties' => array(
                        'id'   => array(
                            'type'        => 'integer',
                            'description' => 'The ID of the media item',
                        ),
                        'size' => array(
                            'type'        => 'string',
                            'description' => 'Optional. The size of the image to retrieve (thumbnail, medium, large, full). Defaults to full/original size.',
                        ),
                    ),
                    'required'   => array( 'id' ),
                ),
                'execute_callback'    => array( $this, 'get_media_file' ),
                'permission_callback' => function () {
                    return current_user_can( 'upload_files' );
                },
                'meta'                => array(
                    'show_in_rest' => true,
                    'mcp'          => array(
                        'public' => true,
                        'type'   => 'tool',
                    ),
                    'annotations'  => array(
                        'readOnlyHint'  => true,
                        'openWorldHint' => false,
                    ),
                ),
            )
        );
    }

    public function search_media( array $input ): WP_Error|array {
        $args = array(
            'post_type'      => 'attachment',
            'post_status'    => 'inherit',
            'posts_per_page' => isset( $input['per_page'] ) ? intval( $input['per_page'] ) : 10,
            'paged'          => isset( $input['page'] ) ? intval( $input['page'] ) : 1,
        );

        if ( ! empty( $input['search'] ) ) {
            $args['s'] = sanitize_text_field( $input['search'] );
        }

        if ( ! empty( $input['media_type'] ) ) {
            $args['post_mime_type'] = sanitize_text_field( $input['media_type'] );
        }

        if ( ! empty( $input['mime_type'] ) ) {
            $args['post_mime_type'] = sanitize_text_field( $input['mime_type'] );
        }

        if ( ! empty( $input['author'] ) ) {
            $args['author'] = intval( $input['author'] );
        }

        if ( ! empty( $input['parent'] ) ) {
            $args['post_parent'] = intval( $input['parent'] );
        }

        $query = new WP_Query( $args );

        return array(
            'data'     => $query->posts,
            'total'    => $query->found_posts,
            'pages'    => $query->max_num_pages,
            'page'     => $args['paged'],
            'per_page' => $args['posts_per_page'],
        );
    }

    public function get_media_file( array $input ): WP_Error|array {
        $id   = intval( $input['id'] );
        $size = isset( $input['size'] ) ? sanitize_text_field( $input['size'] ) : 'full';

        if ( ! $id ) {
            return new WP_Error( 'invalid_media_id', __( 'Invalid media ID', 'hostinger-ai-assistant' ), array( 'status' => 400 ) );
        }

        $file_path = get_attached_file( $id );
        if ( ! $file_path || ! file_exists( $file_path ) ) {
            return new WP_Error( 'file_not_found', __( 'File not found', 'hostinger-ai-assistant' ), array( 'status' => 404 ) );
        }

        if ( $size !== 'full' && $size !== 'original' ) {
            $meta = wp_get_attachment_metadata( $id );
            if ( isset( $meta['sizes'][ $size ]['file'] ) ) {
                $base_dir  = pathinfo( $file_path, PATHINFO_DIRNAME );
                $file_path = $base_dir . '/' . $meta['sizes'][ $size ]['file'];
            }
        }

        if ( ! file_exists( $file_path ) ) {
            return new WP_Error( 'size_not_found', __( 'Requested size not found', 'hostinger-ai-assistant' ), array( 'status' => 404 ) );
        }

        $mime_type = get_post_mime_type( $id );
        $file_data = file_get_contents( $file_path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents

        return array(
            'results'  => $file_data,
            'type'     => 'image',
            'mimeType' => $mime_type,
        );
    }
}
