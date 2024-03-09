<?php
/**
 * Class pebas_booking
 *
 * @author pebas
 * @ver 1.0.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * pebas_booking
 */
class pebas_booking {

	protected static $_instance = null;

	protected $internal_meta_keys = array(
		'_visibility',
		'_sku',
		'_price',
		'_regular_price',
		'_sale_price',
		'_sale_price_dates_from',
		'_sale_price_dates_to',
		'total_sales',
		'_tax_status',
		'_tax_class',
		'_manage_stock',
		'_stock',
		'_stock_status',
		'_backorders',
		'_sold_individually',
		'_weight',
		'_length',
		'_width',
		'_height',
		'_upsell_ids',
		'_crosssell_ids',
		'_purchase_note',
		'_default_attributes',
		'_product_attributes',
		'_virtual',
		'_downloadable',
		'_featured',
		'_downloadable_files',
		'_wc_rating_count',
		'_wc_average_rating',
		'_wc_review_count',
		'_variation_description',
	);

	protected $booking_meta_key_to_props = array(
		'_has_additional_costs'                  => 'has_additional_costs',
		'_wc_booking_apply_adjacent_buffer'      => 'apply_adjacent_buffer',
		'_wc_booking_availability'               => 'availability',
		'_wc_booking_block_cost'                 => 'block_cost',
		'_wc_booking_buffer_period'              => 'buffer_period',
		'_wc_booking_calendar_display_mode'      => 'calendar_display_mode',
		'_wc_booking_cancel_limit_unit'          => 'cancel_limit_unit',
		'_wc_booking_cancel_limit'               => 'cancel_limit',
		'_wc_booking_check_availability_against' => 'check_start_block_only',
		'_wc_booking_cost'                       => 'cost',
		'_wc_booking_default_date_availability'  => 'default_date_availability',
		'_wc_booking_duration_type'              => 'duration_type',
		'_wc_booking_duration_unit'              => 'duration_unit',
		'_wc_booking_duration'                   => 'duration',
		'_wc_booking_enable_range_picker'        => 'enable_range_picker',
		'_wc_booking_first_block_time'           => 'first_block_time',
		'_wc_booking_has_person_types'           => 'has_person_types',
		'_wc_booking_has_persons'                => 'has_persons',
		'_wc_booking_has_resources'              => 'has_resources',
		'_wc_booking_has_restricted_days'        => 'has_restricted_days',
		'_wc_booking_max_date_unit'              => 'max_date_unit',
		'_wc_booking_max_date'                   => 'max_date_value',
		'_wc_booking_max_duration'               => 'max_duration',
		'_wc_booking_max_persons_group'          => 'max_persons',
		'_wc_booking_min_date_unit'              => 'min_date_unit',
		'_wc_booking_min_date'                   => 'min_date_value',
		'_wc_booking_min_duration'               => 'min_duration',
		'_wc_booking_min_persons_group'          => 'min_persons',
		'_wc_booking_person_cost_multiplier'     => 'has_person_cost_multiplier',
		'_wc_booking_person_qty_multiplier'      => 'has_person_qty_multiplier',
		'_wc_booking_pricing'                    => 'pricing',
		'_wc_booking_qty'                        => 'qty',
		'_wc_booking_requires_confirmation'      => 'requires_confirmation',
		'_wc_booking_resources_assignment'       => 'resources_assignment',
		'_wc_booking_restricted_days'            => 'restricted_days',
		'_wc_booking_user_can_cancel'            => 'user_can_cancel',
		'_wc_display_cost'                       => 'display_cost',
		'wc_booking_resource_label'              => 'resource_label',
		'_price'                                 => 'price',
	);

	/**
	 * @return null|pebas_booking
	 */
	public static function instance() {
		return null === self::$_instance ? ( self::$_instance = new self ) : self::$_instance;
	}

	/**
	 * pebas_booking constructor.
	 */
	public function __construct() {
		// add actions
		add_action( 'lisner_booking_product', array( $this, 'booking_product' ) );
		add_action( 'lisner_booking_orders', array( $this, 'booking_orders' ) );
		add_action( 'lisner_ajax_create_booking_product', array( $this, 'create_booking_product' ) );
		add_action( 'lisner_ajax_update_booking_product', array( $this, 'update_booking_product' ) );
		add_action( 'lisner_ajax_confirm_booking', array( $this, 'confirm_booking' ) );
	}

