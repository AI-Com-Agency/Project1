<?php
/**
 * Class pebas_events
 *
 * @author pebas
 * @ver 1.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * pebas_events
 */
// set coupon expiring cron
class pebas_events {

	protected static $_instance = null;


	/**
	 * @return null|pebas_events
	 */
	public static function instance() {
		return null === self::$_instance ? ( self::$_instance = new self ) : self::$_instance;
	}

	/**
	 * pebas_events constructor.
	 */
	public function __construct() {
		// display coupons
		add_action( 'lisner_listing_events', array( $this, 'listing_events' ), 10, 1 );
		add_action( 'lisner_listing_sidebar_events', array( $this, 'display_event' ), 10, 1 );

		// crud coupon
		add_action( 'lisner_ajax_save_event', array( $this, 'save_event' ) );
		add_action( 'lisner_ajax_remove_event', array( $this, 'remove_event' ) );

		// restrict media access && allow uploads to certain roles
		add_filter( 'ajax_query_attachments_args', array( $this, 'restrict_media_library' ) );
		add_action( 'init', array( $this, 'allow_file_uploads' ) );

		// call event modal
		add_action( 'wp_footer', array( $this, 'get_event_modal' ), 10 );

		// set cron to clear expired coupons
		add_action( 'ple_hourly_event', array( $this, 'clear_expired_events' ) );

		add_action( 'lisner_ajax_update_event_attendees', array( $this, 'update_event_attendees' ) );
		add_action( 'lisner_ajax_nopriv_update_event_attendees', array( $this, 'update_event_attendees' ) );
	}

	/**
	 * Load event modal
	 */
	public function get_event_modal() {
		$events = get_posts( array(
			'post_type'  => pebas_listing_events_install::$post_type_name,
			'meta_query' => array(
				array(
					'key'     => '_event_listing',
					'value'   => get_the_ID(),
					'compare' => '='
				)
			)
		) );
		if ( ! empty( $events ) && 0 != count( $events ) ) {
			wc_get_template( 'event_modal.php', array(
				'events'     => $events,
				'listing_id' => get_the_ID(),
			), '', PEBAS_LE_DIR . '/templates/' );
		}
	}


	/**
	 * Allow file uploads for the given roles
	 */
	public function allow_file_uploads() {
		if ( self::role_exists( 'subscriber' ) ) {
			$contributor = get_role( 'subscriber' );
			$contributor->add_cap( 'upload_files' );
		}
		if ( self::role_exists( 'author' ) ) {
			$author = get_role( 'author' );
			$author->add_cap( 'upload_files' );
		}

		if ( self::role_exists( 'employer' ) ) {
			$subscriber = get_role( 'employer' );
			$subscriber->add_cap( 'upload_files' );
		}
	}

	/**
	 * Check if an role exists
	 * --------------------------------
	 *
	 *
	 * @param $role
	 *
	 * @return bool
	 */
	public static function role_exists( $role ) {

		if ( ! empty( $role ) ) {
			return $GLOBALS['wp_roles']->is_role( $role );
		}

		return false;
	}

	/**
	 * Restrict media library access to images that the
	 * current user has uploaded
	 * ------------------------------------------------
	 *
	 * @param $query
	 *
	 * @return mixed
	 */
	function restrict_media_library( $query ) {
		$user_id = get_current_user_id();
		if ( $user_id ) {
			$query['author'] = $user_id;
		}

		return $query;
	}

