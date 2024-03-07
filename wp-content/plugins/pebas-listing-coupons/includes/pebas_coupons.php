<?php
/**
 * Class pebas_coupons
 *
 * @author pebas
 * @ver 1.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * pebas_coupons
 */
// set coupon expiring cron
class pebas_coupons {

	protected static $_instance = null;


	/**
	 * @return null|pebas_coupons
	 */
	public static function instance() {
		return null === self::$_instance ? ( self::$_instance = new self ) : self::$_instance;
	}

	/**
	 * pebas_coupons constructor.
	 */
	public function __construct() {
		// display coupons
		add_action( 'lisner_listing_coupons', array( $this, 'listing_coupons' ), 10, 1 );
		add_action( 'lisner_listing_sidebar_coupons', array( $this, 'display_coupon' ), 10, 1 );

		// crud coupon
		add_action( 'lisner_ajax_save_coupon', array( $this, 'save_coupon' ) );
		add_action( 'lisner_ajax_remove_coupon', array( $this, 'remove_coupon' ) );

		// restrict media access && allow uploads to certain roles
		add_filter( 'ajax_query_attachments_args', array( $this, 'restrict_media_library' ) );
		add_action( 'init', array( $this, 'allow_file_uploads' ) );

		// call coupon modal
		add_action( 'wp_footer', array( $this, 'get_coupon_modal' ), 10 );

		// set cron to clear expired coupons
		add_action( 'plc_hourly_event', array( $this, 'clear_expired_coupons' ) );
	}