	/**
	 * Load booking product managing page
	 *
	 * @param string $listing_id
	 */
	public function booking_product( $listing_id = '' ) {
		$product_id       = get_post_meta( $listing_id, '_listing_products', true );
		$bookable_product = new WC_Product_Booking( $product_id );

		$restricted_meta = $bookable_product->get_restricted_days();

		for ( $i = 0; $i < 7; $i ++ ) {

			if ( $restricted_meta && in_array( $i, $restricted_meta ) ) {
				$restricted_days[ $i ] = $i;
			} else {
				$restricted_days[ $i ] = false;
			}
		}
		wc_get_template( 'booking-product.php', array(
			'listing_id'       => $listing_id,
			'post'             => $product_id,
			'bookable_product' => $bookable_product,
			'restricted_days'  => $restricted_days
		), '', PEBAS_BO_DIR . '/templates/' );
	}

	/**
	 * Get all products from a currently logged user
	 * ---------------------------------------------
	 *
	 *
	 * @return array|bool
	 */
	public function get_user_products() {
		$args = array();
		if ( ! current_user_can( 'administrator' ) ) {
			$args = array(
				array(
					'key'     => '_job_author',
					'value'   => get_current_user_id(),
					'compare' => '='
				)
			);
		}
		$listings    = get_posts( array(
			'post_type'      => 'job_listing',
			'meta_query'     => $args,
			'posts_per_page' => - 1
		) );
		$product_ids = array();
		foreach ( $listings as $listing ) {
			$product = get_post_meta( $listing->ID, '_listing_products', true );
			if ( isset( $product ) && ! empty( $product ) ) {
				$product_ids[] = $product;
			}
		}

		return ! empty( $product_ids ) ? $product_ids : false;
	}

	/**
	 * Template for displaying information to the user
	 * -----------------------------------------------
	 *
	 *
	 */
	public function display_user_payout_information() {
		$option                     = get_option( 'pbs_option' );
		$display_payout_information = $option['booking-payout-display'];
		$title                      = isset( $option['booking-payout-title'] ) ? $option['booking-payout-title'] : '';
		$description                = isset( $option['booking-payout-description'] ) ? $option['booking-payout-description'] : '';
		if ( isset( $display_payout_information ) && 1 == $display_payout_information ) {
			wc_get_template( 'payout-information.php', array(
				'title'       => $title,
				'description' => $description,
			), '', PEBAS_BO_DIR . '/templates/' );
		}
	}

	/**
	 * Display all booking orders for a given order
	 * ---------------------------------------
	 *
	 *
	 * @param $current_page
	 */
	public function booking_orders( $current_page = 0 ) {
		$products          = $this->get_user_products();
		$bookings_per_page = 10;
		$current_page      = empty( $current_page ) ? 1 : absint( $current_page );

		// display booking payout information to the user
		$this->display_user_payout_information();

		if ( isset( $_GET['booking_id'] ) && ! empty( $_GET['booking_id'] ) ) {
			$booking = get_wc_booking( $_GET['booking_id'] );
			wc_get_template( 'booking-order.php', array(
				'booking' => $booking,
			), '', PEBAS_BO_DIR . '/templates/' );
		} else {
			// bookings
			$bookings = array();
			if ( ! isset( $_GET['find'] ) || 'old_bookings' != $_GET['find'] ) {
				$statuses = array( 'paid', 'pending-confirmation' );
				if ( $products ) {
					foreach ( $products as $product ) {
						$title      = esc_html__( 'Upcoming Bookings', 'pebas-bookings-extension' );
						$bookings[] = WC_Bookings_Controller::get_bookings_for_product( $product, $statuses );
					}
				}
				wc_get_template( 'booking-orders.php', array(
					'title'             => isset( $title ) ? $title : '',
					'products'          => isset( $products ) ? $products : false,
					'bookings'          => $bookings,
					'page'              => $current_page,
					'bookings_per_page' => $bookings_per_page,
				), '', PEBAS_BO_DIR . '/templates/' );
			}

			if ( isset( $_GET['find'] ) && 'old_bookings' == $_GET['find'] ) {
				$title    = esc_html__( 'Past Bookings', 'pebas-bookings-extension' );
				$statuses = array( 'paid', 'pending-confirmation', 'cancelled', 'complete' );
				foreach ( $products as $product ) {
					$title      = esc_html__( 'Past Bookings', 'pebas-bookings-extension' );
					$bookings[] = WC_Bookings_Controller::get_bookings_for_product( $product, $statuses );
				}
				wc_get_template( 'booking-orders-past.php', array(
					'title'             => $title,
					'products'          => isset( $products ) ? $products : false,
					'bookings'          => $bookings,
					'page'              => $current_page,
					'bookings_per_page' => $bookings_per_page,
				), '', PEBAS_BO_DIR . '/templates/' );
			}
		}

	}

