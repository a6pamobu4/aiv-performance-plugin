<?php
/**
 * Resource hints.
 *
 * @package AIV_Performance
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add developer-configured resource hints.
 *
 * No external domains or preloads are added by default. Projects can add
 * precise hints with the aiv_performance_resource_hints filter.
 *
 * @param array<int, string|array<string, mixed>> $urls          URLs for the relation type.
 * @param string                                  $relation_type Resource hint relation type.
 * @return array<int, string|array<string, mixed>>
 */
function aiv_perf_resource_hints( array $urls, string $relation_type ): array {
	if ( ! aiv_perf_is_frontend_request() ) {
		return $urls;
	}

	$allowed_relation_types = array( 'dns-prefetch', 'preconnect', 'prefetch', 'prerender', 'preload' );

	if ( ! in_array( $relation_type, $allowed_relation_types, true ) ) {
		return $urls;
	}

	$additional_urls = apply_filters( 'aiv_performance_resource_hints', array(), $relation_type );

	if ( ! is_array( $additional_urls ) ) {
		return $urls;
	}

	return array_values( array_unique( array_merge( $urls, $additional_urls ), SORT_REGULAR ) );
}
add_filter( 'wp_resource_hints', 'aiv_perf_resource_hints', 10, 2 );
