<?php
/**
 * Class pebas_events_meta
 *
 * @author pebas
 * @ver 1.0.0
 */

/**
 * Class pebas_events_meta
 */
class pebas_events_meta {

	protected static $_instance = null;

	/**
	 * @return null|pebas_events_meta
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
				'id'        => '_event_listing',
				'name'      => esc_html__( 'Listing', 'pebas-listing-events' ),
				'type'      => 'post',
				'post_type' => 'job_listing',
				'desc'      => esc_html__( 'Choose listing where event will be displayed.', 'pebas-listing-events' ),
			),
			array(
				'id'          => '_event_title',
				'name'        => esc_html__( 'Event Title', 'pebas-listing-events' ),
				'type'        => 'text',
				'placeholder' => esc_html__( 'Friday Night Party!', 'pebas-listing-events' ),
				'desc'        => esc_html__( 'Enter title for the event', 'pebas-listing-events' ),
			),
			array(
				'id'          => '_event_description',
				'name'        => esc_html__( 'Event Description', 'pebas-listing-events' ),
				'type'        => 'textarea',
				'placeholder' => esc_html__( 'The very best party is coming this Friday!', 'pebas-listing-events' ),
				'desc'        => esc_html__( 'Enter description for the coupon', 'pebas-listing-events' ),
			),
			array(
				'id'           => '_event_image',
				'name'         => esc_html__( 'Event Image', 'pebas-listing-events' ),
				'type'         => 'single_image',
				'force_delete' => true,
				'desc'         => esc_html__( 'Upload image for the event.', 'pebas-listing-events' ),
			),
			array(
				'id'         => '_event_start',
				'name'       => esc_html__( 'Event Start', 'pebas-listing-events' ),
				'type'       => 'datetime',
				'js_options' => array(
					'stepMinute'     => 15,
					'showTimepicker' => true,
					'oneLine'        => true,
				),
				'desc'       => esc_html__( 'Choose start date of the event.', 'pebas-listing-events' ),
			),
			array(
				'id'   => '_event_address',
				'name' => esc_html__( 'Event Address', 'pebas-listing-events' ),
				'type' => 'text',
				'desc' => esc_html__( 'Please type the address of the event', 'pebas-listing-events' ),
			),
			array(
				'id'            => '_event_address_map',
				'name'          => esc_html__( 'Event Location', 'pebas-listing-events' ),
				'type'          => 'map',
				'desc'          => esc_html__( 'Please type the address of the location', 'pebas-listing-events' ),
				'std'           => '',
				'language'      => 'en',
				'address_field' => '_event_address',
				'api_key'       => isset( $option['map-google-api'] ) ? $option['map-google-api'] : '',
			),

			// default option
			array(
				'id'   => '_event_ticket_url',
				'name' => esc_html__( 'Event Ticket URL', 'pebas-listing-events' ),
				'type' => 'text',
				'desc' => esc_html__( 'Enter URL to the event tickets sale', 'pebas-listing-events' ),
			),
		);

		$meta_boxes[] = array(
			'title'   => esc_html__( 'Event Information', 'pebas-listing-events' ),
			'pages'   => pebas_listing_events_install::$post_type_name,
			'fields'  => $information,
			'context' => 'advanced',
		);

		$attendees = array(
			array(
				'id'   => '_event_attendees',
				'name' => esc_html__( 'Event Attendees', 'lisner-core' ),
				'type' => 'number',
				'min'  => '0',
				'desc' => esc_html__( 'Number of event attendees', 'lisner-core' ),
			),
			array(
				'id'   => '_event_attendees_ip',
				'name' => esc_html__( 'Event Attendees IP\'s', 'lisner-core' ),
				'type' => 'textarea',
				'desc' => esc_html__( 'IP addresses of the users that click on event attending', 'lisner-core' ),
			),
		);

		$meta_boxes[] = array(
			'title'   => esc_html__( 'Event Attendees', 'pebas-listing-events' ),
			'pages'   => pebas_listing_events_install::$post_type_name,
			'fields'  => $attendees,
			'context' => 'advanced',
		);

		$status = array(
			array(
				'id'      => '_event_status',
				'name'    => esc_html__( 'Event Status', 'pebas-listing-events' ),
				'type'    => 'select',
				'options' => array(
					'upcoming' => esc_html__( 'Upcoming', 'pebas-listing-events' ),
					'started'  => esc_html__( 'Started', 'pebas-listing-events' ),
				),
				'desc'    => esc_html__( 'Set status of the coupon', 'pebas-listing-events' ),
			),
		);

		$meta_boxes[] = array(
			'title'   => esc_html__( 'Event Status', 'pebas-listing-events' ),
			'pages'   => pebas_listing_events_install::$post_type_name,
			'fields'  => $status,
			'context' => 'side',
		);

		return $meta_boxes;
	}

}

/** Instantiate class
 *
 * @return null|pebas_events_meta
 */
function pebas_events_meta() {
	return pebas_events_meta::instance();
}
