<?php

namespace Hostinger\AiAssistant\Mcp\Abilities\Tools;

use WP_REST_Settings_Controller;
use WP_Error;
use WP_REST_Request;

if ( ! defined( 'ABSPATH' ) ) {
    die;
}

class SettingsTools extends RestEndpointTool {
    public function register(): void {
        $this->register_operations(
            array(
                'custom' => array(
                    'tool_name'   => 'hostinger-ai/wp-settings-get',
                    'label'       => __( 'Get General Settings', 'hostinger-ai-assistant' ),
                    'description' => 'Get the general site settings, such as title, tagline, timezone, date format and front page. Use when you need to check how the site is configured before changing it.',
                    'meta'        => array(
                        'annotations' => array(
                            'readOnlyHint'  => true,
                            'openWorldHint' => false,
                        ),
                    ),
                ),
                'update' => array(
                    'tool_name'                  => 'hostinger-ai/wp-settings-update',
                    'label'                      => __( 'Update General Settings', 'hostinger-ai-assistant' ),
                    'description'                => 'Update general site settings, such as title, tagline or timezone; only the fields you pass change. Use when asked to rename the site or change a site-wide setting.',
                    'input_schema_modifications' => array(
                        'properties' => array(
                            'title'                  => array(
                                'type'        => 'string',
                                'description' => 'Site title',
                            ),
                            'description'            => array(
                                'type'        => 'string',
                                'description' => 'Site tagline/description',
                            ),
                            'timezone_string'        => array(
                                'type'        => 'string',
                                'description' => 'Site timezone',
                            ),
                            'date_format'            => array(
                                'type'        => 'string',
                                'description' => 'Date format',
                            ),
                            'time_format'            => array(
                                'type'        => 'string',
                                'description' => 'Time format',
                            ),
                            'start_of_week'          => array(
                                'type'        => 'integer',
                                'description' => 'Start of week (0 = Sunday, 1 = Monday, etc.)',
                            ),
                            'language'               => array(
                                'type'        => 'string',
                                'description' => 'Site language',
                            ),
                            'use_smilies'            => array(
                                'type'        => 'boolean',
                                'description' => 'Convert emoticons to graphics',
                            ),
                            'default_category'       => array(
                                'type'        => 'integer',
                                'description' => 'Default post category',
                            ),
                            'default_post_format'    => array(
                                'type'        => 'string',
                                'description' => 'Default post format',
                            ),
                            'posts_per_page'         => array(
                                'type'        => 'integer',
                                'description' => 'Number of posts to show per page',
                            ),
                            'default_comment_status' => array(
                                'type'        => 'string',
                                'description' => 'Default comment status (open/closed)',
                            ),
                            'default_ping_status'    => array(
                                'type'        => 'string',
                                'description' => 'Default ping status (open/closed)',
                            ),
                        ),
                    ),
                    'meta'                       => array(
                        'annotations' => array(
                            'readOnlyHint'    => false,
                            'destructiveHint' => false,
                            'idempotentHint'  => true,
                            'openWorldHint'   => false,
                        ),
                    ),
                    'skip_ids'                   => true,
                ),
            ),
            WP_REST_Settings_Controller::class,
            '/wp/v2/settings',
            'settings'
        );
    }

    protected function execute_operation( $config, array $input ): WP_Error|array {
        $operation = $config->get_operation();
        $route     = $config->get_route();
        $method    = $config->get_http_method_for_operation();

        $request = new WP_REST_Request( $method, $route );

        foreach ( $input as $key => $value ) {
            $request->set_param( $key, $value );
        }

        $response = rest_do_request( $request );

        if ( $response->is_error() ) {
            return $response->as_error();
        }

        return $response->get_data();
    }
}
