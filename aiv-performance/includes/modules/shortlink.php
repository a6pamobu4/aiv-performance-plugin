<?php
/**
 * Shortlink and adjacent post link cleanup.
 *
 * @package AIV_Performance
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Remove low-value head links from normal frontend pages.
 *
 * Adjacent post links are not required for modern themes, but this may affect
 * legacy SEO or browser navigation expectations, so the whole module is
 * filterable.
 *
 * @return void
 */
function aiv_perf_remove_shortlink_head_links(): void {
	if ( ! aiv_perf_is_frontend_request() ) {
		return;
	}

	remove_action( 'wp_head', 'wp_shortlink_wp_head', 10 );
	remove_action( 'template_redirect', 'wp_shortlink_header', 11 );
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
}
add_action( 'init', 'aiv_perf_remove_shortlink_head_links' );