	/**
	 * Add Booking Product
	 */
	public function create_booking_product() {
		global $wpdb;
		if ( ! empty( $_POST['action'] ) && 'create_booking_product' == $_POST['action'] && wp_verify_nonce( $_REQUEST['nonce'], 'create_booking_product' ) ) {
			$listing_id      = isset( $_REQUEST['listing_id'] ) ? $_REQUEST['listing_id'] : 0;
			$listing_product = get_post_meta( $listing_id, '_listing_products', true );
			if ( isset( $listing_product ) && ! empty( $listing_product ) ) {
				$result['error'] = esc_html__( 'Listing product already exists', 'pebas-bookings-extension' );

				return false;
			}
			$product = new WC_Product_Booking();
			$save    = new WC_Product_Booking_Data_Store_CPT;
			$save->create( $product );
			wp_update_post( array(
				'ID'         => $product->get_id(),
				'post_title' => sprintf( esc_html__( '%s - Booking Product', 'pebas-bookings-extension' ), get_the_title( $listing_id ) )
			) );
			update_post_meta( $listing_id, '_listing_products', $product->get_id() );

			$result['product_id'] = $product->get_id();
			$result['success']    = esc_html__( 'Product created', 'pebas-bookings-extension' );

		} else {
			$result['error'] = esc_html__( 'Something has not being set up properly. Please contact site administrator.', 'pebas-bookings-extension' );
		}
		wp_send_json( $result );
	}

