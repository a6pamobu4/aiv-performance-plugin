<?php
/**
 * Safe head cleanup.
 *
 * @package AIV_Performance
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Remove low-risk head output on frontend requests.
 *
 * Canonical tags, SEO plugin output, and REST links remain untouched by default.
 *
 * @return void
 */
function aiv_performance_cleanup_head_output(): void {
	if ( ! aiv_performance_is_frontend_request() ) {
		return;
	}

	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head', 10 );
	remove_action( 'template_redirect', 'wp_shortlink_header', 11 );
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );

	if ( (bool) apply_filters( 'aiv_performance_remove_rest_api_head_links', false ) ) {
		remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
		remove_action( 'template_redirect', 'rest_output_link_header', 11 );
	}
}
add_action( 'init', 'aiv_performance_cleanup_head_output' );
