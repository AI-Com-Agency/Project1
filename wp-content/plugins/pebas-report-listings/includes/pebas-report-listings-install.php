<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * pebas_report_listings_install
 */
class pebas_report_listings_install {

	protected static $_instance = null;

	/**
	 * Default paid listings term name
	 *
	 * @var string
	 */
	public static $pebas_report_type_name = 'listing_report';

	/**
	 * @return null|pebas_report_listings_install
	 */
	public static function instance() {
		return null === self::$_instance ? ( self::$_instance = new self ) : self::$_instance;
	}

	/**
	 * pebas_report_listings_install constructor.
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
		$report_post_type = self::$pebas_report_type_name;
		if ( post_type_exists( $report_post_type ) ) {
			return;
		}

		$singular = __( 'Listing Report', 'pebas-report-listings' );
		$plural   = __( 'Listing Reports', 'pebas-report-listings' );

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
			'supports'           => array( 'title', 'author' ),
			'labels'             => array(
				'name'               => sprintf( __( '%s', 'pebas-report-listings' ), $plural ),
				'singular_name'      => sprintf( __( '%s', 'pebas-report-listings' ), $singular ),
				'menu_name'          => sprintf( __( '%s', 'pebas-report-listings' ), $plural ),
				'name_admin_bar'     => sprintf( __( '%s', 'pebas-report-listings' ), $plural ),
				'add_new'            => __( 'Add New', 'pebas-report-listings' ),
				'add_new_item'       => sprintf( __( 'Add New %s', 'pebas-report-listings' ), $singular ),
				'new_item'           => sprintf( __( 'New %s', 'pebas-report-listings' ), $singular ),
				'edit_item'          => sprintf( __( 'Edit %s', 'pebas-report-listings' ), $singular ),
				'view_item'          => sprintf( __( 'View %s', 'pebas-report-listings' ), $singular ),
				'all_items'          => sprintf( __( 'All %s', 'pebas-report-listings' ), $plural ),
				'search_items'       => sprintf( __( 'Search %s', 'pebas-report-listings' ), $plural ),
				'parent_item_colon'  => sprintf( __( 'Parent %s', 'pebas-report-listings' ), $plural ),
				'not_found'          => sprintf( __( 'No %s found', 'pebas-report-listings' ), $plural ),
				'not_found_in_trash' => sprintf( __( 'No %s found in Trash', 'pebas-report-listings' ), $plural ),
			),
		);

		register_post_type( $report_post_type, $args );
	}


}

/**
 * Instantiate the class
 *
 * @return null|pebas_report_listings_install
 */
function pebas_report_listings_install() {
	return pebas_report_listings_install::instance();
}
