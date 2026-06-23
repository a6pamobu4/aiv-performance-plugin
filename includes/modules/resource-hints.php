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
 * Add developer-configured DNS prefetch and preconnect URLs.
 *
 * No external domains are added by default.
 *
 * @param array<int, string|array<string, mixed>> $urls          URLs for the relation type.
 * @param string                                  $relation_type Resource hint relation type.
 * @return array<int, string|array<string, mixed>>
 */
function aiv_performance_resource_hints( array $urls, string $relation_type ): array {
	if ( ! aiv_performance_is_frontend_request() ) {
		return $urls;
	}

	if ( 'preconnect' === $relation_type ) {
		$additional_urls = apply_filters( 'aiv_performance_preconnect_urls', array() );
	} elseif ( 'dns-prefetch' === $relation_type ) {
		$additional_urls = apply_filters( 'aiv_performance_dns_prefetch_urls', array() );
	} else {
		$additional_urls = array();
	}

	if ( ! is_array( $additional_urls ) ) {
		return $urls;
	}

	return array_values( array_unique( array_merge( $urls, $additional_urls ), SORT_REGULAR ) );
}
add_filter( 'wp_resource_hints', 'aiv_performance_resource_hints', 10, 2 );

/**
 * Print developer-configured preload links.
 *
 * @return void
 */
function aiv_performance_print_preload_assets(): void {
	if ( ! aiv_performance_is_frontend_request() ) {
		return;
	}

	$assets = apply_filters( 'aiv_performance_preload_assets', array() );

	if ( ! is_array( $assets ) ) {
		return;
	}

	foreach ( $assets as $asset ) {
		if ( ! is_array( $asset ) || empty( $asset['href'] ) || ! is_string( $asset['href'] ) ) {
			continue;
		}

		$attributes = array(
			'rel'  => 'preload',
			'href' => esc_url( $asset['href'] ),
		);

		foreach ( array( 'as', 'type', 'media', 'imagesrcset', 'imagesizes' ) as $key ) {
			if ( isset( $asset[ $key ] ) && is_scalar( $asset[ $key ] ) && '' !== (string) $asset[ $key ] ) {
				$attributes[ $key ] = esc_attr( (string) $asset[ $key ] );
			}
		}

		if ( ! empty( $asset['crossorigin'] ) ) {
			$crossorigin = is_string( $asset['crossorigin'] ) ? $asset['crossorigin'] : 'anonymous';

			if ( in_array( $crossorigin, array( 'anonymous', 'use-credentials' ), true ) ) {
				$attributes['crossorigin'] = esc_attr( $crossorigin );
			}
		}

		$output = '<link';

		foreach ( $attributes as $name => $value ) {
			$output .= sprintf( ' %s="%s"', esc_attr( $name ), $value );
		}

		$output .= ' />' . "\n";

		echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Attributes are escaped above.
	}
}
add_action( 'wp_head', 'aiv_performance_print_preload_assets', 1 );
