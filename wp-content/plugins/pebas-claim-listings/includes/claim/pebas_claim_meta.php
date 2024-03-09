<?php
/**
 * Class pebas_claim_meta
 *
 * @author pebas
 * @ver 1.0.0
 */

/**
 * Class pebas_claim_meta
 */
class pebas_claim_meta {

	protected static $_instance = null;

	/**
	 * @return null|pebas_claim_meta
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

		// add custom meta boxes
		add_filter( 'rwmb_meta_boxes', array( $this, 'meta_boxes' ), 11 );

		// update listing claim status on field update
		add_action( 'rwmb__status_after_save_field', array( $this, 'update_claim_status' ), 10, 5 );
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
				'id'      => '_status',
				'name'    => esc_html__( 'Listing Claim Status', 'pebas-claim-listings' ),
				'type'    => 'select',
				'desc'    => esc_html__( 'Choose listing template that will be used for listing single page.', 'pebas-claim-listings' ),
				'options' => pebas_claim()->get_listing_claim_statuses(),
				'std'     => 'pending',
			),
		);

		$meta_boxes[] = array(
			'title'    => esc_html__( 'Listing Claim Status', 'pebas-claim-listings' ),
			'pages'    => pebas_claim_install()->pebas_claim_type_name,
			'fields'   => $status,
			'context'  => 'side',
			'priority' => 'low'
		);

		// Listing Claims Meta Fields / Information
		$information = array(
			array(
				'id'        => '_listing_id',
				'name'      => esc_html__( 'Listing Claim Status', 'pebas-claim-listings' ),
				'type'      => 'post',
				'post_type' => 'job_listing',
				'std'       => isset( $_REQUEST['listing_id'] ) ? $_REQUEST['listing_id'] : '',
				'desc'      => esc_html__( 'Choose claimed listing from the list of available ones', 'pebas-claim-listings' ),
			),
			array(
				'id'   => '_user_id',
				'name' => esc_html__( 'Listing Claimed By', 'pebas-claim-listings' ),
				'type' => 'user',
				'desc' => esc_html__( 'Choose which user has claimed the listing', 'pebas-claim-listings' ),
			),
		);

		$meta_boxes[] = array(
			'title'   => esc_html__( 'Listing Claim Information', 'pebas-claim-listings' ),
			'pages'   => pebas_claim_install()->pebas_claim_type_name,
			'fields'  => $information,
			'context' => 'advanced',
		);

		return $meta_boxes;
	}

	public function remove_default_meta_boxes() {
		remove_meta_box( 'authordiv', pebas_claim_install()->pebas_claim_type_name, 'normal' ); // author
		remove_meta_box( 'slugdiv', pebas_claim_install()->pebas_claim_type_name, 'normal' ); // slug
	}

	public function update_claim_status( $null, $field, $new, $old, $post_id ) {
		$field_id = $field['id'];
		if ( '_status' == $field_id ) {
			if ( ! $old ) {
				update_post_meta( $post_id, '_status', $new );

				do_action( 'pebas_claim_listings_create_new_claim', $post_id, $context = 'admin' );
			} elseif ( $old != $new ) {
				update_post_meta( $post_id, '_status', $new );

				do_action( 'pebas_claim_listings_claim_status_updated', $post_id, $old, $new );
			}
		}
	}

}

/** Instantiate class
 *
 * @return null|pebas_claim_meta
 */
function pebas_claim_meta() {
	return pebas_claim_meta::instance();
}
