<?php
/**
 * Class pebas_claim_listings_settings
 *
 * @author pebas
 * @ver 1.0.0
 */

/**
 * Class pebas_claim_listings_settings
 */
class pebas_claim_listings_settings {

	protected static $_instance = null;

	/**
	 * @return null|pebas_claim_listings_settings
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * lisner_meta constructor.
	 */
	function __construct() {
		// add custom settings
		add_filter( 'job_manager_settings', array( $this, 'add_settings' ) );

		// sanitize settings
		add_filter( 'sanitize_option_pebas_paid_listings_email_message_new_claim_claimer', array(
			'pebas_claim_notification',
			'sanitize_email'
		) );
		add_filter( 'sanitize_option_pebas_paid_listings_email_message_new_claim_admin', array(
			'pebas_claim_notification',
			'sanitize_email'
		) );
		add_filter( 'sanitize_option_pebas_paid_listings_email_message_status_update_claimer', array(
			'pebas_claim_notification',
			'sanitize_email'
		) );
		add_filter( 'sanitize_option_pebas_paid_listings_email_message_status_update_claimer', array(
			'pebas_claim_notification',
			'sanitize_email'
		) );

	}

	/**
	 * Add custom claim listings settings
	 *
	 * @param $settings
	 *
	 * @return mixed
	 */
	public function add_settings( $settings ) {

		$settings['job_pages'][1][] = array(
			'name'  => 'job_manager_claim_listing_page_id',
			'std'   => '',
			'label' => __( 'Claim Listing Page', 'pebas-claim-listings' ),
			'desc'  => __( 'Select the page where you have placed the [claim_listing] shortcode (required).', 'pebas-claim-listings' ),
			'type'  => 'page',
		);

		/* Header */
		$settings['pebas_claim_settings'] = array(
			__( 'Claim Listing', 'pebas-claim-listings' ),
			array(),
		);

		/* Allow claimer submit claim data */
		$settings['pebas_claim_settings'][1][] = array(
			'name'       => 'claim_own_listing',
			'std'        => '',
			'label'      => __( 'Claim Own Listing', 'pebas-claim-listings' ),
			'cb_label'   => __( 'Allow non-claimed listings to be claimed by the same owner.', 'pebas-claim-listings' ),
			'desc'       => __( 'If enabled user can claim their own listing.', 'pebas-claim-listings' ),
			'type'       => 'checkbox',
			'attributes' => array(),
		);

		/* Allow claimer submit claim data */
		$settings['pebas_claim_settings'][1][] = array(
			'name'     => 'paid_claiming',
			'std'      => '',
			'label'    => __( 'Paid Claims', 'pebas-claim-listings' ),
			'cb_label' => __( 'Require a purchase', 'pebas-claim-listings' ),
			'desc'     => __( 'A listing is claimed by purchasing a listing package.', 'pebas-claim-listings' ),
			'type'     => 'checkbox',
		);

		/* NOTIFICATIONS */
		/* New Claim Content: Claimer */
		$settings['pebas_claim_settings'][1][] = array(
			'name'  => 'email_message_new_claim_claimer',
			'std'   => pebas_claim_notification::default_email_message_new_claim_claimer(),
			'label' => __( 'New Claim Email Content For Claimer', 'wp-job-manager-claim-listing' ),
			'desc'  => __( 'Available tag: <br/> %claimer_name%, %claim_date%, %listing_url%, %claim_status%, %claim_url%', 'wp-job-manager-claim-listing' ),
			'type'  => 'textarea',
		);

		/* New Claim Content: Admin */
		$settings['pebas_claim_settings'][1][] = array(
			'name'  => 'email_message_new_claim_admin',
			'std'   => pebas_claim_notification::default_email_message_new_claim_admin(),
			'label' => __( 'New Claim Email Content For Admin', 'wp-job-manager-claim-listing' ),
			'desc'  => __( 'Available tag: <br/> %claimer_name%, %claim_date%, %listing_url%, %claim_status%, %claim_url%, %claim_edit_url%', 'wp-job-manager-claim-listing' ),
			'type'  => 'textarea',
		);

		/* Status Update Content: Claimer */
		$settings['pebas_claim_settings'][1][] = array(
			'name'  => 'email_message_status_update_claimer',
			'std'   => pebas_claim_notification::default_email_message_status_update_claimer(),
			'label' => __( 'Status Update Email Content For Claimer', 'wp-job-manager-claim-listing' ),
			'desc'  => __( 'Available tag: <br/> %claimer_name%, %claim_date%, %listing_url%, %claim_status%, %claim_status_old%, %claim_url%', 'wp-job-manager-claim-listing' ),
			'type'  => 'textarea',
		);

		/* Status Update Content: Admin */
		$settings['pebas_claim_settings'][1][] = array(
			'name'  => 'email_message_status_update_admin',
			'std'   => pebas_claim_notification::default_email_message_status_update_admin(),
			'label' => __( 'Status Update Email Content For Admin', 'wp-job-manager-claim-listing' ),
			'desc'  => __( 'Available tag: <br/> %claimer_name%, %claim_date%, %listing_url%, %claim_status%, %claim_status_old%, %claim_url%, %claim_edit_url%', 'wp-job-manager-claim-listing' ),
			'type'  => 'textarea',
		);

		return $settings;
	}

}

/** Instantiate class
 *
 * @return null|pebas_claim_listings_settings
 */
function pebas_claim_listings_settings() {
	return pebas_claim_listings_settings::instance();
}
