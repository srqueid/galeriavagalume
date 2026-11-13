<?php

namespace Hostinger\AiAssistant\Mcp\Abilities\Tools;

use WP_REST_Posts_Controller;

if ( ! defined( 'ABSPATH' ) ) {
    die;
}

class NavigationTools extends RestEndpointTool {
    public function register(): void {
        $controller_class = $this->get_controller_class();

        if ( $controller_class === null ) {
            return;
        }

        $this->register_operations(
            array(
                'list'   => array(
                    'tool_name'   => 'hostinger-ai/navigation-search',
                    'label'       => __( 'List Navigations', 'hostinger-ai-assistant' ),
                    'description' => 'List the Site Editor navigations (block theme menus). Use when you need a navigation ID on a block theme, for example to edit its links or place it in a header. Classic themes use `hostinger-ai/menus-search` instead.',
                    'meta'        => array(
                        'annotations' => array(
                            'readOnlyHint'  => true,
                            'openWorldHint' => false,
                        ),
                    ),
                ),
                'get'    => array(
                    'tool_name'   => 'hostinger-ai/navigation-get',
                    'label'       => __( 'Get Navigation', 'hostinger-ai-assistant' ),
                    'description' => 'Get one Site Editor navigation by ID with its block content. Use when you need the links of a known navigation. Needs `id` from `hostinger-ai/navigation-search`.',
                    'meta'        => array(
                        'annotations' => array(
                            'readOnlyHint'  => true,
                            'openWorldHint' => false,
                        ),
                    ),
                ),
                'create' => array(
                    'tool_name'   => 'hostinger-ai/navigation-create',
                    'label'       => __( 'Create Navigation', 'hostinger-ai-assistant' ),
                    'description' => 'Create a Site Editor navigation (block theme menu). Use when a block theme needs a new menu; place it with `hostinger-ai/template-part-assign-navigation`.',
                    'meta'        => array(
                        'annotations' => array(
                            'readOnlyHint'    => false,
                            'destructiveHint' => false,
                            'idempotentHint'  => false,
                            'openWorldHint'   => false,
                        ),
                    ),
                ),
                'update' => array(
                    'tool_name'   => 'hostinger-ai/navigation-update',
                    'label'       => __( 'Update Navigation', 'hostinger-ai-assistant' ),
                    'description' => 'Update a Site Editor navigation, such as its title or links. Use when asked to change the links of a block theme menu. Needs `id` from `hostinger-ai/navigation-search`.',
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
                    'tool_name'   => 'hostinger-ai/navigation-delete',
                    'label'       => __( 'Delete Navigation', 'hostinger-ai-assistant' ),
                    'description' => 'Move a Site Editor navigation to the trash; it can be restored from the trash. Use when asked to remove a block theme menu. Needs `id` from `hostinger-ai/navigation-search`.',
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
            $controller_class,
            '/wp/v2/navigation',
            'wp_navigation'
        );
    }

    protected function get_controller_class(): ?string {
        if ( ! post_type_exists( 'wp_navigation' ) ) {
            return null;
        }

        if ( class_exists( 'WP_REST_Navigation_Controller' ) ) {
            return 'WP_REST_Navigation_Controller';
        }

        $post_type        = get_post_type_object( 'wp_navigation' );
        $controller_class = $post_type->rest_controller_class ?? '';

        if ( is_string( $controller_class ) && class_exists( $controller_class ) && is_subclass_of( $controller_class, 'WP_REST_Controller' ) ) {
            return $controller_class;
        }

        return WP_REST_Posts_Controller::class;
    }
}
