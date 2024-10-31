<?php
/** 
 * Plugin Name: One Item Allow Cart
 * Description: This plugin is restricts the number of products that can be added to the cart to just one. This means that if a customer adds a new product to the cart, any existing product will be removed automatically, ensuring that only one product remains in the cart.
 * Author: Meet Raj
 * Author URI: https://meetraj093.wordpress.com/
 * Version: 1.0.0
 * License: GPL2 or later
 * Text Domain: one-item-allow-cart
 * Domain Path: /languages
 */

defined('ABSPATH') || exit;

class OneCart {
    function __construct() {
        add_action( 'woocommerce_before_calculate_totals', array( $this, 'one_cart_validation' ), 30, 1 );
    }

    function one_cart_validation( $cart ) {
        if ( is_admin() && ! defined( 'DOING_AJAX' ) )
            return;
        
        // Perform your custom validation here
        if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 )
            return;
        
        $cart_items = $cart->get_cart();
        if ( count( $cart_items ) > 1 ) {
            // Remove all items from the cart except the last one
            $cart_item_keys = array_keys( $cart_items );
            $last_key = end( $cart_item_keys );
            foreach ($cart_item_keys as $key) {
                if ($key !== $last_key) {
                    $cart->remove_cart_item( $key );
                }
            }
        }
    }
}

$one_cart_plugin = new OneCart();
