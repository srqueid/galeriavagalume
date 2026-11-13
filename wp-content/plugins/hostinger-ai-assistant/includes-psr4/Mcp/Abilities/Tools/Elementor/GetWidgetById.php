<?php

namespace Hostinger\AiAssistant\Mcp\Abilities\Tools\Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    die;
}

class GetWidgetById extends BaseElementorTool {
    public function register(): void {
        $this->register_ability(
            'hostinger-ai/elementor-get-widget-by-id',
            array(
                'label'               => __( 'Get Elementor Widget by ID', 'hostinger-ai-assistant' ),
                'description'         => 'Get all settings, the position and the parent of one Elementor widget. Use when you need the current settings of a widget before changing it. Needs `post_id` from `hostinger-ai/elementor-list-pages` and `widget_id` from `hostinger-ai/elementor-get-page-structure`.',
                'category'            => $this->category,
                'input_schema'        => array(
                    'type'       => 'object',
                    'properties' => array(
                        'post_id'   => array(
                            'type'        => 'integer',
                            'description' => 'The ID of the post/page containing the widget',
                        ),
                        'widget_id' => array(
                            'type'        => 'string',
                            'description' => 'The unique ID of the widget to retrieve',
                        ),
                    ),
                    'required'   => array( 'post_id', 'widget_id' ),
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
        $post_id   = $input['post_id'];
        $widget_id = $input['widget_id'];

        $data           = get_post_meta( $post_id, '_elementor_data', true );
        $elementor_data = json_decode( $data, true );

        if ( json_last_error() !== JSON_ERROR_NONE ) {
            return array(
                'error_code' => 'INVALID_JSON',
                'message'    => 'Could not parse Elementor data',
            );
        }

        $result = $this->find_element_by_id( $elementor_data, $widget_id, array() );

        if ( ! $result ) {
            return array(
                'found'      => false,
                'error_code' => 'WIDGET_NOT_FOUND',
                'message'    => "Widget with ID '{$widget_id}' not found in post {$post_id}",
            );
        }

        return array(
            'found'  => true,
            'widget' => $result,
        );
    }
}
