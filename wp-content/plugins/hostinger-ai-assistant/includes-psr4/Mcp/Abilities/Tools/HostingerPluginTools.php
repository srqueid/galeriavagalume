<?php

namespace Hostinger\AiAssistant\Mcp\Abilities\Tools;

use Hostinger\Admin\PluginSettings;
use stdClass;
use WP_Error;

if ( ! defined( 'ABSPATH' ) ) {
    die;
}

class HostingerPluginTools {
    protected string $type = 'tool';

    public function register(): void {
        if ( ! $this->is_hostinger_plugin_active() ) {
            return;
        }

        $this->register_get_operation();
        $this->register_update_operation();
    }

    private function register_get_operation(): void {
        $ability_args = array(
            'label'               => __( 'Get Hostinger Plugin Settings', 'hostinger-ai-assistant' ),
            'description'         => 'Get the Hostinger plugin settings, such as maintenance mode, XML-RPC, forced HTTPS and other security options. Use when you need to check these settings before changing them.',
            'category'            => 'hostinger-ai-assistant',
            'input_schema'        => array(
                'type'       => 'object',
                'properties' => new stdClass(),
            ),
            'output_schema'       => $this->output_schema(),
            'execute_callback'    => array( $this, 'execute_get_operation' ),
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
        );

        wp_register_ability( 'hostinger-ai/hostinger-plugin-settings-get', $ability_args );
    }

    private function register_update_operation(): void {
        $ability_args = array(
            'label'               => __( 'Update Hostinger Plugin Settings', 'hostinger-ai-assistant' ),
            'description'         => 'Update the Hostinger plugin settings, such as maintenance mode, XML-RPC or forced HTTPS; only the fields you pass change. Use when asked to turn one of these options on or off.',
            'category'            => 'hostinger-ai-assistant',
            'input_schema'        => array(
                'type'       => 'object',
                'properties' => array(
                    'maintenance_mode'                => array(
                        'type'        => 'boolean',
                        'description' => 'Enable or disable maintenance mode',
                    ),
                    'bypass_code'                     => array(
                        'type'        => 'string',
                        'description' => 'Bypass code for maintenance mode',
                    ),
                    'disable_xml_rpc'                 => array(
                        'type'        => 'boolean',
                        'description' => 'Enable or disable XML-RPC',
                    ),
                    'force_https'                     => array(
                        'type'        => 'boolean',
                        'description' => 'Force HTTPS redirects',
                    ),
                    'force_www'                       => array(
                        'type'        => 'boolean',
                        'description' => 'Force WWW in URLs',
                    ),
                    'disable_authentication_password' => array(
                        'type'        => 'boolean',
                        'description' => 'Disable authentication password',
                    ),
                    'enable_llms_txt'                 => array(
                        'type'        => 'boolean',
                        'description' => 'Enable llms.txt generation',
                    ),
                    'optin_mcp'                       => array(
                        'type'        => 'boolean',
                        'description' => 'Opt-in to MCP',
                    ),
                ),
            ),
            'output_schema'       => $this->output_schema(),
            'execute_callback'    => array( $this, 'execute_update_operation' ),
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
                    'readOnlyHint'    => false,
                    'destructiveHint' => false,
                    'idempotentHint'  => true,
                    'openWorldHint'   => false,
                ),
            ),
        );

        wp_register_ability( 'hostinger-ai/hostinger-plugin-settings-update', $ability_args );
    }

    public function execute_get_operation(): WP_Error|array {
        if ( ! defined( 'HOSTINGER_PLUGIN_SETTINGS_OPTION' ) ) {
            return new WP_Error(
                'hostinger_plugin_not_configured',
                __( 'Hostinger plugin settings option not defined', 'hostinger-ai-assistant' ),
                array( 'status' => 500 )
            );
        }

        $plugin_settings = new PluginSettings();
        $settings        = $plugin_settings->get_plugin_settings();

        return $settings->to_array();
    }

    public function execute_update_operation( array $input ): WP_Error|array {
        if ( ! defined( 'HOSTINGER_PLUGIN_SETTINGS_OPTION' ) ) {
            return new WP_Error(
                'hostinger_plugin_not_configured',
                __( 'Hostinger plugin settings option not defined', 'hostinger-ai-assistant' ),
                array( 'status' => 500 )
            );
        }

        $plugin_settings = new PluginSettings();
        $settings        = $plugin_settings->get_plugin_settings();

        if ( isset( $input['maintenance_mode'] ) ) {
            $settings->set_maintenance_mode( (bool) $input['maintenance_mode'] );
        }
        if ( isset( $input['bypass_code'] ) ) {
            $settings->set_bypass_code( (string) $input['bypass_code'] );
        }
        if ( isset( $input['disable_xml_rpc'] ) ) {
            $settings->set_disable_xml_rpc( (bool) $input['disable_xml_rpc'] );
        }
        if ( isset( $input['force_https'] ) ) {
            $settings->set_force_https( (bool) $input['force_https'] );
        }
        if ( isset( $input['force_www'] ) ) {
            $settings->set_force_www( (bool) $input['force_www'] );
        }
        if ( isset( $input['disable_authentication_password'] ) ) {
            $settings->set_disable_authentication_password( (bool) $input['disable_authentication_password'] );
        }
        if ( isset( $input['enable_llms_txt'] ) ) {
            $settings->set_enable_llms_txt( (bool) $input['enable_llms_txt'] );
        }
        if ( isset( $input['optin_mcp'] ) ) {
            $settings->set_optin_mcp( (bool) $input['optin_mcp'] );
        }

        $updated_settings = $plugin_settings->save_plugin_settings( $settings );

        if ( isset( $input['maintenance_mode'] ) ) {
            do_action( 'litespeed_purge_all' );
        }

        return $updated_settings->to_array();
    }

    public function output_schema(): array {
        return array(
            'type'       => 'object',
            'properties' => array(
                'maintenance_mode'                => array(
                    'type'        => 'boolean',
                    'description' => 'Maintenance mode status',
                ),
                'bypass_code'                     => array(
                    'type'        => 'string',
                    'description' => 'Bypass code for maintenance mode. Needed only when maintenance mode is enabled.',
                ),
                'disable_xml_rpc'                 => array(
                    'type'        => 'boolean',
                    'description' => 'Disable XML-RPC status',
                ),
                'force_https'                     => array(
                    'type'        => 'boolean',
                    'description' => 'Force HTTPS status',
                ),
                'force_www'                       => array(
                    'type'        => 'boolean',
                    'description' => 'Force WWW status',
                ),
                'disable_authentication_password' => array(
                    'type'        => 'boolean',
                    'description' => 'Disable authentication password status',
                ),
                'enable_llms_txt'                 => array(
                    'type'        => 'boolean',
                    'description' => 'Enable llms.txt status',
                ),
                'optin_mcp'                       => array(
                    'type'        => 'boolean',
                    'description' => 'Opt-in Web2Agent MCP status',
                ),
            ),
        );
    }

    private function is_hostinger_plugin_active(): bool {
        return defined( 'HOSTINGER_PLUGIN_SETTINGS_OPTION' )
            && class_exists( 'Hostinger\Admin\Options\PluginOptions' )
            && class_exists( 'Hostinger\Admin\PluginSettings' );
    }
}
