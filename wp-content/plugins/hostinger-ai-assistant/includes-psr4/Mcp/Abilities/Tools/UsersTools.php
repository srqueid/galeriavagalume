<?php

namespace Hostinger\AiAssistant\Mcp\Abilities\Tools;

use WP_REST_Users_Controller;

if ( ! defined( 'ABSPATH' ) ) {
    die;
}

class UsersTools extends RestEndpointTool {
    public function register(): void {
        $this->register_operations(
            array(
                'list'   => array(
                    'tool_name'                  => 'hostinger-ai/users-search',
                    'label'                      => __( 'Search Users', 'hostinger-ai-assistant' ),
                    'description'                => 'Search and filter users with pagination. Use when you need a user ID, for example for a post author, or to find an account.',
                    'input_schema_modifications' => array(
                        'properties' => array(
                            'has_published_posts' => array(
                                'items' => array(
                                    'enum' => array(
                                        'post',
                                        'page',
                                    ),
                                ),
                            ),
                        ),
                    ),
                    'meta'                       => array(
                        'annotations' => array(
                            'readOnlyHint'  => true,
                            'openWorldHint' => false,
                        ),
                    ),
                    'permission_callback'        => function () {
                        return current_user_can( 'list_users' );
                    },
                ),
                'get'    => array(
                    'tool_name'           => 'hostinger-ai/users-get',
                    'label'               => __( 'Get User', 'hostinger-ai-assistant' ),
                    'description'         => 'Get one user by ID with all their fields. Use when you need the details of a known account. Needs `id` from `hostinger-ai/users-search`.',
                    'meta'                => array(
                        'annotations' => array(
                            'readOnlyHint'  => true,
                            'openWorldHint' => false,
                        ),
                    ),
                    'permission_callback' => function () {
                        return current_user_can( 'list_users' );
                    },
                ),
                'create' => array(
                    'tool_name'           => 'hostinger-ai/users-create',
                    'label'               => __( 'Create User', 'hostinger-ai-assistant' ),
                    'description'         => 'Create a user account from a username, email and password. Use when asked to add a new user or administrator to the site.',
                    'meta'                => array(
                        'annotations' => array(
                            'readOnlyHint'    => false,
                            'destructiveHint' => false,
                            'idempotentHint'  => false,
                            'openWorldHint'   => false,
                        ),
                    ),
                    'permission_callback' => function () {
                        return current_user_can( 'create_users' );
                    },
                ),
                'update' => array(
                    'tool_name'           => 'hostinger-ai/users-update',
                    'label'               => __( 'Update User', 'hostinger-ai-assistant' ),
                    'description'         => 'Update a user account; only the fields you pass change. Use when asked to change a user role, name, email or password. Needs `id` from `hostinger-ai/users-search`.',
                    'meta'                => array(
                        'annotations' => array(
                            'readOnlyHint'    => false,
                            'destructiveHint' => false,
                            'idempotentHint'  => true,
                            'openWorldHint'   => false,
                        ),
                    ),
                    'permission_callback' => function () {
                        return current_user_can( 'edit_users' );
                    },
                ),
                'delete' => array(
                    'tool_name'                  => 'hostinger-ai/users-delete',
                    'label'                      => __( 'Delete User', 'hostinger-ai-assistant' ),
                    'description'                => 'Permanently delete a user account; their posts and links are reassigned to the user given in `reassign`. This cannot be undone. Use when asked to remove a user. Needs `id` and `reassign` from `hostinger-ai/users-search`.',
                    'input_schema_modifications' => array(
                        'properties' => array(
                            'reassign' => array(
                                'type'        => 'integer',
                                'description' => 'ID of the user who receives the deleted user\'s posts and links.',
                            ),
                        ),
                        'required'   => array( 'reassign' ),
                    ),
                    'meta'                       => array(
                        'annotations' => array(
                            'readOnlyHint'    => false,
                            'destructiveHint' => true,
                            'idempotentHint'  => true,
                            'openWorldHint'   => false,
                        ),
                    ),
                    'permission_callback'        => function () {
                        return current_user_can( 'delete_users' );
                    },
                ),
            ),
            WP_REST_Users_Controller::class,
            '/wp/v2/users',
            'user'
        );

        $this->register_current_user_operations();
    }

    private function register_current_user_operations(): void {
        $this->register_operations(
            array(
                'get'    => array(
                    'tool_name'           => 'hostinger-ai/users-get-current',
                    'label'               => __( 'Get Current User', 'hostinger-ai-assistant' ),
                    'description'         => 'Get the account you are acting as. Use when you need your own user ID or profile, for example to set yourself as a post author.',
                    'meta'                => array(
                        'annotations' => array(
                            'readOnlyHint'  => true,
                            'openWorldHint' => false,
                        ),
                    ),
                    'permission_callback' => function () {
                        return is_user_logged_in();
                    },
                    'skip_ids'            => true,
                ),
                'update' => array(
                    'tool_name'           => 'hostinger-ai/users-update-current',
                    'label'               => __( 'Update Current User', 'hostinger-ai-assistant' ),
                    'description'         => 'Update the profile of the account you are acting as; only the fields you pass change. Use when asked to change your own name, email or password.',
                    'meta'                => array(
                        'annotations' => array(
                            'readOnlyHint'    => false,
                            'destructiveHint' => false,
                            'idempotentHint'  => true,
                            'openWorldHint'   => false,
                        ),
                    ),
                    'permission_callback' => function () {
                        return is_user_logged_in();
                    },
                    'skip_ids'            => true,
                ),
            ),
            WP_REST_Users_Controller::class,
            '/wp/v2/users/me',
            'user'
        );
    }
}
