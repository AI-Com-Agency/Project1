<?php

/**
 * Class lisner_meta
 */
class lisner_meta {

	protected static $_instance = null;

	/**
	 * @return null|lisner_meta
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
		add_filter( 'rwmb_meta_boxes', array( $this, 'meta_boxes' ), 11 );
		add_filter( 'rwmb_meta_boxes', array( $this, 'meta_boxes_homepage' ), 11 );
		add_filter( 'rwmb_meta_boxes', array( $this, 'meta_boxes_search_page' ), 11 );
		add_filter( 'rwmb_meta_boxes', array( $this, 'meta_boxes_pages' ), 11 );
	}

	/**
	 * Default WordPress pages specific meta fields
	 *
	 * @param $meta_boxes
	 *
	 * @return array
	 */
	public function meta_boxes_pages( $meta_boxes ) {
		$option = get_option( 'pbs_option' );

		$page_container = array(
			// home background
			array(
				'id'      => 'page_container_custom',
				'name'    => esc_html__( 'Page Content Container Override', 'lisner-core' ),
				'type'    => 'select',
				'options' => array(
					0 => esc_html__( 'No', 'lisner-core' ),
					1 => esc_html__( 'Yes', 'lisner-core' ),
				),
				'desc'    => esc_html__( 'Choose whether you wish to set custom width of page content container', 'lisner-core' ),
			),
			array(
				'id'         => 'page_container_width',
				'name'       => esc_html__( 'Page Content Container', 'lisner-core' ),
				'type'       => 'slider',
				'desc'       => esc_html__( 'Manually set width of page content container, leave empty to use default value.', 'lisner-core' ),
				'js_options' => array(
					'min'    => 710,
					'max'    => 1380,
					'step'   => 10,
					'suffix' => 'px'
				),
				'value'      => 710,
				'std'        => 710,
				'tooltip'    => array(
					'icon'     => 'info',
					'content'  => esc_html__( 'Setting this option will override default value of page container width for desktops.', 'lisner-core' ),
					'position' => 'right'
				),
				'hidden'     => array( 'page_container_custom', '!=', 1 )
			),
		);

		$meta_boxes[] = array(
			'title'   => esc_html__( 'Page Content Container', 'lisner-core' ),
			'pages'   => 'page',
			'fields'  => $page_container,
			'context' => 'advanced',
			'include' => array(
				'template' => array( 'templates/tpl-page-narrow.php' ),
			)
		);

		$information = array(
			array(
				'id'      => 'page_title',
				'name'    => esc_html__( 'Page Title', 'lisner-core' ),
				'type'    => 'text',
				'desc'    => esc_html__( 'Enter page title if you wish to override default page name, leave empty otherwise.', 'lisner-core' ),
				'tooltip' => array(
					'icon'     => 'info',
					'content'  => esc_html__( 'Use [] tags to make word bold like: Home [Page] Template', 'lisner-core' ),
					'position' => 'right'
				),
			),
			array(
				'id'      => 'page_subtitle',
				'name'    => esc_html__( 'Page Subtitle', 'lisner-core' ),
				'type'    => 'text',
				'desc'    => esc_html__( 'Enter page subtitle if you wish to display one below page title', 'lisner-core' ),
				'tooltip' => array(
					'icon'     => 'info',
					'content'  => esc_html__( 'Use [] tags to make word bold like: Home [Page] Template', 'lisner-core' ),
					'position' => 'right'
				),
			),
			array(
				'id'      => 'page_title_alignment',
				'name'    => esc_html__( 'Page Title Alignment', 'lisner-core' ),
				'type'    => 'select',
				'desc'    => esc_html__( 'Choose page title alignment', 'lisner-core' ),
				'options' => array(
					'left'   => esc_html__( 'Left', 'lisner-core' ),
					'center' => esc_html__( 'Center', 'lisner-core' ),
					'right'  => esc_html__( 'Right', 'lisner-core' ),
				),
				'std'     => 'center'
			),
			array(
				'id'               => 'page_bg_image',
				'name'             => esc_html__( 'Page Header Image', 'lisner-core' ),
				'type'             => 'image_advanced',
				'desc'             => esc_html__( 'Choose page header background image', 'lisner-core' ),
				'max_file_uploads' => 1,
				'max_status'       => true,
			),
			array(
				'name'    => esc_html__( 'Use Background Image Overlay?', 'lisner-core' ),
				'id'      => 'page_bg_overlay_show',
				'type'    => 'select_advanced',
				'desc'    => esc_html__( 'Choose whether you wish to use background image overlay', 'lisner-core' ),
				'std'     => 0,
				'options' => array(
					0 => esc_html__( 'No', 'lisner-core' ),
					1 => esc_html__( 'Yes', 'lisner-core' ),
				),
			),
			array(
				'name'          => esc_html__( 'Background Image Overlay', 'lisner-core' ),
				'id'            => 'page_bg_overlay',
				'type'          => 'color',
				'desc'          => esc_html__( 'Please choose background image overlay', 'lisner-core' ),
				'alpha_channel' => false,
				'std'           => 'transparent',
				'value'         => 'transparent',
				'js_options'    => array(
					'color'        => '',
					'defaultColor' => '',
					'palettes'     => false,
				),
				'hidden'        => array( 'page_bg_overlay_show', '!=', 1 )
			),
			array(
				'name'       => esc_html__( 'Background Image Opacity', 'lisner-core' ),
				'id'         => 'page_bg_overlay_opacity',
				'type'       => 'slider',
				'desc'       => esc_html__( 'Please choose background image overlay opacity', 'lisner-core' ),
				'std'        => '0',
				'value'      => '0',
				'js_options' => array(
					'min'  => 0,
					'max'  => 1,
					'step' => 0.1
				),
				'hidden'     => array( 'page_bg_overlay_show', '!=', 1 )
			),
		);

		$meta_boxes[] = array(
			'title'   => esc_html__( 'Page Information', 'lisner-core' ),
			'pages'   => 'page',
			'fields'  => $information,
			'context' => 'advanced',
			'exclude' => array(
				'relation' => 'OR',
				'template' => array( 'templates/tpl-home.php', 'templates/tpl-contact.php' ),
				'custom'   => array( $this, 'show_on_search' ),
			)
		);

		$shop_page = array(
			array(
				'id'               => 'page_bg_image',
				'name'             => esc_html__( 'Page Header Image', 'lisner-core' ),
				'type'             => 'image_advanced',
				'desc'             => esc_html__( 'Choose page header background image', 'lisner-core' ),
				'max_file_uploads' => 1,
				'max_status'       => true,
			),
			array(
				'name'    => esc_html__( 'Use Background Image Overlay?', 'lisner-core' ),
				'id'      => 'page_bg_overlay_show',
				'type'    => 'select_advanced',
				'desc'    => esc_html__( 'Choose whether you wish to use background image overlay', 'lisner-core' ),
				'std'     => 0,
				'options' => array(
					0 => esc_html__( 'No', 'lisner-core' ),
					1 => esc_html__( 'Yes', 'lisner-core' ),
				),
			),
			array(
				'name'          => esc_html__( 'Background Image Overlay', 'lisner-core' ),
				'id'            => 'page_bg_overlay',
				'type'          => 'color',
				'desc'          => esc_html__( 'Please choose background image overlay', 'lisner-core' ),
				'alpha_channel' => false,
				'std'           => 'transparent',
				'value'         => 'transparent',
				'js_options'    => array(
					'color'        => '',
					'defaultColor' => '',
					'palettes'     => false,
				),
				'hidden'        => array( 'page_bg_overlay_show', '!=', 1 )
			),
			array(
				'name'       => esc_html__( 'Background Image Opacity', 'lisner-core' ),
				'id'         => 'page_bg_overlay_opacity',
				'type'       => 'slider',
				'desc'       => esc_html__( 'Please choose background image overlay opacity', 'lisner-core' ),
				'std'        => '0',
				'value'      => '0',
				'js_options' => array(
					'min'  => 0,
					'max'  => 1,
					'step' => 0.1
				),
				'hidden'     => array( 'page_bg_overlay_show', '!=', 1 )
			),
		);

		$meta_boxes[] = array(
			'title'   => esc_html__( 'Page Information', 'lisner-core' ),
			'pages'   => 'page',
			'fields'  => $shop_page,
			'context' => 'advanced',
			'include' => array(
				'custom'   => array( $this, 'show_on_shop' ),
				'template' => array( 'templates/tpl-contact.php' ),
			)
		);

		// Contact meta fields
		$contact_fields = array(
			array(
				'id'   => 'contact_form',
				'name' => esc_html__( 'Contact Form 7 Shortcode', 'lisner-core' ),
				'type' => 'text',
				'desc' => esc_html__( 'Please enter contact form 7 shortcode here', 'lisner-core' ),
			),
			array(
				'id'   => 'contact_address',
				'name' => esc_html__( 'Address', 'lisner-core' ),
				'type' => 'text',
				'desc' => esc_html__( 'Please type the address', 'lisner-core' ),
			),
			array(
				'id'            => 'contact_address_map',
				'name'          => esc_html__( 'Location', 'lisner-core' ),
				'type'          => 'map',
				'desc'          => esc_html__( 'Please type the address of the location', 'lisner-core' ),
				'std'           => '',
				'language'      => 'en',
				'address_field' => 'contact_address',
				'api_key'       => isset( $option['map-google-api'] ) ? $option['map-google-api'] : '',
			),
			array(
				'id'   => 'contact_zoom',
				'name' => esc_html__( 'Map Zoom Level', 'lisner-core' ),
				'type' => 'number',
				'std'  => '14',
				'desc' => esc_html__( 'Please enter map zoom level', 'lisner-core' ),
			),
			array(
				'id'   => 'contact_marker',
				'name' => esc_html__( 'Marker Image', 'lisner-core' ),
				'type' => 'single_image',
				'std'  => '',
				'desc' => esc_html__( 'Upload marker image', 'lisner-core' ),
			),
			array(
				'id'   => 'contact_social_facebook',
				'name' => esc_html__( 'Link to facebook page', 'lisner-core' ),
				'type' => 'text',
				'desc' => esc_html__( 'Please enter link to facebook page', 'lisner-core' ),
			),
			array(
				'id'   => 'contact_social_twitter',
				'name' => esc_html__( 'Link to twitter page', 'lisner-core' ),
				'type' => 'text',
				'desc' => esc_html__( 'Please enter link to twitter page', 'lisner-core' ),
			),
			array(
				'id'   => 'contact_social_google',
				'name' => esc_html__( 'Link to google+ page', 'lisner-core' ),
				'type' => 'text',
				'desc' => esc_html__( 'Please enter link to google+ page', 'lisner-core' ),
			),
			array(
				'id'   => 'contact_social_linkedin',
				'name' => esc_html__( 'Link to linkedin page', 'lisner-core' ),
				'type' => 'text',
				'desc' => esc_html__( 'Please enter link to linkedin page', 'lisner-core' ),
			),
		);

		$meta_boxes[] = array(
			'title'   => esc_html__( 'Contact Information', 'lisner-core' ),
			'pages'   => 'page',
			'fields'  => $contact_fields,
			'context' => 'advanced',
			'include' => array(
				'template' => array( 'templates/tpl-contact.php' ),
			)
		);


		// FAQ meta fields
		$faq_fields   = array(
			array(
				'id'      => 'faq_contact',
				'name'    => esc_html__( 'Display contact button?', 'lisner-core' ),
				'type'    => 'select',
				'options' => array(
					'yes' => esc_html__( 'Yes', 'lisner-core' ),
					'no'  => esc_html__( 'No', 'lisner-core' ),
				),
				'desc'    => esc_html__( 'Choose whether you wish to display contact button at the end of the page', 'lisner-core' ),
			),
			array(
				'id'         => 'faq_group',
				'name'       => esc_html__( 'FAQ Accordion', 'lisner-core' ),
				'type'       => 'group',
				'clone'      => true,
				'sort_clone' => true,
				'fields'     => array(
					array(
						'id'   => 'faq_heading',
						'name' => esc_html__( 'Faq Item Heading', 'lisner-core' ),
						'type' => 'text',
						'desc' => esc_html__( 'Please enter faq item heading', 'lisner-core' ),
					),
					array(
						'id'   => 'faq_content',
						'name' => esc_html__( 'Faq Item Content', 'lisner-core' ),
						'type' => 'textarea',
						'desc' => esc_html__( 'Please enter faq item content', 'lisner-core' ),
					),
				)
			)
		);
		$meta_boxes[] = array(
			'title'   => esc_html__( 'Faq Content', 'lisner-core' ),
			'pages'   => 'page',
			'fields'  => $faq_fields,
			'context' => 'advanced',
			'include' => array(
				'template' => array( 'templates/tpl-faq.php' ),
			),
		);

		return $meta_boxes;
	}

