<?php
/**
 * WordPress version exposure optimization.
 *
 * @package AIV_Performance
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Remove the WordPress generator meta tag from frontend output.
 *
 * Version query strings are intentionally not removed in v1 because they are
 * useful for cache invalidation and removing them broadly can be risky.
 *
 * @return void
 */
function aiv_perf_remove_wp_generator(): void {
	if ( ! aiv_perf_is_frontend_request() ) {
		return;
	}

	remove_action( 'wp_head', 'wp_generator' );
}
add_action( 'init', 'aiv_perf_remove_wp_generator' );
