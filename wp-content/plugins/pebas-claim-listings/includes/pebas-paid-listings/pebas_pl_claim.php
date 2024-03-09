<?php
/**
 * Class pebas_pl_claim
 *
 * @author pebas
 * @ver 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * pebas_pl_claim
 */
class pebas_pl_claim {

	protected static $_instance = null;


	/**
	 * @return null|pebas_pl_claim
	 */
	public static function instance() {
		return null === self::$_instance ? ( self::$_instance = new self ) : self::$_instance;
	}

	/**
	 * pebas_pl_claim constructor.
	 */
	public function __construct() {
		$paid_listing_enabled = get_option( 'paid_claiming' );
		// add new claim statuses
		if ( $paid_listing_enabled ) {
			add_filter( 'pebas_claim_listings_claim_statuses', array( $this, 'add_claim_statuses' ) );
			add_action( 'pebas_claim_listings_create_new_claim', array( $this, 'set_new_claim_status' ), 5, 2 );

			add_filter( 'pebas_claim_listings_notification_mail_claimer_new_claim_args', '__return_empty_array' );
			add_filter( 'pebas_claim_listings_notification_mail_admin_new_claim_args', '__return_empty_array' );

			add_action( 'pebas_claim_listings_claim_status_updated', array(
				$this,
				'set_listing_claim_status'
			), 11, 3 );
		}

		add_filter( 'pebas_paid_listings_get_job_packages_args', array( $this, 'exclude_claim_package' ) );

		// WooCommerce
		add_filter( 'product_type_options', array( $this, 'add_job_package_use_for_claims_options' ) );

		/* Add "Claimed Listing?" for auto approve if order completed. */
		add_action( 'woocommerce_product_options_general_product_data', array( $this, 'add_claimed_checkbox' ), 20 );

		/* Save data. Use priority 20, so we can override WC Paid Listing data. */
		add_action( 'woocommerce_process_product_meta_job_package', array( $this, 'save_data' ), 20 );
		add_action( 'woocommerce_process_product_meta_job_package_subscription', array( $this, 'save_data' ), 20 );

		/* Claim Columns */
		add_filter( 'manage_edit-claim_columns', array( $this, 'manage_columns' ) );
		add_action( 'manage_claim_posts_custom_column', array( $this, 'manage_custom_column' ), 10, 2 );

		/* Admin Scripts */
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
	}

	/**
	 * Add claim listings statuses
	 *
	 * @param $statuses
	 *
	 * @return mixed
	 */
	function add_claim_statuses( $statuses ) {
		$statuses['pending_purchase'] = __( 'Pending Purchase', 'pebas-claim-listings' );
		$statuses['pending_order']    = __( 'Pending Order', 'pebas-claim-listings' );
		$statuses['order_completed']  = __( 'Order Completed', 'pebas-claim-listings' );

		return $statuses;
	}

	/**
	 * Set new claim status
	 *
	 * @param $claim_id
	 * @param $context
	 */
	public function set_new_claim_status( $claim_id, $context ) {
		if ( 'front' == $context ) {
			update_post_meta( $claim_id, '_status', 'pending_purchase' );
		}
	}

	/**
	 * Set listing data as package claim data when approved
	 *
	 * @param $claim_id
	 * @param $old_status
	 * @param $request
	 */
	public function set_listing_claim_status( $claim_id, $old_status, $request ) {

		/* Claim Data */
		$claim_status = get_post_meta( $claim_id, '_status', true );

		/* Listing Data */
		$listing_id      = get_post_meta( $claim_id, '_listing_id', true );
		$listing_claimed = get_post_meta( $listing_id, '_claimed', true );

		/* Status is approved */
		if ( ( 'approved' == $claim_status ) && $listing_claimed ) {
			pebas_listing_claim()->update_listing_on_claim_approval( $claim_id, $listing_id );
		}
	}

	/**
	 * Exclude listing paid packages for claim in pebas-paid-listings
	 *
	 * @param $args
	 *
	 * @return mixed
	 */
	public function exclude_claim_package( $args ) {
		$args['meta_query'][] = array(
			'key'     => '_use_for_claims',
			'value'   => 'yes',
			'compare' => '!=',
		);

		return $args;
	}

