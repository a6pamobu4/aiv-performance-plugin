<?php
/**
 * Plugin bootstrap and module loader.
 *
 * @package AIV_Performance
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once AIV_PERFORMANCE_PATH . 'includes/helpers.php';

/**
 * Load one module when its enable filter allows it.
 *
 * @param string $module   Module file basename without extension.
 * @param string $filter   Filter name controlling the module.
 * @param bool   $default  Default enabled state.
 * @param string $constant Optional constant name that can force the module off.
 * @return void
 */
function aiv_perf_load_module( string $module, string $filter, bool $default = true, string $constant = '' ): void {
	if ( ! aiv_perf_bool_config( $filter, $default, $constant ) ) {
		return;
	}

	$path = AIV_PERFORMANCE_PATH . 'includes/modules/' . $module . '.php';

	if ( is_readable( $path ) ) {
		require_once $path;
	}
}

/**
 * Load frontend-safe modules.
 *
 * Modules register their own hooks and must guard admin, AJAX, REST, cron, and
 * editor contexts before changing frontend output.
 *
 * @return void
 */
function aiv_perf_bootstrap(): void {
	$modules = array(
		'emojis'         => array( 'aiv_performance_enable_emojis_optimization', true, 'AIV_PERFORMANCE_ENABLE_EMOJIS_OPTIMIZATION' ),
		'embeds'         => array( 'aiv_performance_enable_embeds_optimization', true, 'AIV_PERFORMANCE_ENABLE_EMBEDS_OPTIMIZATION' ),
		'wp-version'     => array( 'aiv_performance_enable_wp_version_optimization', true, 'AIV_PERFORMANCE_ENABLE_WP_VERSION_OPTIMIZATION' ),
		'shortlink'      => array( 'aiv_performance_enable_shortlink_optimization', true, 'AIV_PERFORMANCE_ENABLE_SHORTLINK_OPTIMIZATION' ),
		'resource-hints' => array( 'aiv_performance_enable_resource_hints', true, 'AIV_PERFORMANCE_ENABLE_RESOURCE_HINTS' ),
		'scripts'        => array( 'aiv_performance_enable_scripts_optimization', true, 'AIV_PERFORMANCE_ENABLE_SCRIPTS_OPTIMIZATION' ),
		'styles'         => array( 'aiv_performance_enable_styles_optimization', true, 'AIV_PERFORMANCE_ENABLE_STYLES_OPTIMIZATION' ),
		'images'         => array( 'aiv_performance_enable_images_optimization', true, 'AIV_PERFORMANCE_ENABLE_IMAGES_OPTIMIZATION' ),
		'woocommerce'    => array( 'aiv_performance_enable_woocommerce_optimization', true, 'AIV_PERFORMANCE_ENABLE_WOOCOMMERCE_OPTIMIZATION' ),
	);

	foreach ( $modules as $module => $args ) {
		aiv_perf_load_module( $module, $args[0], $args[1], $args[2] );
	}
}
add_action( 'after_setup_theme', 'aiv_perf_bootstrap', 0 );
