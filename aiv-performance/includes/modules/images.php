<?php
/**
 * Image attribute helpers.
 *
 * @package AIV_Performance
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Adjust image attributes only when explicitly configured.
 *
 * WordPress already lazy-loads many images. This module lets projects exclude
 * known images from lazy loading or promote a known LCP image without guessing.
 *
 * @param array<string, string|int|bool> $attr       Image attributes.
 * @param WP_Post                       $attachment Attachment object.
 * @param string|array<int, int>        $size       Requested image size.
 * @return array<string, string|int|bool>
 */
function aiv_perf_filter_attachment_image_attributes( array $attr, WP_Post $attachment, string|array $size ): array {
	if ( ! aiv_perf_is_frontend_request() ) {
		return $attr;
	}

	$exclude_ids = array_map(
		'absint',
		(array) apply_filters( 'aiv_performance_lazy_load_excluded_attachment_ids', array() )
	);

	$exclude_classes = aiv_perf_normalize_string_list(
		apply_filters( 'aiv_performance_lazy_load_excluded_classes', array() )
	);

	$classes = isset( $attr['class'] ) && is_string( $attr['class'] ) ? preg_split( '/\s+/', $attr['class'] ) : array();
	$classes = is_array( $classes ) ? $classes : array();

	if ( in_array( (int) $attachment->ID, $exclude_ids, true ) || array_intersect( $exclude_classes, $classes ) ) {
		unset( $attr['loading'] );
	}

	$fetchpriority_high_id = absint(
		apply_filters( 'aiv_performance_fetchpriority_high_attachment_id', 0 )
	);

	$fetchpriority_high_classes = aiv_perf_normalize_string_list(
		apply_filters( 'aiv_performance_fetchpriority_high_classes', array() )
	);

	if ( ( $fetchpriority_high_id && (int) $attachment->ID === $fetchpriority_high_id ) || array_intersect( $fetchpriority_high_classes, $classes ) ) {
		$attr['fetchpriority'] = 'high';
		unset( $attr['loading'] );
	}

	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'aiv_perf_filter_attachment_image_attributes', 10, 3 );
