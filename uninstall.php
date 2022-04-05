<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package   Amid Custom Post Type
 * @author    Dmitry Nesteruk
 * @link      #
 * @copyright Dmitry Nesteruk
 */

// If uninstall not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

// Remove taxonomy and custom post type
unregister_taxonomy('topic');
unregister_post_type( 'news' );

// Clear any cached data that has been removed.
wp_cache_flush();

// Updates the WP rewrite rules
flush_rewrite_rules();