	/**
	 * Search page specific meta fields
	 *
	 * @param $meta_boxes
	 *
	 * @return array
	 */
	public function meta_boxes_search_page( $meta_boxes ) {

		$appearance = array(
			// search appearance
			array(
				'id'      => 'search_template',
				'name'    => esc_html__( 'Search Page Template', 'lisner-core' ),
				'type'    => 'image_select',
				'desc'    => esc_html__( 'Choose Search Page template', 'lisner-core' ),
				'options' => array(
					1 => esc_url( LISNER_URL . 'assets/images/search-thumbnails/1.png' ),
					2 => esc_url( LISNER_URL . 'assets/images/search-thumbnails/2.png' ),
				),
				'std'     => 1,
				'value'   => 1,
				'tab'     => 'search_appearance'
			),
			array(
				'id'      => 'search_box_template',
				'name'    => esc_html__( 'Search Listing Box Style', 'lisner-core' ),
				'type'    => 'image_select',
				'desc'    => esc_html__( 'Choose search listing box style from the available ones', 'lisner-core' ),
				'options' => array(
					1 => esc_url( LISNER_URL . 'assets/images/search-thumbnails/3.png' ),
					2 => esc_url( LISNER_URL . 'assets/images/search-thumbnails/4.png' ),
					3 => esc_url( LISNER_URL . 'assets/images/search-thumbnails/5.png' ),
					4 => esc_url( LISNER_URL . 'assets/images/search-thumbnails/6.png' ),
				),
				'std'     => 1,
				'value'   => 1,
				'tab'     => 'search_appearance'
			),
			array(
				'id'      => 'search_map_active',
				'name'    => esc_html__( 'Search Map Visibility On Page Load', 'lisner-core' ),
				'type'    => 'select',
				'desc'    => esc_html__( 'Choose whether the search map should be visible on page load or hidden', 'lisner-core' ),
				'options' => array(
					0 => esc_html__( 'Hidden', 'lisner-core' ),
					1 => esc_html__( 'Visible', 'lisner-core' ),
				),
				'std'     => 0,
				'value'   => 0,
				'tab'     => 'search_appearance',
				'hidden'  => array( 'search_template', '!=', 1 )
			),
		);

		$meta_boxes[] = array(
			'title'     => esc_html__( 'Search Page Specific Settings', 'lisner-core' ),
			'pages'     => 'page',
			'fields'    => $appearance,
			'context'   => 'advanced',
			'include'   => array(
				'custom' => array( $this, 'show_on_search' )
			),
			'tabs'      => array(
				'search_appearance' => array(
					'label' => esc_html__( 'Home/Hero Appearance', 'lisner-core' ),
					'icon'  => 'dashicons-arrow-right'
				),
			),
			'tab_style' => 'left',
		);

		return $meta_boxes;
	}

