<?php
/**
 * Emoji asset optimization.
 *
 * @package AIV_Performance
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Disable WordPress emoji frontend assets.
 *
 * This removes extra emoji detection JS, styles, and the related CDN hint. It
 * does not alter stored content, feeds, email, admin, or editor behavior.
 *
 * @return void
 */
function aiv_perf_disable_emojis(): void {
	if ( ! aiv_perf_is_frontend_request() ) {
		return;
	}

	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_print_scripts', 'print_emoji_detection_script' );
	remove_filter( 'wp_resource_hints', 'wp_resource_hints_emoji', 10 );
}
add_action( 'init', 'aiv_perf_disable_emojis' );
