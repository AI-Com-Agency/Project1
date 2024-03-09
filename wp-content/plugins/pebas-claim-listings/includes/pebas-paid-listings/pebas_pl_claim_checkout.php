<?php
/**
 * Class pebas_pl_claim_checkout
 *
 * @author pebas
 * @ver 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * pebas_pl_claim_checkout
 */
class pebas_pl_claim_checkout {

	protected static $_instance = null;


	/**
	 * @return null|pebas_pl_claim_checkout
	 */
	public static function instance() {
		return null === self::$_instance ? ( self::$_instance = new self ) : self::$_instance;
	}

	/**
	 * pebas_pl_claim_checkout constructor.
	 */
	public function __construct() {
		/* Get data from session on page load */
		add_filter( 'woocommerce_get_cart_item_from_session', array( $this, 'get_cart_item_from_session' ), 11, 2 );

		/* Add order item meta */
		add_action( 'woocommerce_new_order_item', array( $this, 'order_item_meta' ), 11, 3 );

		/* Display item meta */
		add_filter( 'woocommerce_get_item_data', array( $this, 'get_item_data' ), 11, 2 );
	}

	public function get_cart_item_from_session( $cart_item, $values ) {
		if ( ! empty( $values['claim_id'] ) ) {
			$cart_item['claim_id'] = $values['claim_id'];
		}
		return $cart_item;
	}

	public function order_item_meta( $item_id, $item, $order_id ) {
		if ( isset( $item->legacy_values['claim_id'] ) ) {
			$claim_obj = get_post( absint( $item->legacy_values['claim_id'] ) );
			$claimer_obj = get_userdata( $claim_obj->post_author );

			wc_add_order_item_meta( $item_id, __( 'Claim By', 'pebas-claim-listings' ), $claimer_obj->data->display_name . " ({$claimer_obj->data->user_login})" );
			wc_add_order_item_meta( $item_id, '_claim_id', $item->legacy_values['claim_id'] );
			wc_add_order_item_meta( $item_id, '_claimer_id', $claimer_obj->ID );
		}
	}

	public function get_item_data( $data, $cart_item ) {
		if ( isset( $cart_item['claim_id'] ) ) {
			$claim_obj = get_post( absint( $cart_item['claim_id'] ) );
			$claimer_obj = get_userdata( $claim_obj ? $claim_obj->post_author : false );

			$value = $claimer_obj ? $claimer_obj->data->display_name : '';
			if ( ! $value && is_user_logged_in() ) {
				$user = wp_get_current_user();
				$value = $user->display_name;
			}

			$data[] = array(
				'name'   => __( 'Claim By', 'pebas-claim-listings' ),
				'value'  => $value ? $value : __( 'Guest', 'pebas-claim-listings' ),
			);
		}
		return $data;
	}

}

/**
 * Instantiate the class
 *
 * @return null|pebas_pl_claim_checkout
 */
function pebas_pl_claim_checkout() {
	return pebas_pl_claim_checkout::instance();
}