	/**
	 * Load coupon code modal
	 */
	public function get_coupon_modal() {
		$coupons = get_posts( array(
			'post_type'  => pebas_listing_coupons_install::$post_type_name,
			'meta_query' => array(
				array(
					'key'     => '_coupon_listing',
					'value'   => get_the_ID(),
					'compare' => '='
				)
			)
		) );
		if ( ! empty( $coupons ) && 0 != count( $coupons ) ) {
			wc_get_template( 'coupon_modal.php', array(
				'coupons'    => $coupons,
				'listing_id' => get_the_ID(),
			), '', PEBAS_LC_DIR . '/templates/' );
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

		if( ! empty( $role ) ) {
			return $GLOBALS['wp_roles']->is_role( $role );
		}

		return false;
	}

	/**
	 * Restrict media library access to images that the
	 * current user has uploaded
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
	 * Save coupon functionality
	 */
	public function save_coupon() {
		if ( ! empty( $_POST['action'] ) && 'save_coupon' == $_POST['action'] && wp_verify_nonce( $_REQUEST['save_coupon_nonce'], 'save_coupon_nonce' ) ) {
			$coupon_id   = isset( $_REQUEST['coupon_id'] ) ? $_REQUEST['coupon_id'] : '';
			$listing_id  = isset( $_REQUEST['listing_id'] ) ? $_REQUEST['listing_id'] : '';
			$title       = isset( $_REQUEST['_coupon_title'] ) ? $_REQUEST['_coupon_title'] : '';
			$description = isset( $_REQUEST['_coupon_description'] ) ? $_REQUEST['_coupon_description'] : '';
			$discount    = isset( $_REQUEST['_coupon_discount'] ) ? $_REQUEST['_coupon_discount'] : '';
			$button      = isset( $_REQUEST['_coupon_button'] ) ? $_REQUEST['_coupon_button'] : '';
			$start       = isset( $_REQUEST['_coupon_start'] ) ? $_REQUEST['_coupon_start'] : '';
			$end         = isset( $_REQUEST['_coupon_end'] ) ? $_REQUEST['_coupon_end'] : '';
			$type        = isset( $_REQUEST['_coupon_type'] ) ? $_REQUEST['_coupon_type'] : '';
			$code        = isset( $_REQUEST['_coupon_code'] ) ? $_REQUEST['_coupon_code'] : '';
			$link        = isset( $_REQUEST['_coupon_link'] ) ? $_REQUEST['_coupon_link'] : '';
			$print       = isset( $_REQUEST['_coupon_print'] ) ? $_REQUEST['_coupon_print'] : '';

			if ( empty( $coupon_id ) ) {
				$listing   = get_post( $listing_id );
				$count     = pebas_coupons::get_listing_coupons_count( $listing_id );
				$coupon_id = wp_insert_post(
					array(
						'post_type'   => 'listing_coupon',
						'post_status' => 'publish',
						'post_title'  => $listing->post_title . ' Coupon #' . $count
					)
				);
				if ( is_wp_error( $coupon_id ) ) {
					$result['error'] = esc_html__( 'Coupon post has not been created.', 'pebas-listing-coupons' );
				} else {
					update_post_meta( $coupon_id, '_coupon_listing', $listing_id );
				}
			}
			update_post_meta( $coupon_id, '_coupon_title', $title );
			update_post_meta( $coupon_id, '_coupon_description', $description );
			update_post_meta( $coupon_id, '_coupon_discount', $discount );
			update_post_meta( $coupon_id, '_coupon_button', $button );
			update_post_meta( $coupon_id, '_coupon_start', $start );
			update_post_meta( $coupon_id, '_coupon_end', $end );
			update_post_meta( $coupon_id, '_coupon_type', $type );
			update_post_meta( $coupon_id, '_coupon_code', $code );
			update_post_meta( $coupon_id, '_coupon_link', $link );
			update_post_meta( $coupon_id, '_coupon_print', $print );
			update_post_meta( $coupon_id, '_coupon_status', 'active' );

			ob_start();
			$this->listing_coupons( $listing_id );
			$result['html'] = ob_get_clean();

			$result['success'] = esc_html__( 'Coupon has been successfully saved!', 'pebas-listing-coupons' );
		} else {
			$result['error'] = esc_html__( 'There has been an error with this request!', 'pebas-listing-coupons' );
		}

		wp_send_json( $result );
	}

	public function remove_coupon() {
		$coupon_id = isset( $_REQUEST['coupon_id'] ) ? $_REQUEST['coupon_id'] : '';
		if ( ! empty( $coupon_id ) ) {
			wp_delete_post( $coupon_id, true );
			$result['success'] = esc_html__( 'Coupon has been successfully deleted!', 'pebas-listing-coupons' );
		} else {
			$result['error'] = esc_html__( 'Coupon could not be deleted!', 'pebas-listing-coupons' );
		}

		wp_send_json( $result );
	}

	/**
	 * Display listing coupon on listing single page
	 *
	 * @param $listing_id
	 */
	public function display_coupon( $listing_id ) {
		$coupons = get_posts( array(
			'post_type'  => pebas_listing_coupons_install::$post_type_name,
			'meta_query' => array(
				array(
					'key'     => '_coupon_listing',
					'value'   => $listing_id,
					'compare' => '='
				)
			)
		) );
		if ( ! empty( $coupons ) && 0 != count( $coupons ) ) {
			if ( $this->has_active_coupons( $listing_id ) ) {
				wc_get_template( 'coupon.php', array(
					'coupons'    => $coupons,
					'listing_id' => $listing_id,
					'type'       => 'listing_coupon',
					'template'   => 'coupon'
				), '', PEBAS_LC_DIR . '/templates/' );
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
	public function has_active_coupons( $listing_id ) {
		$coupons = get_posts( array(
			'post_type'  => pebas_listing_coupons_install::$post_type_name,
			'meta_query' => array(
				array(
					'key'     => '_coupon_listing',
					'value'   => $listing_id,
					'compare' => '='
				)
			)
		) );

		foreach ( $coupons as $coupon ) {
			if ( 'active' == $coupon->_coupon_status ) {
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
	public function get_active_coupons( $listing_id ) {
		$coupons = get_posts( array(
			'post_type'  => pebas_listing_coupons_install::$post_type_name,
			'meta_query' => array(
				array(
					'key'     => '_coupon_listing',
					'value'   => $listing_id,
					'compare' => '=',
				),
				array(
					'key'     => '_coupon_status',
					'value'   => 'active',
					'compare' => '=',
				)
			)
		) );

		if ( $this->has_active_coupons( $listing_id ) ) {
			return $coupons;
		}

		return false;
	}

	/**
	 * Get array of coupons discounts for the given listing
	 *
	 * @param $listing_id
	 *
	 * @return array|bool
	 */
	public function get_coupons_discounts( $listing_id ) {
		$coupons = $this->get_active_coupons( $listing_id );
		if ( ! $coupons ) {
			return false;
		}
		$coupon_prices = array();
		foreach ( $coupons as $coupon ) {
			$coupon_prices[] = $coupon->_coupon_discount;
		}

		sort( $coupon_prices );

		return $coupon_prices;
	}

	/**
	 * Get coupons label values for displaying on
	 * a listing box
	 *
	 * @param $listing_id
	 *
	 * @return array|bool
	 */
	public function get_coupons_label_values( $listing_id ) {
		$coupons = $this->get_coupons_discounts( $listing_id );
		if ( ! $coupons ) {
			return false;
		}
		$discount = $coupons;

		if ( 1 < count( $coupons ) ) {
			$lowest   = array_shift( $coupons );
			$highest  = array_pop( $coupons );
			$discount = array( $lowest, $highest );
		}

		return $discount;
	}

	/**
	 * Count listing coupons
	 *
	 * @param $listing_id
	 *
	 * @return bool
	 */
	public function count_coupons( $listing_id ) {
		$coupons     = get_posts( array(
			'post_type'  => pebas_listing_coupons_install::$post_type_name,
			'meta_query' => array(
				array(
					'key'     => '_coupon_listing',
					'value'   => $listing_id,
					'compare' => '='
				)
			)
		) );
		$all_coupons = array();
		foreach ( $coupons as $coupon ) {
			$all_coupons[] = $coupon->ID;
		}

		return count( $all_coupons );
	}


	/**
	 * Display listing coupons in user dashboard
	 *
	 * @param $listing_id
	 */
	public function listing_coupons( $listing_id ) {
		$listing_author = get_post_meta( $listing_id, '_job_author', true );
		$coupons        = get_posts( array(
			'post_type'  => pebas_listing_coupons_install::$post_type_name,
			'meta_query' => array(
				array(
					'key'     => '_coupon_listing',
					'value'   => $listing_id,
					'compare' => '='
				)
			)
		) );
		if ( ! empty( $coupons ) && 0 != count( $coupons ) && ( current_user_can( 'administrator' ) || get_current_user_id() == $listing_author ) ) {
			wc_get_template( 'listing-coupons.php', array(
				'coupons'    => $coupons,
				'listing_id' => $listing_id,
				'type'       => 'listing_coupon',
				'template'   => 'listing_coupons'
			), '', PEBAS_LC_DIR . '/templates/' );
		} else {
			wc_get_template( 'no-listing-coupons.php', array( 'listing_id' => $listing_id ), '', PEBAS_LC_DIR . '/templates/' );
		}
	}

	/**
	 * Display coupon form
	 *
	 * @param $listing_id
	 */
	public function coupon_form( $listing_id ) {
		$listing_author = get_post_meta( $listing_id, '_job_author', true );
		if ( ( current_user_can( 'administrator' ) || get_current_user_id() == $listing_author ) ) {
			wc_get_template( 'coupon-form.php', array(
				'listing_id' => $listing_id,
				'type'       => 'listing_coupon',
				'template'   => 'listing_coupons'
			), '', PEBAS_LC_DIR . '/templates/' );
		} else {
			wc_get_template( 'no-listing.php', array(), '', PEBAS_LC_DIR . '/templates/' );
		}
	}

	/**
	 * Get coupon count for the given listing
	 *
	 * @param $listing_id
	 *
	 * @return int
	 */
	public static function get_listing_coupons_count( $listing_id ) {
		$coupons = get_posts( array(
			'post_type'  => pebas_listing_coupons_install::$post_type_name,
			'meta_query' => array(
				array(
					'key'     => '_coupon_listing',
					'value'   => $listing_id,
					'compare' => '='
				)
			)
		) );

		$all_coupons = array();
		foreach ( $coupons as $coupon ) {
			$all_coupons[] = $coupon->ID;
		}

		return count( $coupons );
	}

	/**
	 * Coupon has expired
	 *
	 * @param $coupon_id
	 *
	 * @return bool
	 */
	public function has_coupon_expired( $coupon_id ) {
		$coupon   = get_post( $coupon_id );
		$now      = current_time( 'Y-m-d H:i' );
		$end_date = isset( $coupon->_coupon_end ) && ! empty( $coupon->_coupon_end ) ? $coupon->_coupon_end : '';

		if ( ! empty( $end_date ) && $now > $end_date ) {
			return true;
		}

		return false;
	}

	/**
	 * Coupon has started
	 *
	 * @param $coupon_id
	 *
	 * @return bool
	 */
	public function has_coupon_started( $coupon_id ) {
		$coupon     = get_post( $coupon_id );
		$now        = current_time( 'Y-m-d H:i' );
		$start_date = isset( $coupon->_coupon_start ) && ! empty( $coupon->_coupon_start ) ? $coupon->_coupon_start : '';

		if ( ! empty( $start_date ) && $now < $start_date ) {
			return false;
		}

		return true;
	}

	public function clear_expired_coupons() {
		$coupons = get_posts( array(
			'post_type'  => pebas_listing_coupons_install::$post_type_name,
			'meta_query' => array(
				array(
					'key'     => '_coupon_status',
					'value'   => 'expired',
					'compare' => '!='
				)
			)
		) );

		if ( ! empty( $coupons ) && 0 != count( $coupons ) ) {
			foreach ( $coupons as $coupon ) {
				if ( $this->has_coupon_expired( $coupon->ID ) ) {
					update_post_meta( $coupon->ID, '_coupon_status', 'expired' );
				}
			}
		}
	}

}

/**
 * Instantiate the class
 *
 * @return null|pebas_coupons
 */
function pebas_coupons() {
	return pebas_coupons::instance();
}
