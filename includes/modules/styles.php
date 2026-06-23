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
function aiv_performance_dequeue_selected_styles(): void {
	if ( ! aiv_performance_is_frontend_request() ) {
		return;
	}

	if ( aiv_performance_is_woocommerce_sensitive_page() ) {
		return;
	}

	$handles = aiv_performance_normalize_string_list(
		apply_filters( 'aiv_performance_dequeue_style_handles', array() )
	);

	$blocked_handles = aiv_performance_normalize_string_list(
		apply_filters(
			'aiv_performance_never_dequeue_style_handles',
			array(
				'wp-block-library',
				'global-styles',
				'woocommerce-general',
				'woocommerce-layout',
				'woocommerce-smallscreen',
			)
		)
	);

	foreach ( $handles as $handle ) {
		if ( in_array( $handle, $blocked_handles, true ) ) {
			continue;
		}

		wp_dequeue_style( $handle );
	}
}
add_action( 'wp_enqueue_scripts', 'aiv_performance_dequeue_selected_styles', 100 );
