<?php

namespace Hostinger\AiAssistant\Mcp\Abilities\Tools;

use WP_REST_Terms_Controller;

if ( ! defined( 'ABSPATH' ) ) {
    die;
}

class TagsTools extends RestEndpointTool {
    public function register(): void {
        $this->register_operations(
            array(
                'list'   => array(
                    'tool_name'   => 'hostinger-ai/tags-list',
                    'label'       => __( 'List Tags', 'hostinger-ai-assistant' ),
                    'description' => 'List post tags with pagination and filtering. Use when you need a tag ID, for example to tag a post.',
                    'meta'        => array(
                        'annotations' => array(
                            'readOnlyHint'  => true,
                            'openWorldHint' => false,
                        ),
                    ),
                ),
                'get'    => array(
                    'tool_name'   => 'hostinger-ai/tags-get',
                    'label'       => __( 'Get Tag', 'hostinger-ai-assistant' ),
                    'description' => 'Get one post tag by ID. Use when you need the details of a known tag. Needs `id` from `hostinger-ai/tags-list`.',
                    'meta'        => array(
                        'annotations' => array(
                            'readOnlyHint'  => true,
                            'openWorldHint' => false,
                        ),
                    ),
                ),
                'create' => array(
                    'tool_name'   => 'hostinger-ai/tags-create',
                    'label'       => __( 'Create Tag', 'hostinger-ai-assistant' ),
                    'description' => 'Create a post tag from a name. Use when the tag you need is not in `hostinger-ai/tags-list` yet.',
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
                    'tool_name'   => 'hostinger-ai/tags-update',
                    'label'       => __( 'Update Tag', 'hostinger-ai-assistant' ),
                    'description' => 'Update a post tag; only the fields you pass change. Use when asked to rename a tag. Needs `id` from `hostinger-ai/tags-list`.',
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
                    'tool_name'                  => 'hostinger-ai/tags-delete',
                    'label'                      => __( 'Delete Tag', 'hostinger-ai-assistant' ),
                    'description'                => 'Permanently delete a post tag; this cannot be undone. Use when asked to remove a tag. Needs `id` from `hostinger-ai/tags-list`.',
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
            '/wp/v2/tags',
            'post_tag'
        );
    }
}