	/**
	 * Add Booking Product
	 */
	public function update_booking_product() {
		global $wpdb;
		if ( ! empty( $_POST['action'] ) && 'update_booking_product' == $_POST['action'] && wp_verify_nonce( $_REQUEST['pebas_booking_nonce'], 'pebas_booking_nonce' ) ) {
			$listing_id = isset( $_REQUEST['listing_id'] ) ? $_REQUEST['listing_id'] : 0;
			if ( ! isset( $listing_id ) ) {
				return false;
			}
			$listing_product = get_post_meta( $listing_id, '_listing_products', true );
			$product         = new WC_Product_Booking( $listing_product );
			$update          = new WC_Product_Booking_Data_Store_CPT;
			foreach ( $this->booking_meta_key_to_props as $key => $prop ) {
				if ( isset( $_REQUEST[ $key ] ) ) {
					update_post_meta( $product->get_id(), $key, $_REQUEST[ $key ] );
				}
			}

			// default woocommerce meta boxes
			$title          = isset( $_REQUEST['_wc_booking_custom_title'] ) ? $_REQUEST['_wc_booking_custom_title'] : '';
			$apply_buffer   = isset( $_REQUEST['_wc_booking_apply_adjacent_buffer'] ) ? 'yes' : '';
			$has_restricted = isset( $_REQUEST['_wc_booking_has_restricted_days'] ) ? 'yes' : '';
			$enable_range   = isset( $_REQUEST['_wc_booking_enable_range_picker'] ) ? 'yes' : '';
			$virtual        = isset( $_REQUEST['_virtual'] ) ? 'yes' : 'no';
			$disabled       = isset( $_REQUEST['_is_disabled'] ) ? 'yes' : 'no';
			$has_persons    = isset( $_REQUEST['_wc_booking_has_persons'] ) ? true : false;
			$has_resources  = isset( $_REQUEST['_wc_booking_has_resources'] ) ? true : false;
			$confirmation   = isset( $_REQUEST['_wc_booking_requires_confirmation'] ) ? 'yes' : 'no';
			$cancel         = isset( $_REQUEST['_wc_booking_user_can_cancel'] ) ? 'yes' : 'no';
			$resource_label = isset( $_REQUEST['_wc_booking_resource_label'] ) ? $_REQUEST['_wc_booking_resource_label'] : '';

			update_post_meta( $product->get_id(), '_wc_booking_custom_title', $title );
			update_post_meta( $product->get_id(), '_is_disabled', $disabled );
			update_post_meta( $product->get_id(), '_virtual', $virtual );
			update_post_meta( $product->get_id(), '_wc_booking_requires_confirmation', $confirmation );
			update_post_meta( $product->get_id(), '_wc_booking_user_can_cancel', $cancel );
			update_post_meta( $product->get_id(), '_wc_booking_has_persons', $has_persons );
			update_post_meta( $product->get_id(), '_wc_booking_has_resources', $has_resources );
			update_post_meta( $product->get_id(), '_wc_booking_enable_range_picker', $enable_range );
			update_post_meta( $product->get_id(), '_wc_booking_has_restricted_days', $has_restricted );
			update_post_meta( $product->get_id(), '_wc_booking_apply_adjacent_buffer', $apply_buffer );
			update_post_meta( $product->get_id(), '_wc_booking_resource_label', $resource_label );

			for ( $i = 0; $i < 7; $i ++ ) {
				$day = isset( $_REQUEST["_wc_booking_restricted_days[{$i}]"] ) ? 'yes' : '';
				update_post_meta( $product->get_id(), "_wc_booking_restricted_days[{$i}]", $day );
			}

			$loop = 0;
			if ( isset( $_REQUEST['person_id'] ) && ! empty( $_REQUEST['person_id'] ) ) {
				foreach ( $_REQUEST['person_id'] as $id ) {
					$person_object = new WC_Product_Booking_Person_Type();
					$person_object->set_parent_id( $product->get_id() );
					wp_update_post( array(
						'ID'           => $id,
						'post_parent'  => $product->get_id(),
						'post_title'   => $_REQUEST['person_name'][ $loop ],
						'post_excerpt' => $_REQUEST['person_description'][ $loop ]
					) );
					update_post_meta( $id, 'cost', $_REQUEST['person_cost'][ $loop ] );
					update_post_meta( $id, 'block_cost', $_REQUEST['person_block_cost'][ $loop ] );
					update_post_meta( $id, 'min', $_REQUEST['person_min'][ $loop ] );
					update_post_meta( $id, 'max', $_REQUEST['person_max'][ $loop ] );
					$loop ++;
				}
			}

			$res_loop = 0;
			// get resource values
			$base_costs  = maybe_unserialize( get_post_meta( $product->get_id(), '_resource_base_costs' ) );
			$block_costs = maybe_unserialize( get_post_meta( $product->get_id(), '_resource_block_costs' ) );
			$base_costs  = isset( $base_costs ) ? array_shift( $base_costs ) : '';
			$block_costs = isset( $block_costs ) ? array_shift( $block_costs ) : '';
			if ( isset( $_REQUEST['resource_id'] ) && ! empty( $_REQUEST['resource_id'] ) ) {
				foreach ( $_REQUEST['resource_id'] as $id ) {
					// change resource values
					$base_costs[ $id ]  = $_REQUEST['resource_cost'][ $res_loop ];
					$block_costs[ $id ] = $_REQUEST['resource_block_cost'][ $res_loop ];

					$res_loop ++;
				}
			}
			// update resource post meta
			update_post_meta( $product->get_id(), '_resource_base_costs', $base_costs );
			update_post_meta( $product->get_id(), '_resource_block_costs', $block_costs );

			// update ranges
			$this->update_ranges( $product, 'pricing' );
			$this->update_ranges( $product, 'availability' );

			$result['product_id'] = $product->get_id();
			$result['success']    = esc_html__( 'Product Updated', 'pebas-bookings-extension' );

		} else {
			$result['error'] = esc_html__( 'Something has not being set up properly. Please contact site administrator.', 'pebas-bookings-extension' );
		}
		wp_send_json( $result );
	}