	/**
	 * Add product type option.
	 * Add a product type option to allow job_listings to also be claim listing packages
	 * For array key, do not use "_" underscore. WC will add that
	 * The array key is the "post meta key".
	 *
	 * @param $product_type_options
	 *
	 * @return mixed
	 *
	 * @see WC/includes/admin/meta-boxes/class-wc-meta-box-product-data.php line 66 (v.2.6.4)
	 */
	public function add_job_package_use_for_claims_options( $product_type_options ) {
		$product_type_options['use_for_claims'] = array(
			'id'            => '_use_for_claims',
			'wrapper_class' => 'show_if_job_package show_if_job_package_subscription',
			'label'         => __( 'Use for Claiming a Listing', 'pebas-claim-listings' ),
			'description'   => __( 'Allow this package to be a option for claiming a listing. These packages will not appear on the standard listing submission form.', 'pebas-claim-listings' ),
			'default'       => 'no',
		);

		return $product_type_options;
	}


	/**
	 * Add claimed checkbox to the listing products.
	 * When checked the created listing will be set to claimed automatically.
	 * This checkbox is added in "woocommerce_product_options_general_product_data"
	 *
	 * @uses woocommerce_wp_checkbox()
	 * @see  WC/includes/admin/wc-meta-box-functions.php line 135 (v.2.6.4)
	 * @see  WC/includes/admin/meta-boxes/class-wc-meta-box-product-data.php line 272 (v.2.6.4)
	 */
	public function add_claimed_checkbox() {

		/* Get post data */
		$post = get_post();

		/* Add checkbox. */
		woocommerce_wp_checkbox( array(
			'id'            => '_default_to_claimed',
			'name'          => '_default_to_claimed',
			'label'         => __( 'Claimed Listing?', 'pebas-claim-listings' ),
			'description'   => __( 'Automatically be mark listing as claimed/verified if user completed the purchase.', 'pebas-claim-listings' ),
			'value'         => get_post_meta( $post->ID, '_default_to_claimed', true ),
			'cbvalue'       => 'yes',
			'wrapper_class' => 'show_if_job_package show_if_listing_job_subscription',
		) );

	}


	/**
	 * Save Product Data
	 *
	 * @since 3.0.0
	 */
	public function save_data( $post_id ) {

		/* Save Product Data Type Options. Product type options do not have "value" attr. */
		$for_claims = isset( $_POST['_use_for_claims'] ) ? 'yes' : 'no';
		update_post_meta( $post_id, '_use_for_claims', $for_claims );

		/* if use for claim */
		if ( 'yes' == $for_claims ) {

			/* Set job listing limit to 1 */
			update_post_meta( $post_id, '_listing_limit', 1 );

			/* Set listing subs package to "listing" */
			update_post_meta( $post_id, '_package_subscription_type', 'listing' );
		}

		/* Save default to claimed data */
		$value = ( isset( $_POST['_default_to_claimed'] ) && 'yes' == $_POST['_default_to_claimed'] ) ? 'yes' : 'no';
		update_post_meta( $post_id, '_default_to_claimed', $value );
	}

	function manage_columns( $columns ) {
		$columns['order'] = __( 'Order', 'pebas-claim-listings' );

		return $columns;
	}

	function manage_custom_column( $column, $post_id ) {
		switch ( $column ) {
			case 'order' :

				/* Var */
				$text = '';

				/* Order */
				$order_id = intval( get_post_meta( $post_id, '_order_id', true ) );
				if ( $order_id ) {

					/* Add order ID in text */
					$text = "#{$order_id}";

					/* Get order object */
					$order_obj = wc_get_order( $order_id );
					if ( $order_obj ) {

						/* Add Edit Link */
						if ( $edit_link = get_edit_post_link( $order_id ) ) {
							$text = '<a target="_blank" href="' . esc_url( $edit_link ) . '">' . $text . '</a>';
						}

						/* Add Order Status */
						$status = $order_obj->get_status();
						$text   .= " &ndash; <em>{$status}</em>";
					} else {
						$text .= ' &ndash; ' . __( 'Cannot retrieve order.', 'pebas-claim-listings' );
					}
				}
				echo $text;
				break;
		}

		return $column;
	}

	public function admin_scripts( $hook_suffix ) {
		global $post_type;

		wp_register_script( 'pebas-wp-product-claim-data', PEBAS_CL_URL . 'assets/scripts/claim-product-data.js', array( 'jquery' ), PEBAS_CL_VERSION, true );

		if ( 'product' == $post_type && in_array( $hook_suffix, array( 'post.php', 'post-new.php' ) ) ) {
			wp_enqueue_script( 'pebas-wp-product-claim-data' );
		}
	}

}

/**
 * Instantiate the class
 *
 * @return null|pebas_pl_claim
 */
function pebas_pl_claim() {
	return pebas_pl_claim::instance();
}
