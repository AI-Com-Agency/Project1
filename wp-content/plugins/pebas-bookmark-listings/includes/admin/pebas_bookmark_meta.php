<?php
/**
 * Class pebas_bookmark_meta
 *
 * @author pebas
 * @ver 1.0.0
 */

/**
 * Class pebas_bookmark_meta
 */
class pebas_bookmark_meta {

	protected static $_instance = null;

	/**
	 * @return null|pebas_bookmark_meta
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
				'id'        => '_bookmark_listing',
				'name'      => esc_html__( 'Listings Bookmarked', 'pebas-bookmark-listings' ),
				'type'      => 'post',
				'post_type' => 'job_listing',
				'desc'      => esc_html__( 'Choose which user has reported the listing', 'pebas-bookmark-listings' ),
			),
			array(
				'id'         => '_bookmark_users',
				'name'       => esc_html__( 'Listings Bookmarked By', 'pebas-bookmark-listings' ),
				'type'       => 'user',
				'field_type' => 'select_advanced',
				'multiple'  => true,
				'desc'       => esc_html__( 'Choose which users has reported the listing', 'pebas-bookmark-listings' ),
			),
		);

		$meta_boxes[] = array(
			'title'   => esc_html__( 'User Bookmark Information', 'pebas-bookmark-listings' ),
			'pages'   => pebas_bookmark_listings_install::$pebas_bookmark_type_name,
			'fields'  => $information,
			'context' => 'advanced',
		);

		return $meta_boxes;
	}

}

/** Instantiate class
 *
 * @return null|pebas_bookmark_meta
 */
function pebas_bookmark_meta() {
	return pebas_bookmark_meta::instance();
}
