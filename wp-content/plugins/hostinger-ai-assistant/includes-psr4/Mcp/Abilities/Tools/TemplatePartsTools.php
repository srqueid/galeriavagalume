<?php

namespace Hostinger\AiAssistant\Mcp\Abilities\Tools;

use WP_Error;
use WP_REST_Request;

if ( ! defined( 'ABSPATH' ) ) {
    die;
}

class TemplatePartsTools extends RestEndpointTool {
    public const ASSIGN_NAVIGATION_TOOL = 'hostinger-ai/template-part-assign-navigation';
    private const ROUTE                 = '/wp/v2/template-parts';

    public function register(): void {
        $id_schema = array(
            'properties' => array(
                'id' => array(
                    'type'        => 'string',
                    'description' => 'Template part ID in the theme//slug format (e.g. twentytwentyfive//header), as returned by `hostinger-ai/template-parts-search`.',
                ),
            ),
        );

        $this->register_operations(
            array(
                'list'   => array(
                    'tool_name'   => 'hostinger-ai/template-parts-search',
                    'label'       => __( 'List Template Parts', 'hostinger-ai-assistant' ),
                    'description' => 'List the template parts of a block theme, such as its header and footer. Use when you need a template part ID before reading or editing it.',
                    'meta'        => array(
                        'annotations' => array(
                            'readOnlyHint'  => true,
                            'openWorldHint' => false,
                        ),
                    ),
                ),
                'get'    => array(
                    'tool_name'                  => 'hostinger-ai/template-part-get',
                    'label'                      => __( 'Get Template Part', 'hostinger-ai-assistant' ),
                    'description'                => 'Get one template part by ID with its content. Use when you need the current content of a header, footer or other part. Needs `id` from `hostinger-ai/template-parts-search`.',
                    'input_schema_modifications' => $id_schema,
                    'meta'                       => array(
                        'annotations' => array(
                            'readOnlyHint'  => true,
                            'openWorldHint' => false,
                        ),
                    ),
                ),
                'update' => array(
                    'tool_name'                  => 'hostinger-ai/template-part-update',
                    'label'                      => __( 'Update Template Part', 'hostinger-ai-assistant' ),
                    'description'                => 'Update the content or title of a template part. Use when asked to change a block theme header, footer or other part. Needs `id` from `hostinger-ai/template-parts-search`.',
                    'input_schema_modifications' => $id_schema,
                    'meta'                       => array(
                        'annotations' => array(
                            'readOnlyHint'    => false,
                            'destructiveHint' => false,
                            'idempotentHint'  => true,
                            'openWorldHint'   => false,
                        ),
                    ),
                ),
                'delete' => array(
                    'tool_name'                  => 'hostinger-ai/template-part-delete',
                    'label'                      => __( 'Delete Template Part', 'hostinger-ai-assistant' ),
                    'description'                => 'Delete a template part. For a part provided by the theme, this removes the customizations and restores the theme version. Use when asked to remove or reset a header, footer or other part. Needs `id` from `hostinger-ai/template-parts-search`.',
                    'input_schema_modifications' => $id_schema,
                    'meta'                       => array(
                        'annotations' => array(
                            'readOnlyHint'    => false,
                            'destructiveHint' => true,
                            'idempotentHint'  => true,
                            'openWorldHint'   => false,
                        ),
                    ),
                ),
            ),
            'WP_REST_Templates_Controller',
            self::ROUTE,
            'wp_template_part'
        );

        $this->register_assign_navigation();
    }

    public function assign_navigation( array $input ): WP_Error|array {
        $template_part_id = $this->format_route_id( $input['template_part_id'] ?? '' );
        $navigation_id    = intval( $input['navigation_id'] ?? 0 );
        $navigation       = get_post( $navigation_id );

        if ( ! $navigation || $navigation->post_type !== 'wp_navigation' || $navigation->post_status === 'trash' ) {
            return new WP_Error(
                'navigation_not_found',
                __( 'Navigation not found.', 'hostinger-ai-assistant' ),
                array( 'status' => 404 )
            );
        }

        $request = new WP_REST_Request( 'GET', self::ROUTE . '/' . $template_part_id );
        $request->set_param( 'context', 'edit' );
        $response = rest_do_request( $request );

        if ( $response->is_error() ) {
            return $response->as_error();
        }

        $template_part = $response->get_data();
        $blocks        = $this->set_navigation_ref( parse_blocks( $template_part['content']['raw'] ?? '' ), $navigation_id );

        if ( $blocks === null ) {
            return new WP_Error(
                'navigation_block_not_found',
                __( 'The template part does not contain a Navigation block.', 'hostinger-ai-assistant' ),
                array( 'status' => 400 )
            );
        }

        $request = new WP_REST_Request( 'PUT', self::ROUTE . '/' . $template_part_id );
        $request->set_param( 'content', serialize_blocks( $blocks ) );
        $response = rest_do_request( $request );

        if ( $response->is_error() ) {
            return $response->as_error();
        }

        return $response->get_data();
    }

    protected function format_route_id( mixed $id ): string {
        return is_scalar( $id ) ? preg_replace( '/[^\w.\-\/]/', '', (string) $id ) : '';
    }

    private function register_assign_navigation(): void {
        wp_register_ability(
            self::ASSIGN_NAVIGATION_TOOL,
            array(
                'label'               => __( 'Assign Navigation To Template Part', 'hostinger-ai-assistant' ),
                'description'         => 'Assign a navigation to the first Navigation block of a template part. Use when a block theme header or footer should show a given menu. Needs `template_part_id` from `hostinger-ai/template-parts-search` and `navigation_id` from `hostinger-ai/navigation-search`.',
                'category'            => 'hostinger-ai-assistant',
                'input_schema'        => array(
                    'type'       => 'object',
                    'properties' => array(
                        'template_part_id' => array(
                            'type'        => 'string',
                            'description' => 'Template part ID in the theme//slug format (e.g. twentytwentyfive//header), as returned by `hostinger-ai/template-parts-search`.',
                        ),
                        'navigation_id'    => array(
                            'type'        => 'integer',
                            'description' => 'Navigation (wp_navigation) post ID.',
                        ),
                    ),
                    'required'   => array( 'template_part_id', 'navigation_id' ),
                ),
                'execute_callback'    => function ( $input ) {
                    return $this->assign_navigation( is_array( $input ) ? $input : array() );
                },
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

    private function set_navigation_ref( array $blocks, int $navigation_id ): ?array {
        foreach ( $blocks as $index => $block ) {
            if ( ( $block['blockName'] ?? '' ) === 'core/navigation' ) {
                $blocks[ $index ]['attrs']['ref'] = $navigation_id;
                $blocks[ $index ]['innerBlocks']  = array();
                $blocks[ $index ]['innerContent'] = array();
                $blocks[ $index ]['innerHTML']    = '';

                return $blocks;
            }

            if ( empty( $block['innerBlocks'] ) ) {
                continue;
            }

            $inner_blocks = $this->set_navigation_ref( $block['innerBlocks'], $navigation_id );

            if ( $inner_blocks !== null ) {
                $blocks[ $index ]['innerBlocks'] = $inner_blocks;

                return $blocks;
            }
        }

        return null;
    }
}
