<?php
/**
 * Class pebas_claim_submit_claim
 *
 * @author pebas
 * @ver 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * pebas_claim_submit_claim
 */
class pebas_claim_submit_claim {

	protected static $_instance = null;


	/**
	 * @return null|pebas_claim_submit_claim
	 */
	public static function instance() {
		return null === self::$_instance ? ( self::$_instance = new self ) : self::$_instance;
	}

	/**
	 * pebas_claim_submit_claim constructor.
	 */
	public function __construct() {
		add_action( 'template_redirect', array( $this, 'load_form' ) );

		add_action( 'init', array( $this, 'register_shortcodes' ) );
	}

	public function is_submit_claim_page() {
		$page_id = job_manager_get_page_id( 'claim_listing' );
		if ( ! $page_id ) {
			return false;
		}
		if ( is_page( $page_id ) ) {
			return true;
		}

		return false;
	}


	/**
	 * Load Form
	 *
	 * @since 3.0.0
	 */
	public function load_form() {

		/* Only in submit claim page */
		if ( $this->is_submit_claim_page() ) {

			// Make sure registration enabled and account required in claim page.
			add_filter( 'job_manager_enable_registration', '__return_true' );
			add_filter( 'job_manager_user_requires_account', '__return_true' );

			// Load Form.
			$form = pebas_claim_submit_form();
			$form->process();
		}
	}

	/**
	 * Register Shortcode
	 *
	 * @since 3.0.0
	 */
	function register_shortcodes() {
		if ( ! is_admin() ) {
			add_shortcode( 'claim_listing', array( $this, 'claim_listing_shortcode' ) );
		}
	}

	/**
	 * Claim Listing Shortcode
	 *
	 * @return bool|string
	 */
	function claim_listing_shortcode() {
		if ( $this->is_submit_claim_page() ) {
			$form = pebas_claim_submit_form();
			ob_start();
			$form->output();

			return ob_get_clean();
		}

		return false;
	}


}

/**
 * Instantiate the class
 *
 * @return null|pebas_claim_submit_claim
 */
function pebas_claim_submit_claim() {
	return pebas_claim_submit_claim::instance();
}
