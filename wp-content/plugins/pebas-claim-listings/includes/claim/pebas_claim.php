<?php
/**
 * Class pebas_claim
 *
 * @author pebas
 * @ver 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * pebas_claim
 */
class pebas_claim {

	protected static $_instance = null;


	/**
	 * @return null|pebas_claim
	 */
	public static function instance() {
		return null === self::$_instance ? ( self::$_instance = new self ) : self::$_instance;
	}

	/**
	 * pebas_claim_listings_claim constructor.
	 */
	public function __construct() {
	}

	/**
	 * Get Listing claim post type statuses
	 *
	 * @return array
	 */
	public function get_listing_claim_statuses() {
		$statuses = array(
			'approved' => esc_html__( 'Approved', 'pebas-claim-listings' ),
			'pending'  => esc_html__( 'Pending', 'pebas-claim-listings' ),
			'declined' => esc_html__( 'Declined', 'pebas-claim-listings' )
		);

		return apply_filters( 'pebas_claim_listings_claim_statuses', $statuses );
	}

	/**
	 * Get default claim status
	 *
	 * @return mixed
	 */
	public function get_default_status() {
		return apply_filters( 'pebas_claim_listings_default_status', 'pending' );
	}

	/**
	 * Sanitize default claim statuses
	 *
	 * @param $status
	 *
	 * @return mixed
	 */
	public function sanitize_claim_status( $status ) {
		$default = self::get_default_status();
		if ( ! $status ) {
			return $default;
		}
		$statuses = self::get_listing_claim_statuses();
		if ( array_key_exists( $status, $statuses ) ) {
			return $status;
		}

		return $default;
	}

	/**
	 * Get status of claim listing
	 *
	 * @param $claim_id
	 *
	 * @return mixed
	 */
	public function get_claim_status( $claim_id ) {
		$status = self::sanitize_claim_status( get_post_meta( $claim_id, '_status', true ) );

		return $status;
	}

	/**
	 * Get claim sattus label
	 *
	 * @param $claim_id
	 *
	 * @return string
	 */
	public function get_claim_status_label( $claim_id ) {
		$status       = self::get_claim_status( $claim_id );
		$statuses     = self::get_listing_claim_statuses();
		$status_label = isset( $statuses[ $status ] ) ? $statuses[ $status ] : __( 'Unknown', 'pebas-claim-listings' );

		return $status_label;
	}

	/**
	 * Create new claim post
	 *
	 * @param $listing_id
	 * @param $user_id
	 * @param string $claim_data
	 * @param string $context
	 *
	 * @return bool|int|WP_Error
	 */
	public function create_new_claim( $listing_id, $user_id, $claim_data = '', $context = 'front' ) {

		/* Check listing */
		if ( ! self::is_claimable( $listing_id ) ) {
			return false;
		}

		/* Get listing post object */
		$listing_obj = get_post( $listing_id );

		/* Create Claim */
		$post_data = array(
			'post_author' => $user_id,
			'post_title'  => $listing_obj->post_title,
			'post_type'   => pebas_claim_install()->pebas_claim_type_name,
			'post_status' => 'publish',
		);
		$claim_id  = wp_insert_post( $post_data );
		if ( ! is_wp_error( $claim_id ) ) {

			/* Update Status */
			add_post_meta( $claim_id, '_status', 'pending' );

			/* Listing ID */
			add_post_meta( $claim_id, '_listing_id', intval( $listing_id ) );

			/* User ID */
			add_post_meta( $claim_id, '_user_id', intval( $user_id ) );

			/* Claim Data */
			if ( $claim_data ) {
				add_post_meta( $claim_id, '_claim_data', wp_kses_post( $claim_data ) );
			}

			do_action( 'pebas_claim_listings_create_new_claim', $claim_id, $context );

			return $claim_id;
		}

		return false;
	}

	/**
	 * Get claim data
	 *
	 * @param $claim_id
	 *
	 * @return bool|mixed
	 */
	public function get_data( $claim_id ) {

		/* Vars */
		$claim_obj   = get_post( $claim_id );
		$claimer_obj = get_userdata( $claim_obj->post_author );
		$listing_id  = get_post_meta( $claim_id, '_listing_id', true );
		$listing_obj = get_post( $listing_id );

		/* Bail if not complete data */
		if ( ! $claim_obj || ! $listing_obj ) {
			return false;
		}

		/* Data */
		$claim_edit_url = add_query_arg( array(
			'post'   => $claim_id,
			'action' => 'edit',
		), admin_url( 'post.php' ) );
		$claim_url      = add_query_arg( array(
			'listing_id' => $listing_id,
		), get_permalink( job_manager_get_page_id( 'claim_listing' ) ) ); //todo: check this page creation

		/* Output */
		$datas = array(
			/* Claim */
			'claim_id'       => $claim_id,
			'claim_title'    => get_the_title( $claim_id ),
			'claim_date'     => get_the_date( get_option( 'date_format' ), $claim_id ),
			'claim_status'   => self::get_claim_status_label( $claim_id ),
			'claim_edit_url' => $claim_edit_url,
			'claim_url'      => $claim_url,
			/* Claimer */
			'claimer_id'     => $claimer_obj ? $claimer_obj->ID : 0,
			'claimer_name'   => $claimer_obj ? $claimer_obj->data->display_name : '',
			'claimer_login'  => $claimer_obj ? $claimer_obj->data->user_login : '',
			'claimer_email'  => $claimer_obj ? $claimer_obj->data->user_email : '',
			/* Listing */
			'listing_id'     => $listing_id,
			'listing_title'  => get_the_title( $claim_id ),
			'listing_url'    => get_permalink( $listing_id ),
		);

		return apply_filters( 'pebas_claim_listings_get_data', $datas, $claim_id );
	}

	/**
	 * Check whether the listings is claimable
	 *
	 * @param $job_listing_id
	 *
	 * @return mixed
	 */
	public function is_claimable( $job_listing_id ) {
		$claimable = true;

		/* Check if listing entry exist */
		$post_obj = get_post( $job_listing_id );

		if ( ! $post_obj ) {
			$claimable = false;
		}

		/* Check post type */
		if ( 'job_listing' !== $post_obj->post_type ) {
			$claimable = false;
		}

		/* Check status. */
		if ( 'preview' === $post_obj->post_status ) {
			$claimable = false;
		}

		/* Check if it's already claimed/verified. */
		$claimed = get_post_meta( $job_listing_id, '_claimed', true );

		if ( 1 == $claimed ) {
			$claimable = false;
		}

		return apply_filters( 'pebas_claim_listings_is_claimable', $claimable, $post_obj );
	}

}

/**
 * Instantiate the class
 *
 * @return null|pebas_claim
 */
function pebas_claim() {
	return pebas_claim::instance();
}
