<?php

namespace Hostinger\AiAssistant\Mcp\Abilities\Tools;

use WP_REST_Request;
use WP_REST_Revisions_Controller;

if ( ! defined( 'ABSPATH' ) ) {
    die;
}

class RevisionsTools extends RestEndpointTool {
    use AbilityResultTrait;

    public function register(): void {
        $this->register_operations(
            array(
                'list'   => array(
                    'tool_name'                  => 'hostinger-ai/revisions-list',
                    'label'                      => __( 'List Revisions', 'hostinger-ai-assistant' ),
                    'description'                => 'List the revisions of a post, page or other item. Use when asked about the edit history or to undo changes. Needs `parent` from `hostinger-ai/posts-search` or `hostinger-ai/pages-search`, and `base_api` set to the REST base of that item (e.g. `posts` or `pages`).',
                    'meta'                       => array(
                        'annotations' => array(
                            'readOnlyHint'  => true,
                            'openWorldHint' => false,
                        ),
                    ),
                    'input_schema_modifications' => array(
                        'properties' => array(
                            'base_api' => array(
                                'type'        => 'string',
                                'description' => 'REST API base for the post type (e.g., posts, pages, templates, product, etc.).',
                            ),
                            'parent'   => array(
                                'type'        => 'integer',
                                'description' => 'The parent item ID to list revisions for.',
                            ),
                        ),
                        'required'   => array( 'base_api', 'parent' ),
                    ),
                    'skip_ids'                   => true,
                ),
                'get'    => array(
                    'tool_name'                  => 'hostinger-ai/revisions-get',
                    'label'                      => __( 'Get Revision', 'hostinger-ai-assistant' ),
                    'description'                => 'Get one revision of an item with its content. Use when you need to inspect or compare an older version. Needs `parent` from `hostinger-ai/posts-search` or `hostinger-ai/pages-search`, and `base_api` set to the REST base of that item (e.g. `posts` or `pages`), and `id` from `hostinger-ai/revisions-list`.',
                    'meta'                       => array(
                        'annotations' => array(
                            'readOnlyHint'  => true,
                            'openWorldHint' => false,
                        ),
                    ),
                    'input_schema_modifications' => array(
                        'properties' => array(
                            'base_api' => array(
                                'type'        => 'string',
                                'description' => 'REST API base for the post type (e.g., posts, pages, templates, product, etc.).',
                            ),
                            'parent'   => array(
                                'type'        => 'integer',
                                'description' => 'The parent item ID.',
                            ),
                        ),
                        'required'   => array( 'base_api', 'parent', 'id' ),
                    ),
                ),
                'delete' => array(
                    'tool_name'                  => 'hostinger-ai/revisions-delete',
                    'label'                      => __( 'Delete Revision', 'hostinger-ai-assistant' ),
                    'description'                => 'Permanently delete one revision of an item. Use when asked to remove an old version. Needs `parent` from `hostinger-ai/posts-search` or `hostinger-ai/pages-search`, and `base_api` set to the REST base of that item (e.g. `posts` or `pages`), and `id` from `hostinger-ai/revisions-list`.',
                    'meta'                       => array(
                        'annotations' => array(
                            'readOnlyHint'    => false,
                            'destructiveHint' => true,
                            'idempotentHint'  => true,
                            'openWorldHint'   => false,
                        ),
                    ),
                    'input_schema_modifications' => array(
                        'properties' => array(
                            'base_api' => array(
                                'type'        => 'string',
                                'description' => 'REST API base for the post type (e.g., posts, pages, templates, product, etc.).',
                            ),
                            'parent'   => array(
                                'type'        => 'integer',
                                'description' => 'The parent item ID.',
                            ),
                        ),
                        'required'   => array( 'base_api', 'parent', 'id' ),
                    ),
                ),
            ),
            WP_REST_Revisions_Controller::class,
            '/wp/v2/(?P<base_api>[a-zA-Z0-9_-]+)/(?P<parent>[\\d]+)/revisions',
            'post'
        );

        $this->register_restore_revision_ability();
    }

