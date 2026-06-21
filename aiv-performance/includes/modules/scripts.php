<?php
/**
 * Script loading helpers.
 *
 * @package AIV_Performance
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add defer to explicitly selected script handles.
 *
 * Scripts are never guessed. Developers must opt in by handle and should test
 * forms, checkout, analytics, consent, and interactive components.
 *
 * @param string $tag    Script tag HTML.
 * @param string $handle Script handle.
 * @param string $src    Script source.
 * @return string
 */
function aiv_perf_defer_selected_scripts( string $tag, string $handle, string $src ): string {
	if ( ! aiv_perf_is_frontend_request() || aiv_perf_is_logged_in_request() ) {
		return $tag;
	}

	if ( aiv_perf_is_woocommerce_sensitive_page() ) {
		return $tag;
	}

	$defer_handles = aiv_perf_normalize_string_list(
		apply_filters( 'aiv_performance_defer_script_handles', array() )
	);

	if ( ! in_array( $handle, $defer_handles, true ) ) {
		return $tag;
	}

	$blocked_handles = aiv_perf_normalize_string_list(
		apply_filters(
			'aiv_performance_never_defer_script_handles',
			array(
				'jquery',
				'jquery-core',
				'jquery-migrate',
				'wc-checkout',
				'wc-cart',
				'wc-cart-fragments',
				'woocommerce',
			)
		)
	);

	if ( in_array( $handle, $blocked_handles, true ) ) {
		return $tag;
	}

	if ( false !== strpos( $tag, ' defer ' ) || false !== strpos( $tag, ' defer=' ) ) {
		return $tag;
	}

	return str_replace( '<script ', '<script defer ', $tag );
}
add_filter( 'script_loader_tag', 'aiv_perf_defer_selected_scripts', 10, 3 );
