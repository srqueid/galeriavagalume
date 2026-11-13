<?php

namespace Hostinger\AiAssistant\Mcp\Abilities\Tools;

use WP_REST_Terms_Controller;

if ( ! defined( 'ABSPATH' ) ) {
    die;
}

class CategoriesTools extends RestEndpointTool {
    public function register(): void {
        $this->register_operations(
            array(
                'list'   => array(
                    'tool_name'   => 'hostinger-ai/categories-list',
                    'label'       => __( 'List Categories', 'hostinger-ai-assistant' ),
                    'description' => 'List post categories with pagination and filtering. Use when you need a category ID, for example to file a post under a category.',
                    'meta'        => array(
                        'annotations' => array(
                            'readOnlyHint'  => true,
                            'openWorldHint' => false,
                        ),
                    ),
                ),
                'get'    => array(
                    'tool_name'   => 'hostinger-ai/categories-get',
                    'label'       => __( 'Get Category', 'hostinger-ai-assistant' ),
                    'description' => 'Get one post category by ID. Use when you need the details of a known category. Needs `id` from `hostinger-ai/categories-list`.',
                    'meta'        => array(
                        'annotations' => array(
                            'readOnlyHint'  => true,
                            'openWorldHint' => false,
                        ),
                    ),
                ),
                'create' => array(
                    'tool_name'   => 'hostinger-ai/categories-create',
                    'label'       => __( 'Create Category', 'hostinger-ai-assistant' ),
                    'description' => 'Create a post category from a name. Use when the category you need is not in `hostinger-ai/categories-list` yet.',
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
                    'tool_name'   => 'hostinger-ai/categories-update',
                    'label'       => __( 'Update Category', 'hostinger-ai-assistant' ),
                    'description' => 'Update a post category; only the fields you pass change. Use when asked to rename a category or change its parent. Needs `id` from `hostinger-ai/categories-list`.',
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
                    'tool_name'                  => 'hostinger-ai/categories-delete',
                    'label'                      => __( 'Delete Category', 'hostinger-ai-assistant' ),
                    'description'                => 'Permanently delete a post category; this cannot be undone. Use when asked to remove a category. Needs `id` from `hostinger-ai/categories-list`.',
                    'input_schema_modifications' => array(
                        'type'       => 'object',
                        'properties' => array(
                            'force' => array(
                                'type'        => 'boolean',
                                'description' => 'Force category deletion',
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
            WP_REST_Terms_Controller::class,
            '/wp/v2/categories',
            'category'
        );
    }
}