	/**
	 * Save event functionality
	 * --------------------------
	 *
	 */
	public function save_event() {
		if ( ! empty( $_POST['action'] ) && 'save_event' == $_POST['action'] && wp_verify_nonce( $_REQUEST['save_event_nonce'], 'save_event_nonce' ) ) {
			$result      = array();
			$event_id    = isset( $_REQUEST['event_id'] ) ? $_REQUEST['event_id'] : '';
			$listing_id  = isset( $_REQUEST['listing_id'] ) ? $_REQUEST['listing_id'] : '';
			$title       = isset( $_REQUEST['_event_title'] ) ? $_REQUEST['_event_title'] : '';
			$description = isset( $_REQUEST['_event_description'] ) ? $_REQUEST['_event_description'] : '';
			$start       = isset( $_REQUEST['_event_start'] ) ? $_REQUEST['_event_start'] : '';
			$address     = isset( $_REQUEST['_event_address'] ) ? $_REQUEST['_event_address'] : '';
			$lat         = isset( $_REQUEST['location_lat'] ) ? $_REQUEST['location_lat'] : '';
			$lon         = isset( $_REQUEST['location_long'] ) ? $_REQUEST['location_long'] : '';
			$tickets     = isset( $_REQUEST['_event_ticket_url'] ) ? $_REQUEST['_event_ticket_url'] : '';
			$image       = isset( $_REQUEST['_event_image'] ) ? $_REQUEST['_event_image'] : '';

			if ( empty( $title ) ) {
				$result['error'] = esc_html__( 'Event title is required.', 'pebas-listing-events' );
			}

			if ( empty( $start ) ) {
				$result['error'] = esc_html__( 'Event start date is required.', 'pebas-listing-events' );
			}

			if ( ! $result['error'] ) {
				if ( empty( $event_id ) ) {
					$listing  = get_post( $listing_id );
					$count    = pebas_events::get_listing_events_count( $listing_id );
					$event_id = wp_insert_post(
						array(
							'post_type'   => pebas_listing_events_install::$post_type_name,
							'post_status' => 'publish',
							'post_title'  => $listing->post_title . ' Event #' . $count
						)
					);
					if ( is_wp_error( $event_id ) ) {
						$result['error'] = esc_html__( 'Event post has not been created.', 'pebas-listing-events' );
					} else {
						update_post_meta( $event_id, '_event_listing', $listing_id );
					}
				}
				update_post_meta( $event_id, '_event_title', $title );
				update_post_meta( $event_id, '_event_description', $description );
				update_post_meta( $event_id, '_event_start', $start );
				update_post_meta( $event_id, '_event_image', $image );
				update_post_meta( $event_id, '_event_ticket_url', $tickets );
				update_post_meta( $event_id, '_event_status', 'upcoming' );
				update_post_meta( $event_id, '_event_address', $address );
				// update the coordinates of the event
				$coords = implode( ',', array( $lat, $lon ) );
				update_post_meta( $event_id, '_event_address_map', $coords );

				ob_start();
				$this->listing_events( $listing_id );
				$result['html'] = ob_get_clean();

				$result['success'] = esc_html__( 'Event has been successfully saved!', 'pebas-listing-events' );

			} else {
				wp_send_json( $result );
			}
		} else {
			$result['error'] = esc_html__( 'There has been an error with this request!', 'pebas-listing-events' );
		}

		wp_send_json( $result );
	}

	/**
	 * Remove event
	 * ------------------
	 *
	 */
	public function remove_event() {
		$event_id = isset( $_REQUEST['event_id'] ) ? $_REQUEST['event_id'] : '';
		if ( ! empty( $event_id ) ) {
			wp_delete_post( $event_id, true );
			$result['success'] = esc_html__( 'Event has been successfully deleted!', 'pebas-listing-events' );
		} else {
			$result['error'] = esc_html__( 'Event could not be deleted!', 'pebas-listing-events' );
		}

		wp_send_json( $result );
	}

	/**
	 * Get events count for the given listing
	 *
	 * @param $listing_id
	 *
	 * @return int
	 */
	public static function get_listing_events_count( $listing_id ) {
		$events = get_posts( array(
			'post_type'  => pebas_listing_events_install::$post_type_name,
			'meta_query' => array(
				array(
					'key'     => '_event_listing',
					'value'   => $listing_id,
					'compare' => '='
				)
			)
		) );

		$all_events = array();
		foreach ( $events as $event ) {
			$all_events[] = $event->ID;
		}

		return count( $all_events );
	}

