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
function aiv_performance_defer_selected_scripts( string $tag, string $handle, string $src ): string {
	unset( $src );

	if ( ! aiv_performance_is_frontend_request() ) {
		return $tag;
	}

	if ( aiv_performance_is_woocommerce_sensitive_page() ) {
		return $tag;
	}

	$defer_handles = aiv_performance_normalize_string_list(
		apply_filters( 'aiv_performance_defer_script_handles', array() )
	);

	if ( ! in_array( $handle, $defer_handles, true ) ) {
		return $tag;
	}

	$blocked_handles = aiv_performance_normalize_string_list(
		apply_filters(
			'aiv_performance_never_defer_script_handles',
			array(
				'jquery-ui-core',
				'jquery',
				'jquery-core',
				'jquery-migrate',
				'woocommerce',
				'wc-add-to-cart',
				'wc-add-to-cart-variation',
				'wc-cart',
				'wc-cart-fragments',
				'wc-checkout',
				'wc-credit-card-form',
				'wc-single-product',
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
add_filter( 'script_loader_tag', 'aiv_performance_defer_selected_scripts', 10, 3 );
