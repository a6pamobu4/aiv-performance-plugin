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
 * @param string $module  Module file basename without extension.
 * @param bool   $enabled_by_default Default enabled state.
 * @return void
 */
function aiv_performance_load_module( string $module, bool $enabled_by_default = true ): void {
	$filter = 'aiv_performance_enable_' . str_replace( '-', '_', $module ) . '_module';

	if ( ! (bool) apply_filters( $filter, $enabled_by_default ) ) {
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
function aiv_performance_bootstrap(): void {
	$modules = array(
		'emojis'         => true,
		'embeds'         => true,
		'head-cleanup'   => true,
		'resource-hints' => true,
		'scripts'        => true,
		'styles'         => true,
		'images'         => true,
		'woocommerce'    => true,
	);

	foreach ( $modules as $module => $enabled ) {
		aiv_performance_load_module( $module, $enabled );
	}
}
add_action( 'plugins_loaded', 'aiv_performance_bootstrap', 0 );