    private function register_restore_revision_ability(): void {
        $tool_name = 'hostinger-ai/revisions-restore';

        wp_register_ability(
            $tool_name,
            array(
                'label'               => __( 'Restore Revision', 'hostinger-ai-assistant' ),
                'description'         => 'Restore a revision by copying its content back to the parent item; works for any post type through its REST base. Use when asked to undo changes or go back to an older version. Needs `parent` from `hostinger-ai/posts-search` or `hostinger-ai/pages-search`, and `base_api` set to the REST base of that item (e.g. `posts` or `pages`), and `revision_id` from `hostinger-ai/revisions-list`.',
                'category'            => 'hostinger-ai-assistant',
                'input_schema'        => array(
                    'type'       => 'object',
                    'properties' => array(
                        'base_api'    => array(
                            'type'        => 'string',
                            'description' => 'REST API base for the post type (e.g., posts, pages, templates, product, etc.).',
                        ),
                        'parent'      => array(
                            'type'        => 'integer',
                            'description' => 'ID of the parent item to restore into.',
                        ),
                        'revision_id' => array(
                            'type'        => 'integer',
                            'description' => 'ID of the revision to restore from.',
                        ),
                        'fields'      => array(
                            'type'        => 'array',
                            'items'       => array(
                                'type' => 'string',
                                'enum' => array( 'title', 'content', 'excerpt' ),
                            ),
                            'description' => 'Optional list of fields to restore. Defaults to title, content, and excerpt.',
                        ),
                    ),
                    'required'   => array( 'base_api', 'parent', 'revision_id' ),
                ),
                'execute_callback'    => function ( $input ) {
                    return $this->to_ability_result( $this->execute( is_array( $input ) ? $input : array() ) );
                },
                'permission_callback' => function () {
                    return current_user_can( 'edit_posts' );
                },
                'meta'                => array(
                    'show_in_rest' => true,
                    'mcp'          => array(
                        'public' => true,
                        'type'   => 'tool',
                    ),
                    'annotations'  => array(
                        'readOnlyHint'    => false,
                        'destructiveHint' => false,
                        'idempotentHint'  => false,
                        'openWorldHint'   => false,
                    ),
                ),
            )
        );
    }

    private function execute( array $input ): array {
        $base_api    = isset( $input['base_api'] ) && is_string( $input['base_api'] ) ? trim( (string) $input['base_api'] ) : '';
        $parent_id   = isset( $input['parent'] ) ? intval( $input['parent'] ) : 0;
        $revision_id = isset( $input['revision_id'] ) ? intval( $input['revision_id'] ) : 0;
        $fields      = isset( $input['fields'] ) && is_array( $input['fields'] ) && ! empty( $input['fields'] ) ? $input['fields'] : array( 'title', 'content', 'excerpt' );

        if ( $base_api === '' || $parent_id <= 0 || $revision_id <= 0 ) {
            return array(
                'success'    => false,
                'error_code' => 'INVALID_PARAMS',
                'message'    => __( 'Invalid base API, parent, or revision ID.', 'hostinger-ai-assistant' ),
            );
        }

        $base_route = '/wp/v2/' . $base_api;

        $get_revision = new WP_REST_Request( 'GET', $base_route . '/' . $parent_id . '/revisions/' . $revision_id );
        $get_revision->set_param( 'context', 'edit' );
        $rev_response = rest_do_request( $get_revision );
        $rev_data     = $rev_response->get_data();

        if ( $rev_response->is_error() || empty( $rev_data ) || ! is_array( $rev_data ) ) {
            return array(
                'success'    => false,
                'error_code' => 'REVISION_NOT_FOUND',
                /* translators: %s: revision id  */
                'message'    => sprintf( __( "Revision with ID '%s' not found", 'hostinger-ai-assistant' ), $revision_id ),
            );
        }

        $update_payload = array();
        foreach ( array_intersect( array( 'title', 'content', 'excerpt' ), $fields ) as $field ) {
            if ( isset( $rev_data[ $field ]['raw'] ) ) {
                $update_payload[ $field ] = $rev_data[ $field ]['raw'];
            }
        }

        if ( empty( $update_payload ) ) {
            return array(
                'success'    => false,
                'error_code' => 'NOT_UPDATED',
                'message'    => __( 'Nothing was updated', 'hostinger-ai-assistant' ),
            );
        }

        $update_request = new WP_REST_Request( 'PUT', $base_route . '/' . $parent_id );
        foreach ( $update_payload as $k => $v ) {
            $update_request->set_param( $k, $v );
        }

        $update_response = rest_do_request( $update_request );
        if ( $update_response->is_error() ) {
            return array(
                'success'    => false,
                'error_code' => 'WP_ERROR',
                'message'    => $update_response->as_error()->get_error_message(),
            );
        }

        $updated = $update_response->get_data();

        return array(
            'success'       => true,
            'restored_from' => array(
                'id'       => $revision_id,
                'base_api' => $base_api,
                'parent'   => $parent_id,
                'fields'   => $fields,
            ),
            'updated'       => $updated,
        );
    }
}