	/**
	 * Display listing event on listing single page
	 *
	 * @param $listing_id
	 */
	public function display_event( $listing_id ) {
		$events = get_posts( array(
			'post_type'  => pebas_listing_events_install::$post_type_name,
			'meta_query' => array(
				array(
					'key'     => '_event_listing',
					'value'   => $listing_id,
					'compare' => '='
				)
			)
		) );
		if ( ! empty( $events ) && 0 != count( $events ) ) {
			if ( $this->has_active_events( $listing_id ) ) {
				wc_get_template( 'event.php', array(
					'events'     => $events,
					'listing_id' => $listing_id,
					'type'       => 'listing_event',
					'template'   => 'event'
				), '', PEBAS_LE_DIR . '/templates/' );
			}
		}
	}

	/**
	 * Check if listing has active coupons attached
	 *
	 * @param $listing_id
	 *
	 * @return bool
	 */
	public function has_active_events( $listing_id ) {
		$events = get_posts( array(
			'post_type'  => pebas_listing_events_install::$post_type_name,
			'meta_query' => array(
				array(
					'key'     => '_event_listing',
					'value'   => $listing_id,
					'compare' => '='
				)
			)
		) );

		foreach ( $events as $event ) {
			if ( 'upcoming' == $event->_event_status ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Get active coupons for the given listing
	 *
	 * @param $listing_id
	 *
	 * @return array|bool
	 */
	public function get_active_events( $listing_id ) {
		$events = get_posts( array(
			'post_type'  => pebas_listing_events_install::$post_type_name,
			'meta_query' => array(
				array(
					'key'     => '_event_listing',
					'value'   => $listing_id,
					'compare' => '=',
				),
				array(
					'key'     => '_event_status',
					'value'   => 'upcoming',
					'compare' => '=',
				)
			)
		) );

		if ( $this->has_active_events( $listing_id ) ) {
			return $events;
		}

		return false;
	}

	/**
	 * Count listing coupons
	 *
	 * @param $listing_id
	 *
	 * @return bool
	 */
	public function count_events( $listing_id ) {
		$events     = get_posts( array(
			'post_type'  => pebas_listing_events_install::$post_type_name,
			'meta_query' => array(
				array(
					'key'     => '_event_listing',
					'value'   => $listing_id,
					'compare' => '='
				)
			)
		) );
		$all_events = array();
		foreach ( $events as $event ) {
			$all_events[] = $event->ID;
		}

		return count( $all_events );
	}


	/**
	 * Display listing coupons in user dashboard
	 *
	 * @param $listing_id
	 */
	public function listing_events( $listing_id ) {
		$listing_author = get_post_meta( $listing_id, '_job_author', true );
		$events         = get_posts( array(
			'post_type'  => pebas_listing_events_install::$post_type_name,
			'meta_query' => array(
				array(
					'key'     => '_event_listing',
					'value'   => $listing_id,
					'compare' => '='
				)
			)
		) );
		if ( ! empty( $events ) && 0 != count( $events ) && ( current_user_can( 'administrator' ) || get_current_user_id() == $listing_author ) ) {
			wc_get_template( 'listing-events.php', array(
				'events'     => $events,
				'listing_id' => $listing_id,
				'type'       => pebas_listing_events_install::$post_type_name,
				'template'   => 'listing_events'
			), '', PEBAS_LE_DIR . '/templates/' );
		} else {
			wc_get_template( 'no-listing-events.php', array( 'listing_id' => $listing_id ), '', PEBAS_LE_DIR . '/templates/' );
		}
	}

	/**
	 * Display coupon form
	 *
	 * @param $listing_id
	 */
	public function event_form( $listing_id ) {
		$listing_author = get_post_meta( $listing_id, '_job_author', true );
		if ( ( current_user_can( 'administrator' ) || get_current_user_id() == $listing_author ) ) {
			wc_get_template( 'event-form.php', array(
				'listing_id' => $listing_id,
				'type'       => pebas_listing_events_install::$post_type_name,
				'template'   => 'listing_event'
			), '', PEBAS_LE_DIR . '/templates/' );
		} else {
			wc_get_template( 'no-listing.php', array(), '', PEBAS_LE_DIR . '/templates/' );
		}
	}

	/**
	 * Coupon has started
	 *
	 * @param $event_id
	 *
	 * @return bool
	 */
	public function has_event_started( $event_id ) {
		$event      = get_post( $event_id );
		$now        = current_time( 'Y-m-d H:i' );
		$start_date = isset( $event->_event_start ) && ! empty( $event->_event_start ) ? $event->_event_start : '';

		if ( ! empty( $start_date ) && $now < $start_date ) {
			return false;
		}

		return true;
	}

	/**
	 * Clear events that are already started
	 * --------------------------------------
	 *
	 */
	public function clear_expired_events() {
		$events = get_posts( array(
			'post_type'  => pebas_listing_events_install::$post_type_name,
			'meta_query' => array(
				array(
					'key'     => '_event_status',
					'value'   => 'started',
					'compare' => '!='
				)
			)
		) );

		if ( ! empty( $events ) && 0 != count( $events ) ) {
			foreach ( $events as $event ) {
				if ( $this->has_event_started( $event->ID ) ) {
					update_post_meta( $event->ID, '_event_status', 'started' );
				}
			}
		}
	}

	/**
	 * Update event attendees
	 */
	public function update_event_attendees() {
		$ip        = isset( $_REQUEST['ip'] ) ? $_REQUEST['ip'] : '';
		$id        = isset( $_REQUEST['id'] ) ? $_REQUEST['id'] : '';
		$ips       = get_post_meta( $id, '_event_attendees_ip' );
		$attendees = get_post_meta( $id, '_event_attendees', true );

		$ips = implode( PHP_EOL, $ips );
		$ips = explode( PHP_EOL, $ips );
		if ( ! in_array( $ip, $ips ) ) {
			$ips[] = $ip;
			$ips   = array( implode( PHP_EOL, $ips ) );
			update_post_meta( $id, '_event_attendees_ip', implode( ',', $ips ) );
			if ( empty( $attendees ) ) {
				update_post_meta( $id, '_event_attendees', 1 );
			} else {
				$attendees ++;
				update_post_meta( $id, '_event_attendees', $attendees );
			}
			$result['going']      = true;

		} else {
			$attendees --;
			update_post_meta( $id, '_event_attendees', $attendees );
			unset( $ips[ array_search( $ip, $ips ) ] );
			$ips = array( implode( PHP_EOL, $ips ) );
			update_post_meta( $id, '_event_attendees_ip', implode( ',', $ips ) );
			$result['false']      = true;
		}
		$result['attendees_count'] = get_post_meta( $id, '_event_attendees', true );
		$result['error']       = false;
		$result['notice']      = esc_html__( 'Thanks for response!', 'pebas-listing-events' );

		wp_send_json( $result );
	}

	/**
	 * Check whether current user is attending event
	 *
	 * @param $listing_id
	 *
	 * @return bool
	 */
	public static function has_user_attending_event( $listing_id ) {
		$attendees = get_post_meta( $listing_id, '_event_attendees_ip' );
		$ips   = array_unique( $attendees );
		$ips   = implode( PHP_EOL, $ips );
		$ips   = explode( PHP_EOL, $ips );
		$ip    = lisner_helper()->get_client_ip();
		if ( in_array( $ip, $ips ) ) {
			return true;
		}

		return false;
	}

}

//todo move this to function file later
function pbs_count_events( $listing_id ) {
	return pebas_events()->count_events( $listing_id );
}

function pbs_count_active_events( $listing_id ) {
	return pebas_events()->get_active_events( $listing_id );
}

/**
 * Instantiate the class
 *
 * @return null|pebas_events
 */
function pebas_events() {
	return pebas_events::instance();
}