	/**
	 * Make sure to display only on search page template
	 *
	 * @return bool
	 */
	public static function show_on_search() {
		$search_tpl = lisner_search()->get_search_page_template();
		$tpl        = lisner_helper::check_template( 'page-search' );
		if ( $tpl || $search_tpl == lisner_helper::get_current_post_id() ) {
			return true;
		}

		return false;
	}

	public static function show_on_shop() {
		if ( get_option( 'woocommerce_shop_page_id' ) == lisner_helper::get_current_post_id() ) {
			return true;
		}

		return false;
	}

	/**
	 * Register all news meta boxes
	 *
	 * @param $meta_boxes
	 *
	 * @return array
	 */
	public function meta_boxes_homepage( $meta_boxes ) {
		// Listing Settings/Appearance
		$home = array(
			// home appearance
			array(
				'id'      => 'home_hero_template',
				'name'    => esc_html__( 'Home Hero Section Template', 'lisner-core' ),
				'type'    => 'image_select',
				'desc'    => esc_html__( 'Choose hero section template from the list of available ones', 'lisner-core' ),
				'options' => array(
					1  => esc_url( LISNER_URL . 'assets/images/hero-template-images/1.png' ),
					2  => esc_url( LISNER_URL . 'assets/images/hero-template-images/2.png' ),
					3  => esc_url( LISNER_URL . 'assets/images/hero-template-images/3.png' ),
					4  => esc_url( LISNER_URL . 'assets/images/hero-template-images/4.png' ),
					5  => esc_url( LISNER_URL . 'assets/images/hero-template-images/5.png' ),
					6  => esc_url( LISNER_URL . 'assets/images/hero-template-images/6.png' ),
					7  => esc_url( LISNER_URL . 'assets/images/hero-template-images/7.png' ),
					8  => esc_url( LISNER_URL . 'assets/images/hero-template-images/8.png' ),
					9  => esc_url( LISNER_URL . 'assets/images/hero-template-images/9.png' ),
					10 => esc_url( LISNER_URL . 'assets/images/hero-template-images/10.png' ),
				),
				'std'     => 1,
				'tab'     => 'home_appearance'
			),
			array(
				'id'      => 'home_hero_heading',
				'name'    => esc_html__( 'Enter Hero Text', 'lisner-core' ),
				'type'    => 'wysiwyg',
				'desc'    => __( 'Enter hero section text. To use city geolocation type <strong>[city-locate]</strong> in editor', 'lisner-core' ),
				'options' => array(
					'textarea_rows' => 8,
					'media_buttons' => true,
					'quicktags'     => false
				),
				'tab'     => 'home_appearance'
			),
			array(
				'id'     => 'home_hero_category_heading',
				'name'   => esc_html__( 'Enter Category Text', 'lisner-core' ),
				'type'   => 'text',
				'desc'   => esc_html__( 'Enter text that goes above the category. Put text inside "[]" brackets if you wish to make it bold.', 'lisner-core' ),
				'tab'    => 'home_appearance',
				'hidden' => array( 'home_hero_template', 'in', array( 1, 4, 5 ) )
			),
			array(
				'id'         => 'home_hero_position',
				'name'       => esc_html__( 'Reposition Search', 'lisner-core' ),
				'type'       => 'slider',
				'desc'       => esc_html__( 'Move slider left or right to reposition search section lower or higher on the site. Leaving to 0 will use predefined values.', 'lisner-core' ),
				'suffix'     => 'px',
				'js_options' => array(
					'min'  => 0,
					'max'  => 300,
					'step' => 5
				),
				'tab'        => 'home_appearance',
			),
			array(
				'id'      => 'home_hero_position_side',
				'name'    => esc_html__( 'Reposition Search From', 'lisner-core' ),
				'type'    => 'select',
				'desc'    => esc_html__( 'Choose from what margin you wish to reposition search section', 'lisner-core' ),
				'options' => array(
					'top'    => esc_html__( 'From Top', 'lisner-core' ),
					'bottom' => esc_html__( 'From Bottom', 'lisner-core' ),
				),
				'tab'     => 'home_appearance',
			),
			// home background
			array(
				'id'      => 'home_bg_type',
				'name'    => esc_html__( 'Hero Background Type', 'lisner-core' ),
				'type'    => 'select',
				'options' => array(
					'image' => esc_html__( 'Image', 'lisner-core' ),
					'video' => esc_html__( 'Video', 'lisner-core' ),
				),
				'desc'    => esc_html__( 'Choose hero section background type between image and video', 'lisner-core' ),
				'tab'     => 'home_bg'
			),
			// home background / background video
			array(
				'id'          => 'home_bg_video',
				'name'        => esc_html__( 'Hero Video Url', 'lisner-core' ),
				'type'        => 'oembed',
				'desc'        => esc_html__( 'Choose background image that will be displayed before and after a video has been played.', 'lisner-core' ),
				'placeholder' => esc_html( 'https://www.youtube.com/watch?v=XXXX' ),
				'tab'         => 'home_bg',
				'hidden'      => array( 'home_bg_type', '!=', 'video' )
			),
			array(
				'id'               => 'home_bg_video_image',
				'name'             => esc_html__( 'Hero Video Thumbnail', 'lisner-core' ),
				'type'             => 'image_advanced',
				'desc'             => esc_html__( 'Choose background image that will be displayed before and after a video has been played.', 'lisner-core' ),
				'max_file_uploads' => 1,
				'max_status'       => true,
				'tab'              => 'home_bg',
				'hidden'           => array( 'home_bg_type', '!=', 'video' )
			),
			array(
				'id'            => 'home_bg_video_color',
				'name'          => esc_html__( 'Hero Video Background Color', 'lisner-core' ),
				'type'          => 'color',
				'desc'          => esc_html__( 'Please choose video background color', 'lisner-core' ),
				'alpha_channel' => false,
				'std'           => '#37003c',
				'js_options'    => array(
					'color'        => '#37003c',
					'defaultColor' => '#37003c',
					'palettes'     => false,
				),
				'tooltip'       => array(
					'icon'     => 'info',
					'content'  => esc_html__( 'Color will be visible only if image isn\'t used', 'lisner-core' ),
					'position' => 'top'
				),
				'tab'           => 'home_bg',
				'hidden'        => array( 'home_bg_type', '!=', 'video' )
			),
			array(
				'name'       => esc_html__( 'Background Video Opacity', 'lisner-core' ),
				'id'         => 'home_bg_video_overlay_opacity',
				'type'       => 'slider',
				'desc'       => esc_html__( 'Please choose background video overlay opacity', 'lisner-core' ),
				'std'        => '0',
				'value'      => '0',
				'js_options' => array(
					'min'  => 0,
					'max'  => 1,
					'step' => 0.1
				),
				'tab'        => 'home_bg',
				'hidden'     => array( 'home_bg_type', '!=', 'video' )
			),
			array(
				'id'      => 'home_bg_video_loop',
				'name'    => esc_html__( 'Hero Video Loop', 'lisner-core' ),
				'type'    => 'radio',
				'options' => array(
					true  => esc_html__( 'Yes', 'lisner-core' ),
					false => esc_html__( 'No', 'lisner-core' ),
				),
				'desc'    => esc_html__( 'Choose if the video should be set to loop or it should show fallback image that you have set previously.', 'lisner-core' ),
				'tab'     => 'home_bg',
				'hidden'  => array( 'home_bg_type', '!=', 'video' )
			),
			array(
				'id'          => 'home_bg_video_start',
				'name'        => esc_html__( 'Hero Video Start Time', 'lisner-core' ),
				'type'        => 'text',
				'desc'        => esc_html__( 'Enter start time of banner video in seconds', 'lisner-core' ),
				'placeholder' => '0',
				'tab'         => 'home_bg',
				'hidden'      => array( 'home_bg_type', '!=', 'video' )
			),
			array(
				'id'          => 'home_bg_video_end',
				'name'        => esc_html__( 'Hero Video End Time', 'lisner-core' ),
				'type'        => 'text',
				'desc'        => esc_html__( 'Enter end time of banner video in seconds', 'lisner-core' ),
				'placeholder' => '0',
				'tab'         => 'home_bg',
				'hidden'      => array( 'home_bg_type', '!=', 'video' )
			),
			// home background / background image
			array(
				'id'               => 'home_bg_image',
				'name'             => esc_html__( 'Hero Background Image', 'lisner-core' ),
				'type'             => 'image_advanced',
				'desc'             => esc_html__( 'Choose listing template that will be used for listing single page.', 'lisner-core' ),
				'max_file_uploads' => 16,
				'max_status'       => true,
				'tab'              => 'home_bg',
				'hidden'           => array( 'home_bg_type', '!=', 'image' )
			),
			array(
				'id'            => 'home_bg_color',
				'name'          => esc_html__( 'Hero Background Color', 'lisner-core' ),
				'type'          => 'color',
				'desc'          => esc_html__( 'Please choose background color', 'lisner-core' ),
				'alpha_channel' => true,
				'std'           => '#37003c',
				'js_options'    => array(
					'color'        => '#37003c',
					'defaultColor' => '#37003c',
					'palettes'     => false,
				),
				'tooltip'       => array(
					'icon'     => 'info',
					'content'  => esc_html__( 'Color will be visible only if image isn\'t used', 'lisner-core' ),
					'position' => 'top'
				),
				'tab'           => 'home_bg',
				'hidden'        => array( 'home_bg_type', '!=', 'image' )
			),
			array(
				'id'            => 'home_bg_font_color',
				'name'          => esc_html__( 'Hero Font Color', 'lisner-core' ),
				'type'          => 'color',
				'desc'          => esc_html__( 'Please choose font color', 'lisner-core' ),
				'alpha_channel' => true,
				'std'           => '#fff',
				'js_options'    => array(
					'color'        => '#fff',
					'defaultColor' => '#fff',
					'palettes'     => false,
				),
				'tab'           => 'home_bg',
				'hidden'        => array( 'home_bg_type', '!=', 'image' )
			),
			array(
				'name'          => esc_html__( 'Use Background Image Overlay?', 'lisner-core' ),
				'id'            => 'home_bg_overlay_show',
				'type'          => 'select_advanced',
				'desc'          => esc_html__( 'Choose whether you wish to use background image overlay', 'lisner-core' ),
				'alpha_channel' => false,
				'std'           => 0,
				'options'       => array(
					0 => esc_html__( 'No', 'lisner-core' ),
					1 => esc_html__( 'Yes', 'lisner-core' ),
				),
				'tab'           => 'home_bg',
				'hidden'        => array( 'home_bg_type', '!=', 'image' )
			),
			array(
				'name'          => esc_html__( 'Background Image Overlay', 'lisner-core' ),
				'id'            => 'home_bg_overlay',
				'type'          => 'color',
				'desc'          => esc_html__( 'Please choose background images overlay', 'lisner-core' ),
				'alpha_channel' => false,
				'std'           => 'transparent',
				'value'         => 'transparent',
				'js_options'    => array(
					'color'        => 'transparent',
					'defaultColor' => 'transparent',
					'palettes'     => false,
				),
				'tab'           => 'home_bg',
				'hidden'        => array( 'home_bg_overlay_show', '!=', 1 )
			),
			array(
				'name'       => esc_html__( 'Background Image Opacity', 'lisner-core' ),
				'id'         => 'home_bg_overlay_opacity',
				'type'       => 'slider',
				'desc'       => esc_html__( 'Please choose background images overlay opacity', 'lisner-core' ),
				'std'        => '0',
				'value'      => '0',
				'js_options' => array(
					'min'  => 0,
					'max'  => 1,
					'step' => 0.1
				),
				'tab'        => 'home_bg',
				'hidden'     => array( 'home_bg_overlay_show', '!=', 1 )
			),

			// home search options
			/*	array(
					'name'    => esc_html__( 'Allow Multi-Categories Search', 'lisner-core' ),
					'id'      => 'home_search_multi_category',
					'type'    => 'select',
					'desc'    => esc_html__( 'Choose if you wish to allow multi-category search in homepage search', 'lisner-core' ),
					'options' => array(
						1 => esc_html__( 'Yes', 'lisner-core' ),
						0 => esc_html__( 'No', 'lisner-core' ),
					),
					'std'     => 0,
					'tab'     => 'home_search',
				),*/
			array(
				'name'       => esc_html__( 'Choose Default Suggested Categories', 'lisner-core' ),
				'id'         => 'home_search_taxonomies_job_listing_category',
				'type'       => 'taxonomy_advanced',
				'field_type' => 'select_advanced',
				'taxonomy'   => 'job_listing_category',
				'desc'       => esc_html__( 'Choose predefined default taxonomies that will be displayed when user clicks on taxonomy search', 'lisner-core' ),
				'multiple'   => true,
				'js_options' => array(
					'width' => 'auto',
				),
				'tab'        => 'home_search',
			),
			array(
				'name' => esc_html__( 'Limit Found Listings', 'lisner-core' ),
				'id'   => 'home_search_limit_listings',
				'type' => 'number',
				'desc' => esc_html__( 'Limit number of found listings when searching using keyword', 'lisner-core' ),
				'tab'  => 'home_search',
			),
			// home search options
			array(
				'name'       => esc_html__( 'Choose Featured Categories', 'lisner-core' ),
				'id'         => 'home_search_featured_taxonomies',
				'type'       => 'taxonomy_advanced',
				'field_type' => 'select_advanced',
				'taxonomy'   => 'job_listing_category',
				'desc'       => esc_html__( 'Choose featured categories', 'lisner-core' ),
				'multiple'   => true,
				'js_options' => array(
					'width' => 'auto'
				),
				'tab'        => 'home_search',
			),
			array(
				'name'        => esc_html__( 'Category Field Placeholder', 'lisner-core' ),
				'id'          => 'home_search_taxonomies_placeholder',
				'type'        => 'text',
				'desc'        => esc_html__( 'Enter placeholder text for the category field', 'lisner-core' ),
				'placeholder' => esc_html__( 'car wash, burger, spas' ),
				'std'         => esc_html__( 'car wash, burger, spas', 'lisner-core' ),
				'tab'         => 'home_search',
			),
			array(
				'name'        => esc_html__( 'Location Field Placeholder', 'lisner-core' ),
				'id'          => 'home_search_location_placeholder',
				'type'        => 'text',
				'desc'        => esc_html__( 'Enter placeholder text for the location field', 'lisner-core' ),
				'std'         => esc_html__( 'Chicago, New York...', 'lisner-core' ),
				'placeholder' => esc_html__( 'Chicago, New York...' ),
				'tab'         => 'home_search',
			),

			// menu appearance
			array(
				'id'      => 'home-menu-active',
				'name'    => esc_html__( 'Override default menu', 'lisner-core' ),
				'type'    => 'select',
				'desc'    => esc_html__( 'Select whether you wish to override default menu settings set in theme options.', 'lisner-core' ),
				'options' => array(
					0 => esc_html__( 'No', 'lisner-core' ),
					1 => esc_html__( 'Yes', 'lisner-core' ),
				),
				'std'     => 1,
				'tab'     => 'home_menu',
			),
			array(
				'name'          => esc_html__( 'Menu Background Color', 'lisner-core' ),
				'id'            => 'color-menu-bg',
				'type'          => 'color',
				'desc'          => esc_html__( 'Please set menu background color', 'lisner-core' ),
				'alpha_channel' => true,
				'std'           => '#37003c',
				'js_options'    => array(
					'color'        => '#37003c',
					'defaultColor' => '#37003c',
					'palettes'     => false,
				),
				'tab'           => 'home_menu',
				'tooltip'       => array(
					'icon'     => 'info',
					'content'  => esc_html__( 'Used for sticky menu and for some header templates', 'lisner-core' ),
					'position' => 'top'
				),
				'hidden'        => array( 'home-menu-active', '!=', '1' )
			),
			array(
				'name'          => esc_html__( 'Menu Dropdown Background Color Hover', 'lisner-core' ),
				'id'            => 'color-menu-dropdown-hover-bg',
				'type'          => 'color',
				'desc'          => esc_html__( 'Please set dropdown menu background color', 'lisner-core' ),
				'alpha_channel' => true,
				'std'           => '#37003c',
				'js_options'    => array(
					'color'        => '#37003c',
					'defaultColor' => '#37003c',
					'palettes'     => false,
				),
				'tab'           => 'home_menu',
				'tooltip'       => array(
					'icon'     => 'info',
					'content'  => esc_html__( 'Used for dropdown menu', 'lisner-core' ),
					'position' => 'top'
				),
				'hidden'        => array( 'home-menu-active', '!=', '1' )
			),

			// home colors
			array(
				'id'               => 'site-logo',
				'name'             => esc_html__( 'Logo', 'lisner-core' ),
				'type'             => 'image_advanced',
				'desc'             => esc_html__( 'Choose the logo that will be displayed on this page only', 'lisner-core' ),
				'max_file_uploads' => 1,
				'tab'              => 'home_colors'
			),
			array(
				'name'          => esc_html__( 'Primary Color', 'lisner-core' ),
				'id'            => 'color-primary',
				'type'          => 'color',
				'desc'          => esc_html__( 'Please choose primary color of the theme', 'lisner-core' ),
				'alpha_channel' => true,
				'std'           => '#fe015b',
				'js_options'    => array(
					'color'        => '#fe015b',
					'defaultColor' => '#fe015b',
				),
				'tab'           => 'home_colors',
			),
			array(
				'name'          => esc_html__( 'Primary Color Font', 'lisner-core' ),
				'id'            => 'color-primary-font',
				'type'          => 'color',
				'desc'          => esc_html__( 'Please choose color of the fonts for the primary color', 'lisner-core' ),
				'alpha_channel' => true,
				'std'           => '#ffffff',
				'js_options'    => array(
					'color'        => '#ffffff',
					'defaultColor' => '#ffffff',
				),
				'tab'           => 'home_colors',
			),
			array(
				'name'          => esc_html__( 'Secondary Color', 'lisner-core' ),
				'id'            => 'color-secondary',
				'type'          => 'color',
				'desc'          => esc_html__( 'Please choose secondary color of the theme', 'lisner-core' ),
				'alpha_channel' => true,
				'std'           => '#07f0ff',
				'js_options'    => array(
					'color'        => '#07f0ff',
					'defaultColor' => '#07f0ff',
				),
				'tab'           => 'home_colors',
			),
			array(
				'name'          => esc_html__( 'Secondary Color Font', 'lisner-core' ),
				'id'            => 'color-secondary-font',
				'type'          => 'color',
				'desc'          => esc_html__( 'Please choose color of the fonts for the secondary color', 'lisner-core' ),
				'alpha_channel' => true,
				'std'           => '#3d0941',
				'js_options'    => array(
					'color'        => '#3d0941',
					'defaultColor' => '#3d0941',
				),
				'tab'           => 'home_colors',
			),
		);

		$meta_boxes[] = array(
			'title'     => esc_html__( 'Homepage Specific Settings', 'lisner-core' ),
			'pages'     => 'page',
			'fields'    => $home,
			'context'   => 'advanced',
			'include'   => array(
				'template' => array( 'templates/tpl-home.php' )
			),
			'tabs'      => array(
				'home_appearance' => array(
					'label' => esc_html__( 'Home/Hero Appearance', 'lisner-core' ),
					'icon'  => 'dashicons-arrow-right'
				),
				'home_bg'         => array(
					'label' => esc_html__( 'Hero Background', 'lisner-core' ),
					'icon'  => 'dashicons-arrow-right'
				),
				'home_search'     => array(
					'label' => esc_html__( 'Home Search Options', 'lisner-core' ),
					'icon'  => 'dashicons-arrow-right'
				),
				'home_menu'       => array(
					'label' => esc_html__( 'Home Menu Appearance', 'lisner-core' ),
					'icon'  => 'dashicons-arrow-right'
				),
				'home_colors'     => array(
					'label' => esc_html__( 'Home Colors & Logo', 'lisner-core' ),
					'icon'  => 'dashicons-arrow-right'
				),
			),
			'tab_style' => 'left',
		);

		return $meta_boxes;

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
		$appearance = array(
			array(
				'id'      => '_listing_template',
				'name'    => esc_html__( 'Listing Template', 'lisner-core' ),
				'type'    => 'image_select',
				'desc'    => esc_html__( 'Choose listing template that will be used for listing single page.', 'lisner-core' ),
				'options' => array(
					'1' => esc_url( LISNER_URL . 'assets/images/listing-single/1.png' ),
					'2' => esc_url( LISNER_URL . 'assets/images/listing-single/2.png' ),
					'3' => esc_url( LISNER_URL . 'assets/images/listing-single/3.png' ),
				),
				'std'     => '1',
				'tab'     => 'appearance'
			),
			array(
				'id'      => '_listing_sidebar_template',
				'name'    => esc_html__( 'Listing Sidebar Template', 'lisner-core' ),
				'type'    => 'image_select',
				'desc'    => esc_html__( 'Choose listing sidebar template that will be used for listing single page.', 'lisner-core' ),
				'options' => array(
					'1' => esc_url( LISNER_URL . 'assets/images/listing-single/4.png' ),
					'2' => esc_url( LISNER_URL . 'assets/images/listing-single/5.png' ),
				),
				'std'     => '1',
				'tab'     => 'appearance'
			),
			array(
				'id'      => '_listing_banner_template',
				'name'    => esc_html__( 'Listing Header Template', 'lisner-core' ),
				'type'    => 'image_select',
				'desc'    => esc_html__( 'Choose listing header template that will be used for listing single page', 'lisner-core' ),
				'options' => array(
					'image' => esc_url( LISNER_URL . 'assets/images/listing-single/6.png' ),
					'video' => esc_url( LISNER_URL . 'assets/images/listing-single/7.png' ),
				),
				'std'     => 'image',
				'tab'     => 'appearance',
				'hidden'  => array( '_listing_template', '=', '3' )
			),
		);

		// Listing Settings/General
		$general = array(
			array(
				'id'      => '_featured',
				'name'    => esc_html__( 'Listing Featured', 'lisner-core' ),
				'type'    => 'select',
				'options' => array(
					0 => esc_html__( 'Not Featured', 'lisner-core' ),
					1 => esc_html__( 'Featured', 'lisner-core' ),
				),
				'desc'    => esc_html__( 'Please enter listing email address.', 'lisner-core' ),
				'tab'     => 'general',
			),
			array(
				'id'      => '_listing_email',
				'name'    => esc_html__( 'Listing Email', 'lisner-core' ),
				'type'    => 'text',
				'desc'    => esc_html__( 'Please enter listing email address.', 'lisner-core' ),
				'tab'     => 'general',
				'tooltip' => array(
					'icon'     => 'info',
					'content'  => esc_html__( 'If the field is empty then listing owner email address will be used instead', 'lisner-core' ),
					'position' => 'right'
				)
			),
			array(
				'id'   => '_listing_website',
				'name' => esc_html__( 'Listing Website Address', 'lisner-core' ),
				'type' => 'text',
				'desc' => esc_html__( 'Please enter link to the listing website address', 'lisner-core' ),
				'tab'  => 'general'
			),
			array(
				'id'   => '_listing_phone',
				'name' => esc_html__( 'Listing Phone', 'lisner-core' ),
				'type' => 'text',
				'desc' => esc_html__( 'Please enter listing phone number.', 'lisner-core' ),
				'tab'  => 'general',
			),
			array(
				'id'          => '_job_author',
				'name'        => esc_html__( 'Listing Owner', 'lisner-core' ),
				'type'        => 'user',
				'field_type'  => 'select_advanced',
				'placeholder' => esc_html__( 'Select an author', 'lisner-core' ),
				'desc'        => esc_html__( 'Please choose owner of the listing', 'lisner-core' ),
				'js_options'  => array(
					'allowClear' => false
				),
				'tab'         => 'general'
			),
			array(
				'id'         => '_job_duration',
				'name'       => esc_html__( 'Listing Expiration Date', 'lisner-core' ),
				'type'       => 'date',
				'desc'       => esc_html__( 'Set listing expiration date', 'lisner-core' ),
				'js_options' => array(
					'dateFormat' => 'M d, yy'
				),
				'tab'        => 'general'
			),
		);

		// Listing Settings/Media
		$media = array(
			array(
				'id'               => '_listing_gallery',
				'name'             => esc_html__( 'Listing Gallery', 'lisner-core' ),
				'type'             => 'image_advanced',
				'desc'             => esc_html__( 'Upload images for the listing gallery', 'lisner-core' ),
				'max_file_uploads' => 16, //todo: add option for this
				'max_status'       => 'true',
				'tab'              => 'media'
			),
			array(
				'id'               => '_listing_cover',
				'name'             => esc_html__( 'Listing Banner Image', 'lisner-core' ),
				'type'             => 'image_advanced',
				'desc'             => esc_html__( 'Please enter link to featured video', 'lisner-core' ),
				'max_file_uploads' => 1,
				'max_status'       => 'true',
				'multiple'         => false,
				'tab'              => 'media',
				'tooltip'          => array(
					'icon'     => 'info',
					'content'  => esc_html__( 'Will be displayed only if single image is set as Listing Header Template in Listing Appearance section', 'lisner-core' ),
					'position' => 'right'
				)
			),
			array(
				'id'   => '_listing_video',
				'name' => esc_html__( 'Listing Video', 'lisner-core' ),
				'type' => 'oembed',
				'desc' => esc_html__( 'Please enter link to featured video', 'lisner-core' ),
				'tab'  => 'media'
			),
			array(
				'id'               => '_listing_files',
				'name'             => esc_html__( 'Listing Files', 'lisner-core' ),
				'type'             => 'file_advanced',
				'desc'             => esc_html__( 'Upload additional files for the listing', 'lisner-core' ),
				'max_file_uploads' => 16,
				'max_status'       => 'true',
				'tab'              => 'media'
			),
		);

		// Listing Settings/Location
		$location = array(
			array(
				'id'   => '_job_location',
				'name' => esc_html__( 'Listing Address', 'lisner-core' ),
				'type' => 'text',
				'desc' => esc_html__( 'Please type address of the location', 'lisner-core' ),
				'tab'  => 'location'
			),
			array(
				'id'            => '_job_location_map',
				'name'          => esc_html__( 'Listing Location', 'lisner-core' ),
				'type'          => 'map',
				'desc'          => esc_html__( 'Please type address of the location', 'lisner-core' ),
				'std'           => '',
				'language'      => 'en',
				'address_field' => '_job_location',
				'api_key'       => isset( $option['map-google-api'] ) ? $option['map-google-api'] : '',
				'tab'           => 'location'
			)
		);

		// Listing Settings/Pricing
		$pricing = array(
			array(
				'id'      => '_listing_pricing_range',
				'name'    => esc_html__( 'Listing Pricing Range', 'lisner-core' ),
				'type'    => 'select',
				'options' => array(
					'none'      => esc_html__( 'Unknown', 'lisner-core' ),
					'cheap'     => esc_html__( '$ - Cheap', 'lisner-core' ),
					'moderate'  => esc_html__( '$$ -Moderate', 'lisner-core' ),
					'expensive' => esc_html__( '$$$ - Extensive', 'lisner-core' ),
					'ultra'     => esc_html__( '$$$$ - Ultra High', 'lisner-core' ),
				),
				'desc'    => esc_html__( 'Please choose listing pricing range', 'lisner-core' ),
				'tab'     => 'pricing'
			),
			array(
				'id'   => '_listing_pricing_from',
				'name' => esc_html__( 'Listing Price From', 'lisner-core' ),
				'type' => 'number',
				'desc' => esc_html__( 'Please enter lowest price', 'lisner-core' ),
				'min'  => 0,
				'tab'  => 'pricing'
			),
			array(
				'id'   => '_listing_pricing_to',
				'name' => esc_html__( 'Listing Price To', 'lisner-core' ),
				'type' => 'number',
				'desc' => esc_html__( 'Please enter highest price', 'lisner-core' ),
				'min'  => 0,
				'tab'  => 'pricing'
			),
			array(
				'id'      => '_listing_pricing_currency',
				'name'    => esc_html__( 'Listing Price Currency', 'lisner-core' ),
				'type'    => 'text',
				'desc'    => esc_html__( 'Please enter currency symbol', 'lisner-core' ),
				'tab'     => 'pricing',
				'tooltip' => array(
					'icon'     => 'info',
					'content'  => esc_html__( 'Use this to override pricing symbol set in the theme options for this listing', 'lisner-core' ),
					'position' => 'right'
				)
			),
		);

		// Listing Settings/Social
		$social = array(
			array(
				'id'   => '_listing_social__facebook',
				'name' => esc_html__( 'Listing Social / Facebook', 'lisner-core' ),
				'type' => 'text',
				'desc' => esc_html__( 'Please enter listing facebook link', 'lisner-core' ),
				'tab'  => 'social'
			),
			array(
				'id'   => '_listing_social__twitter',
				'name' => esc_html__( 'Listing Social / Twitter', 'lisner-core' ),
				'type' => 'text',
				'desc' => esc_html__( 'Please enter listing twitter link', 'lisner-core' ),
				'tab'  => 'social'
			),
			array(
				'id'   => '_listing_social__google',
				'name' => esc_html__( 'Listing Social / Google+', 'lisner-core' ),
				'type' => 'text',
				'desc' => esc_html__( 'Please enter listing google+ link', 'lisner-core' ),
				'tab'  => 'social'
			),
			array(
				'id'   => '_listing_social__instagram',
				'name' => esc_html__( 'Listing Social / Instagram', 'lisner-core' ),
				'type' => 'text',
				'desc' => esc_html__( 'Please enter listing instagram link', 'lisner-core' ),
				'tab'  => 'social'
			),
			array(
				'id'   => '_listing_social__youtube',
				'name' => esc_html__( 'Listing Social / YouTube', 'lisner-core' ),
				'type' => 'text',
				'desc' => esc_html__( 'Please enter listing youtube link', 'lisner-core' ),
				'tab'  => 'social'
			),
			array(
				'id'   => '_listing_social__pinterest',
				'name' => esc_html__( 'Listing Social / Pinterest', 'lisner-core' ),
				'type' => 'text',
				'desc' => esc_html__( 'Please enter listing pinterest link', 'lisner-core' ),
				'tab'  => 'social'
			),
			array(
				'id'   => '_listing_social__linkedin',
				'name' => esc_html__( 'Listing Social / Linkedin', 'lisner-core' ),
				'type' => 'text',
				'desc' => esc_html__( 'Please enter listing linkedin link', 'lisner-core' ),
				'tab'  => 'social'
			),
		);

		// Meta / Likes
		$likes = array(
			array(
				'id'   => 'listing_views',
				'name' => esc_html__( 'Listing Views', 'lisner-core' ),
				'type' => 'number',
				'min'  => '0',
				'desc' => esc_html__( 'Number of listing views', 'lisner-core' ),
				'tab'  => 'likes'
			),
			array(
				'id'   => 'listing_likes',
				'name' => esc_html__( 'Listing Likes', 'lisner-core' ),
				'type' => 'number',
				'min'  => '0',
				'desc' => esc_html__( 'Number of listing likes', 'lisner-core' ),
				'tab'  => 'likes'
			),
			array(
				'id'   => 'listing_likes_ip',
				'name' => esc_html__( 'Listing Likes IP\'s', 'lisner-core' ),
				'type' => 'textarea',
				'desc' => esc_html__( 'IP addresses that liked the listing', 'lisner-core' ),
				'tab'  => 'likes'
			),
		);

		// Call working hours function
		$working_hours = $this->working_hours();

		$fields       = array_merge( $appearance, $general, $media, $location, $working_hours, $pricing, $social, $likes );
		$meta_boxes[] = array(
			'title'      => esc_html__( 'Listing Data', 'lisner-core' ),
			'pages'      => 'job_listing',
			'fields'     => $fields,
			'priority'   => 'high',
			'tabs'       => array(
				'general'       => array(
					'label' => esc_html__( 'General Information', 'lisner-core' ),
					'icon'  => 'dashicons-editor-alignleft'
				),
				'media'         => array(
					'label' => esc_html__( 'Listing Media', 'lisner-core' ),
					'icon'  => 'dashicons-admin-media'
				),
				'location'      => array(
					'label' => esc_html__( 'Location', 'lisner-core' ),
					'icon'  => 'dashicons-location'
				),
				'working-hours' => array(
					'label' => esc_html__( 'Working Hours', 'lisner-core' ),
					'icon'  => 'dashicons-clock'
				),
				'social'        => array(
					'label' => esc_html__( 'Social Settings', 'lisner-core' ),
					'icon'  => 'dashicons-share-alt'
				),
				'pricing'       => array(
					'label' => esc_html__( 'Pricing Range', 'lisner-core' ),
					'icon'  => 'dashicons-chart-area'
				),
				'appearance'    => array(
					'label' => esc_html__( 'Listing Appearance', 'lisner-core' ),
					'icon'  => 'dashicons-welcome-widgets-menus'
				),
				'likes'         => array(
					'label' => esc_html__( 'Listing Likes & Views', 'lisner-core' ),
					'icon'  => 'dashicons-image-filter'
				),
			),
			'tab_style'  => 'left',
			'validation' => array(
				'rules' => array(
					'_listing_email'   => array(
						'email' => true
					),
					'_listing_website' => array(
						'url' => true
					),
				)
			)
		);

		$booking_fields = array(
			array(
				'id'          => '_listing_products',
				'name'        => esc_html__( 'Listing Products', 'lisner-core' ),
				'type'        => 'post',
				'field_type'  => 'select_advanced',
				'multiple'    => false,
				'query_args'  => array(
					'post_type' => 'product',
					'tax_query' => array(
						array(
							'taxonomy' => 'product_type',
							'field'    => 'slug',
							'terms'    => array( 'booking', 'accommodation-booking' ),
							'operator' => 'IN'
						)
					)
				),
				'desc'        => esc_html__( 'Choose booking products that this listing is associated with', 'lisner-core' ),
				'placeholder' => esc_html__( 'Select a product', 'lisner-core' )
			),
		);

		// Job Listing meta boxes
		if ( class_exists( 'WC_Bookings' ) ) {
			$meta_boxes[] = array(
				'title'    => esc_html__( 'Listing Additional', 'lisner-core' ),
				'pages'    => 'job_listing',
				'fields'   => $booking_fields,
				'context'  => 'side',
				'priority' => 'high'
			);
		}

		// Default Posts meta boxes
		$post_fields  = array_merge( $likes );
		$meta_boxes[] = array(
			'title'   => esc_html__( 'Post Additional', 'lisner-core' ),
			'pages'   => 'post',
			'fields'  => $post_fields,
			'context' => 'advanced',
		);

		return apply_filters( 'lisner__form_meta_fields', $meta_boxes );
	}

