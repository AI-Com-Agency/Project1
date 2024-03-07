<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * pebas_bookmark_listings_install
 */
class pebas_bookmark_listings_install {

	protected static $_instance = null;

	/**
	 * Default paid listings term name
	 *
	 * @var string
	 */
	public static $pebas_bookmark_type_name = 'listing_bookmark';

	/**
	 * @return null|pebas_bookmark_listings_install
	 */
	public static function instance() {
		return null === self::$_instance ? ( self::$_instance = new self ) : self::$_instance;
	}

	/**
	 * pebas_bookmark_listings_install constructor.
	 */
	public function __construct() {
		// add actions
		add_action( 'init', array( $this, 'register_post_type' ) );

		// add filters
	}


	/**
	 * Register report post type
	 */
	public function register_post_type() {
		$bookmark_post_type = self::$pebas_bookmark_type_name;
		if ( post_type_exists( $bookmark_post_type ) ) {
			return;
		}

		$singular = __( 'Listing Bookmark', 'pebas-bookmark-listings' );
		$plural   = __( 'Listing Bookmarks', 'pebas-bookmark-listings' );

		$args = array(
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => false,
			'query_var'          => true,
			'rewrite'            => false,
			'capability_type'    => 'job_listing',
			'map_meta_cap'       => true,
			'has_archive'        => false,
			'hierarchical'       => false,
			'supports'           => array( 'title' ),
			'labels'             => array(
				'name'               => sprintf( __( '%s', 'pebas-bookmark-listings' ), $plural ),
				'singular_name'      => sprintf( __( '%s', 'pebas-bookmark-listings' ), $singular ),
				'menu_name'          => sprintf( __( '%s', 'pebas-bookmark-listings' ), $plural ),
				'name_admin_bar'     => sprintf( __( '%s', 'pebas-bookmark-listings' ), $plural ),
				'add_new'            => __( 'Add New Bookmark', 'pebas-bookmark-listings' ),
				'add_new_item'       => sprintf( __( 'Add New %s', 'pebas-bookmark-listings' ), $singular ),
				'new_item'           => sprintf( __( 'New %s', 'pebas-bookmark-listings' ), $singular ),
				'edit_item'          => sprintf( __( 'Edit %s', 'pebas-bookmark-listings' ), $singular ),
				'view_item'          => sprintf( __( 'View %s', 'pebas-bookmark-listings' ), $singular ),
				'all_items'          => sprintf( __( 'All %s', 'pebas-bookmark-listings' ), $plural ),
				'search_items'       => sprintf( __( 'Search %s', 'pebas-bookmark-listings' ), $plural ),
				'parent_item_colon'  => sprintf( __( 'Parent %s', 'pebas-bookmark-listings' ), $plural ),
				'not_found'          => sprintf( __( 'No %s found', 'pebas-bookmark-listings' ), $plural ),
				'not_found_in_trash' => sprintf( __( 'No %s found in Trash', 'pebas-bookmark-listings' ), $plural ),
			),
		);

		register_post_type( $bookmark_post_type, $args );
	}


}

/**
 * Instantiate the class
 *
 * @return null|pebas_bookmark_listings_install
 */
function pebas_bookmark_listings_install() {
	return pebas_bookmark_listings_install::instance();
}
