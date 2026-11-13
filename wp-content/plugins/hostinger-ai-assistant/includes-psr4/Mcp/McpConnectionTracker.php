<?php

namespace Hostinger\AiAssistant\Mcp;

use Hostinger\AiAssistant\Mcp\Rest\JwtAuth;
use Hostinger\Amplitude\AmplitudeManager;
use Hostinger_Ai_Assistant_Amplitude_Actions;
use Throwable;
use WP_REST_Request;
use WP_REST_Server;

if ( ! defined( 'ABSPATH' ) ) {
    die;
}

class McpConnectionTracker {
    private const AMPLITUDE_ENDPOINT    = '/api/v1/events/trigger';
    private const INITIALIZE_METHOD     = 'initialize';
    private const UNKNOWN_CLIENT        = 'unknown';
    private const CLI_CLIENT            = 'hostinger-agent-cli';
    private const CLI_TRACKED_TRANSIENT = 'hostinger_ai_mcp_cli_connected_';

    private AmplitudeManager $amplitude_manager;
    private JwtAuth $jwt_auth;
    private bool $is_cli_connection_tracked = false;

    public function __construct( AmplitudeManager $amplitude_manager, JwtAuth $jwt_auth ) {
        $this->amplitude_manager = $amplitude_manager;
        $this->jwt_auth          = $jwt_auth;
    }

    public function init(): void {
        if ( defined( 'WP_CLI' ) && WP_CLI ) {
            add_action( 'wp_before_execute_ability', array( $this, 'maybe_track_cli_connection' ) );

            return;
        }

        $current_url = $_SERVER['REQUEST_URI'] ?? '';
        if ( ! McpRoutes::is_mcp_request_uri( $current_url ) ) {
            return;
        }

        add_filter( 'rest_pre_dispatch', array( $this, 'maybe_track_initialize' ), 10, 3 );
    }

    public function maybe_track_initialize( mixed $result, WP_REST_Server $server, WP_REST_Request $request ): mixed {
        try {
            $body = $request->get_json_params();

            if ( ! is_array( $body ) || ( $body['method'] ?? '' ) !== self::INITIALIZE_METHOD ) {
                return $result;
            }

            $this->track_connection( $request, $body );
        } catch ( Throwable $e ) {
            return $result;
        }

        return $result;
    }

    public function maybe_track_cli_connection(): void {
        if ( $this->is_cli_connection_tracked ) {
            return;
        }

        $this->is_cli_connection_tracked = true;

        try {
            $cli_args = array_map( 'strval', $_SERVER['argv'] ?? array() );

            if ( ! in_array( 'mcp-adapter', $cli_args, true ) || ! in_array( 'serve', $cli_args, true ) ) {
                return;
            }

            $transient_key = self::CLI_TRACKED_TRANSIENT . get_current_user_id();
            if ( get_transient( $transient_key ) ) {
                return;
            }

            set_transient( $transient_key, 1, DAY_IN_SECONDS );

            $this->send_connected_event(
                self::CLI_CLIENT,
                null,
                false,
                in_array( '--server=' . McpRoutes::DEFAULT_SERVER_ID, $cli_args, true ) ? 'default' : 'plugin'
            );
        } catch ( Throwable $e ) {
            return;
        }
    }

    private function track_connection( WP_REST_Request $request, array $body ): void {
        $params             = is_array( $body['params'] ?? null ) ? $body['params'] : array();
        $mcp_client_info    = is_array( $params['clientInfo'] ?? null ) ? $params['clientInfo'] : array();
        $mcp_client_name    = is_string( $mcp_client_info['name'] ?? null ) ? $mcp_client_info['name'] : null;
        $mcp_client_version = is_string( $mcp_client_info['version'] ?? null ) ? $mcp_client_info['version'] : null;
        $is_mcp_connector   = ! empty( $this->jwt_auth->get_token_from_request( $request ) );

        if ( ! $mcp_client_name ) {
            $user_agent      = $request->get_header( 'user_agent' );
            $mcp_client_name = ! empty( $user_agent ) ? $user_agent : self::UNKNOWN_CLIENT;
        }

        $this->send_connected_event(
            $mcp_client_name,
            $mcp_client_version,
            $is_mcp_connector,
            str_contains( $request->get_route(), McpRoutes::DEFAULT_SERVER_ROUTE ) ? 'default' : 'plugin'
        );
    }

    private function send_connected_event( string $mcp_client_name, ?string $mcp_client_version, bool $is_mcp_connector, string $mcp_server ): void {
        $args = array(
            'action'               => Hostinger_Ai_Assistant_Amplitude_Actions::MCP_CONNECTED,
            'mcp_client_name'      => $mcp_client_name,
            'mcp_client_version'   => $mcp_client_version,
            'mcp_is_mcp_connector' => $is_mcp_connector ? 'yes' : 'no',
            'mcp_server'           => $mcp_server,
        );

        $this->amplitude_manager->sendRequest(
            self::AMPLITUDE_ENDPOINT,
            $args
        );
    }
}
