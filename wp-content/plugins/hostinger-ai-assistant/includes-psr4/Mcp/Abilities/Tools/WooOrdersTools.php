<?php

namespace Hostinger\AiAssistant\Mcp\Abilities\Tools;

if ( ! defined( 'ABSPATH' ) ) {
    die;
}

class WooOrdersTools extends RestEndpointTool {
    public function register(): void {
        if ( ! $this->is_woocommerce_active() ) {
            return;
        }

        $this->register_operations(
            array(
                'list'   => array(
                    'tool_name'   => 'hostinger-ai/wc-orders-search',
                    'label'       => __( 'Search WooCommerce Orders', 'hostinger-ai-assistant' ),
                    'description' => 'Search and filter WooCommerce orders by status, customer, product or date, with pagination. Use when you need an order ID, or to find, list or count orders.',
                    'meta'        => array(
                        'annotations' => array(
                            'readOnlyHint'  => true,
                            'openWorldHint' => false,
                        ),
                    ),
                ),
                'get'    => array(
                    'tool_name'   => 'hostinger-ai/wc-orders-get',
                    'label'       => __( 'Get WooCommerce Order', 'hostinger-ai-assistant' ),
                    'description' => 'Get one WooCommerce order by ID with all its fields. Use when you need the details of a known order. Needs `id` from `hostinger-ai/wc-orders-search`.',
                    'meta'        => array(
                        'annotations' => array(
                            'readOnlyHint'  => true,
                            'openWorldHint' => false,
                        ),
                    ),
                ),
                'create' => array(
                    'tool_name'   => 'hostinger-ai/wc-orders-create',
                    'label'       => __( 'Create WooCommerce Order', 'hostinger-ai-assistant' ),
                    'description' => 'Create a WooCommerce order from customer details and line items. Use when asked to place or record an order manually. Line item `product_id` values come from `hostinger-ai/wc-products-search` and `customer_id` from `hostinger-ai/users-search`.',
                    'meta'        => array(
                        'annotations' => array(
                            'readOnlyHint'    => false,
                            'destructiveHint' => false,
                            'idempotentHint'  => false,
                            'openWorldHint'   => false,
                        ),
                    ),
                ),
                'update' => array(
                    'tool_name'   => 'hostinger-ai/wc-orders-update',
                    'label'       => __( 'Update WooCommerce Order', 'hostinger-ai-assistant' ),
                    'description' => 'Update the status or other editable fields of a WooCommerce order. Setting the status to refunded does not refund the payment. Use when asked to fulfil, cancel or edit an order. Needs `id` from `hostinger-ai/wc-orders-search`.',
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
                    'tool_name'   => 'hostinger-ai/wc-orders-delete',
                    'label'       => __( 'Delete WooCommerce Order', 'hostinger-ai-assistant' ),
                    'description' => 'Move a WooCommerce order to the trash; it can be restored from the trash. Use when asked to remove an order. Needs `id` from `hostinger-ai/wc-orders-search`.',
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
            'WC_REST_Orders_Controller',
            '/wc/v3/orders',
            'shop_order'
        );

        $reports = array(
            array(
                'tool_name'   => 'hostinger-ai/wc-reports-coupons-totals',
                'label'       => __( 'Get WooCommerce Coupons Report', 'hostinger-ai-assistant' ),
                'description' => 'Get the number of WooCommerce coupons by coupon type. Use when asked how many coupons the store has.',
                'route'       => '/wc/v3/reports/coupons/totals',
            ),
            array(
                'tool_name'   => 'hostinger-ai/wc-reports-customers-totals',
                'label'       => __( 'Get WooCommerce Customers Report', 'hostinger-ai-assistant' ),
                'description' => 'Get the number of WooCommerce customers, paying and non-paying. Use when asked how many customers the store has.',
                'route'       => '/wc/v3/reports/customers/totals',
            ),
            array(
                'tool_name'   => 'hostinger-ai/wc-reports-orders-totals',
                'label'       => __( 'Get WooCommerce Orders Report', 'hostinger-ai-assistant' ),
                'description' => 'Get the number of WooCommerce orders by status. Use when asked how many orders the store has or how many are in each status.',
                'route'       => '/wc/v3/reports/orders/totals',
            ),
            array(
                'tool_name'   => 'hostinger-ai/wc-reports-products-totals',
                'label'       => __( 'Get WooCommerce Products Report', 'hostinger-ai-assistant' ),
                'description' => 'Get the number of WooCommerce products by product type. Use when asked how many products the store has.',
                'route'       => '/wc/v3/reports/products/totals',
            ),
            array(
                'tool_name'   => 'hostinger-ai/wc-reports-reviews-totals',
                'label'       => __( 'Get WooCommerce Reviews Report', 'hostinger-ai-assistant' ),
                'description' => 'Get the number of WooCommerce product reviews by rating. Use when asked how many reviews the store has or how they are rated.',
                'route'       => '/wc/v3/reports/reviews/totals',
            ),
            array(
                'tool_name'                  => 'hostinger-ai/wc-reports-sales',
                'label'                      => __( 'Get WooCommerce Sales Report', 'hostinger-ai-assistant' ),
                'description'                => 'Get WooCommerce sales and revenue for a period, with totals for orders, items, tax, shipping and discounts. Use when asked how much the store sold or earned.',
                'route'                      => '/wc/v3/reports/sales',
                'input_schema_modifications' => array(
                    'properties' => array(
                        'period'   => array(
                            'type'        => 'string',
                            'description' => 'Report period. Options: week, month, last_month, year.',
                            'enum'        => array( 'week', 'month', 'last_month', 'year' ),
                        ),
                        'date_min' => array(
                            'type'        => 'string',
                            'description' => 'Return sales for a specific start date. Format: YYYY-MM-DD.',
                        ),
                        'date_max' => array(
                            'type'        => 'string',
                            'description' => 'Return sales for a specific end date. Format: YYYY-MM-DD.',
                        ),
                    ),
                ),
            ),
        );

        foreach ( $reports as $report ) {
            $report_config = array(
                'tool_name'   => $report['tool_name'],
                'label'       => $report['label'],
                'description' => $report['description'],
                'meta'        => array(
                    'annotations' => array(
                        'readOnlyHint'  => true,
                        'openWorldHint' => false,
                    ),
                ),
            );

            if ( ! empty( $report['input_schema_modifications'] ) ) {
                $report_config['input_schema_modifications'] = $report['input_schema_modifications'];
            }

            $this->register_operations(
                array(
                    'report' => $report_config,
                ),
                'WC_REST_Orders_Controller',
                $report['route'],
                'shop_order'
            );
        }
    }

    private function is_woocommerce_active(): bool {
        return class_exists( 'WooCommerce' );
    }
}
