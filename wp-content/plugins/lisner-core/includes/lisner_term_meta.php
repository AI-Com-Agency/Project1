<?php

/**
 * Class lisner_term_meta
 */
class lisner_term_meta {

	protected static $_instance = null;

	/**
	 * @return null|lisner_term_meta
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * lisner_term_meta constructor.
	 */
	function __construct() {
		add_filter( 'rwmb_meta_boxes', array( $this, 'meta_boxes' ), 11 );
		add_action( 'admin_init', array( $this, 'get_material_icons' ) );
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

		// Listing Settings/Appearance
		$fields = array(
			array(
				'id'               => 'term_bg_image',
				'name'             => esc_html__( 'Background Image', 'lisner-core' ),
				'type'             => 'image_advanced',
				'desc'             => esc_html__( 'Upload background image for this term that will be displayed where possible.', 'lisner-core' ),
				'max_file_uploads' => 1,
				'max_status'       => 'true',
			),
			array(
				'id'      => 'term_bg_position_y',
				'name'    => esc_html__( 'Background Image Position / Vertical', 'lisner-core' ),
				'type'    => 'select',
				'options' => array(
					'top'    => esc_html__( 'Top', 'lisner-core' ),
					'center' => esc_html__( 'Center', 'lisner-core' ),
					'bottom' => esc_html__( 'Bottom', 'lisner-core' ),
				),
				'std'     => 'center',
				'desc'    => esc_html__( 'Choose vertical position of background image', 'lisner-core' ),
			),
			array(
				'id'      => 'term_bg_position_x',
				'name'    => esc_html__( 'Background Image Position / Horizontal', 'lisner-core' ),
				'type'    => 'select',
				'options' => array(
					'left'   => esc_html__( 'Left', 'lisner-core' ),
					'center' => esc_html__( 'Center', 'lisner-core' ),
					'right'  => esc_html__( 'Right', 'lisner-core' ),
				),
				'std'     => 'center',
				'desc'    => esc_html__( 'Choose vertical position of background image', 'lisner-core' ),
			),
			array(
				'name'          => esc_html__( 'Background Image Overlay', 'lisner-core' ),
				'id'            => 'term_bg_overlay',
				'type'          => 'color',
				'desc'          => esc_html__( 'Please choose background images overlay', 'lisner-core' ),
				'alpha_channel' => false,
				'js_options'    => array(
					'palettes' => false,
				),
			),
			array(
				'name'       => esc_html__( 'Background Image Opacity', 'lisner-core' ),
				'id'         => 'term_bg_overlay_opacity',
				'type'       => 'slider',
				'desc'       => esc_html__( 'Please choose background image overlay opacity', 'lisner-core' ),
				'std'        => '0',
				'value'      => '0',
				'js_options' => array(
					'min'  => 0,
					'max'  => 1,
					'step' => 0.1
				),
			),
			array(
				'name'        => esc_html__( 'Select Category Icon', 'lisner-core' ),
				'id'          => 'term_icon',
				'type'        => 'select_advanced',
				'desc'        => sprintf( __( 'Choose category icon from list of available material icons: %s', 'lisner-core' ), '<a href="https://material.io/tools/icons/?style=outline" target="_blank">' . esc_html__( 'List of Material Icons', 'lisner-core' ) . '</a>' ),
				'placeholder' => esc_html__( '_', 'lisner-core' ),
				'js_options'  => array(
					'allowClear' => true
				),
				'options'     => $this->create_material_icons_select(),
			),
		);


		$fields       = array_merge( $fields );
		$meta_boxes[] = array(
			'title'      => esc_html__( 'Additional Options', 'lisner-core' ),
			'taxonomies' => array( 'job_listing_category', 'listing_amenity', 'listing_tag', 'listing_location' ),
			'fields'     => $fields,
		);

		return $meta_boxes;
	}

	/**
	 * Get material design icons
	 *
	 * @return array|mixed
	 */
	public function get_material_icons() {
		$transient = get_transient( 'lisner_material_icons' );
		if ( ! isset( $transient ) || empty( $transient ) ) {
			$result     = wp_remote_get( 'https://raw.githubusercontent.com/google/material-design-icons/master/iconfont/codepoints' );
			$icons_list = $result['body'];
			$icons      = array();
			$icons_list = explode( '\n', wp_json_encode( $icons_list ) );
			foreach ( $icons_list as $icon ) {
				$icon = explode( ' ', $icon );
				if ( isset( $icon[0] ) && isset( $icon[1] ) ) {
					$icons[ str_replace( '"', '', $icon[0] ) ] = str_replace( '"', '', $icon[1] );
				}
			}
			set_transient( 'lisner_material_icons', $icons, 1 * MONTH_IN_SECONDS );

			return $icons;
		}

		return $transient;

	}

	/**
	 * Rearrange material icons for the select field
	 *
	 * @return array
	 */
	public function create_material_icons_select() {
		$icons        = $this->get_material_icons();
		$icons_select = array();

		foreach ( $icons as $icon => $code ) {
			$icons_select[ $icon ] = $icon;
		}

		return $icons_select;
	}

}

/** Instantiate class
 *
 * @return null|lisner_term_meta
 */
function lisner_term_meta() {
	return lisner_term_meta::instance();
}
