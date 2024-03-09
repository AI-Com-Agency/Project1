<?php
/**
 * Class pebas_pl_claim_order
 *
 * @author pebas
 * @ver 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * pebas_pl_claim_order
 */
class pebas_pl_claim_order {

	protected static $_instance = null;


	/**
	 * @return null|pebas_pl_claim_order
	 */
	public static function instance() {
		return null === self::$_instance ? ( self::$_instance = new self ) : self::$_instance;
	}

	/**
	 * pebas_pl_claim_order constructor.
	 */
	public function __construct() {
		/* Order Created On Checkout */
		add_action( 'woocommerce_checkout_order_processed', array( $this, 'order_created' ) );

		/* Two hook, but only process this once. Which ever first. */
		add_action( 'woocommerce_order_status_processing', array( $this, 'order_paid' ), 11 );
		add_action( 'woocommerce_order_status_completed', array( $this, 'order_paid' ), 11 );

		// Use "default to claim" user package.
		add_action( 'pebas_process_package', array(
			$this,
			'default_to_claim_for_user_package'
		), 10, 3 );

	}

	public function order_created( $order_id ) {
		$order = wc_get_order( $order_id );

		// Order WP_User
		$order_user = $order->get_user();

		/* Loop each item, and process. */
		foreach ( $order->get_items() as $item_id => $item ) {
			$product_id = $item['product_id'];
			$product    = wc_get_product( $product_id );

			if ( $product->is_type( array(
					'job_package',
					'job_package_subscription'
				) ) && $order->get_user_id() && isset( $item['claim_id'] ) ) {

				$claim_id = $item['claim_id'];

				if ( $order_user ) {

					/* Set claim author */
					$claim_args = array(
						'ID'          => $claim_id,
						'post_author' => $order_user->ID,
					);
					$claim_id   = wp_update_post( $claim_args );

					/* Update Order Meta */
					wc_update_order_item_meta( $item_id, 'Claim By', $order_user->display_name );
					wc_update_order_item_meta( $item_id, '_claimer_id', $order_user->ID );
				}

				/* Add order and product info in claim */
				add_post_meta( $claim_id, '_order_id', $order_id );
				add_post_meta( $claim_id, '_package_id', $product_id );

				/* Update claim status and send notification. */
				$old_status = get_post_meta( $claim_id, '_status', true );
				$update     = update_post_meta( $claim_id, '_status', 'pending_order' );
				if ( $update ) {
					do_action( 'pebas_claim_listings_claim_status_updated', $claim_id, $old_status, array(
						'_order_id'   => $order_id,
						'_package_id' => $product_id,
						'context'     => 'order_created',
					) );
				}
			}
		}
	}

	public function order_paid( $order_id ) {
		// Get the order obj
		$order = wc_get_order( $order_id );

		/* Only do it once, if not processing/completed. */
		if ( get_post_meta( $order_id, 'pebas_claim_listings_claim_packages_processed', true ) ) {
			return;
		}

		/* Loop each item, and process. */
		foreach ( $order->get_items() as $item ) {

			// Get product.
			$product = wc_get_product( $item['product_id'] );

			// Only for job package and job package subscription.
			if ( $product && $product->is_type( array(
					'job_package',
					'job_package_subscription'
				) ) && $order->get_user_id() ) {

				// Claiming a listing package.
				if ( 'yes' === get_post_meta( $product->get_id(), '_use_for_claims', true ) && isset( $item['claim_id'] ) && $item['claim_id'] ) {

					// Update claim status and send notification.
					$old_status = get_post_meta( $item['claim_id'], '_status', true );
					$new_status = 'completed' === $order->get_status() ? 'approved' : 'order_completed';
					$update     = update_post_meta( $item['claim_id'], '_status', $new_status );
					if ( $update ) {
						do_action( 'pebas_claim_listings_claim_status_updated', $item['claim_id'], $old_status, array(
							'_send_notification' => array( 'admin' ),
							'context'            => 'order_paid',
						) );
					}

					// Look for the subscription ID for user packages if exists
					if ( class_exists( 'WC_Subscriptions' ) ) {
						if ( wcs_order_contains_subscription( $order ) ) {
							$subs = wcs_get_subscriptions_for_order( $order_id );
							if ( ! empty( $subs ) ) {
								$sub      = current( $subs );
								$order_id = $sub->id;
							}
						}
					}

					// Get user package (created by payment plugin).
					global $wpdb;
					$table        = pebas_paid_listings_install::$pebas_paid_listings_table;
					$user_package = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}{$table} WHERE user_id = %d AND order_id = %d AND ( package_count < package_limit OR package_limit = 0 );", $order->get_user_id(), $order_id ) );

					if ( $user_package ) {

						// Increase package count to 1/1.
						pebas_pl_increase_package_count( $order->get_user_id(), $user_package->id );

						if ( isset( $item['listing_id'] ) ) {
							$job = get_post( $item['listing_id'] );
							if ( $job ) {

								update_post_meta( $job->ID, '_user_package_id', $user_package->id );

								if ( $product->is_type( 'job_package_subscription' ) ) {
									do_action( 'pebas_paid_listings_switched_subscription', $job->ID, $user_package );
								}
							}
						}

					}

				} elseif ( 'yes' === get_post_meta( $product->get_id(), '_default_to_claimed', true ) && isset( $item['listing_id'] ) && $item['listing_id'] ) { // Initial purchase of package with default to claim.

					$job = get_post( $item['listing_id'] );
					if ( $job ) {
						// All needed is to set the listing to claimed, everything else is done by payment plugin.
						$claim_id = pebas_claim()->create_new_claim( $job->ID, $order->get_user_id(), __( 'Automatically verified with initial purchase.', 'pebas-claim-listings' ), false );
						update_post_meta( $claim_id, '_order_id', $order_id );
						update_post_meta( $claim_id, '_status', 'approved' );
						update_post_meta( $job->ID, '_claimed', 1 );
					}
				}
			}

		}
		update_post_meta( $order_id, 'pebas_claim_listings_claim_packages_processed', true );
	}

	function default_to_claim_for_user_package( $package_id, $is_user_package, $job_id ) {
		// Only for user package.
		if ( ! $is_user_package ) {
			return;
		}

		// Get user package, product and order var.
		$user_package = pebas_pl_get_user_package( $package_id );
		$product      = wc_get_product( $user_package->get_product_id() );
		$order_id     = $user_package->get_order_id();
		if ( ! $product ) {
			return;
		}

		$job = get_post( $job_id );

		// Default to claim?
		$default_to_claim = 'yes' === get_post_meta( $product->get_id(), '_default_to_claimed', true ) ? true : false;

		// If default to claim, create new claim, and auto claimed it.
		if ( $default_to_claim ) {

			$claim_id = pebas_claim()->create_new_claim( $job_id, $job->post_author, __( 'Automatically verified with initial purchase.', 'pebas-claim-listings' ), false );
			if ( $order_id ) {
				update_post_meta( $claim_id, '_order_id', $order_id );
			}
			update_post_meta( $claim_id, '_status', 'approved' );
			update_post_meta( $job_id, '_claimed', 1 );
		}
	}

}

/**
 * Instantiate the class
 *
 * @return null|pebas_pl_claim_order
 */
function pebas_pl_claim_order() {
	return pebas_pl_claim_order::instance();
}
