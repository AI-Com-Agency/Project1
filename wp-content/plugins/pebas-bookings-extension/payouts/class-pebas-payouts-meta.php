<?php
/**
 * Class pebas_payouts_meta
 *
 * @author pebas
 * @ver 1.0.0
 */

/**
 * Class pebas_payouts_meta
 */
class pebas_payouts_meta {

	protected static $_instance = null;

	/**
	 * @return null|pebas_payouts_meta
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
		// add custom meta boxes
		add_filter( 'rwmb_meta_boxes', array( $this, 'meta_boxes' ), 11 );
	}

	/**
	 * Register all news meta boxes
	 *
	 * @param $meta_boxes
	 *
	 * @return array
	 */
	public function meta_boxes( $meta_boxes ) {
		$option = get_option( 'pbs_option' );

		// Listing reports Meta Fields / Information
		$post_id        = isset( $_GET['post'] ) ? $_GET['post'] : ( isset( $_POST['post_ID'] ) ? $_POST['post_ID'] : false );
		$payout_booking = get_post_meta( $post_id, '_payout_booking', true );
		$payout_amount  = get_post_meta( $post_id, '_payout_amount', true );
		$information    = array(
			array(
				'id'       => '_payout_booking_display',
				'name'     => esc_html__( 'Booking', 'pebas-bookmark-listings' ),
				'type'     => 'custom_html',
				'readonly' => true,
				'std'      => '<div class="alert alert-info">' . esc_html( get_the_title( $payout_booking ) ) . '</div>',
				'desc'     => esc_html__( 'Booking for which payment is due.', 'pebas-bookmark-listings' ),
			),
			array(
				'id'         => '_payout_customer',
				'name'       => esc_html__( 'Customer PayPal Address', 'pebas-bookmark-listings' ),
				'type'       => 'user',
				'field_type' => 'select_advanced',
				'desc'       => esc_html__( 'Choose which user has reported the listing', 'pebas-bookmark-listings' ),
			),
			array(
				'id'       => '_payout_amount_display',
				'name'     => esc_html__( 'Amount Due to Pay', 'pebas-bookmark-listings' ),
				'type'     => 'custom_html',
				'std'      => '<div class="alert alert-info">' . wc_price( $payout_amount ) . '</div>',
				'desc'     => esc_html__( 'Payment amount that needs to be sent', 'pebas-bookmark-listings' ),
			),
		);

		$meta_boxes[] = array(
			'title'   => esc_html__( 'Booking Payout Information', 'pebas-bookmark-listings' ),
			'pages'   => pebas_payouts_install::$type_name,
			'fields'  => $information,
			'context' => 'advanced',
		);

		// Listing reports Meta Fields / Status
		$status = array(
			array(
				'id'      => '_payout_status',
				'name'    => esc_html__( 'Payout Status', 'pebas-report-listings' ),
				'type'    => 'select',
				'desc'    => esc_html__( 'Choose listing report status', 'pebas-report-listings' ),
				'options' => array(
					'unpaid' => esc_html__( 'Unpaid', 'pebas-bookings-extension' ),
					'paid'   => esc_html__( 'Paid', 'pebas-bookings-extension' ),
				),
				'std'     => 'unpaid',
			),
		);

		$meta_boxes[] = array(
			'title'    => esc_html__( 'Booking Payout Status', 'pebas-bookings-extension' ),
			'pages'    => pebas_payouts_install::$type_name,
			'fields'   => $status,
			'context'  => 'side',
			'priority' => 'low'
		);

		return $meta_boxes;
	}

}

/** Instantiate class
 *
 * @return null|pebas_payouts_meta
 */
function pebas_payouts_meta() {
	return pebas_payouts_meta::instance();
}
