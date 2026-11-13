<?php

namespace Hostinger\AiAssistant\Mcp\Abilities\Tools;

use Hostinger\AiAssistant\Mcp\ContentUtils;
use WP_REST_Posts_Controller;

if ( ! defined( 'ABSPATH' ) ) {
    die;
}

class PostsTools extends RestEndpointTool {
    public function register(): void {
        $text_fields = $this->get_text_fields_schema( array( 'title', 'content', 'excerpt' ) );

        $this->register_operations(
            array(
                'list'   => array(
                    'tool_name'   => 'hostinger-ai/posts-search',
                    'label'       => __( 'Search Posts', 'hostinger-ai-assistant' ),
                    'description' => 'Search and filter blog posts with pagination. Use when you need a post ID, or to find, list or count posts.',
                    'meta'        => array(
                        'annotations' => array(
                            'readOnlyHint'  => true,
                            'openWorldHint' => false,
                        ),
                    ),
                ),
                'get'    => array(
                    'tool_name'   => 'hostinger-ai/posts-get',
                    'label'       => __( 'Get Post', 'hostinger-ai-assistant' ),
                    'description' => 'Get one blog post by ID with all its fields. Use when you need the full content or settings of a known post. Needs `id` from `hostinger-ai/posts-search`.',
                    'meta'        => array(
                        'annotations' => array(
                            'readOnlyHint'  => true,
                            'openWorldHint' => false,
                        ),
                    ),
                ),
                'create' => array(
                    'tool_name'                  => 'hostinger-ai/posts-create',
                    'input_schema_modifications' => array_merge( $text_fields, array( 'required' => array( 'title' ) ) ),
                    'label'                      => __( 'Create Post', 'hostinger-ai-assistant' ),
                    'description'                => 'Create a blog post from a title and content. Use when asked to write or publish a new post. ' . ContentUtils::CONTENT_FORMAT_HINT . ' Optional `categories`, `tags`, `featured_media` and `author` IDs come from `hostinger-ai/categories-list`, `hostinger-ai/tags-list`, `hostinger-ai/media-search` and `hostinger-ai/users-search`. The response includes view_url and edit_url; after creating it, always include a direct link to the new post in your reply (view_url if published, otherwise edit_url).',
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
                    'tool_name'                  => 'hostinger-ai/posts-update',
                    'input_schema_modifications' => $text_fields,
                    'label'                      => __( 'Update Post', 'hostinger-ai-assistant' ),
                    'description'                => 'Update a blog post; only the fields you pass change. Use when asked to edit, publish, schedule or recategorise a post. ' . ContentUtils::CONTENT_FORMAT_HINT . ' Needs `id` from `hostinger-ai/posts-search`.',
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
                    'tool_name'   => 'hostinger-ai/posts-delete',
                    'label'       => __( 'Delete Post', 'hostinger-ai-assistant' ),
                    'description' => 'Move a blog post to the trash and remove the menu links that point to it; the post can be restored from the trash, the menu links cannot. Use when asked to remove a post. Needs `id` from `hostinger-ai/posts-search`.',
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
            WP_REST_Posts_Controller::class,
            '/wp/v2/posts',
            'post'
        );
    }
}
