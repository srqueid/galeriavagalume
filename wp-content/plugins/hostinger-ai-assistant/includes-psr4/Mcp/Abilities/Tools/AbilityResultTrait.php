<?php

namespace Hostinger\AiAssistant\Mcp\Abilities\Tools;

use WP_Error;

if ( ! defined( 'ABSPATH' ) ) {
    die;
}

trait AbilityResultTrait {
    protected function to_ability_result( array $result ): WP_Error|array {
        if ( ! isset( $result['error_code'] ) || ! empty( $result['requires_confirmation'] ) ) {
            return $result;
        }

        return new WP_Error(
            strtolower( $result['error_code'] ),
            $result['message'] ?? $result['error_code'],
            array( 'status' => 400 )
        );
    }
}
