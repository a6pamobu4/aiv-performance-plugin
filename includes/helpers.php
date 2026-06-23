<?php
/**
 * Shared helpers.
 *
 * @package AIV_Performance
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Determine whether frontend optimizations should be allowed in this request.
 *
 * @return bool
 */
function aiv_performance_is_frontend_request(): bool {
	if ( is_admin() ) {
		return false;
	}

	if ( wp_doing_ajax() || wp_doing_cron() ) {
		return false;
	}

	if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
		return false;
	}

	if ( function_exists( 'wp_is_json_request' ) && wp_is_json_request() ) {
		return false;
	}

	if ( defined( 'WP_CLI' ) && WP_CLI ) {
		return false;
	}

	return (bool) apply_filters( 'aiv_performance_is_frontend_request', true );
}

/**
 * Normalize a list of string handles/classes/URLs.
 *
 * @param mixed $values Values from a filter.
 * @return array<int, string>
 */
function aiv_performance_normalize_string_list( mixed $values ): array {
	if ( ! is_array( $values ) ) {
		return array();
	}

	$values = array_map(
		'trim',
		array_map(
			static function ( $value ): string {
				return is_scalar( $value ) ? (string) $value : '';
			},
			$values
		)
	);
	$values = array_filter( $values );

	return array_values( array_unique( $values ) );
}

/**
 * Check whether WooCommerce is active enough for frontend conditionals.
 *
 * @return bool
 */
function aiv_performance_is_woocommerce_active(): bool {
	return class_exists( 'WooCommerce' );
}

/**
 * Check whether the current request is a sensitive WooCommerce page.
 *
 * @return bool
 */
function aiv_performance_is_woocommerce_sensitive_page(): bool {
	if ( ! aiv_performance_is_woocommerce_active() ) {
		return false;
	}

	$checks = array(
		'is_cart',
		'is_checkout',
		'is_account_page',
		'is_product',
		'is_product_category',
		'is_product_tag',
	);

	foreach ( $checks as $check ) {
		if ( function_exists( $check ) && $check() ) {
			return true;
		}
	}

	return false;
}
