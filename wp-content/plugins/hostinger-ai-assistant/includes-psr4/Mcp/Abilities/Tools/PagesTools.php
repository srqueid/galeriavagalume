<?php

namespace Hostinger\AiAssistant\Mcp\Abilities\Tools;

use Hostinger\AiAssistant\Mcp\ContentUtils;
use WP_REST_Posts_Controller;

if ( ! defined( 'ABSPATH' ) ) {
    die;
}

class PagesTools extends RestEndpointTool {
    public function register(): void {
        $text_fields = $this->get_text_fields_schema( array( 'title', 'content' ) );

        $this->register_operations(
            array(
                'list'   => array(
                    'tool_name'   => 'hostinger-ai/pages-search',
                    'label'       => __( 'Search Pages', 'hostinger-ai-assistant' ),
                    'description' => 'Search and filter pages with pagination. Use when you need a page ID, or to find, list or count pages.',
                    'meta'        => array(
                        'annotations' => array(
                            'readOnlyHint'  => true,
                            'openWorldHint' => false,
                        ),
                    ),
                ),
                'get'    => array(
                    'tool_name'   => 'hostinger-ai/pages-get',
                    'label'       => __( 'Get Page', 'hostinger-ai-assistant' ),
                    'description' => 'Get one page by ID with all its fields. Use when you need the full content or settings of a known page. Needs `id` from `hostinger-ai/pages-search`.',
                    'meta'        => array(
                        'annotations' => array(
                            'readOnlyHint'  => true,
                            'openWorldHint' => false,
                        ),
                    ),
                ),
                'create' => array(
                    'tool_name'                  => 'hostinger-ai/pages-create',
                    'input_schema_modifications' => array_merge( $text_fields, array( 'required' => array( 'title' ) ) ),
                    'label'                      => __( 'Create Page', 'hostinger-ai-assistant' ),
                    'description'                => 'Create a page from a title and content. Use when asked to add a new page to the site. ' . ContentUtils::CONTENT_FORMAT_HINT . ' Only use a block you are certain is registered on the site, since an unregistered block breaks the editor; for a contact form use the [hostinger_contact_form] shortcode. An optional `parent` page ID comes from `hostinger-ai/pages-search` and `featured_media` from `hostinger-ai/media-search`. The response includes view_url and edit_url; after creating it, always include a direct link to the new page in your reply (view_url if published, otherwise edit_url).',
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
                    'tool_name'                  => 'hostinger-ai/pages-update',
                    'input_schema_modifications' => $text_fields,
                    'label'                      => __( 'Update Page', 'hostinger-ai-assistant' ),
                    'description'                => 'Update a page; only the fields you pass change. Use when asked to edit, publish or rewrite a page. ' . ContentUtils::CONTENT_FORMAT_HINT . ' Only use a block you are certain is registered on the site, since an unregistered block breaks the editor; for a contact form use the [hostinger_contact_form] shortcode. Needs `id` from `hostinger-ai/pages-search`.',
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
                    'tool_name'   => 'hostinger-ai/pages-delete',
                    'label'       => __( 'Delete Page', 'hostinger-ai-assistant' ),
                    'description' => 'Move a page to the trash and remove the menu links that point to it; the page can be restored from the trash, the menu links cannot. Use when asked to remove a page. Needs `id` from `hostinger-ai/pages-search`.',
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
            '/wp/v2/pages',
            'page'
        );
    }
}
