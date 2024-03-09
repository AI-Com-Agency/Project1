<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * pebas_listing_events_install
 */
class pebas_listing_events_install {

	protected static $_instance = null;

	/**
	 * Default paid listings term name
	 *
	 * @var string
	 */
	public static $post_type_name = 'listing_event';

	/**
	 * @return null|pebas_listing_events_install
	 */
	public static function instance() {
		return null === self::$_instance ? ( self::$_instance = new self ) : self::$_instance;
	}

	/**
	 * pebas_listing_events_install constructor.
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
		$post_type = self::$post_type_name;
		if ( post_type_exists( $post_type ) ) {
			return;
		}

		$singular = __( 'Listing Event', 'pebas-listing-events' );
		$plural   = __( 'Listing Events', 'pebas-listing-events' );

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
				'name'               => sprintf( __( '%s', 'pebas-listing-events' ), $plural ),
				'singular_name'      => sprintf( __( '%s', 'pebas-listing-events' ), $singular ),
				'menu_name'          => sprintf( __( '%s', 'pebas-listing-events' ), $plural ),
				'name_admin_bar'     => sprintf( __( '%s', 'pebas-listing-events' ), $plural ),
				'add_new'            => sprintf( __( 'Add New %s', 'pebas-listing-events' ), $singular ),
				'add_new_item'       => sprintf( __( 'Add New %s', 'pebas-listing-events' ), $singular ),
				'new_item'           => sprintf( __( 'New %s', 'pebas-listing-events' ), $singular ),
				'edit_item'          => sprintf( __( 'Edit %s', 'pebas-listing-events' ), $singular ),
				'view_item'          => sprintf( __( 'View %s', 'pebas-listing-events' ), $singular ),
				'all_items'          => sprintf( __( 'All %s', 'pebas-listing-events' ), $plural ),
				'search_items'       => sprintf( __( 'Search %s', 'pebas-listing-events' ), $plural ),
				'parent_item_colon'  => sprintf( __( 'Parent %s', 'pebas-listing-events' ), $plural ),
				'not_found'          => sprintf( __( 'No %s found', 'pebas-listing-events' ), $plural ),
				'not_found_in_trash' => sprintf( __( 'No %s found in Trash', 'pebas-listing-events' ), $plural ),
			),
		);

		register_post_type( $post_type, $args );
	}


}

/**
 * Instantiate the class
 *
 * @return null|pebas_listing_events_install
 */
function pebas_listing_events_install() {
	return pebas_listing_events_install::instance();
}
