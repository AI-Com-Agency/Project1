<?php

/**
 * Class lisner_comments_meta
 */
class pebas_comments_meta {

	protected static $_instance = null;

	/**
	 * @return null|pebas_comments_meta
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
		add_filter( 'rwmb_meta_boxes', array( $this, 'comment_meta_boxes' ), 11 );
	}

	/**
	 * Register all news meta boxes
	 *
	 * @param $meta_boxes
	 *
	 * @return array
	 */
	public function comment_meta_boxes( $meta_boxes ) {
		$option = get_option( 'pbs_option' );

		// Listing Settings/Appearance
		$fields = array(
			array(
				'id'      => 'review_rating',
				'name'    => esc_html__( 'Review Rating', 'pebas-review-listings' ),
				'type'    => 'select',
				'desc'    => esc_html__( 'Choose listing template that will be used for listing single page.', 'pebas-review-listings' ),
				'options' => array(
					'1' => esc_html__( '1 Star Rating', 'pebas-review-listings' ),
					'2' => esc_html__( '2 Star Rating', 'pebas-review-listings' ),
					'3' => esc_html__( '3 Star Rating', 'pebas-review-listings' ),
					'4' => esc_html__( '4 Star Rating', 'pebas-review-listings' ),
					'5' => esc_html__( '5 Star Rating', 'pebas-review-listings' ),
				),
			),
			array(
				'id'               => 'review_gallery',
				'name'             => esc_html__( 'Review Gallery', 'pebas-review-listings' ),
				'type'             => 'image_advanced',
				'desc'             => esc_html__( 'Upload images for the review', 'pebas-review-listings' ),
				'max_file_uploads' => 16, //todo: add option for this
				'max_status'       => 'true',
			),
			array(
				'id'   => 'review_likes',
				'name' => esc_html__( 'Review Likes', 'pebas-review-listings' ),
				'type' => 'number',
				'min'  => '0',
				'desc' => esc_html__( 'Number of review likes', 'pebas-review-listings' ),
			),
			array(
				'id'   => 'review_dislikes',
				'name' => esc_html__( 'Review Dislikes', 'pebas-review-listings' ),
				'type' => 'number',
				'min'  => '0',
				'desc' => esc_html__( 'Number of review dislikes', 'pebas-review-listings' ),
			),
			array(
				'id'   => 'review_likes_ip',
				'name' => esc_html__( 'Review Likes IP\'s', 'pebas-review-listings' ),
				'type' => 'textarea',
				'desc' => esc_html__( 'IP addresses that liked the comment', 'pebas-review-listings' ),
			),
		);

		$meta_boxes[] = array(
			'title'  => esc_html__( 'Review Fields', 'pebas-review-listings' ),
			'fields' => $fields,
			'type'   => 'comment'
		);

		return $meta_boxes;
	}


}

/** Instantiate class
 *
 * @return null|pebas_comments_meta
 */
function pebas_comments_meta() {
	return pebas_comments_meta::instance();
}
