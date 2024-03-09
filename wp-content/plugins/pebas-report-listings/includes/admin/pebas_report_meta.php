<?php
/**
 * Class pebas_report_meta
 *
 * @author pebas
 * @ver 1.0.0
 */

/**
 * Class pebas_report_meta
 */
class pebas_report_meta {

	protected static $_instance = null;

	/**
	 * @return null|pebas_report_meta
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
		// remove default meta boxes
		add_action( 'add_meta_boxes', array( $this, 'remove_default_meta_boxes' ) );
		add_action( 'rwmb_after_save_post', array( $this, 'save_report' ), 1 );

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

		// Listing reports Meta Fields / Status
		$status = array(
			array(
				'id'      => '_report_status',
				'name'    => esc_html__( 'Listing Report Status', 'pebas-report-listings' ),
				'type'    => 'select',
				'desc'    => esc_html__( 'Choose listing report status', 'pebas-report-listings' ),
				'options' => pebas_report()->get_listing_report_statuses(),
				'std'     => 'pending',
			),
		);

		$meta_boxes[] = array(
			'title'    => esc_html__( 'Listing Report Status', 'pebas-report-listings' ),
			'pages'    => pebas_report_listings_install::$pebas_report_type_name,
			'fields'   => $status,
			'context'  => 'side',
			'priority' => 'low'
		);

		// Listing reports Meta Fields / Information
		$information = array(
			array(
				'id'        => '_report_listing_id',
				'name'      => esc_html__( 'Listing Reported', 'pebas-report-listings' ),
				'type'      => 'post',
				'post_type' => 'job_listing',
				'desc'      => esc_html__( 'Choose reported listing from the list of available ones', 'pebas-report-listings' ),
			),
			array(
				'id'      => '_report_user_id',
				'name'    => esc_html__( 'Listing Reported By ( id )', 'pebas-report-listings' ),
				'type'    => 'user',
				'desc'    => esc_html__( 'Choose which user has reported the listing', 'pebas-report-listings' ),
				'tooltip' => array(
					'icon'    => 'info',
					'content' => esc_html__( 'Will be automatically populated if the user has account registered on site', 'pebas-report-listing' ),
				)
			),
			array(
				'id'   => '_report_user_email',
				'name' => esc_html__( 'Listing Reported By ( email )', 'pebas-report-listings' ),
				'type' => 'text',
				'desc' => esc_html__( 'Choose which user has reported the listing', 'pebas-report-listings' ),
			),
			array(
				'id'       => '_report_user_ip',
				'name'     => esc_html__( 'Listing Reported By', 'pebas-report-listings' ),
				'type'     => 'text',
				'readonly' => true,
				'desc'     => esc_html__( 'IP address of user that reported the listing', 'pebas-report-listings' ),
				'tooltip'  => array(
					'icon'    => 'info',
					'content' => esc_html__( 'This is readonly field that is automatically populated with user IP address.', 'pebas-report-listing' ),
				)
			),
			array(
				'id'      => '_report_data',
				'name'    => esc_html__( 'Reporting Reason', 'pebas-report-listings' ),
				'type'    => 'wysiwyg',
				'options' => array(
					'teeny'         => true,
					'media_buttons' => false,
					'quicktags'     => false,
					'textarea_rows' => 7
				),
				'desc'    => esc_html__( 'Message that explains reason why the listing was reported', 'pebas-report-listings' ),
			),
		);

		$meta_boxes[] = array(
			'title'   => esc_html__( 'Listing Report Information', 'pebas-report-listings' ),
			'pages'   => pebas_report_listings_install::$pebas_report_type_name,
			'fields'  => $information,
			'context' => 'advanced',
		);

		return $meta_boxes;
	}

	/**
	 * Remove default report post type meta boxes
	 */
	public function remove_default_meta_boxes() {
		remove_meta_box( 'authordiv', pebas_report_listings_install::$pebas_report_type_name, 'normal' ); // author
		remove_meta_box( 'slugdiv', pebas_report_listings_install::$pebas_report_type_name, 'normal' ); // slug
	}

	/**
	 * Update listing reports on save
	 *
	 * @param $post_id
	 */
	public function save_report( $post_id ) {
		if ( 'listing_report' == get_post_type() ) {
			$title = get_the_title( $post_id );
			if ( empty( $title ) ) {
				$args = array(
					'ID'         => $post_id,
					'post_title' => sprintf( esc_html__( 'Listing Report #%s', 'pebas-report-listings' ), $post_id ),
				);

				wp_update_post( $args );

			}
		}
	}

}

/** Instantiate class
 *
 * @return null|pebas_report_meta
 */
function pebas_report_meta() {
	return pebas_report_meta::instance();
}
