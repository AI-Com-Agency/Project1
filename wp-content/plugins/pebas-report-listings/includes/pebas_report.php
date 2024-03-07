<?php
/**
 * Class pebas_report
 *
 * @author pebas
 * @ver 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * pebas_report
 */
class pebas_report {

	protected static $_instance = null;


	/**
	 * @return null|pebas_report
	 */
	public static function instance() {
		return null === self::$_instance ? ( self::$_instance = new self ) : self::$_instance;
	}

	/**
	 * pebas_claim_listings_claim constructor.
	 */
	public function __construct() {
		add_action( 'lisner_ajax_submit_report', array( $this, 'submit_report' ) );
	}

	/**
	 * Get Listing claim post type statuses
	 *
	 * @return array
	 */
	public function get_listing_report_statuses() {
		$statuses = array(
			'stashed' => esc_html__( 'Stashed', 'pebas-claim-listings' ),
			'pending' => esc_html__( 'Pending', 'pebas-claim-listings' ),
		);

		return apply_filters( 'pebas_report_listings_report_statuses', $statuses );
	}

	/**
	 * Get default claim status
	 *
	 * @return mixed
	 */
	public function get_default_status() {
		return apply_filters( 'pebas_report_listings_default_status', 'pending' );
	}

	/**
	 * Sanitize default claim statuses
	 *
	 * @param $status
	 *
	 * @return mixed
	 */
	public function sanitize_report_status( $status ) {
		$default = self::get_default_status();
		if ( ! $status ) {
			return $default;
		}
		$statuses = self::get_listing_report_statuses();
		if ( array_key_exists( $status, $statuses ) ) {
			return $status;
		}

		return $default;
	}

	/**
	 * Get status of listing report
	 *
	 * @param $report_id
	 *
	 * @return mixed
	 */
	public function get_report_status( $report_id ) {
		$status = self::sanitize_report_status( get_post_meta( $report_id, '_report_status', true ) );

		return $status;
	}

	/**
	 * Create new claim post
	 *
	 * @param $listing_id
	 * @param $user_id
	 * @param string $report_data
	 * @param string $context
	 *
	 * @return bool|int|WP_Error
	 */
	public function create_new_report( $listing_id, $user_id, $report_data = '', $context = 'front' ) {

		/* Get listing post object */
		$listing_obj = get_post( $listing_id );

		/* Create Claim */
		$post_data = array(
			'post_author' => $user_id,
			'post_title'  => $listing_obj->post_title,
			'post_type'   => pebas_report_listings_install::$pebas_report_type_name,
			'post_status' => 'publish',
		);
		$claim_id  = wp_insert_post( $post_data );
		if ( ! is_wp_error( $claim_id ) ) {

			/* Update Status */
			add_post_meta( $claim_id, '_report_status', 'pending' );

			/* Listing ID */
			add_post_meta( $claim_id, '_listing_id', intval( $listing_id ) );

			/* User ID */
			add_post_meta( $claim_id, '_user_id', intval( $user_id ) );

			/* Claim Data */
			if ( $report_data ) {
				add_post_meta( $claim_id, '_report_data', wp_kses_post( $report_data ) );
			}

			do_action( 'pebas_report_listings_create_new_report', $claim_id, $context );

			return $claim_id;
		}

		return false;
	}

	public function submit_report() {
		$result = array();
		if ( ! empty( $_POST['action'] ) && wp_verify_nonce( $_REQUEST['submit-report-nonce'], 'submit_report' ) ) {
			$listing = lisner_get_var( $_REQUEST['report_listing_id'] );
			$user_id = lisner_get_var( $_REQUEST['report_user_id'] );
			$user_ip = lisner_get_var( $_REQUEST['report_user_ip'] );
			$reason  = lisner_get_var( $_REQUEST['report_reason'] );
			$user    = get_userdata( $user_id );

			if ( ! isset( $listing ) ) {
				$result['error'] = esc_html__( 'Listing is not set, please contact site administrator', 'lisner-core' );
			}
			if ( ! isset( $user_id ) ) {
				$result['error'] = esc_html__( 'User is not set, please contact site administrator', 'lisner-core' );
			}
			if ( ! isset( $reason ) || empty( $reason ) ) {
				$result['error'] = esc_html__( 'Please provide a reason for listing report', 'lisner-core' );
			}
			if ( ! isset( $result['error'] ) ) {
				$args = array(
					'post_status' => 'publish',
					'post_type'   => pebas_report_listings_install::$pebas_report_type_name
				);
				$id   = wp_insert_post( $args );
				wp_update_post( array(
					'ID'         => $id,
					'post_title' => sprintf( esc_html__( 'Listing Report #%s', 'lisner-core' ), $id )
				) );

				update_post_meta( $id, '_report_listing_id', sanitize_text_field( $listing ) );
				update_post_meta( $id, '_report_user_id', sanitize_text_field( $user_id ) );
				update_post_meta( $id, '_report_user_email', sanitize_email( $user->user_email ) );
				update_post_meta( $id, '_report_user_ip', sanitize_text_field( $user_ip ) );
				update_post_meta( $id, '_report_data', sanitize_textarea_field( $reason ) );
				update_post_meta( $id, '_report_status', 'pending' );

				// send email
				$site  = get_bloginfo( 'site_title' );
				$email = get_bloginfo( 'admin_email' );

				$headers[] = "MIME-Version: 1.0";
				$headers[] = "Content-Type: text/html;";
				$headers[] = "From: " . $site . "<" . $email . ">";
				$subject   = esc_html__( "[{$site}] Listing has been reported!", 'lisner-core' );
				$link      = '<a href="' . esc_url( admin_url() . '/edit.php?post_type=' . pebas_report_listings_install::$pebas_report_type_name ) . '">' . esc_html( get_the_title( $listing ) ) . '</a>';
				$message   = sprintf( __( 'Listing: %s has been reported!', 'lisner-core' ), $link );

				if ( is_wp_error( $email ) ) {
					$result['error'] = $message;
				} else {
					wp_mail( $email, $subject, $message, $headers );
					$result['success'] = esc_html__( 'Listing has been successfully reported', 'lisner-core' );
				}
			}
			wp_send_json( $result );
		}
	}

	/**
	 * Check whether the listing has been already reported
	 * by the same user
	 *
	 * @param $listing_id
	 *
	 * @return bool
	 */
	public static function is_reported_by_user( $listing_id ) {
		global $wpdb;
		$sql     = "select post_id from $wpdb->postmeta where meta_key='_report_user_id' and meta_value=%d";
		$results = $wpdb->get_col( $wpdb->prepare( $sql, get_current_user_id() ) );
		$reports = array();

		foreach ( $results as $result ) {
			$report = get_post_meta( $result, '_report_listing_id', true );
			if ( $listing_id == $report ) {
				$reports[] = $report;
			}
		}
		if ( ! empty( $reports ) ) {
			return true;
		}

		return false;
	}

}

/**
 * Instantiate the class
 *
 * @return null|pebas_report
 */
function pebas_report() {
	return pebas_report::instance();
}
