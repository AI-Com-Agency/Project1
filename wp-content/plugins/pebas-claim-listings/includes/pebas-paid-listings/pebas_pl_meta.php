<?php
/**
 * Class pebas_pl_meta
 *
 * @author pebas
 * @ver 1.0.0
 */

/**
 * Class pebas_pl_meta
 */
class pebas_pl_meta {

	protected static $_instance = null;

	/**
	 * @return null|pebas_pl_meta
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

		// Listing Claims Meta Fields / Status
		$status = array(
			array(
				'id'       => '_claim_paid_order',
				'type'     => 'custom_html',
				'callback' => 'pebas_paid_claim_information_meta',
			),
		);

		$meta_boxes[] = array(
			'title'    => esc_html__( 'Listing Paid Claim Information', 'pebas-claim-listings' ),
			'pages'    => pebas_claim_install()->pebas_claim_type_name,
			'fields'   => $status,
			'context'  => 'side',
			'priority' => 'low'
		);

		return $meta_boxes;
	}

}

function pebas_paid_claim_information_meta() {
	if ( ! $post_id = filter_input( INPUT_GET, 'listing_id', FILTER_SANITIZE_NUMBER_INT ) ) {
		return '';
	}

	$text     = __( 'No Order Found', 'pebas-claim-listings' );
	$desc     = '';
	$order_id = intval( get_post_meta( $post_id, '_order_id', true ) );
	if ( $order_id ) {

		/* Add order ID in text */
		$text = "#{$order_id}";

		/* Get order object */
		$order_obj = wc_get_order( $order_id );
		if ( $order_obj ) {

			/* Add Edit Link */
			if ( $edit_link = get_edit_post_link( $order_id ) ) {
				$text = '<a target="_blank" href="' . esc_url( $edit_link ) . '">' . $text . '</a>';
			}

			/* Add Order Status */
			$status = $order_obj->get_status();
			$text   .= " &ndash; <em>{$status}</em>";
		} else {
			$text .= ' &ndash; ' . __( 'Cannot retrieve order.', 'pebas-claim-listings' );
		}
	}

	return $text;
}

/** Instantiate class
 *
 * @return null|pebas_pl_meta
 */
function pebas_pl_meta() {
	return pebas_pl_meta::instance();
}
