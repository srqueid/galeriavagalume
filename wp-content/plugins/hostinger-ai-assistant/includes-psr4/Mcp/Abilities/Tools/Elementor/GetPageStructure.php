<?php

namespace Hostinger\AiAssistant\Mcp\Abilities\Tools\Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    die;
}

class GetPageStructure extends BaseElementorTool {
    public function register(): void {
        $this->register_ability(
            'hostinger-ai/elementor-get-page-structure',
            array(
                'label'               => __( 'Get Elementor Page Structure', 'hostinger-ai-assistant' ),
                'description'         => 'Get a readable tree of the containers and widgets on an Elementor page, with their IDs, without raw JSON. Each container reports its existing background (video/image/gradient/color) in a "background" field; always check it before suggesting or adding a background, so you do not overwrite an existing video or image background. Use when you need a `container_id` or `widget_id` before editing a page. Needs `post_id` from `hostinger-ai/elementor-list-pages`.',
                'category'            => $this->category,
                'input_schema'        => array(
                    'type'       => 'object',
                    'properties' => array(
                        'post_id'          => array(
                            'type'        => 'integer',
                            'description' => 'The ID of the post/page to get structure for',
                        ),
                        'max_depth'        => array(
                            'type'        => 'integer',
                            'description' => 'Maximum recursion depth to display. Default: 10',
                            'default'     => 10,
                            'minimum'     => 1,
                            'maximum'     => 200,
                        ),
                        'include_settings' => array(
                            'type'        => 'boolean',
                            'description' => 'Whether to include settings. Default: false',
                            'default'     => false,
                        ),
                    ),
                    'required'   => array( 'post_id' ),
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
        $post_id          = $input['post_id'];
        $max_depth        = $input['max_depth'] ?? 10;
        $include_settings = $input['include_settings'] ?? false;

        $post = get_post( $post_id );
        $data = get_post_meta( $post_id, '_elementor_data', true );

        if ( empty( $data ) ) {
            return array(
                'post_id'    => $post_id,
                'post_title' => $post->post_title,
                'structure'  => array(),
            );
        }

        $elementor_data = json_decode( $data, true );
        if ( json_last_error() !== JSON_ERROR_NONE ) {
            return array(
                'error_code' => 'INVALID_JSON',
                'message'    => 'Could not parse Elementor data',
            );
        }

        $structure = $this->build_structure_tree( $elementor_data, 0, $max_depth, $include_settings );

        return array(
            'post_id'    => $post_id,
            'post_title' => $post->post_title,
            'structure'  => $structure,
        );
    }
}
