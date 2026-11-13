<?php

namespace Hostinger\AiAssistant\Mcp\Abilities\Tools\Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    die;
}

class GetKitById extends BaseElementorTool {
    public function register(): void {
        $this->register_ability(
            'hostinger-ai/elementor-get-kit-by-id',
            array(
                'label'               => __( 'Get Elementor Kit by ID', 'hostinger-ai-assistant' ),
                'description'         => 'Get one Elementor kit by post ID with its global colors, typography presets and theme style settings. Use when you need a kit other than the active one. Needs `kit_id` from `hostinger-ai/elementor-get-active-kit` or `hostinger-ai/elementor-list-templates`.',
                'category'            => $this->category,
                'input_schema'        => array(
                    'type'       => 'object',
                    'properties' => array(
                        'kit_id'             => array(
                            'type'        => 'integer',
                            'description' => 'The post ID of the Elementor kit',
                        ),
                        'include_colors'     => array(
                            'type'        => 'boolean',
                            'description' => 'Include global colors in response. Default: true',
                            'default'     => true,
                        ),
                        'include_typography' => array(
                            'type'        => 'boolean',
                            'description' => 'Include global typography in response. Default: true',
                            'default'     => true,
                        ),
                        'include_settings'   => array(
                            'type'        => 'boolean',
                            'description' => 'Include all other kit settings. Default: false',
                            'default'     => false,
                        ),
                    ),
                    'required'   => array( 'kit_id' ),
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
        $kit_id             = $input['kit_id'];
        $include_colors     = $input['include_colors'] ?? true;
        $include_typography = $input['include_typography'] ?? true;
        $include_settings   = $input['include_settings'] ?? false;

        return $this->get_kit_data( $kit_id, $include_colors, $include_typography, $include_settings );
    }
}