	/**
	 * Create working hours meta field
	 * @return array
	 */
	public function working_hours() {
		$tab           = 'working-hours';
		$working_hours = array(
			array(
				'id'      => '_listing_working_hours',
				'name'    => esc_html__( 'Listing Working Hours', 'lisner-core' ),
				'type'    => 'button_group',
				'desc'    => esc_html__( 'Please set working hours of listing', 'lisner-core' ),
				'options' => array(
					'monday'    => esc_html__( 'Monday', 'lisner-core' ),
					'tuesday'   => esc_html__( 'Tuesday', 'lisner-core' ),
					'wednesday' => esc_html__( 'Wednesday', 'lisner-core' ),
					'thursday'  => esc_html__( 'Thursday', 'lisner-core' ),
					'friday'    => esc_html__( 'Friday', 'lisner-core' ),
					'saturday'  => esc_html__( 'Saturday', 'lisner-core' ),
					'sunday'    => esc_html__( 'Sunday', 'lisner-core' ),
				),
				'inline'  => true,
				'tab'     => $tab
			),
		);
		$name          = '';
		$title         = '';
		for ( $i = 1; $i <= 7; $i ++ ) {
			switch ( $i ) {
				case $i == 1:
					$name  = esc_html( 'monday' );
					$title = esc_html__( 'Monday', 'lisner-core' );
					break;
				case $i == 2:
					$name  = esc_html( 'tuesday' );
					$title = esc_html__( 'Tuesday', 'lisner-core' );
					break;
				case $i == 3:
					$name  = esc_html( 'wednesday' );
					$title = esc_html__( 'Wednesday', 'lisner-core' );
					break;
				case $i == 4:
					$name  = esc_html( 'thursday' );
					$title = esc_html__( 'Thursday', 'lisner-core' );
					break;
				case $i == 5:
					$name  = esc_html( 'friday' );
					$title = esc_html__( 'Friday', 'lisner-core' );
					break;
				case $i == 6:
					$name  = esc_html( 'saturday' );
					$title = esc_html__( 'Saturday', 'lisner-core' );
					break;
				case $i == 7:
					$name  = esc_html( 'sunday' );
					$title = esc_html__( 'Sunday', 'lisner-core' );
					break;
				default;
			}
			$daily_hours   = array(
				array(
					'id'      => "_listing_{$name}_hours_radio",
					'name'    => esc_html__( 'Listing Working Hours Options', 'lisner-core' ),
					'type'    => 'radio',
					'desc'    => esc_html__( 'Please set working hours of listing', 'lisner-core' ),
					'options' => array(
						'custom'      => esc_html__( 'Enter Times', 'lisner-core' ),
						'open'        => esc_html__( 'Open All Day', 'lisner-core' ),
						'closed'      => esc_html__( 'Closed All Day', 'lisner-core' ),
						'appointment' => esc_html__( 'By Appointment Only', 'lisner-core' ),
					),
					'std'     => 'custom',
					'inline'  => true,
					'tab'     => $tab,
					'visible' => array( '_listing_working_hours', '=', $name ),
				),
				array(
					'id'      => "_listing_{$name}_hours_open",
					'name'    => sprintf( esc_html__( '%s Opening Hours', 'lisner-core' ), $title ),
					'type'    => 'time',
					'desc'    => sprintf( esc_html__( 'Please enter your %s opening hours. Leave empty if non working day.', 'lisner-core' ), $title ),
					'tab'     => $tab,
					'visible' => array(
						array( '_listing_working_hours', '=', $name ),
						array( "_listing_{$name}_hours_radio", '=', 'custom' )
					),
					'clone'   => true
				),
				array(
					'id'      => "_listing_{$name}_hours_close",
					'name'    => sprintf( esc_html__( '%s Closing Hours', 'lisner-core' ), $title ),
					'type'    => 'time',
					'desc'    => sprintf( esc_html__( 'Please enter your %s closing hours. Leave empty if non working day.', 'lisner-core' ), $title ),
					'tab'     => $tab,
					'visible' => array(
						array( '_listing_working_hours', '=', $name ),
						array( "_listing_{$name}_hours_radio", '=', 'custom' )
					),
					'clone'   => true
				),
			);
			$working_hours = array_merge( $working_hours, $daily_hours );
		}

		return $working_hours;
	}

}

/** Instantiate class
 *
 * @return null|lisner_meta
 */
function lisner_meta() {
	return lisner_meta::instance();
}
