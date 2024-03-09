<?php
/**
 * Class pebas_listing_meta
 *
 * @author pebas
 * @ver 1.0.0
 */

/**
 * Class pebas_listing_meta
 */
class pebas_listing_meta {

	protected static $_instance = null;

	/**
	 * @return null|pebas_listing_meta
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
		$claims = array(
			array(
				'id'   => '_claimed',
				'name' => esc_html__( 'Listing Claim Status', 'pebas-claim-listings' ),
				'type' => 'checkbox',
				'desc' => esc_html__( 'Choose whether the listing has been claimed by its owner', 'pebas-claim-listings' ),
				'std'  => 0,
			),
			array(
				'id'       => '_claim_history',
				'name'     => esc_html__( 'Listing Claim History', 'pebas-claim-listings' ),
				'type'     => 'custom_html',
				'callback' => 'pebas_listing_claim_history_meta',
			),
		);

		$meta_boxes[] = array(
			'title'   => esc_html__( 'Listing Claims', 'pebas-claim-listings' ),
			'pages'   => 'job_listing',
			'fields'  => $claims,
			'context' => 'side',
		);

		return $meta_boxes;
	}

}

function pebas_listing_claim_history_meta() {
	if ( ! $page_id = filter_input( INPUT_GET, 'post', FILTER_SANITIZE_NUMBER_INT ) ) {
		return '';
	}
	if ( ! empty( $page_id ) ) {
		$claims = get_posts( array(
			'post_type'      => pebas_claim_install()->pebas_claim_type_name,
			'posts_per_page' => - 1,
			'meta_key'       => '_listing_id',
			'meta_value'     => $page_id,
		) );
		if ( $claims ) {
			foreach ( $claims as $claim ) {
				$claimer_obj = get_userdata( $claim->post_author );
				$text        = "#{$claim->ID}:  " . pebas_claim()->get_claim_status_label( $claim->ID );
				if ( $edit_link = get_edit_post_link( $claim->ID ) ) {
					$text = '<a target="_blank" href="' . esc_url( $edit_link ) . '">' . $text . '</a>';
				}
				if ( $claimer_obj ) {
					$text .= '<br />' . sprintf( __( 'by %s', 'pebas-claim-listings' ), $claimer_obj->data->display_name . " ({$claimer_obj->data->user_login})" );
				} else {
					$text .= '<br />' . __( 'by Guest', 'pebas-claim-listings' );
				}

				return "<p>{$text}</p>";
			}
		} else {
			return wpautop( __( 'No Claims Found', 'pebas-claim-listings' ) );
		}
	}
}

/** Instantiate class
 *
 * @return null|pebas_listing_meta
 */
function pebas_listing_meta() {
	return pebas_listing_meta::instance();
}
