<?php

namespace Hostinger\AiAssistant\Mcp\Abilities\Tools;

if ( ! defined( 'ABSPATH' ) ) {
    die;
}

class WooCouponsTools extends RestEndpointTool {
    public function register(): void {
        if ( ! $this->is_woocommerce_active() ) {
            return;
        }

        $this->register_operations(
            array(
                'list'   => array(
                    'tool_name'   => 'hostinger-ai/wc-coupons-search',
                    'label'       => __( 'Search WooCommerce Coupons', 'hostinger-ai-assistant' ),
                    'description' => 'Search and filter WooCommerce coupons with pagination. Use when you need a coupon ID, or to check which coupon codes exist.',
                    'meta'        => array(
                        'annotations' => array(
                            'readOnlyHint'  => true,
                            'openWorldHint' => false,
                        ),
                    ),
                ),
                'get'    => array(
                    'tool_name'   => 'hostinger-ai/wc-coupons-get',
                    'label'       => __( 'Get WooCommerce Coupon', 'hostinger-ai-assistant' ),
                    'description' => 'Get one WooCommerce coupon by ID with all its fields. Use when you need the details of a known coupon. Needs `id` from `hostinger-ai/wc-coupons-search`.',
                    'meta'        => array(
                        'annotations' => array(
                            'readOnlyHint'  => true,
                            'openWorldHint' => false,
                        ),
                    ),
                ),
                'create' => array(
                    'tool_name'                  => 'hostinger-ai/wc-coupons-create',
                    'input_schema_modifications' => array( 'required' => array( 'code' ) ),
                    'label'                      => __( 'Create WooCommerce Coupon', 'hostinger-ai-assistant' ),
                    'description'                => 'Create a WooCommerce coupon from a code and discount details. Use when asked to add a discount or promo code.',
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
                    'tool_name'   => 'hostinger-ai/wc-coupons-update',
                    'label'       => __( 'Update WooCommerce Coupon', 'hostinger-ai-assistant' ),
                    'description' => 'Update a WooCommerce coupon; only the fields you pass change. Use when asked to change a discount, expiry date or usage limit. Needs `id` from `hostinger-ai/wc-coupons-search`.',
                    'meta'        => array(
                        'annotations' => array(
                            'readOnlyHint'    => false,
                            'destructiveHint' => false,
                            'idempotentHint'  => true,
                            'openWorldHint'   => false,
                        ),
                    ),
                ),
                'delete' => array(
                    'tool_name'   => 'hostinger-ai/wc-coupons-delete',
                    'label'       => __( 'Delete WooCommerce Coupon', 'hostinger-ai-assistant' ),
                    'description' => 'Move a WooCommerce coupon to the trash; it can be restored from the trash. Use when asked to remove a coupon. Needs `id` from `hostinger-ai/wc-coupons-search`.',
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
            'WC_REST_Coupons_Controller',
            '/wc/v3/coupons',
            'shop_coupon'
        );

        $this->register_operations(
            array(
                'batch' => array(
                    'tool_name'   => 'hostinger-ai/wc-coupons-batch',
                    'label'       => __( 'Batch Update WooCommerce Coupons', 'hostinger-ai-assistant' ),
                    'description' => 'Create, update and delete several WooCommerce coupons in one request. Use when asked to change many coupons at once. IDs for `update` and `delete` come from `hostinger-ai/wc-coupons-search`.',
                    'meta'        => array(
                        'annotations' => array(
                            'readOnlyHint'    => false,
                            'destructiveHint' => true,
                            'idempotentHint'  => false,
                            'openWorldHint'   => false,
                        ),
                    ),
                ),
            ),
            'WC_REST_Coupons_Controller',
            '/wc/v3/coupons/batch',
            'shop_coupon'
        );
    }

    protected function is_woocommerce_active(): bool {
        return class_exists( 'WooCommerce' );
    }
}
