<?php

/**
 * Order Item
 *
 * A class which represents an item within an order
 *
 * @package BakExtension\controllers
 * @version 1.0.0
 * @since   1.0.0
 */

namespace BakExtension\controllers;

defined('ABSPATH') || exit;
class Order
{

    public static function display_asset_fingerprint_in_cart(
        $item_data,
        $cart_item
    ) {
        $fingerprint = get_post_meta($cart_item["product_id"], 'bk_token_fingerprint', true);
        if ($fingerprint) {
            $item_data[] = array(
                'key' => __('Asset', ''),
                'value' => $fingerprint,
                'display' => $fingerprint,
            );
        }


        return $item_data;
    }

    public static function add_asset_fingerprint_to_order_line_item_meta($item, $cart_item_key, $values, $order)
    {
        $fingerprint = get_post_meta($values["data"]->get_id(), 'bk_token_fingerprint', true);
        if ($fingerprint) {
            $item->add_meta_data('Asset', $fingerprint);
        }
    }

    public static function bak_woocommerce_order_item_name($name, $item)
    {
        $fingerprint = null;
        $product = $item->get_product();

        // Check if the product is a variation of a variable product
        if ($product && $product->is_type('variation')) {
            // Get the parent product of the variation
            $parent_product = wc_get_product($product->get_parent_id());

            // Get the custom attribute data from the parent product
            $fingerprint = $parent_product->get_attribute('bk_token_fingerprint');
        } else {
            // Get the custom attribute data from the parent product
            $fingerprint = $product->get_attribute('bk_token_fingerprint');
        }

        if ($fingerprint) {
            // Append the custom attribute data to the item name
            $name .= '<label>' . esc_html($fingerprint) . '</label>';
        }

        return $name;

    }
}