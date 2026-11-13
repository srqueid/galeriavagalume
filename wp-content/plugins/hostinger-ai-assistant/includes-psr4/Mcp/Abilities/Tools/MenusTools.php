<?php

namespace Hostinger\AiAssistant\Mcp\Abilities\Tools;

use WP_Error;
use WP_REST_Request;

if ( ! defined( 'ABSPATH' ) ) {
    die;
}

class MenusTools extends RestEndpointTool {
    public const ASSIGN_LOCATION_TOOL = 'hostinger-ai/menu-locations-assign';

    public function register(): void {
        $this->register_operations(
            array(
                'list'   => array(
                    'tool_name'   => 'hostinger-ai/menus-search',
                    'label'       => __( 'List Menus', 'hostinger-ai-assistant' ),
                    'description' => 'List the classic navigation menus. Use when you need a menu ID, for example to add items or assign a menu to a theme location. Block themes use `hostinger-ai/navigation-search` instead.',
                    'meta'        => array(
                        'annotations' => array(
                            'readOnlyHint'  => true,
                            'openWorldHint' => false,
                        ),
                    ),
                ),
                'get'    => array(
                    'tool_name'   => 'hostinger-ai/menus-get',
                    'label'       => __( 'Get Menu', 'hostinger-ai-assistant' ),
                    'description' => 'Get one classic navigation menu by ID. Use when you need the details of a known menu. Needs `id` from `hostinger-ai/menus-search`.',
                    'meta'        => array(
                        'annotations' => array(
                            'readOnlyHint'  => true,
                            'openWorldHint' => false,
                        ),
                    ),
                ),
                'create' => array(
                    'tool_name'   => 'hostinger-ai/menus-create',
                    'label'       => __( 'Create Menu', 'hostinger-ai-assistant' ),
                    'description' => 'Create a classic navigation menu from a name. Use when asked for a new menu; then add links with `hostinger-ai/menu-items-create` and place it with `hostinger-ai/menu-locations-assign`.',
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
                    'tool_name'   => 'hostinger-ai/menus-update',
                    'label'       => __( 'Update Menu', 'hostinger-ai-assistant' ),
                    'description' => 'Update a classic navigation menu, such as its name. Use when asked to rename a menu. Needs `id` from `hostinger-ai/menus-search`.',
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
                    'tool_name'                  => 'hostinger-ai/menus-delete',
                    'label'                      => __( 'Delete Menu', 'hostinger-ai-assistant' ),
                    'description'                => 'Permanently delete a classic navigation menu and all its items; this cannot be undone. Use when asked to remove a menu. Needs `id` from `hostinger-ai/menus-search`.',
                    'input_schema_modifications' => array(
                        'type'       => 'object',
                        'properties' => array(
                            'force' => array(
                                'type'        => 'boolean',
                                'description' => 'Force menu item deletion',
                            ),
                        ),
                    ),
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
            'WP_REST_Menus_Controller',
            '/wp/v2/menus',
            'nav_menu'
        );

        $this->register_operations(
            array(
                'list'   => array(
                    'tool_name'   => 'hostinger-ai/menu-items-search',
                    'label'       => __( 'List Menu Items', 'hostinger-ai-assistant' ),
                    'description' => 'List classic menu items, filterable by menu. Use when you need a menu item ID, or to see what a menu contains. Filter with a `menus` ID from `hostinger-ai/menus-search`.',
                    'meta'        => array(
                        'annotations' => array(
                            'readOnlyHint'  => true,
                            'openWorldHint' => false,
                        ),
                    ),
                ),
                'get'    => array(
                    'tool_name'   => 'hostinger-ai/menu-items-get',
                    'label'       => __( 'Get Menu Item', 'hostinger-ai-assistant' ),
                    'description' => 'Get one classic menu item by ID. Use when you need the details of a known menu link. Needs `id` from `hostinger-ai/menu-items-search`.',
                    'meta'        => array(
                        'annotations' => array(
                            'readOnlyHint'  => true,
                            'openWorldHint' => false,
                        ),
                    ),
                ),
                'create' => array(
                    'tool_name'                  => 'hostinger-ai/menu-items-create',
                    'input_schema_modifications' => array( 'required' => array( 'menus' ) ),
                    'label'                      => __( 'Create Menu Item', 'hostinger-ai-assistant' ),
                    'description'                => 'Create a classic menu item from a title and either a url or an object_id/object (such as a page). Use when asked to add a link to a menu. Needs `menus` from `hostinger-ai/menus-search`, and `object_id` from `hostinger-ai/pages-search` or `hostinger-ai/posts-search` when linking to content.',
                    'meta'                       => array(
                        'annotations' => array(
                            'readOnlyHint'    => false,
                            'destructiveHint' => false,
                            'idempotentHint'  => false,
                            'openWorldHint'   => false,
                        ),
                    ),
                ),
                'update' => array(
                    'tool_name'   => 'hostinger-ai/menu-items-update',
                    'label'       => __( 'Update Menu Item', 'hostinger-ai-assistant' ),
                    'description' => 'Update a classic menu item, including its label, URL, parent and order. Use when asked to rename, move or reorder a menu link. Needs `id` from `hostinger-ai/menu-items-search`.',
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
                    'tool_name'   => 'hostinger-ai/menu-items-delete',
                    'label'       => __( 'Delete Menu Item', 'hostinger-ai-assistant' ),
                    'description' => 'Permanently delete a classic menu item; this cannot be undone. Use when asked to remove a link from a menu. Needs `id` from `hostinger-ai/menu-items-search`.',
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
            'WP_REST_Menu_Items_Controller',
            '/wp/v2/menu-items',
            'nav_menu_item'
        );

        $this->register_operations(
            array(
                'list' => array(
                    'tool_name'   => 'hostinger-ai/menu-locations-list',
                    'label'       => __( 'List Menu Locations', 'hostinger-ai-assistant' ),
                    'description' => 'List the theme menu locations and the menus assigned to them. Use when you need a `location` slug, or to see which menu shows where.',
                    'meta'        => array(
                        'annotations' => array(
                            'readOnlyHint'  => true,
                            'openWorldHint' => false,
                        ),
                    ),
                    'skip_ids'    => true,
                ),
            ),
            'WP_REST_Menu_Locations_Controller',
            '/wp/v2/menu-locations',
            'menu_location'
        );

        $this->register_assign_location();
    }

    public function assign_location( array $input ): WP_Error|array {
        $location             = is_string( $input['location'] ?? null ) ? $input['location'] : '';
        $menu_id              = intval( $input['menu'] ?? 0 );
        $registered_locations = array_keys( get_registered_nav_menus() );

        if ( empty( $registered_locations ) ) {
            return new WP_Error(
                'no_menu_locations',
                sprintf(
                    /* translators: %s is a tool name */
                    __( 'The active theme has no classic menu locations. For block themes, use %s to set the navigation of a header or footer template part.', 'hostinger-ai-assistant' ),
                    TemplatePartsTools::ASSIGN_NAVIGATION_TOOL
                ),
                array( 'status' => 400 )
            );
        }

        if ( ! in_array( $location, $registered_locations, true ) ) {
            return new WP_Error(
                'invalid_menu_location',
                sprintf(
                    /* translators: %s is a comma-separated list of menu locations */
                    __( 'Invalid menu location. Available locations: %s', 'hostinger-ai-assistant' ),
                    implode( ', ', $registered_locations )
                ),
                array( 'status' => 400 )
            );
        }

        $menu_locations = array_keys(
            array_filter(
                get_nav_menu_locations(),
                function ( $assigned_menu_id ) use ( $menu_id ) {
                    return intval( $assigned_menu_id ) === $menu_id;
                }
            )
        );

        $menu_locations   = array_intersect( $menu_locations, $registered_locations );
        $menu_locations[] = $location;

        $request = new WP_REST_Request( 'PUT', '/wp/v2/menus/' . $menu_id );
        $request->set_param( 'locations', array_values( array_unique( $menu_locations ) ) );
        $response = rest_do_request( $request );

        if ( $response->is_error() ) {
            return $response->as_error();
        }

        return $response->get_data();
    }

    private function register_assign_location(): void {
        wp_register_ability(
            self::ASSIGN_LOCATION_TOOL,
            array(
                'label'               => __( 'Assign Menu To Location', 'hostinger-ai-assistant' ),
                'description'         => 'Assign a classic menu to a theme location, such as primary. Other locations that already use this menu keep it. Use when asked to show a menu in the header, footer or another location. Needs `location` from `hostinger-ai/menu-locations-list` and `menu` from `hostinger-ai/menus-search`.',
                'category'            => 'hostinger-ai-assistant',
                'input_schema'        => array(
                    'type'       => 'object',
                    'properties' => array(
                        'location' => array(
                            'type'        => 'string',
                            'description' => 'Theme location slug, as returned by `hostinger-ai/menu-locations-list` (e.g., primary, header, footer).',
                        ),
                        'menu'     => array(
                            'type'        => 'integer',
                            'description' => 'Menu ID to assign to the location.',
                        ),
                    ),
                    'required'   => array( 'location', 'menu' ),
                ),
                'execute_callback'    => function ( $input ) {
                    return $this->assign_location( is_array( $input ) ? $input : array() );
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
}
