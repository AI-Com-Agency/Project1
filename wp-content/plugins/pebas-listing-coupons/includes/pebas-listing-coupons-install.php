<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * pebas_listing_coupons_install
 */
class pebas_listing_coupons_install {

	protected static $_instance = null;

	/**
	 * Default paid listings term name
	 *
	 * @var string
	 */
	public static $post_type_name = 'listing_coupon';

	/**
	 * @return null|pebas_listing_coupons_install
	 */
	public static function instance() {
		return null === self::$_instance ? ( self::$_instance = new self ) : self::$_instance;
	}

	/**
	 * pebas_listing_coupons_install constructor.
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
		$coupon_post_type = self::$post_type_name;
		if ( post_type_exists( $coupon_post_type ) ) {
			return;
		}

		$singular = __( 'Listing Coupon', 'pebas-listing-coupons' );
		$plural   = __( 'Listing Coupons', 'pebas-listing-coupons' );

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
				'name'               => sprintf( __( '%s', 'pebas-listing-coupons' ), $plural ),
				'singular_name'      => sprintf( __( '%s', 'pebas-listing-coupons' ), $singular ),
				'menu_name'          => sprintf( __( '%s', 'pebas-listing-coupons' ), $plural ),
				'name_admin_bar'     => sprintf( __( '%s', 'pebas-listing-coupons' ), $plural ),
				'add_new'            => sprintf( __( 'Add New %s', 'pebas-listing-coupons' ), $singular ),
				'add_new_item'       => sprintf( __( 'Add New %s', 'pebas-listing-coupons' ), $singular ),
				'new_item'           => sprintf( __( 'New %s', 'pebas-listing-coupons' ), $singular ),
				'edit_item'          => sprintf( __( 'Edit %s', 'pebas-listing-coupons' ), $singular ),
				'view_item'          => sprintf( __( 'View %s', 'pebas-listing-coupons' ), $singular ),
				'all_items'          => sprintf( __( 'All %s', 'pebas-listing-coupons' ), $plural ),
				'search_items'       => sprintf( __( 'Search %s', 'pebas-listing-coupons' ), $plural ),
				'parent_item_colon'  => sprintf( __( 'Parent %s', 'pebas-listing-coupons' ), $plural ),
				'not_found'          => sprintf( __( 'No %s found', 'pebas-listing-coupons' ), $plural ),
				'not_found_in_trash' => sprintf( __( 'No %s found in Trash', 'pebas-listing-coupons' ), $plural ),
			),
		);

		register_post_type( $coupon_post_type, $args );
	}


}

/**
 * Instantiate the class
 *
 * @return null|pebas_listing_coupons_install
 */
function pebas_listing_coupons_install() {
	return pebas_listing_coupons_install::instance();
}
