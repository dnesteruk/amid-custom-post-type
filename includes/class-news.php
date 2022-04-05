<?php

namespace includes;

defined( 'ABSPATH' ) || exit;

class News {

	private $post_type_name = 'news';
	private $post_type_slug = 'novosti';
	private $post_type_taxonomy = 'topic';
	private $post_taxonomy_slug = 'tema';
	private $post_capability_type = [ 'new', 'news' ];

	public function __construct() {
		$this->init();
	}

	private function init() {
		add_action( 'amid_run_plugin', array( $this, 'amid_activate_flush_rewrite_rules' ) );
		add_action( 'amid_stop_plugin', array( $this, 'amid_deactivation_flush_rewrite_rules' ) );
		add_action( 'init', array( $this, 'custom_post_type_news' ) );
	}

	public function custom_post_type_news() {
		register_taxonomy( $this->post_type_taxonomy, array( $this->post_type_name ), array(
			'labels'             => array(
				'name'              => _x( 'Topics', 'amid-custom-post-type' ),
				'singular_name'     => _x( 'Topic', 'amid-custom-post-type' ),
				'search_items'      => __( 'Search topic', 'amid-custom-post-type' ),
				'all_items'         => __( 'All topics', 'amid-custom-post-type' ),
				'parent_item'       => __( 'Parent topic', 'amid-custom-post-type' ),
				'parent_item_colon' => __( 'Parent topic:', 'amid-custom-post-type' ),
				'edit_item'         => __( 'Edit topic', 'amid-custom-post-type' ),
				'update_item'       => __( 'Update topic', 'amid-custom-post-type' ),
				'add_new_item'      => __( 'Add new topic', 'amid-custom-post-type' ),
				'new_item_name'     => __( 'New topic', 'amid-custom-post-type' ),
				'menu_name'         => __( 'Topics', 'amid-custom-post-type' ),
			),
			'show_ui'            => true,
			'show_in_nav_menus'  => true,
			'show_in_rest'       => true,
			'hierarchical'       => true,
			'publicly_queryable' => true,
			'show_admin_column'  => true,
			'show_in_quick_edit' => false,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => $this->post_taxonomy_slug ),
		) );

		$labels = array(
			'name'                     => _x( 'News', 'amid-custom-post-type' ),
			'singular_name'            => _x( 'News', 'amid-custom-post-type' ),
			'add_new'                  => __( 'Add news', 'amid-custom-post-type' ),
			'add_new_item'             => __( 'Add new news', 'amid-custom-post-type' ),
			'edit_item'                => __( 'Edit news', 'amid-custom-post-type' ),
			'new_item'                 => __( 'New news', 'amid-custom-post-type' ),
			'view_item'                => __( 'View news', 'amid-custom-post-type' ),
			'search_items'             => __( 'Find news', 'amid-custom-post-type' ),
			'not_found'                => __( 'No news found', 'amid-custom-post-type' ),
			'not_found_in_trash'       => __( 'There are no news in the cart', 'amid-custom-post-type' ),
			'parent_item_colon'        => __( 'Parent news', 'amid-custom-post-type' ),
			'all_items'                => __( 'All news', 'amid-custom-post-type' ),
			'archives'                 => __( 'News archives', 'amid-custom-post-type' ),
			'menu_name'                => __( 'News', 'amid-custom-post-type' ),
			'name_admin_bar'           => __( 'News', 'amid-custom-post-type' ),
			'view_items'               => __( 'Viewing news', 'amid-custom-post-type' ),
			'attributes'               => __( 'News Properties', 'amid-custom-post-type' ),
			// media uploader labels
			'insert_into_item'         => __( 'Insert in news', 'amid-custom-post-type' ),
			'uploaded_to_this_item'    => __( 'Loaded for this news', 'amid-custom-post-type' ),
			'featured_image'           => __( 'Image news', 'amid-custom-post-type' ),
			'set_featured_image'       => __( 'Set news image', 'amid-custom-post-type' ),
			'remove_featured_image'    => __( 'Delete news image', 'amid-custom-post-type' ),
			'use_featured_image'       => __( 'Use as news image', 'amid-custom-post-type' ),
			// Gutenberg, WordPress 5.0+
			'item_updated'             => __( 'The news has been updated', 'amid-custom-post-type' ),
			'item_published'           => __( 'News added', 'amid-custom-post-type' ),
			'item_published_privately' => __( 'News added privately', 'amid-custom-post-type' ),
			'item_reverted_to_draft'   => __( 'Newsletter saved as draft', 'amid-custom-post-type' ),
			'item_scheduled'           => __( 'Publication of the news is scheduled', 'amid-custom-post-type' ),
		);

		$args = array(
			'labels'              => $labels,
			'public'              => true,
			'publicly_queryable'  => true,
			'exclude_from_search' => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'show_in_rest'        => true,
			'query_var'           => true,
			'rewrite'             => array(
				'slug'       => $this->post_type_slug,
				'with_front' => true
			),
			'capability_type'     => $this->post_capability_type,
			'map_meta_cap'        => true,
			'has_archive'         => true,
			'hierarchical'        => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-admin-site-alt3',
			'supports'            => array( 'title', 'editor', 'author', 'thumbnail' ),
			'taxonomies'          => array( $this->post_type_taxonomy ),
		);

		register_post_type( $this->post_type_name, $args );
	}

	public function amid_activate_flush_rewrite_rules() {
		$this->custom_post_type_news();
		flush_rewrite_rules();
	}

	public function amid_deactivation_flush_rewrite_rules() {
		flush_rewrite_rules();
	}

}
new News();