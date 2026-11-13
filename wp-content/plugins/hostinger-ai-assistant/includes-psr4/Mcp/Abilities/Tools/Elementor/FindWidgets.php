<?php

namespace Hostinger\AiAssistant\Mcp\Abilities\Tools\Elementor;

use Hostinger\AiAssistant\Mcp\Dto\Elementor\WidgetSearchQuery;

if ( ! defined( 'ABSPATH' ) ) {
    die;
}

class FindWidgets extends BaseElementorTool {
    public function register(): void {
        $this->register_ability(
            'hostinger-ai/elementor-find-widgets',
            array(
                'label'               => __( 'Find Elementor Widgets', 'hostinger-ai-assistant' ),
                'description'         => 'Find the widgets of given types on an Elementor page, with their settings and position. Use when you need the IDs of every widget of a kind, such as all buttons or headings on a page. Needs `post_id` from `hostinger-ai/elementor-list-pages`.',
                'category'            => $this->category,
                'input_schema'        => array(
                    'type'       => 'object',
                    'properties' => array(
                        'post_id'          => array(
                            'type'        => 'integer',
                            'description' => 'The ID of the post/page to search in',
                        ),
                        'widget_types'     => array(
                            'type'        => 'array',
                            'description' => 'Array of widget types to search for (e.g., ["button", "heading", "image"])',
                            'items'       => array(
                                'type' => 'string',
                            ),
                        ),
                        'max_depth'        => array(
                            'type'        => 'integer',
                            'description' => 'Maximum search depth. Default: 10',
                            'default'     => 10,
                            'minimum'     => 1,
                            'maximum'     => 20,
                        ),
                        'include_settings' => array(
                            'type'        => 'boolean',
                            'description' => 'Whether to include widget settings. Default: true',
                            'default'     => true,
                        ),
                        'css_class_filter' => array(
                            'type'        => 'string',
                            'description' => 'Optional CSS class to filter by (e.g., "hostinger-ai-*")',
                            'default'     => '',
                        ),
                    ),
                    'required'   => array( 'post_id', 'widget_types' ),
                ),
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
                        'readOnlyHint'  => true,
                        'openWorldHint' => false,
                    ),
                ),
            )
        );
    }

    public function execute( array $input ): array {
        $post_id = $input['post_id'];

        $query = new WidgetSearchQuery(
            $input['widget_types'],
            $input['max_depth'] ?? 10,
            $input['include_settings'] ?? true,
            $input['css_class_filter'] ?? ''
        );

        $result = $this->get_elementor_data( $post_id );
        if ( isset( $result['error_code'] ) ) {
            return $result;
        }

        $elementor_data = $result['data'];
        $found_widgets  = array();
        $this->search_widgets( $elementor_data, $query, $found_widgets );

        return array(
            'post_id'       => $post_id,
            'widgets_found' => count( $found_widgets ),
            'widgets'       => $found_widgets,
        );
    }
}
