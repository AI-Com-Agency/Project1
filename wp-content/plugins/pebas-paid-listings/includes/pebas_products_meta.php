<?php

/**
 * Class lisner_comments_meta
 */
class pebas_products_meta {

	protected static $_instance = null;

	/**
	 * @return null|pebas_products_meta
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
		add_filter( 'rwmb_meta_boxes', array( $this, 'product_meta_boxes' ), 11 );
	}

	/**
	 * Register all product meta boxes
	 *
	 * @param $meta_boxes
	 *
	 * @return array
	 */
	public function product_meta_boxes( $meta_boxes ) {
		$option = get_option( 'pbs_option' );

		// Listing Settings/Appearance
		$fields = array(
			array(
				'id'          => 'package_description',
				'name'        => esc_html__( 'Listing Package Description', 'pebas-paid-listings' ),
				'type'        => 'textarea',
				'placeholder' => sprintf( esc_html__( '%1$s %2$s listing submission active for %3$s days!', 'listing-core' ), '[limit]', '[b]standard[/b]', '[duration]' ),
				'desc'        => esc_html__( 'Please add listing package description, you can use following tags: [duration], [limit], [b]bold text[/b]', 'pebas-paid-listings' ),
			),
			array(
				'id'          => 'package_features',
				'name'        => esc_html__( 'Listing Package Features List', 'pebas-paid-listings' ),
				'type'        => 'text',
				'placeholder' => sprintf( esc_html__( '%s Listing', 'listing-core' ), '[Standard]' ),
				'desc'        => esc_html__( 'Please add price package features list of items. To make a word bold place it inside brackets: [something]', 'pebas-paid-listings' ),
				'clone'       => true,
				'sort_clone'  => true
			),
			array(
				'id'        => 'package_distinctive',
				'name'      => esc_html__( 'Listing Package Distinctive?', 'pebas-paid-listings' ),
				'type'      => 'switch',
				'style'     => 'rounded',
				'on_label'  => esc_html__( 'Yes', 'pebas-paid-listings' ),
				'off_label' => esc_html__( 'No', 'pebas-paid-listings' ),
				'desc'      => esc_html__( 'Marking package as distinctive will make it look differently to the other ones.', 'pebas-paid-listings' ),
			),
		);

		$meta_boxes[] = array(
			'title'   => esc_html__( 'Listing Package Information', 'pebas-paid-listings' ),
			'pages'   => 'product',
			'fields'  => $fields,
			'context' => 'after_editor',
			'visible' => array( 'product-type', '=', 'job_package' )
		);

		return $meta_boxes;
	}


}

/** Instantiate class
 *
 * @return null|pebas_products_meta
 */
function pebas_products_meta() {
	return pebas_products_meta::instance();
}
