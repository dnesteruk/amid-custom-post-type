<?php
/**
 * Plugin Name: Amid Custom Post Type
 * Description: Custom post type with filter.
 * Version: 1.0
 * Author: Dmitry Nesteruk
 * Author URI: #
 * Text Domain: amid-custom-post-type
 * Domain Path: /languages
 * Requires at least: 5.9.2
 * Requires PHP: 7.3
 *
 */

defined('ABSPATH') || exit;

if (!defined('AMID_CPT_VERSION')) {
	define('AMID_CPT_VERSION', '1.0');
}

require_once __DIR__ . '/includes/class-news.php';
require_once __DIR__ . '/includes/role-capability.php';
require_once __DIR__ . '/includes/filter-result.php';

register_activation_hook( __FILE__, 'amid_activate_plugin' );
register_deactivation_hook( __FILE__, 'amid_deactivation_plugin' );
function amid_activate_plugin() {
	do_action( 'amid_run_plugin' );
}

function amid_deactivation_plugin() {
	do_action( 'amid_stop_plugin' );
}

add_action( 'plugins_loaded', 'amid_custom_post_type_load_locale' );
function amid_custom_post_type_load_locale() {
	load_plugin_textdomain( 'amid-custom-post-type', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

function wellspace_scripts() {
	wp_enqueue_style( 'amid-cpt', plugins_url('/assets/css/style.css', __FILE__), array(), AMID_CPT_VERSION );
	wp_enqueue_script( 'amid-cpt-filter', plugins_url('/assets/js/filter.js', __FILE__), array('jquery'), AMID_CPT_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'wellspace_scripts' );
