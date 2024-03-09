<?php
/**
 * Class pebas_claim_install
 *
 * @author pebas
 * @ver 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * pebas_claim_listings_install
 */
class pebas_claim_install {

	protected static $_instance = null;

	/**
	 * Default paid listings term name
	 *
	 * @var string
	 */
	public $pebas_claim_type_name = 'listing_claim';

	public $pebas_claim_statuses;

	/**
	 * @return null|pebas_claim_install
	 */
	public static function instance() {
		return null === self::$_instance ? ( self::$_instance = new self ) : self::$_instance;
	}

	/**
	 * pebas_claim_listings_install constructor.
	 */
	public function __construct() {

		// add actions
		add_action( 'init', array( $this, 'register_post_type' ) );

		// add filters
	}

	public function register_post_type() {
		$claim_post_type = $this->pebas_claim_type_name;
		if ( post_type_exists( $claim_post_type ) ) {
			return;
		}

		$singular = __( 'Listing Claim', 'pebas-claim-listings' );
		$plural   = __( 'Listing Claims', 'pebas-claim-listings' );

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
				'name'               => sprintf( __( '%s', 'pebas-claim-listings' ), $plural ),
				'singular_name'      => sprintf( __( '%s', 'pebas-claim-listings' ), $singular ),
				'menu_name'          => sprintf( __( '%s', 'pebas-claim-listings' ), $plural ),
				'name_admin_bar'     => sprintf( __( '%s', 'pebas-claim-listings' ), $plural ),
				'add_new'            => __( 'Add New', 'pebas-claim-listings' ),
				'add_new_item'       => sprintf( __( 'Add New %s', 'pebas-claim-listings' ), $singular ),
				'new_item'           => sprintf( __( 'New %s', 'pebas-claim-listings' ), $singular ),
				'edit_item'          => sprintf( __( 'Edit %s', 'pebas-claim-listings' ), $singular ),
				'view_item'          => sprintf( __( 'View %s', 'pebas-claim-listings' ), $singular ),
				'all_items'          => sprintf( __( 'All %s', 'pebas-claim-listings' ), $plural ),
				'search_items'       => sprintf( __( 'Search %s', 'pebas-claim-listings' ), $plural ),
				'parent_item_colon'  => sprintf( __( 'Parent %s', 'pebas-claim-listings' ), $plural ),
				'not_found'          => sprintf( __( 'No %s found', 'pebas-claim-listings' ), $plural ),
				'not_found_in_trash' => sprintf( __( 'No %s found in Trash', 'pebas-claim-listings' ), $plural ),
			),
		);

		register_post_type( $this->pebas_claim_type_name, $args );
	}

}

/**
 * Instantiate the class
 *
 * @return null|pebas_claim_install
 */
function pebas_claim_install() {
	return pebas_claim_install::instance();
}
