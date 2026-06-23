<?php
/**
 * WooCommerce-safe helpers.
 *
 * @package AIV_Performance
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Dequeue WooCommerce cart fragments on clearly non-commerce pages.
 *
 * This is disabled by default because cart fragments support dynamic mini-cart
 * behavior. Enable only when the theme does not need mini-cart updates outside
 * WooCommerce pages.
 *
 * @return void
 */
function aiv_performance_maybe_disable_wc_cart_fragments(): void {
	if ( ! aiv_performance_is_frontend_request() || ! aiv_performance_is_woocommerce_active() ) {
		return;
	}

	if ( ! apply_filters( 'aiv_performance_optimize_woocommerce_assets', false ) ) {
		return;
	}

	if ( ! apply_filters( 'aiv_performance_disable_cart_fragments_on_non_wc_pages', false ) ) {
		return;
	}

	if ( aiv_performance_is_woocommerce_sensitive_page() ) {
		return;
	}

	if ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
		return;
	}

	wp_dequeue_script( 'wc-cart-fragments' );
}
add_action( 'wp_enqueue_scripts', 'aiv_performance_maybe_disable_wc_cart_fragments', 100 );
