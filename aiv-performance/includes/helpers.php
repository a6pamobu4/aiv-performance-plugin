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
function aiv_perf_is_frontend_request(): bool {
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

	return (bool) apply_filters( 'aiv_performance_is_frontend_request', true );
}

/**
 * Determine whether the current request is for a logged-in user.
 *
 * @return bool
 */
function aiv_perf_is_logged_in_request(): bool {
	return is_user_logged_in();
}

/**
 * Read a boolean setting from a constant and filter.
 *
 * Defining the constant as false is a hard off switch for environments where
 * plugin/theme filters may be too late or too distributed to audit.
 *
 * @param string $filter   Filter name.
 * @param bool   $default  Default value.
 * @param string $constant Optional constant name.
 * @return bool
 */
function aiv_perf_bool_config( string $filter, bool $default, string $constant = '' ): bool {
	if ( $constant && defined( $constant ) && false === (bool) constant( $constant ) ) {
		return false;
	}

	$value = $default;

	if ( $constant && defined( $constant ) ) {
		$value = (bool) constant( $constant );
	}

	return (bool) apply_filters( $filter, $value );
}

/**
 * Normalize a list of string handles/classes.
 *
 * @param mixed $values Values from a filter.
 * @return array<int, string>
 */
function aiv_perf_normalize_string_list( mixed $values ): array {
	if ( ! is_array( $values ) ) {
		return array();
	}

	$values = array_filter(
		array_map(
			static function ( $value ): string {
				return is_string( $value ) ? trim( $value ) : '';
			},
			$values
		)
	);

	return array_values( array_unique( $values ) );
}

/**
 * Check whether WooCommerce is active enough for frontend conditionals.
 *
 * @return bool
 */
function aiv_perf_is_woocommerce_active(): bool {
	return class_exists( 'WooCommerce' );
}

/**
 * Check whether the current request is a sensitive WooCommerce page.
 *
 * @return bool
 */
function aiv_perf_is_woocommerce_sensitive_page(): bool {
	if ( ! aiv_perf_is_woocommerce_active() ) {
		return false;
	}

	$checks = array( 'is_cart', 'is_checkout', 'is_account_page' );

	foreach ( $checks as $check ) {
		if ( function_exists( $check ) && $check() ) {
			return true;
		}
	}

	return false;
}
