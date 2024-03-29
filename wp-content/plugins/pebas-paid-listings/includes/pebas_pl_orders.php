<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Orders
 */
class peabs_pl_orders {

	/** @var object Class Instance */
	private static $instance;

	/**
	 * Get the class instance
	 *
	 * @return static
	 */
	public static function get_instance() {
		return null === self::$instance ? ( self::$instance = new self ) : self::$instance;
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'woocommerce_thankyou', array( $this, 'woocommerce_thankyou' ), 5 );

		// Displaying user packages on the frontend
		add_action( 'lisner_user_packages', array( $this, 'my_packages' ) );

		// Statuses
		add_action( 'woocommerce_order_status_processing', array( $this, 'order_paid' ) );
		add_action( 'woocommerce_order_status_completed', array( $this, 'order_paid' ) );
		add_action( 'woocommerce_order_status_cancelled', array( $this, 'package_cancelled' ) );

		add_action( 'wp_trash_post', array( $this, 'wp_trash_post' ) );
		add_action( 'untrash_post', array( $this, 'untrash_post' ) );

		// User deletion
		add_action( 'delete_user', array( $this, 'delete_user_packages' ) );
	}

	/**
	 * Thanks page
	 *
	 * @param mixed $order_id
	 */
	public function woocommerce_thankyou( $order_id ) {
		global $wp_post_types;

		$order = wc_get_order( $order_id );

		foreach ( $order->get_items() as $item ) {
			if ( isset( $item['job_id'] ) && 'publish' === get_post_status( $item['job_id'] ) ) {
				switch ( get_post_status( $item['job_id'] ) ) {
					case 'pending' :
						echo wpautop( sprintf( __( '%s has been submitted successfully and will be visible once approved.', 'pebas-paid-listings' ), get_the_title( $item['job_id'] ) ) );
						break;
					case 'pending_payment' :
					case 'expired' :
						echo wpautop( sprintf( __( '%s has been submitted successfully and will be visible once payment has been confirmed.', 'pebas-paid-listings' ), get_the_title( $item['job_id'] ) ) );
						break;
					default :
						echo wpautop( sprintf( __( '%s has been submitted successfully.', 'pebas-paid-listings' ), get_the_title( $item['job_id'] ) ) );
						break;
				}

				echo '<p class="submitted-listing-order-action">';

				if ( 'publish' === get_post_status( $item['job_id'] ) ) {
					echo '<a class="button" href="' . get_permalink( $item['job_id'] ) . '">' . __( 'View Listing', 'pebas-paid-listings' ) . '</a> ';
				} elseif ( get_option( 'job_manager_job_dashboard_page_id' ) ) {
					echo '<a class="button" href="' . get_permalink( get_option( 'job_manager_job_dashboard_page_id' ) ) . '">' . __( 'View Dashboard', 'pebas-paid-listings' ) . '</a> ';
				}

				echo '</p>';

			}
		}// End foreach().
	}

	/**
	 * Show my packages
	 */
	public function my_packages() {
		if ( ( $packages = pebas_pl_get_user_packages( get_current_user_id(), 'job_listing' ) ) && is_array( $packages ) && sizeof( $packages ) > 0 ) {
			wc_get_template( 'my-packages.php', array(
				'packages' => $packages,
				'type'     => 'job_listing',
				'template' => 'listing_packages'
			), '', PEBAS_PL_DIR . '/templates/' );
		} else {
			wc_get_template( 'no-packages.php', array(), '', PEBAS_PL_DIR . '/templates/' );
		}
	}

	/**
	 * Triggered when an order is paid
	 *
	 * @param  int $order_id
	 */
	public function order_paid( $order_id ) {
		// Get the order
		$order = wc_get_order( $order_id );

		if ( get_post_meta( $order_id, 'wc_paid_listings_packages_processed', true ) ) {
			return;
		}
		foreach ( $order->get_items() as $item ) {
			$product = wc_get_product( $item['product_id'] );

			if ( $product->is_type( array( 'job_package' ) ) && pebas_pl_get_order_customer_id( $order ) ) {

				// Give packages to user
				$user_package_id = false;
				for ( $i = 0; $i < $item['qty']; $i ++ ) {
					$user_package_id = pebas_pl_give_user_package( pebas_pl_get_order_customer_id( $order ), $product->get_id(), $order_id );
				}

				$this->attach_package_listings( $item, $order, $user_package_id );
			}
		}

		update_post_meta( $order_id, 'pebas_paid_listings_packages_processed', true );
	}

	/**
	 * Delete packages on user deletion
	 *
	 * @param mixed $user_id
	 */
	public function delete_user_packages( $user_id ) {
		global $wpdb;
		$table = pebas_paid_listings_install::$pebas_paid_listings_table;

		if ( $user_id ) {
			$wpdb->delete(
				"{$wpdb->prefix}{$table}",
				array(
					'user_id' => $user_id,
				)
			);
		}
	}

	/**
	 * Handles the tasks after the restoration of orders and job listing or resume posts.
	 *
	 * @param int $post_id
	 */
	public function untrash_post( $post_id ) {
		$post_type = get_post_type( $post_id );

		switch ( $post_type ) {
			case 'shop_order':
				$this->untrash_shop_order( $post_id );
				break;
			case 'job_listing':
				break;
		}
	}

	/**
	 * Handles the tasks after a job listing or resume post is restored.
	 *
	 * @param int $post_id
	 */
	public function untrash_wpjm_post( $post_id ) {
		$product_id      = get_post_meta( $post_id, '_package_id', true );
		$user_package_id = get_post_meta( $post_id, '_user_package_id', true );
		$product         = wc_get_product( $product_id );
		$user_package    = pebas_pl_get_user_package( $user_package_id );
		$order           = false;
		if ( $user_package && $user_package->has_package() ) {
			$order = wc_get_order( $user_package->get_order_id() );
		}
		if ( $order && $product && $product->is_type( array( 'job_package' ) ) ) {
			/** This filter is documented in includes/package-functions.php */
			if ( apply_filters( 'job_manager_job_listing_affects_package_count', true, $post_id ) ) {
				pebas_pl_increase_package_count( $order->get_user_id(), $user_package_id );
			}
		}
	}

	/**
	 * Handles tasks after a WC order is restored.
	 *
	 * @param int $order_id
	 */
	public function untrash_shop_order( $order_id ) {
		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			return;
		}
		foreach ( $order->get_items() as $item ) {
			/**
			 * @var WC_Order_Item_Product $item
			 */
			$product = $item->get_product();
			if ( $product->is_type( array( 'job_package' ) ) && $order->get_user_id() ) {
				$user_package_id = null;
				// Give packages to user
				for ( $i = 0; $i < $item['qty']; $i ++ ) {
					$user_package_id = pebas_pl_give_user_package( $order->get_user_id(), $product->get_id(), pebas_pl_get_order_id( $order ) );
				}
				$this->attach_package_listings( $item, $order, $user_package_id );
			}
		}
	}

	/**
	 * Attached listings to the user package.
	 *
	 * @param array $item
	 * @param WC_Order $order
	 * @param int $user_package_id
	 */
	private function attach_package_listings( $item, $order, $user_package_id ) {
		global $wpdb;
		$listing_ids   = (array) $wpdb->get_col( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key=%s AND meta_value=%s", '_cancelled_package_order_id', pebas_pl_get_order_id( $order ) ) );
		$listing_ids[] = isset( $item['job_id'] ) ? $item['job_id'] : '';
		$listing_ids   = array_unique( array_filter( array_map( 'absint', $listing_ids ) ) );
		foreach ( $listing_ids as $listing_id ) {
			if ( in_array( get_post_status( $listing_id ), array( 'pending_payment', 'expired' ) ) ) {
				pebas_pl_approve_listing_with_package( $listing_id, $order->get_user_id(), $user_package_id );
				delete_post_meta( $listing_id, '_cancelled_package_order_id' );
			}
		}
	}

	/**
	 * Handles the tasks after WC orders and WPJM related posts are trashed.
	 *
	 * @param int $post_id
	 */
	public function wp_trash_post( $post_id ) {
		$post_type = get_post_type( $post_id );

		switch ( $post_type ) {
			case 'shop_order':
				$this->trash_shop_order( $post_id );
				break;
			case 'job_listing':
				break;
		}
	}

	/**
	 * Handles tasks after a job listing or resume post is trashed.
	 *
	 * @param int $post_id
	 */
	public function trash_wpjm_post( $post_id ) {
		$product_id      = get_post_meta( $post_id, '_package_id', true );
		$user_package_id = get_post_meta( $post_id, '_user_package_id', true );
		$product         = wc_get_product( $product_id );
		$user_package    = pebas_pl_get_user_package( $user_package_id );
		$order           = false;
		if ( $user_package && $user_package->has_package() ) {
			$order = wc_get_order( $user_package->get_order_id() );
		}
		if ( $order && $product && $product->is_type( array( 'job_package' ) ) ) {
			/** This filter is documented in includes/package-functions.php */
			if ( apply_filters( 'job_manager_job_listing_affects_package_count', true, $post_id ) ) {
				pebas_pl_decrease_package_count( $order->get_user_id(), $user_package_id );
			}
		}
	}

	/**
	 * If a listing gets trashed/deleted, the pack may need it's listing count changing
	 *
	 * @param int $order_id
	 */
	public function trash_shop_order( $order_id ) {
		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			return;
		}
		foreach ( $order->get_items() as $item ) {
			$product = $item->get_product();
			if ( ! $product->is_type( array( 'job_package' ) ) && $order->get_user_id() ) {
				continue;
			}
			$this->delete_package( $order_id, $product->get_id() );
		}
	}

	/**
	 * Fires when a order was canceled. Looks for Job Packages in order and deletes the package if found.
	 *
	 * @param $order_id
	 */
	public function package_cancelled( $order_id ) {
		$order = wc_get_order( $order_id );
		foreach ( $order->get_items() as $item ) {
			$product = $item->get_product();
			if ( ! $product->is_type( array( 'job_package' ) ) ) {
				continue;
			}
			$this->delete_package( $order_id, $product->get_id() );
		}
	}

	/**
	 * Deletes a package.
	 *
	 * @param int $order_id
	 * @param int $product_id
	 */
	private function delete_package( $order_id, $product_id ) {
		global $wpdb;
		$table = pebas_paid_listings_install::$pebas_paid_listings_table;

		$user_package = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}{$table} WHERE order_id = %d AND product_id = %d;", $order_id, $product_id ) );
		if ( $user_package ) {
			// Delete the package
			$wpdb->delete(
				"{$wpdb->prefix}{$table}",
				array(
					'id' => $user_package->id,
				)
			);

			$listing_ids = pebas_pl_get_listings_for_package( $user_package->id );
			foreach ( $listing_ids as $listing_id ) {
				$original_status = get_post_status( $listing_id );
				$listing         = array(
					'ID'          => $listing_id,
					'post_status' => 'expired',
				);
				wp_update_post( $listing );

				// Make a record of the order ID and original status in case of re-activation
				update_post_meta( $listing_id, '_cancelled_package_order_id', $order_id );
				update_post_meta( $listing_id, '_post_status_before_package_pause', $original_status );
			}
		}
	}
}

peabs_pl_orders::get_instance();
