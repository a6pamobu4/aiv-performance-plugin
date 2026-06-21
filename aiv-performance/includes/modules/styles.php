<?php
/**
 * Stylesheet helpers.
 *
 * @package AIV_Performance
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Dequeue explicitly selected styles.
 *
 * This intentionally does not remove wp-block-library or block styles globally.
 * Developers can opt in by handle after testing their theme and blocks.
 *
 * @return void
 */
function aiv_perf_dequeue_selected_styles(): void {
	if ( ! aiv_perf_is_frontend_request() || aiv_perf_is_logged_in_request() ) {
		return;
	}

	if ( aiv_perf_is_woocommerce_sensitive_page() ) {
		return;
	}

	$handles = aiv_perf_normalize_string_list(
		apply_filters( 'aiv_performance_dequeue_style_handles', array() )
	);

	foreach ( $handles as $handle ) {
		wp_dequeue_style( $handle );
	}
}
add_action( 'wp_enqueue_scripts', 'aiv_perf_dequeue_selected_styles', 100 );