	/**
	 * Update booking ranges
	 * ----------------------
	 *
	 * @param $product
	 * @param $name
	 */
	public function update_ranges( $product, $name ) {
		$loop = 0;

		$meta = maybe_unserialize( get_post_meta( $product->get_id(), '_wc_booking_pricing' ) );
		$meta = isset( $meta ) ? array_shift( $meta ) : '';
		if ( isset( $_REQUEST["wc_booking_pricing_type"] ) && ! empty( $_REQUEST['wc_booking_pricing_type'] ) ) {
			foreach ( $_REQUEST["wc_booking_pricing_type"] as $type ) {
				$meta[ $loop ]['type'] = $_REQUEST['wc_booking_pricing_type'][ $loop ];

				switch ( $type ) {
					case 'custom':
						$meta[ $loop ]['from'] = wc_clean( $_POST["wc_booking_{$name}_from_date"][ $loop ] );
						$meta[ $loop ]['to']   = wc_clean( $_POST["wc_booking_{$name}_to_date"][ $loop ] );
						break;
					case 'months':
						$meta[ $loop ]['from'] = wc_clean( $_POST["wc_booking_{$name}_from_month"][ $loop ] );
						$meta[ $loop ]['to']   = wc_clean( $_POST["wc_booking_{$name}_to_month"][ $loop ] );
						break;
					case 'weeks':
						$meta[ $loop ]['from'] = wc_clean( $_POST["wc_booking_{$name}_from_week"][ $loop ] );
						$meta[ $loop ]['to']   = wc_clean( $_POST["wc_booking_{$name}_to_week"][ $loop ] );
						break;
					case 'days':
						$meta[ $loop ]['from'] = wc_clean( $_POST["wc_booking_{$name}_from_day_of_week"][ $loop ] );
						$meta[ $loop ]['to']   = wc_clean( $_POST["wc_booking_{$name}_to_day_of_week"][ $loop ] );
						break;
					case 'time':
					case 'time:1':
					case 'time:2':
					case 'time:3':
					case 'time:4':
					case 'time:5':
					case 'time:6':
					case 'time:7':
						$meta[ $loop ]['from'] = wc_booking_sanitize_time( $_POST["wc_booking_{$name}_from_time"][ $loop ] );
						$meta[ $loop ]['to']   = wc_booking_sanitize_time( $_POST["wc_booking_{$name}_to_time"][ $loop ] );
						break;
					case 'time:range':
						$meta[ $loop ]['from'] = wc_booking_sanitize_time( $_POST["wc_booking_{$name}_from_time"][ $loop ] );
						$meta[ $loop ]['to']   = wc_booking_sanitize_time( $_POST["wc_booking_{$name}_to_time"][ $loop ] );

						$meta[ $loop ]['from_date'] = wc_clean( $_POST["wc_booking_{$name}_from_date"][ $loop ] );
						$meta[ $loop ]['to_date']   = wc_clean( $_POST["wc_booking_{$name}_to_date"][ $loop ] );
						break;
					default:
						$meta[ $loop ]['from'] = wc_clean( $_POST["wc_booking_{$name}_from"][ $loop ] );
						$meta[ $loop ]['to']   = wc_clean( $_POST["wc_booking_{$name}_to"][ $loop ] );
						break;
				}

				// change resource values
				if ( 'pricing' == $name ) {
					$meta[ $loop ]['cost']          = $_REQUEST["wc_booking_{$name}_cost"][ $loop ];
					$meta[ $loop ]['modifier']      = $_REQUEST["wc_booking_{$name}_modifier"][ $loop ];
					$meta[ $loop ]['base_cost']     = $_REQUEST["wc_booking_{$name}_base_cost"][ $loop ];
					$meta[ $loop ]['base_modifier'] = $_REQUEST["wc_booking_{$name}_base_modifier"][ $loop ];
				} else {
					$meta[ $loop ]['bookable'] = wc_clean( $_POST['wc_booking_availability_bookable'][ $loop ] );
					$meta[ $loop ]['priority'] = intval( $_POST['wc_booking_availability_priority'][ $loop ] );
				}

				$loop ++;
			}
		}
		update_post_meta( $product->get_id(), "_wc_booking_{$name}", $meta );
	}

