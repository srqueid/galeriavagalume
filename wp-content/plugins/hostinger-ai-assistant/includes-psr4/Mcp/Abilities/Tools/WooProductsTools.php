<?php

namespace Hostinger\AiAssistant\Mcp\Abilities\Tools;

if ( ! defined( 'ABSPATH' ) ) {
    die;
}

class WooProductsTools extends RestEndpointTool {
    public function register(): void {
        if ( ! $this->is_woocommerce_active() ) {
            return;
        }

        $this->register_operations(
            array(
                'list'   => array(
                    'tool_name'   => 'hostinger-ai/wc-products-search',
                    'label'       => __( 'Search WooCommerce Products', 'hostinger-ai-assistant' ),
                    'description' => 'Search and filter WooCommerce products with pagination. Use when you need a product ID, or to find, list or count products.',
                    'meta'        => array(
                        'annotations' => array(
                            'readOnlyHint'  => true,
                            'openWorldHint' => false,
                        ),
                    ),
                ),
                'get'    => array(
                    'tool_name'   => 'hostinger-ai/wc-products-get',
                    'label'       => __( 'Get WooCommerce Product', 'hostinger-ai-assistant' ),
                    'description' => 'Get one WooCommerce product by ID with all its fields. Use when you need the details of a known product. Needs `id` from `hostinger-ai/wc-products-search`.',
                    'meta'        => array(
                        'annotations' => array(
                            'readOnlyHint'  => true,
                            'openWorldHint' => false,
                        ),
                    ),
                ),
                'create' => array(
                    'tool_name'                  => 'hostinger-ai/wc-products-create',
                    'input_schema_modifications' => array( 'required' => array( 'name' ) ),
                    'label'                      => __( 'Create WooCommerce Product', 'hostinger-ai-assistant' ),
                    'description'                => 'Create a WooCommerce product from a name and details such as price and description. Use when asked to add a product to the store. Optional category and tag IDs come from `hostinger-ai/wc-product-categories-list` and `hostinger-ai/wc-product-tags-list`, and image IDs from `hostinger-ai/media-search`.',
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
                    'tool_name'   => 'hostinger-ai/wc-products-update',
                    'label'       => __( 'Update WooCommerce Product', 'hostinger-ai-assistant' ),
                    'description' => 'Update a WooCommerce product; only the fields you pass change. Use when asked to change a price, stock level, description or other product detail. Needs `id` from `hostinger-ai/wc-products-search`.',
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
                    'tool_name'   => 'hostinger-ai/wc-products-delete',
                    'label'       => __( 'Delete WooCommerce Product', 'hostinger-ai-assistant' ),
                    'description' => 'Move a WooCommerce product to the trash; it can be restored from the trash. Use when asked to remove a product. Needs `id` from `hostinger-ai/wc-products-search`.',
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
            'WC_REST_Products_Controller',
            '/wc/v3/products',
            'product'
        );

        $this->register_operations(
            array(
                'list'   => array(
                    'tool_name'   => 'hostinger-ai/wc-product-categories-list',
                    'label'       => __( 'List WooCommerce Product Categories', 'hostinger-ai-assistant' ),
                    'description' => 'List WooCommerce product categories. Use when you need a product category ID, for example to file a product under a category.',
                    'meta'        => array(
                        'annotations' => array(
                            'readOnlyHint'  => true,
                            'openWorldHint' => false,
                        ),
                    ),
                ),
                'create' => array(
                    'tool_name'                  => 'hostinger-ai/wc-product-categories-create',
                    'input_schema_modifications' => array( 'required' => array( 'name' ) ),
                    'label'                      => __( 'Create WooCommerce Product Category', 'hostinger-ai-assistant' ),
                    'description'                => 'Create a WooCommerce product category from a name. Use when the category you need is not in `hostinger-ai/wc-product-categories-list` yet.',
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
                    'tool_name'   => 'hostinger-ai/wc-product-categories-update',
                    'label'       => __( 'Update WooCommerce Product Category', 'hostinger-ai-assistant' ),
                    'description' => 'Update a WooCommerce product category. Use when asked to rename a product category or change its parent. Needs `id` from `hostinger-ai/wc-product-categories-list`.',
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
                    'tool_name'                  => 'hostinger-ai/wc-product-categories-delete',
                    'label'                      => __( 'Delete WooCommerce Product Category', 'hostinger-ai-assistant' ),
                    'description'                => 'Permanently delete a WooCommerce product category; this cannot be undone. Use when asked to remove a product category. Needs `id` from `hostinger-ai/wc-product-categories-list`.',
                    'input_schema_modifications' => array(
                        'type'       => 'object',
                        'properties' => array(
                            'force' => array(
                                'type'        => 'boolean',
                                'description' => 'Force category deletion',
                            ),
                        ),
                    ),
                    'meta'                       => array(
                        'annotations' => array(
                            'readOnlyHint'    => false,
                            'destructiveHint' => true,
                            'idempotentHint'  => true,
                            'openWorldHint'   => false,
                        ),
                    ),
                ),
            ),
            'WC_REST_Product_Categories_Controller',
            '/wc/v3/products/categories',
            'product_cat'
        );

        $this->register_operations(
            array(
                'list'   => array(
                    'tool_name'   => 'hostinger-ai/wc-product-tags-list',
                    'label'       => __( 'List WooCommerce Product Tags', 'hostinger-ai-assistant' ),
                    'description' => 'List WooCommerce product tags. Use when you need a product tag ID, for example to tag a product.',
                    'meta'        => array(
                        'annotations' => array(
                            'readOnlyHint'  => true,
                            'openWorldHint' => false,
                        ),
                    ),
                ),
                'create' => array(
                    'tool_name'                  => 'hostinger-ai/wc-product-tags-create',
                    'input_schema_modifications' => array( 'required' => array( 'name' ) ),
                    'label'                      => __( 'Create WooCommerce Product Tag', 'hostinger-ai-assistant' ),
                    'description'                => 'Create a WooCommerce product tag from a name. Use when the tag you need is not in `hostinger-ai/wc-product-tags-list` yet.',
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
                    'tool_name'   => 'hostinger-ai/wc-product-tags-update',
                    'label'       => __( 'Update WooCommerce Product Tag', 'hostinger-ai-assistant' ),
                    'description' => 'Update a WooCommerce product tag. Use when asked to rename a product tag. Needs `id` from `hostinger-ai/wc-product-tags-list`.',
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
                    'tool_name'                  => 'hostinger-ai/wc-product-tags-delete',
                    'label'                      => __( 'Delete WooCommerce Product Tag', 'hostinger-ai-assistant' ),
                    'description'                => 'Permanently delete a WooCommerce product tag; this cannot be undone. Use when asked to remove a product tag. Needs `id` from `hostinger-ai/wc-product-tags-list`.',
                    'input_schema_modifications' => array(
                        'type'       => 'object',
                        'properties' => array(
                            'force' => array(
                                'type'        => 'boolean',
                                'description' => 'Force tag deletion',
                            ),
                        ),
                    ),
                    'meta'                       => array(
                        'annotations' => array(
                            'readOnlyHint'    => false,
                            'destructiveHint' => true,
                            'idempotentHint'  => true,
                            'openWorldHint'   => false,
                        ),
                    ),
                ),
            ),
            'WC_REST_Product_Tags_Controller',
            '/wc/v3/products/tags',
            'product_tag'
        );

        $this->register_operations(
            array(
                'list'   => array(
                    'tool_name'   => 'hostinger-ai/wc-product-brands-list',
                    'label'       => __( 'List WooCommerce Product Brands', 'hostinger-ai-assistant' ),
                    'description' => 'List WooCommerce product brands. Use when you need a product brand ID, for example to assign a brand to a product.',
                    'meta'        => array(
                        'annotations' => array(
                            'readOnlyHint'  => true,
                            'openWorldHint' => false,
                        ),
                    ),
                ),
                'create' => array(
                    'tool_name'                  => 'hostinger-ai/wc-product-brands-create',
                    'input_schema_modifications' => array( 'required' => array( 'name' ) ),
                    'label'                      => __( 'Create WooCommerce Product Brand', 'hostinger-ai-assistant' ),
                    'description'                => 'Create a WooCommerce product brand from a name. Use when the brand you need is not in `hostinger-ai/wc-product-brands-list` yet.',
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
                    'tool_name'   => 'hostinger-ai/wc-product-brands-update',
                    'label'       => __( 'Update WooCommerce Product Brand', 'hostinger-ai-assistant' ),
                    'description' => 'Update a WooCommerce product brand. Use when asked to rename a brand. Needs `id` from `hostinger-ai/wc-product-brands-list`.',
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
                    'tool_name'                  => 'hostinger-ai/wc-product-brands-delete',
                    'label'                      => __( 'Delete WooCommerce Product Brand', 'hostinger-ai-assistant' ),
                    'description'                => 'Permanently delete a WooCommerce product brand; this cannot be undone. Use when asked to remove a brand. Needs `id` from `hostinger-ai/wc-product-brands-list`.',
                    'input_schema_modifications' => array(
                        'type'       => 'object',
                        'properties' => array(
                            'force' => array(
                                'type'        => 'boolean',
                                'description' => 'Force Product Brand deletion',
                            ),
                        ),
                    ),
                    'meta'                       => array(
                        'annotations' => array(
                            'readOnlyHint'    => false,
                            'destructiveHint' => true,
                            'idempotentHint'  => true,
                            'openWorldHint'   => false,
                        ),
                    ),
                ),
            ),
            'WC_REST_Product_Brands_Controller',
            '/wc/v3/products/brands',
            'product_brand'
        );
    }

    protected function is_woocommerce_active(): bool {
        return class_exists( 'WooCommerce' );
    }
}
