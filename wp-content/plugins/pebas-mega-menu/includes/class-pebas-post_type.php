<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * pebas_mega_menu_post_type
 */
class pebas_mega_menu_post_type {

	protected static $_instance = null;

	/**
	 * @return null|pebas_mega_menu_post_type
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function __construct() {
		add_action( 'init', array( $this, 'register_post_types' ) );
	}

	public function register_post_types() {

		/**
		 * Register plugin post types
		 */
		if ( post_type_exists( 'pebas_mega_menu' ) ) {
			return;
		}
		$admin_capability = 'editor';

		$singular = esc_html__( 'Mega Menu', 'pebas-mega-menu' );
		$plural   = esc_html__( 'Mega Menus', 'pebas-mega-menu' );

		$has_archive = false;

		$rewrite = array(
			'slug'       => _x( 'mega-menu', 'Mega Menu permalink - resave permalinks after changing this', 'pebas-mega-menu' ),
			'with_front' => true,
			'feeds'      => false,
			'pages'      => false
		);

		register_post_type( 'pebas_mega_menu', apply_filters( 'register_post_type_pebas_mega_menu', array(
			'labels'              => array(
				'name'                  => $plural,
				'singular_name'         => $singular,
				'menu_name'             => esc_html__( 'Mega Menu', 'pebas-mega-menu' ),
				'all_items'             => sprintf( esc_html__( 'All %s', 'pebas-mega-menu' ), $plural ),
				'add_new'               => esc_html__( 'Add New', 'pebas-mega-menu' ),
				'add_new_item'          => sprintf( esc_html__( 'Add %s', 'pebas-mega-menu' ), $singular ),
				'edit'                  => esc_html__( 'Edit', 'pebas-mega-menu' ),
				'edit_item'             => sprintf( esc_html__( 'Edit %s', 'pebas-mega-menu' ), $singular ),
				'new_item'              => sprintf( esc_html__( 'New %s', 'pebas-mega-menu' ), $singular ),
				'view'                  => sprintf( esc_html__( 'View %s', 'pebas-mega-menu' ), $singular ),
				'view_item'             => sprintf( esc_html__( 'View %s', 'pebas-mega-menu' ), $singular ),
				'search_items'          => sprintf( esc_html__( 'Search %s', 'pebas-mega-menu' ), $plural ),
				'not_found'             => sprintf( esc_html__( 'No %s found', 'pebas-mega-menu' ), $plural ),
				'not_found_in_trash'    => sprintf( esc_html__( 'No %s found in trash', 'pebas-mega-menu' ), $plural ),
				'parent'                => sprintf( esc_html__( 'Parent %s', 'pebas-mega-menu' ), $singular ),
				'featured_image'        => esc_html__( 'Background Image', 'pebas-mega-menu' ),
				'set_featured_image'    => esc_html__( 'Set Background image', 'pebas-mega-menu' ),
				'remove_featured_image' => esc_html__( 'Remove background image', 'pebas-mega-menu' ),
				'use_featured_image'    => esc_html__( 'Use as background image', 'pebas-mega-menu' ),
			),
			'description'         => sprintf( esc_html__( 'This is where you can create and manage %s.', 'pebas-mega-menu' ), $plural ),
			'public'              => true,
			'show_ui'             => true,
			'map_meta_cap'        => true,
			'publicly_queryable'  => true,
			'exclude_from_search' => false,
			'hierarchical'        => false,
			'rewrite'             => $rewrite,
			'query_var'           => true,
			'supports'            => array( 'title', 'thumbnail' ),
			'has_archive'         => $has_archive,
			'show_in_nav_menus'   => true,
			'menu_icon'           => 'dashicons-excerpt-view'
		) ) );

	}

	public function extra_tables_cleanup( $post_id ) {
		global $post_type, $wpdb;
		if ( $post_type == 'pebas_mega_menu' ) {
			$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->posts} WHERE post_id = %d", $post_id ) );
		}
	}


}

/** Instantiate class
 *
 * @return null|pebas_mega_menu_post_type
 */
function pebas_mega_menu_post_type() {
	return pebas_mega_menu_post_type::instance();
}
