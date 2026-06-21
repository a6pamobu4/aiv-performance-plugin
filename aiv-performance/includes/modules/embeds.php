<?php
/**
 * Embed asset optimization.
 *
 * @package AIV_Performance
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Remove the wp-embed script from normal frontend pages.
 *
 * oEmbed discovery and editor features are left alone. Only the frontend helper
 * script is dequeued, which can affect embedding this site's posts elsewhere.
 *
 * @return void
 */
function aiv_perf_disable_wp_embed_script(): void {
	if ( ! aiv_perf_is_frontend_request() ) {
		return;
	}

	wp_dequeue_script( 'wp-embed' );
	wp_deregister_script( 'wp-embed' );
}
add_action( 'wp_footer', 'aiv_perf_disable_wp_embed_script', 1 );
