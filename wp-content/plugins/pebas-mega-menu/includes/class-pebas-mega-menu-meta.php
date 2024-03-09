<?php

/**
 * Class pebas_mega_menu_meta
 */
class pebas_mega_menu_meta {

	protected static $_instance = null;

	/**
	 * @return null|pebas_mega_menu_meta
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * pebas_mega_menu_meta constructor.
	 */
	function __construct() {
		add_filter( 'rwmb_meta_boxes', array( $this, 'meta_boxes' ), 11 );
	}

	/**
	 * Register all news meta boxes
	 *
	 * @param $meta_boxes
	 *
	 * @return array
	 */
	public static function meta_boxes( $meta_boxes ) {

		/*		$mega_menu = array(
					array(
						'id'          => 'mega_menu',
						'type'        => 'group',
						'group_title' => __( 'Mega Menu Tab {#}', 'pebas-mega-menu' ),
						'clone'       => true,
						'sort_clone'  => true,
						'collapsible' => true,
						// List of sub-fields
						'fields'      => array(
							array(
								'name'       => esc_html__( 'Choose Mega Menu Type', 'pebas-mega-menu' ),
								'id'         => 'mega_menu_type',
								'type'       => 'radio',
								'field_type' => 'radio_list',
								'options'    => array(
									'categorized' => esc_html__( 'Categorized Post', 'pebas-mega-menu' ),
									'custom'      => esc_html__( 'Custom Posts', 'pebas-mega-menu' )
								),
								'desc'       => esc_html__( 'Please choose mega menu type.', 'pebas-mega-menu' ),
							),
							array(
								'name'       => esc_html__( 'Choose Post Format', 'pebas-mega-menu' ),
								'id'         => 'mega_menu_post_format',
								'type'       => 'radio',
								'field_type' => 'radio_list',
								'options'    => array(
									'all'     => esc_html__( 'All Post Formats', 'pebas-mega-menu' ),
									'video'   => esc_html__( 'Video Post Formats', 'pebas-mega-menu' ),
									'gallery' => esc_html__( 'Gallery Post Formats', 'pebas-mega-menu' )
								),
								'desc'       => esc_html__( 'Please choose category from which posts would be displayed.', 'pebas-mega-menu' ),
								'hidden'     => array( 'mega_menu_type', '!=', 'categorized' )
							),
							array(
								'name'       => esc_html__( 'Choose Category', 'pebas-mega-menu' ),
								'id'         => 'mega_menu_category',
								'type'       => 'taxonomy_advanced',
								'taxonomy'   => 'category',
								'field_type' => 'select_advanced',
								'desc'       => esc_html__( 'Please choose category from which posts would be displayed. Leave empty if do not want to set category.', 'pebas-mega-menu' ),
								'hidden'     => array( 'mega_menu_type', '!=', 'categorized' )
							),
							array(
								'name'   => esc_html__( 'Set Title', 'pebas-mega-menu' ),
								'id'     => 'mega_menu_title',
								'type'   => 'text',
								'desc'   => esc_html__( 'Please enter mega menu group title.', 'pebas-mega-menu' ),
								'hidden' => array( 'mega_menu_type', '!=', 'categorized' )
							),
							// custom posts
							array(
								'name'       => esc_html__( 'Choose Posts', 'pebas-mega-menu' ),
								'id'         => 'mega_menu_post',
								'type'       => 'post',
								'field_type' => 'select_advanced',
								'desc'       => esc_html__( 'Please choose category from which posts would be displayed. Leave empty if do not want to set category.', 'pebas-mega-menu' ),
								'multiple'   => true,
								'hidden'     => array( 'mega_menu_type', '!=', 'custom' )
							),
							array(
								'name'   => esc_html__( 'Set Title', 'pebas-mega-menu' ),
								'id'     => 'mega_menu_post_title',
								'type'   => 'text',
								'desc'   => esc_html__( 'Please enter mega menu group title.', 'pebas-mega-menu' ),
								'hidden' => array( 'mega_menu_type', '!=', 'custom' )
							),
						),
					),
				);

				$meta_boxes[] = array(
					'title'    => esc_html__( 'Mega Menu Settings', 'pebas-mega-menu' ),
					'pages'    => 'pebas_mega_menu',
					'fields'   => $mega_menu,
					'priority' => 'high',
				);*/

		$mega_menu = array(
			array(
				'id'          => 'mega_menu',
				'type'        => 'group',
				'group_title' => __( 'Mega Menu Tab {#}', 'pebas-mega-menu' ),
				'clone'       => true,
				'sort_clone'  => true,
				'collapsible' => true,
				// List of sub-fields
				'fields'      => array(
					array(
						'name'       => esc_html__( 'Mega Menu Type', 'pebas-mega-menu' ),
						'id'         => 'mega_menu_type',
						'type'       => 'radio',
						'field_type' => 'radio_list',
						'options'    => array(
							'custom'    => esc_html__( 'Custom link', 'pebas-mega-menu' ),
							'post_type' => esc_html__( 'Post Type', 'pebas-mega-menu' ),
							'taxonomy'  => esc_html__( 'Taxonomy', 'pebas-mega-menu' )
						),
						'std'        => 'custom',
						'desc'       => esc_html__( 'Please choose mega menu type.', 'pebas-mega-menu' ),
					),
					array(
						'name' => esc_html__( 'Title', 'pebas-mega-menu' ),
						'id'   => 'mega_menu_title',
						'type' => 'text',
						'desc' => esc_html__( 'Please enter mega menu group title.', 'pebas-mega-menu' ),
					),
					array(
						'name' => esc_html__( 'Promo Label', 'pebas-mega-menu' ),
						'id'   => 'mega_menu_promo',
						'type' => 'text',
						'desc' => esc_html__( 'Please enter promo label', 'pebas-mega-menu' ),
					),
					array(
						'name'       => esc_html__( 'Background type', 'pebas-mega-menu' ),
						'id'         => 'mega_menu_background_type',
						'type'       => 'radio',
						'field_type' => 'radio_list',
						'options'    => array(
							'image' => esc_html__( 'Image', 'pebas-mega-menu' ),
							'video' => esc_html__( 'Video', 'pebas-mega-menu' ),
						),
						'std'        => 'image',
						'desc'       => esc_html__( 'Please choose category from which posts would be displayed.', 'pebas-mega-menu' ),
						'hidden'     => array( 'mega_menu_type', '!=', 'custom' )
					),
					array(
						'name'             => esc_html__( 'Image/Gallery', 'pebas-mega-menu' ),
						'id'               => 'mega_menu_background_image',
						'type'             => 'image_advanced',
						'desc'             => esc_html__( 'Upload image for the mega menu tab', 'lisner-core' ),
						'max_file_uploads' => 16,
						'max_status'       => 'true',
						'tooltip'          => array(
							'icon'     => 'info',
							'content'  => esc_html__( 'If you upload more than one image then gallery will be loaded.', 'lisner-core' ),
							'position' => 'right'
						),
						'hidden'           => array( 'mega_menu_background_type', '!=', 'image' )
					),
					array(
						'name'   => esc_html__( 'Choose Video', 'pebas-mega-menu' ),
						'id'     => 'mega_menu_background_video',
						'type'   => 'oembed',
						'desc'   => esc_html__( 'Enter link to the desired video for this tab', 'lisner-core' ),
						'hidden' => array( 'mega_menu_background_type', '!=', 'video' )
					),
					array(
						'name'             => esc_html__( 'Video Background Image', 'pebas-mega-menu' ),
						'id'               => 'mega_menu_video_background_image',
						'type'             => 'image_advanced',
						'desc'             => esc_html__( 'Upload image for video placeholder', 'lisner-core' ),
						'max_file_uploads' => 1,
						'max_status'       => 'true',
						'tooltip'          => array(
							'icon'     => 'info',
							'content'  => esc_html__( 'Image that is being used to display before video is loaded.', 'lisner-core' ),
							'position' => 'right'
						),
						'hidden'           => array( 'mega_menu_background_type', '!=', 'video' )
					),
					array(
						'name' => esc_html__( 'Enter link', 'pebas-mega-menu' ),
						'id'   => 'mega_menu_link',
						'type' => 'text',
						'desc' => esc_html__( 'Enter link where user will be lead on click.', 'lisner-core' ),
					),
				),
			),
		);

		$meta_boxes[] = array(
			'title'    => esc_html__( 'Mega Menu Settings', 'pebas-mega-menu' ),
			'pages'    => 'pebas_mega_menu',
			'fields'   => $mega_menu,
			'priority' => 'high',
		);

		// load meta boxes
		return $meta_boxes;
	}


}

/** Instantiate class
 *
 * @return null|pebas_mega_menu_meta
 */
function pebas_mega_menu_meta() {
	return pebas_mega_menu_meta::instance();
}
