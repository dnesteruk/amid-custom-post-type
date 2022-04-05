<?php
/**
 * Roles and Capabilities
 */
defined( 'ABSPATH' ) || exit;

function amid_custom_post_type_capabilities(): array {
	return [
		'publish_news',
		'read_private_news',
		'edit_others_news',
		'edit_private_news',
		'delete_private_news',
		'edit_published_news',
		'edit_news',
		'delete_new',
		'delete_news',
		'delete_others_news',
		'delete_private_news',
		'delete_published_news',
		'read_news',
	];
}

add_action( 'amid_run_plugin', 'amid_custom_post_type_role_activate', 10 );
function amid_custom_post_type_role_activate() {
	$administrator = get_role( 'administrator' );
	$capabilities  = amid_custom_post_type_capabilities();
	if ( isset( $administrator ) ) {

		foreach ( $capabilities as $capability ) {
			$administrator->add_cap( $capability );
		}

	}

	flush_rewrite_rules();
}

add_action( 'amid_stop_plugin', 'amid_custom_post_type_role_deactivation', 10 );
function amid_custom_post_type_role_deactivation() {
	$administrator = get_role( 'administrator' );
	$capabilities  = amid_custom_post_type_capabilities();
	if ( isset( $administrator ) ) {

		foreach ( $capabilities as $capability ) {
			$administrator->remove_cap( $capability );
		}

	}

	flush_rewrite_rules();
}