	/**
	 * Confirm booking availability
	 */
	public function confirm_booking() {
		if ( ! empty( $_POST['action'] ) && 'confirm_booking' == $_POST['action'] && wp_verify_nonce( $_REQUEST['nonce'], 'confirm_booking' ) ) {
			$booking_id = isset( $_REQUEST['id'] ) ? $_REQUEST['id'] : false;
			if ( ! empty( $booking_id ) ) {
				$booking = get_wc_booking( $booking_id );

				if ( 'confirmed' !== $booking->get_status() ) {
					$booking->update_status( 'confirmed' );
				}

				ob_start();
				$this->booking_orders();
				$result['html']          = ob_get_clean();
				$result['pending_count'] = $this->count_bookings_with_status();
				$result['success']       = esc_html__( 'Booking appointment has been confirmed!', 'pebas-bookings-extension' );

			} else {
				$result['error'] = esc_html__( 'Something has not being set up properly. Please contact site administrator.', 'pebas-bookings-extension' );
			}

		} else {
			$result['error'] = esc_html__( 'Something has not being set up properly. Please contact site administrator.', 'pebas-bookings-extension' );
		}
		wp_send_json( $result );
	}

	/*
	 * Get all bookings with statuses for the current user
	 * ---------------------------------
	 *
	 */
	public function get_product_bookings( $statuses = array( 'pending-confirmation' ) ) {
		$products = $this->get_user_products();
		// bookings
		$bookings = array();
		if ( $bookings ) {
			foreach ( $products as $product_id ) {
				$bookings[] = WC_Bookings_Controller::get_bookings_for_product( $product_id, $statuses );
			}
		}

		return $bookings;
	}

	/**
	 * Get active bookings ids with defined status
	 * --------------------------------
	 *
	 * @param array $statuses
	 *
	 * @return array
	 */
	public function get_bookings_ids_with_status( $statuses = array( 'pending-confirmation' ) ) {
		$bookings    = $this->get_product_bookings( $statuses );
		$now         = strtotime( date( 'Y-m-d H:i' ) );
		$booking_ids = array();
		foreach ( $bookings as $booking_object ) {
			foreach ( $booking_object as $booking ) {
				$end = strtotime( $booking->get_end_date() );
				if ( in_array( $booking->get_status(), $statuses ) && $now < $end ) {
					$booking_ids[] = $booking->get_id();
				}
			}
		}

		if ( empty( $booking_ids ) ) {
			return array();
		}

		return $booking_ids;
	}

	/**
	 * Count array of booking ids with defined statuses
	 * ----------------------------------------
	 *
	 * @param array $statuses
	 *
	 * @return int
	 */
	public function count_bookings_with_status( $statuses = array( 'pending-confirmation' ) ) {
		$booking_ids = $this->get_bookings_ids_with_status( $statuses );

		if ( empty( $booking_ids ) ) {
			return 0;
		}

		return count( $booking_ids );
	}

	/**
	 * Get status of the payout
	 * -----------------------------
	 *
	 * @param $booking_id
	 *
	 * @return mixed
	 */
	public function get_payout_status( $booking_id ) {
		$payouts = get_posts( array(
			'post_type'  => 'pebas_payout',
			'meta_query' => array(
				array(
					'key'   => '_payout_booking',
					'value' => $booking_id
				)
			)
		) );

		return array_shift( $payouts );
	}

	/**
	 * Calculate booking payout payments
	 * --------------------------------
	 *
	 * @param array $booking_status
	 * @param string $payment_status
	 *
	 * @return int|mixed
	 */
	public function get_booking_payout_payments( $booking_status = array( 'paid' ), $payment_status = 'unpaid' ) {
		$bookings = $this->get_product_bookings( $booking_status );
		$amount   = 0;

		foreach ( $bookings as $booking_object ) {
			foreach ( $booking_object as $booking ) {
				$payout        = pebas_booking()->get_payout_status( $booking->get_id() );
				$payout_status = get_post_meta( $payout->ID, '_payout_status', true );
				$payout_amount = get_post_meta( $payout->ID, '_payout_amount', true );

				if ( $payout_status == $payment_status ) {
					$amount += $payout_amount;
				}
			}
		}

		return $amount;

	}

}

/**
 * Instantiate the class
 *
 * @return null|pebas_booking
 */
function pebas_booking() {
	return pebas_booking::instance();
}
