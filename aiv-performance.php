<?php
/**
 * Plugin Name: AIV Performance
 * Description: Safe modular performance optimizations for custom WordPress projects.
 * Author: AIV-web
 * Version: 0.1.0
 * Text Domain: aiv-performance
 * Requires PHP: 8.1
 *
 * @package AIV_Performance
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'AIV_PERFORMANCE_VERSION', '0.1.0' );
define( 'AIV_PERFORMANCE_FILE', __FILE__ );
define( 'AIV_PERFORMANCE_PATH', plugin_dir_path( __FILE__ ) );
define( 'AIV_PERFORMANCE_URL', plugin_dir_url( __FILE__ ) );

require_once AIV_PERFORMANCE_PATH . 'includes/bootstrap.php';
