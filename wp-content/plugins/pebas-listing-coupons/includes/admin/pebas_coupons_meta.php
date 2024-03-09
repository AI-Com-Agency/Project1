<?php
/**
 * Class pebas_coupons_meta
 *
 * @author pebas
 * @ver 1.0.0
 */

/**
 * Class pebas_coupons_meta
 */
class pebas_coupons_meta {

	protected static $_instance = null;

	/**
	 * @return null|pebas_coupons_meta
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
		$information = array(
			array(
				'id'        => '_coupon_listing',
				'name'      => esc_html__( 'Listing', 'pebas-listing-coupons' ),
				'type'      => 'post',
				'post_type' => 'job_listing',
				'desc'      => esc_html__( 'Choose listing where coupon will be displayed.', 'pebas-listing-coupons' ),
			),
			array(
				'id'          => '_coupon_title',
				'name'        => esc_html__( 'Coupon Title', 'pebas-listing-coupons' ),
				'type'        => 'text',
				'placeholder' => esc_html__( '25% off discount with this awesome coupon', 'pebas-listing-coupons' ),
				'desc'        => esc_html__( 'Enter title for the coupon', 'pebas-listing-coupons' ),
			),
			array(
				'id'          => '_coupon_description',
				'name'        => esc_html__( 'Coupon Description', 'pebas-listing-coupons' ),
				'type'        => 'textarea',
				'placeholder' => esc_html__( 'Claim this deal and get 25% cash back rewards', 'pebas-listing-coupons' ),
				'desc'        => esc_html__( 'Enter description for the coupon', 'pebas-listing-coupons' ),
			),
			array(
				'id'          => '_coupon_discount',
				'name'        => esc_html__( 'Coupon Discount', 'pebas-listing-coupons' ),
				'type'        => 'text',
				'placeholder' => esc_html__( '25', 'pebas-listing-coupons' ),
				'desc'        => esc_html__( 'Enter coupon discount percentage without any "%" sign.', 'pebas-listing-coupons' ),
			),
			array(
				'id'      => '_coupon_start',
				'name'    => esc_html__( 'Coupon Start', 'pebas-listing-coupons' ),
				'type'    => 'datetime',
				'js_options' => array(
					'stepMinute'      => 15,
					'showTimepicker'  => true,
					'oneLine'         => true,
				),
				'desc'    => esc_html__( 'Choose start date for the coupon.', 'pebas-listing-coupons' ),
			),
			array(
				'id'      => '_coupon_end',
				'name'    => esc_html__( 'Coupon Ends', 'pebas-listing-coupons' ),
				'type'    => 'datetime',
				'js_options' => array(
					'stepMinute'      => 15,
					'showTimepicker'  => true,
					'oneLine'         => true,
				),
				'desc'    => esc_html__( 'Choose end date for the coupon.', 'pebas-listing-coupons' ),
			),
			array(
				'id'      => '_coupon_type',
				'name'    => esc_html__( 'Coupon Type', 'pebas-listing-coupons' ),
				'type'    => 'select',
				'options' => array(
					'code'  => esc_html__( 'Code', 'pebas-listing-coupons' ),
					'link'  => esc_html__( 'Link', 'pebas-listing-coupons' ),
					'print' => esc_html__( 'Print', 'pebas-listing-coupons' ),
				),
				'desc'    => esc_html__( 'Choose coupon type you wish to use.', 'pebas-listing-coupons' ),
			),
			// if code
			array(
				'id'          => '_coupon_code',
				'name'        => esc_html__( 'Coupon Code', 'pebas-listing-coupons' ),
				'type'        => 'text',
				'placeholder' => esc_html__( 'e.g. CODE25', 'pebas-listing-coupons' ),
				'desc'        => esc_html__( 'Enter code for the coupon', 'pebas-listing-coupons' ),
				'hidden'      => array( '_coupon_type', '!=', 'code' )
			),
			// if link
			array(
				'id'          => '_coupon_link',
				'name'        => esc_html__( 'Coupon Link', 'pebas-listing-coupons' ),
				'type'        => 'text',
				'placeholder' => esc_html__( 'https://pebas.rs', 'pebas-listing-coupons' ),
				'desc'        => esc_html__( 'Enter affiliate link for the coupon', 'pebas-listing-coupons' ),
				'hidden'      => array( '_coupon_type', '!=', 'link' )
			),
			// if print
			array(
				'id'     => '_coupon_print',
				'name'   => esc_html__( 'Coupon Printable Image', 'pebas-listing-coupons' ),
				'type'   => 'single_image',
				'desc'   => esc_html__( 'Upload printable image for the coupon.', 'pebas-listing-coupons' ),
				'hidden' => array( '_coupon_type', '!=', 'print' )
			),

			// default option
			array(
				'id'          => '_coupon_button',
				'name'        => esc_html__( 'Coupon Button Text', 'pebas-listing-coupons' ),
				'type'        => 'text',
				'placeholder' => esc_html__( 'Click Here!', 'pebas-listing-coupons' ),
				'desc'        => esc_html__( 'Enter text for the coupon button', 'pebas-listing-coupons' ),
			),
		);

		$meta_boxes[] = array(
			'title'   => esc_html__( 'Coupon Information', 'pebas-listing-coupons' ),
			'pages'   => pebas_listing_coupons_install::$post_type_name,
			'fields'  => $information,
			'context' => 'advanced',
		);

		$status = array(
			array(
				'id'      => '_coupon_status',
				'name'    => esc_html__( 'Coupon Status', 'pebas-listing-coupons' ),
				'type'    => 'select',
				'options' => array(
					'active'   => esc_html__( 'Active', 'pebas-listing-coupons' ),
					'inactive' => esc_html__( 'Inactive', 'pebas-listing-coupons' ),
					'expired'  => esc_html__( 'Expired', 'pebas-listing-coupons' ),
				),
				'desc'    => esc_html__( 'Set status of the coupon', 'pebas-listing-coupons' ),
			),
		);

		$meta_boxes[] = array(
			'title'   => esc_html__( 'Coupon Status', 'pebas-listing-coupons' ),
			'pages'   => pebas_listing_coupons_install::$post_type_name,
			'fields'  => $status,
			'context' => 'side',
		);

		return $meta_boxes;
	}

}

/** Instantiate class
 *
 * @return null|pebas_coupons_meta
 */
function pebas_coupons_meta() {
	return pebas_coupons_meta::instance();
}
