<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * pebas_paid_listings_install
 */
class pebas_paid_listings_install {

	protected static $_instance = null;

	/**
	 * Default paid listings table name
	 *
	 * @var $paid_listings_table
	 */
	public static $pebas_paid_listings_table;

	/**
	 * Default paid listings term name
	 *
	 * @var string
	 */
	public $pebas_paid_term_name = 'job_package';

	/**
	 * @return null|pebas_paid_listings_install
	 */
	public static function instance() {
		return null === self::$_instance ? ( self::$_instance = new self ) : self::$_instance;
	}

	/**
	 * pebas_paid_listings_install constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'create_tables' ), 10 );
		add_action( 'init', array( $this, 'register_post_status' ), 11 );
		add_action( 'plugins_loaded', array( $this, 'set_listing_expiration_on_post_status_change' ), 12 );
		add_filter( 'the_job_status', array( $this, 'listing_status' ), 10, 2 );
		add_filter( 'job_manager_valid_submit_job_statuses', array( $this, 'valid_submit_statuses' ) );
		add_filter( 'job_manager_settings', array( $this, 'job_manager_settings' ) );
	}

	/**
	 * Register new post status for WP Job Manager
	 */
	public function register_post_status() {
		register_post_status( 'pending_payment', array(
			'label'                     => _x( 'Pending Payment', 'job_listing', 'pebas-paid-listings' ),
			'public'                    => false,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => false,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'Pending Payment <span class="count">(%s)</span>', 'Pending Payment <span class="count">(%s)</span>', 'pebas-paid-listings' ),
		) );

	}

	/**
	 * Set paid listing expiration date on post status change from payment to publish
	 */
	public function set_listing_expiration_on_post_status_change() {
		global $job_manager;

		add_action( 'pending_payment_to_publish', array( $job_manager->post_types, 'set_expiry' ) );
	}

	/**
	 * Add listing status
	 *
	 * @param $status
	 * @param $listing
	 *
	 * @return string
	 */
	public function listing_status( $status, $listing ) {
		if ( $listing->post_status == 'pending_payment' ) {
			$status = __( 'Pending Payment', 'pebas-paid-listings' );
		}

		return $status;
	}

	/**
	 * Ensure the submit form lets us continue to edit/process a listing with the pending_payment status
	 *
	 * @param $status
	 *
	 * @return array
	 */
	public function valid_submit_statuses( $status ) {
		$status[] = 'pending_payment';

		return $status;
	}

	/**
	 * Additional field for WP Job Manager Settings
	 *
	 * @param  array $settings
	 *
	 * @return array
	 */
	public function job_manager_settings( $settings = array() ) {
		$settings['job_submission'][1][] = array(
			'name'    => 'job_manager_paid_listings_flow',
			'std'     => '',
			'label'   => __( 'Paid Listings Flow', 'pebas-paid-listings' ),
			'desc'    => __( 'Define when the user should choose a package for submission.', 'pebas-paid-listings' ),
			'type'    => 'select',
			'options' => array(
				''       => __( 'Choose a package after entering listing details', 'pebas-paid-listings' ),
				'before' => __( 'Choose a package before entering listing details', 'pebas-paid-listings' ),
			),
		);

		return $settings;
	}

	/**
	 * Register new WooCommerce terms
	 */
	public function register_woocommerce_terms() {
		if ( ! get_term_by( 'slug', sanitize_title( $this->pebas_paid_term_name ), 'product_type' ) ) {
			wp_insert_term( $this->pebas_paid_term_name, 'product_type' );
		}
	}

	/**
	 * Create necessary tables
	 */
	public function create_tables() {
		global $wpdb;

		// assign name to paid listings table
		self::$pebas_paid_listings_table = 'pbs_user_packages';

		$wpdb->hide_errors();
		$table_name = $wpdb->prefix . self::$pebas_paid_listings_table;

		$collate = '';
		if ( $wpdb->has_cap( 'collation' ) ) {
			if ( ! empty( $wpdb->charset ) ) {
				$collate .= "DEFAULT CHARACTER SET $wpdb->charset";
			}
			if ( ! empty( $wpdb->collate ) ) {
				$collate .= " COLLATE $wpdb->collate";
			}
		}

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );


		// Listing packages table
		$sql = "
			CREATE TABLE $table_name (
  			id bigint(20) NOT NULL auto_increment,
  			user_id bigint(20) NOT NULL,
  			product_id bigint(20) NOT NULL,
  			order_id bigint(20) NOT NULL default 0,
  			package_featured int(1) NULL,
  			package_duration bigint(20) NULL,
  			package_limit bigint(20) NOT NULL,
  			package_count bigint(20) NOT NULL,
  			package_type varchar(100) NOT NULL,
  			PRIMARY KEY  (id)
			) $collate;
		";
		dbDelta( $sql );

		// Update version
		update_option( 'pbs_db_version', PEBAS_PL_VERSION );

		add_action( 'shutdown', array( $this, 'register_woocommerce_terms' ) );
	}

}

/**
 * Instantiate the class
 *
 * @return null|pebas_paid_listings_install
 */
function pebas_paid_listings_install() {
	return pebas_paid_listings_install::instance();
}
