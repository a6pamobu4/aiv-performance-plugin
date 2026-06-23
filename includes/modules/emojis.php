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
function aiv_performance_disable_emojis(): void {
	if ( ! aiv_performance_is_frontend_request() ) {
		return;
	}

	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	remove_filter( 'wp_resource_hints', 'wp_resource_hints_emoji', 10 );
}
add_action( 'init', 'aiv_performance_disable_emojis' );
