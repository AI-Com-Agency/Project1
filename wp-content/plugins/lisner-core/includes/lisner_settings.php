<?php

/**
 * Class lisner_settings
 */
class lisner_settings {

	protected static $_instance = null;

	/**
	 * @return null|lisner_settings
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * lisner_settings constructor.
	 */
	function __construct() {
		add_filter( 'mb_settings_pages', array( $this, 'settings_pages' ), 11 );
		add_filter( 'rwmb_meta_boxes', array( $this, 'meta_boxes' ), 12 );
		// update woocommerce pages too
		add_action( 'rwmb_page_terms_after_save_field', array( $this, 'set_terms_page_template' ), 10, 5 );
		add_action( 'mb_settings_page_submit_buttons', array( $this, 'export_button' ) );
		add_action( 'mb_settings_page_submit_buttons', array( $this, 'export_field' ) );
	}

	public function export_button( $args ) {
		$permalink = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$args      = '<a href="' . esc_url( add_query_arg( array( 'action' => 'export' ), $permalink ) ) . '" class="button">' . esc_html__( 'Export Settings', 'lisner-core' ) . '</a>';

		echo $args;
	}

	public function export_field( $args ) {
		if ( isset( $_REQUEST['action'] ) && 'export' == $_REQUEST['action'] ) {
			$data = $this->export_settings();
			$args = '<div class="export-settings-field"><textarea class="cd-import" rows="20" cols="100">' . json_encode( $data ) . '</textarea></div>';

			echo $args;
		}
	}

	public function export_settings() {
		global $wpdb;
		$data = $wpdb->get_col( "SELECT option_value FROM $wpdb->options WHERE option_name='pbs_option'" );

		return $data;
	}

	/**
	 * Register theme settings
	 *
	 * @param $settings_pages
	 *
	 * @return array
	 */
	public function settings_pages( $settings_pages ) {
		$option_name = 'pbs_option';
		$option      = get_option( 'pbs_option' );

		// Settings Page General
		$settings_pages[] = array(
			'id'            => 'lisner-options',
			'menu_title'    => esc_html__( 'Lisner Options', 'lisner-core' ),
			'option_name'   => $option_name,
			'icon_url'      => LISNER_URL . 'assets/images/logo_xs.png',
			'position'      => 30,
			'style'         => 'no-boxes',
			'submenu_title' => esc_html__( 'Theme Setup', 'lisner-core' )
		);

		$settings_pages[] = array(
			'id'          => 'lisner-options-pages',
			'menu_title'  => esc_html__( 'Pages', 'lisner-core' ),
			'option_name' => $option_name,
			'icon_url'    => 'dashicons-images-alt',
			'style'       => 'no-boxes',
			'parent'      => 'lisner-options'
		);

		$settings_pages[] = array(
			'id'          => 'lisner-options-fallbacks',
			'menu_title'  => esc_html__( 'Fallbacks', 'lisner-core' ),
			'option_name' => $option_name,
			'icon_url'    => 'dashicons-images-alt',
			'style'       => 'no-boxes',
			'parent'      => 'lisner-options'
		);

		$settings_pages[] = array(
			'id'          => 'lisner-options-maintenance',
			'menu_title'  => esc_html__( 'Maintenance Mode', 'lisner-core' ),
			'option_name' => $option_name,
			'icon_url'    => 'dashicons-images-alt',
			'style'       => 'no-boxes',
			'parent'      => 'lisner-options'
		);

		$settings_pages[] = array(
			'id'          => 'lisner-options-listings',
			'menu_title'  => esc_html__( 'Listings', 'lisner-core' ),
			'option_name' => $option_name,
			'icon_url'    => 'dashicons-images-alt',
			'style'       => 'no-boxes',
			'parent'      => 'lisner-options'
		);

		$settings_pages[] = array(
			'id'          => 'lisner-options-listing-fields',
			'menu_title'  => esc_html__( 'Listing Fields', 'lisner-core' ),
			'option_name' => $option_name,
			'icon_url'    => 'dashicons-images-alt',
			'style'       => 'no-boxes',
			'parent'      => 'lisner-options'
		);

		$settings_pages[] = array(
			'id'          => 'lisner-options-listing-statistics',
			'menu_title'  => esc_html__( 'Listing Statistics', 'lisner-core' ),
			'option_name' => $option_name,
			'icon_url'    => 'dashicons-images-alt',
			'style'       => 'no-boxes',
			'parent'      => 'lisner-options'
		);

		$settings_pages[] = array(
			'id'          => 'lisner-options-appearance',
			'menu_title'  => esc_html__( 'Appearance', 'lisner-core' ),
			'option_name' => $option_name,
			'icon_url'    => 'dashicons-images-alt',
			'style'       => 'no-boxes',
			'parent'      => 'lisner-options'
		);

		if ( class_exists( 'Pebas_Bookings' ) ) {
			$settings_pages[] = array(
				'id'          => 'lisner-options-bookings',
				'menu_title'  => esc_html__( 'Bookings', 'lisner-core' ),
				'option_name' => $option_name,
				'icon_url'    => 'dashicons-images-alt',
				'style'       => 'no-boxes',
				'parent'      => 'lisner-options'
			);
		}

		return $settings_pages;
	}

	public function meta_boxes( $meta_boxes ) {

		$ipapi_link  = 'https://ipapi.com/product';
		$ipinfo_link = 'https://ipinfo.io/pricing';
		$auth        = array(
			// general
			array(
				'id'      => 'is-demo',
				'name'    => esc_html__( 'Is Demo?', 'lisner-core' ),
				'type'    => 'select',
				'desc'    => esc_html__( 'Turn on Demo moddel', 'lisner-core' ),
				'options' => array(
					0 => esc_html__( 'No', 'lisner-core' ),
					1 => esc_html__( 'Yes', 'lisner-core' ),
				),
				'std'     => 0,
				'value'   => 0,
				'tab'     => 'setup_general',
			),
			array(
				'id'      => 'general-disable-add-listing-button',
				'name'    => esc_html__( 'Disable Add Listing Button From Menu', 'lisner-core' ),
				'type'    => 'select',
				'desc'    => esc_html__( 'Choose whether you wish to disable add listing button from menu navigation', 'lisner-core' ),
				'options' => array(
					0 => esc_html__( 'Enable', 'lisner-core' ),
					1 => esc_html__( 'Disable', 'lisner-core' ),
				),
				'std'     => 0,
				'value'   => 0,
				'tab'     => 'setup_general',
			),
			array(
				'id'      => 'general-hide-cart-icon',
				'name'    => esc_html__( 'Hide Cart Icon?', 'lisner-core' ),
				'type'    => 'select',
				'desc'    => esc_html__( 'Choose whether you wish to hide cart icon from menu.', 'lisner-core' ),
				'options' => array(
					1 => esc_html__( 'Show', 'lisner-core' ),
					0 => esc_html__( 'Hide', 'lisner-core' ),
				),
				'std'     => 1,
				'value'   => 1,
				'tab'     => 'setup_general',
			),
			array(
				'id'         => 'general-taxonomy-page',
				'name'       => esc_html__( 'Choose Taxonomy Linked Page', 'lisner-core' ),
				'type'       => 'select_advanced',
				'options'    => array(
					'search'  => esc_html__( 'Search Page', 'lisner-core' ),
					'default' => esc_html__( 'Default Taxonomy Page', 'lisner-core' ),
				),
				'desc'       => esc_html__( 'Choose whether taxonomies should lead to search page or their taxonomy page.', 'lisner-core' ),
				'std'        => 'search',
				'value'      => 'search',
				'js_options' => array(
					'allowClear' => false
				),
				'tab'        => 'setup_general',
				'tooltip'    => array(
					'icon'     => 'info',
					'content'  => esc_html__( 'When user clicks on a term he should be lead to search page or default taxonomy page.', 'lisner-core' ),
					'position' => 'top'
				),
			),
			// Locations search
			array(
				'type' => 'heading',
				'name' => esc_html__( 'Locations Search Functionality', 'lisner-core' ),
				'desc' => esc_html__( 'Set the main location search field functionality', 'lisner-core' ),
				'tab'  => 'setup_general',
			),
			array(
				'id'         => 'general-location-search',
				'name'       => esc_html__( 'Choose Location Search Base', 'lisner-core' ),
				'type'       => 'select_advanced',
				'options'    => array(
					'google' => esc_html__( 'Google Autocomplete', 'lisner-core' ),
					'custom' => esc_html__( 'Predefined Locations', 'lisner-core' ),
				),
				'desc'       => esc_html__( 'Choose what you wish to be base of the location search field on homepage and in the header of the inner pages.', 'lisner-core' ),
				'std'        => 'google',
				'value'      => 'google',
				'js_options' => array(
					'allowClear' => false
				),
				'tab'        => 'setup_general',
				'tooltip'    => array(
					'icon'     => 'info',
					'content'  => esc_html__( 'Main location search field can pull data from Google API or from predefined list of locations available on your site.', 'lisner-core' ),
					'position' => 'top'
				),
			),
			array(
				'id'         => 'general-location-autocomplete-return',
				'name'       => esc_html__( 'Google Autocomplete Address Formatting', 'lisner-core' ),
				'type'       => 'select_advanced',
				'multiple'   => false,
				'desc'       => esc_html__( 'Choose the format you wish google autocomplete to return', 'lisner-core' ),
				'js_options' => array(
					'allowClear' => false
				),
				'options'    => array(
					'full' => esc_html__( 'Full Format', 'lisner-core' ),
					'city' => esc_html__( 'City Only', 'lisner-core' ),
				),
				'tab'        => 'setup_general',
				'hidden'     => array( 'general-location-search', '!=', 'google' )
			),
			array(
				'id'         => 'general-location-search-taxonomies',
				'name'       => esc_html__( 'Choose Predefined Locations', 'lisner-core' ),
				'type'       => 'taxonomy_advanced',
				'field_type' => 'select_advanced',
				'taxonomy'   => 'listing_location',
				'multiple'   => true,
				'desc'       => esc_html__( 'Choose predefined location that are allowed for search or leave empty to allow all.', 'lisner-core' ),
				'js_options' => array(
					'allowClear' => true
				),
				'tab'        => 'setup_general',
				'hidden'     => array( 'general-location-search', '!=', 'custom' )
			),
			array(
				'id'         => 'general-location-search-hide-empty',
				'name'       => esc_html__( 'Hide Empty Locations?', 'lisner-core' ),
				'type'       => 'select_advanced',
				'multiple'   => false,
				'desc'       => esc_html__( 'Choose whether you wish to hide empty locations from displaying', 'lisner-core' ),
				'js_options' => array(
					'allowClear' => false
				),
				'options'    => array(
					1 => esc_html__( 'Hide Empty', 'lisner-core' ),
					0 => esc_html__( 'Display Empty', 'lisner-core' ),
				),
				'tab'        => 'setup_general',
				'hidden'     => array( 'general-location-search', '!=', 'custom' )
			),
			// Categories search
			array(
				'type' => 'heading',
				'name' => esc_html__( 'Categories Search Functionality', 'lisner-core' ),
				'desc' => esc_html__( 'Set the main category field functionality', 'lisner-core' ),
				'tab'  => 'setup_general',
			),
			array(
				'id'         => 'general-category-search',
				'name'       => esc_html__( 'Choose Location Search Base', 'lisner-core' ),
				'type'       => 'select_advanced',
				'options'    => array(
					'keyword'    => esc_html__( 'Keyword', 'lisner-core' ),
					'predefined' => esc_html__( 'Predefined Categories', 'lisner-core' ),
				),
				'desc'       => esc_html__( 'Choose what do you wish to be main category field search functionality', 'lisner-core' ),
				'std'        => 'keyword',
				'value'      => 'keyword',
				'js_options' => array(
					'allowClear' => false
				),
				'tab'        => 'setup_general',
				'tooltip'    => array(
					'icon'     => 'info',
					'content'  => esc_html__( 'Choose whether you wish your users to search by keyword or by predefined list of categories', 'lisner-core' ),
					'position' => 'top'
				),
			),
			array(
				'id'         => 'general-products-search',
				'name'       => esc_html__( 'Include Products In Main Search', 'lisner-core' ),
				'type'       => 'select_advanced',
				'desc'       => esc_html__( 'Choose whether you wish to include WooCommerce products to main search by keyword', 'lisner-core' ),
				'options'    => array(
					'no'  => esc_html__( 'No', 'lisner-core' ),
					'yes' => esc_html__( 'Yes', 'lisner-core' ),
				),
				'js_options' => array(
					'allowClear' => false
				),
				'std'        => 'no',
				'tab'        => 'setup_general',
				'hidden'     => array( 'general-category-search', '!=', 'keyword' )
			),
			array(
				'id'         => 'general-category-search-terms',
				'name'       => esc_html__( 'Choose Predefined Categories', 'lisner-core' ),
				'type'       => 'taxonomy_advanced',
				'field_type' => 'select_advanced',
				'taxonomy'   => 'job_listing_category',
				'multiple'   => true,
				'desc'       => esc_html__( 'Choose predefined categories that are allowed for search or leave empty to allow all.', 'lisner-core' ),
				'js_options' => array(
					'allowClear' => true
				),
				'tab'        => 'setup_general',
				'hidden'     => array( 'general-category-search', '!=', 'predefined' )
			),
			array(
				'id'         => 'general-category-search-hide-empty',
				'name'       => esc_html__( 'Hide Empty Categories?', 'lisner-core' ),
				'type'       => 'select_advanced',
				'multiple'   => false,
				'desc'       => esc_html__( 'Choose whether you wish to hide empty categories from displaying', 'lisner-core' ),
				'js_options' => array(
					'allowClear' => false
				),
				'options'    => array(
					1 => esc_html__( 'Hide Empty', 'lisner-core' ),
					0 => esc_html__( 'Display Empty', 'lisner-core' ),
				),
				'tab'        => 'setup_general',
				'hidden'     => array( 'general-category-search', '!=', 'predefined' )
			),
			// menu
			array(
				'id'         => 'auth-generate-username',
				'name'       => esc_html__( 'Auto Generate Username', 'lisner-core' ),
				'type'       => 'select_advanced',
				'desc'       => esc_html__( 'Generate username from email when registering user', 'lisner-core' ),
				'options'    => array(
					'no'  => esc_html__( 'No', 'lisner-core' ),
					'yes' => esc_html__( 'Yes', 'lisner-core' ),
				),
				'js_options' => array(
					'allowClear' => false
				),
				'std'        => 'no',
				'tab'        => 'setup_authentication',
			),
			array(
				'id'         => 'auth-generate-password',
				'name'       => esc_html__( 'Auto Generate Password', 'lisner-core' ),
				'type'       => 'select_advanced',
				'desc'       => esc_html__( 'Automatically generate password when registering user', 'lisner-core' ),
				'options'    => array(
					'no'  => esc_html__( 'No', 'lisner-core' ),
					'yes' => esc_html__( 'Yes', 'lisner-core' ),
				),
				'js_options' => array(
					'allowClear' => false
				),
				'std'        => 'no',
				'tab'        => 'setup_authentication'
			),
			// copyrights
			array(
				'id'    => 'copyrights-text',
				'name'  => esc_html__( 'Copyrights Section Text', 'lisner-core' ),
				'type'  => 'textarea',
				'desc'  => esc_html__( 'Enter copyrights text for copyrights section.', 'lisner-core' ),
				'std'   => 'Made with<span style="font-size:12px">❤</span> by <a href="https://pebas.rs" target="_blank"><strong>pebas</strong></a> - All rights reserved',
				'value' => 'Made with<span style="font-size:12px">❤</span> by <a href="https://pebas.rs" target="_blank"><strong>pebas</strong></a> - All rights reserved',
				'tab'   => 'setup_copyrights'
			),
			// listing sharing
			array(
				'id'       => 'share-posts',
				'name'     => esc_html__( 'Choose Share Options', 'lisner-core' ),
				'type'     => 'select_advanced',
				'desc'     => esc_html__( 'Please Choose your share options for listings and posts', 'lisner-core' ),
				'options'  => array(
					'facebook'  => esc_html__( 'Facebook', 'lisner-core' ),
					'google'    => esc_html__( 'Google+', 'lisner-core' ),
					'twitter'   => esc_html__( 'Twitter', 'lisner-core' ),
					'pinterest' => esc_html__( 'Pinterest', 'lisner-core' ),
					'linkedin'  => esc_html__( 'Linkedin', 'lisner-core' ),
					'reddit'    => esc_html__( 'Reddit', 'lisner-core' ),
					//'print'     => esc_html__( 'Print', 'lisner-core' ),
				),
				'multiple' => true,
				'tab'      => 'setup_share'
			),
			// units
			array(
				'id'         => 'units-clock',
				'name'       => esc_html__( 'Clock Time Format', 'lisner-core' ),
				'type'       => 'select_advanced',
				'desc'       => esc_html__( 'Please choose clock time format to display in places where it is used', 'lisner-core' ),
				'options'    => array(
					'24'    => esc_html__( '24 hour', 'lisner-core' ),
					'am_pm' => esc_html__( 'Am/Pm', 'lisner-core' ),
				),
				'js_options' => array(
					'allowClear' => false
				),
				'std'        => '24',
				'tab'        => 'setup_units'
			),
			array(
				'id'         => 'units-distance',
				'name'       => esc_html__( 'Distance Units', 'lisner-core' ),
				'type'       => 'select_advanced',
				'desc'       => esc_html__( 'Please choose distance units measure for map radius etc.', 'lisner-core' ),
				'options'    => array(
					'km' => esc_html__( 'Kilometers', 'lisner-core' ),
					'mi' => esc_html__( 'Miles', 'lisner-core' ),
				),
				'js_options' => array(
					'allowClear' => false
				),
				'std'        => 'km',
				'tab'        => 'setup_units'
			),

			// map
			array(
				'id'      => 'map-google-api',
				'name'    => esc_html__( 'Google API key', 'lisner-core' ),
				'type'    => 'text',
				'desc'    => esc_html__( 'Enter your google api key for the proper working of the map and search.', 'lisner-core' ),
				'tooltip' => array(
					'icon'     => 'info',
					'content'  => esc_html__( 'If the field is empty then listing owner email address will be used instead', 'lisner-core' ),
					'position' => 'right'
				),
				'tab'     => 'setup_map'
			),
			array(
				'id'   => 'map-style-id',
				'name' => esc_html__( 'Map Style ID', 'lisner-core' ),
				'type' => 'text',
				'desc' => esc_html__( 'Enter MapBox style ID', 'lisner-core' ),
				'std'  => '',
				'tab'  => 'setup_map'
			),
			array(
				'id'   => 'map-style-api',
				'name' => esc_html__( 'MapBox Style API', 'lisner-core' ),
				'type' => 'text',
				'desc' => esc_html__( 'Enter MapBox style api key', 'lisner-core' ),
				'std'  => '',
				'tab'  => 'setup_map'
			),
			array(
				'id'   => 'map-mapbox-username',
				'name' => esc_html__( 'MapBox Username', 'lisner-core' ),
				'type' => 'text',
				'desc' => esc_html__( 'Enter MapBox username', 'lisner-core' ),
				'std'  => '',
				'tab'  => 'setup_map'
			),
			array(
				'id'       => 'map-country-restriction',
				'name'     => esc_html__( 'Country Restriction', 'lisner-core' ),
				'type'     => 'select_advanced',
				'options'  => $this->get_country_codes(),
				'multiple' => true,
				'desc'     => esc_html__( 'Restrict map results to specific country only', 'lisner-core' ),
				'tooltip'  => array(
					'icon'     => 'info',
					'content'  => esc_html__( 'Choosing a single country from the dropdown list will make site specific to a single country only.', 'lisner-core' ),
					'position' => 'right'
				),
				'tab'      => 'setup_map'
			),
			array(
				'id'          => 'map-default-address',
				'name'        => esc_html__( 'Map Default Address', 'lisner-core' ),
				'type'        => 'text',
				'desc'        => esc_html__( 'Enter site default address that will be displayed when user is adding a listing.', 'lisner-core' ),
				'std'         => esc_html__( '5th Ave, New York, NY, USA' ),
				'placeholder' => esc_html__( '5th Ave, New York, NY, USA' ),
				'tab'         => 'setup_map'
			),
			array(
				'id'          => 'map-default-latitude',
				'name'        => esc_html__( 'Map Default Latitude', 'lisner-core' ),
				'type'        => 'text',
				'std'         => esc_html__( '40.7314123' ),
				'placeholder' => esc_html__( '40.7314123' ),
				'desc'        => esc_html__( 'Enter site default latitude value that will decide map marker position when user is adding a listing.', 'lisner-core' ),
				'tab'         => 'setup_map'
			),
			array(
				'id'          => 'map-default-longitude',
				'name'        => esc_html__( 'Map Default Longitude', 'lisner-core' ),
				'type'        => 'text',
				'std'         => esc_html__( '-73.9969848' ),
				'placeholder' => esc_html__( '-73.9969848' ),
				'desc'        => esc_html__( 'Enter site default longitude value that will decide map marker position when user is adding a listing.', 'lisner-core' ),
				'tab'         => 'setup_map'
			),
			array(
				'id'         => 'map-geolocation-provider',
				'name'       => esc_html__( 'Map Geolocation Provider', 'lisner-core' ),
				'type'       => 'select_advanced',
				'options'    => array(
					'ipapi'  => esc_html__( 'ipapi', 'lisner-core' ),
					'ipinfo' => esc_html__( 'ipinfo', 'lisner-core' ),
					'geoip'  => esc_html__( 'GeoIPLookup', 'lisner-core' ),
				),
				'js_options' => array(
					'allowClear' => false,
				),
				'std'        => 'geoip',
				'desc'       => esc_html__( 'Choose your preferred geolocation service from the list of predefined ones. Limit is 10.000 requests per hour.', 'lisner-core' ),
				'tab'        => 'setup_map'
			),
			array(
				'id'     => 'map-ipapi-key',
				'name'   => esc_html__( 'Map ipapi API key', 'lisner-core' ),
				'type'   => 'text',
				'desc'   => sprintf( __( 'ipapi geolocation service is providing 10.000 daily request for geolocating users. If you need more than that you\'ll need an api key which can be created here: %s. You should only do this if you expect more than 10.000 hourly requests and are using SSL certificate.', 'lisner-core' ), '<a href="' . $ipapi_link . '">' . esc_html__( 'Get ipapi API key', 'lisner-core' ) . '</a>' ),
				'tab'    => 'setup_map',
				'hidden' => array( 'map-geolocation-provider', '!=', 'ipapi' )
			),
		);

		$meta_boxes[] = array(
			'title'          => esc_html__( 'API keys', 'lisner-core' ),
			'settings_pages' => 'lisner-options',
			'fields'         => $auth,
			'context'        => 'normal',
			'tabs'           => array(
				'setup_general'        => array(
					'label' => esc_html__( 'General', 'lisner-core' ),
					'icon'  => 'dashicons-arrow-right'
				),
				'setup_authentication' => array(
					'label' => esc_html__( 'Authentication', 'lisner-core' ),
					'icon'  => 'dashicons-arrow-right'
				),
				'setup_copyrights'     => array(
					'label' => esc_html__( 'Copyrights', 'lisner-core' ),
					'icon'  => 'dashicons-arrow-right'
				),
				'setup_share'          => array(
					'label' => esc_html__( 'Sharing', 'lisner-core' ),
					'icon'  => 'dashicons-arrow-right'
				),
				'setup_units'          => array(
					'label' => esc_html__( 'Units', 'lisner-core' ),
					'icon'  => 'dashicons-arrow-right'
				),
				'setup_map'            => array(
					'label' => esc_html__( 'Map', 'lisner-core' ),
					'icon'  => 'dashicons-arrow-right'
				),
			),
			'tab_style'      => 'left',
			'tab_wrapper'    => true,
		);

		$wpjm_search_page       = get_option( 'job_manager_jobs_page_id' );
		$wpjm_add_listing_page  = get_option( 'job_manager_submit_job_form_page_id' );
		$woocommerce_terms_page = get_option( 'woocommerce_terms_page_id' );
		$my_account_page        = get_option( 'woocommerce_myaccount_page_id' );
		$pages                  = array(
			array(
				'id'        => 'page-search',
				'name'      => esc_html__( 'Search Page Template', 'lisner-core' ),
				'type'      => 'post',
				'post_type' => 'page',
				'std'       => ! empty( $wpjm_search_page ) ? $wpjm_search_page : '',
				'desc'      => esc_html__( 'Choose main search page template from the list', 'lisner-core' ),
				'tooltip'   => array(
					'icon'     => 'help',
					'content'  => esc_html__( 'This is the main search template. You can create different search pages by selecting "Search Page Template" as page template when creating page.', 'lisner-core' ),
					'position' => 'top'
				)
			),
			array(
				'id'        => 'page-add-listing',
				'name'      => esc_html__( 'Submit Listing Page Template', 'lisner-core' ),
				'type'      => 'post',
				'post_type' => 'page',
				'std'       => ! empty( $wpjm_add_listing_page ) ? $wpjm_add_listing_page : '',
				'desc'      => esc_html__( 'Choose main listing submit page template from the list', 'lisner-core' ),
				'tooltip'   => array(
					'icon'     => 'help',
					'content'  => esc_html__( 'This is the main listing submit form template and it is used to display listing submission form.', 'lisner-core' ),
					'position' => 'top'
				)
			),
			array(
				'id'        => 'page-dashboard',
				'name'      => esc_html__( 'My Account Page Template', 'lisner-core' ),
				'type'      => 'post',
				'post_type' => 'page',
				'std'       => ! empty( $my_account_page ) ? $my_account_page : '',
				'desc'      => esc_html__( 'Choose user my account page template', 'lisner-core' ),
				'tooltip'   => array(
					'icon'     => 'help',
					'content'  => esc_html__( 'This is your users account page. You can set it up in WooCommerce settings too.', 'lisner-core' ),
					'position' => 'top'
				)
			),
			array(
				'id'        => 'page-terms',
				'name'      => esc_html__( 'Terms & Conditions', 'lisner-core' ),
				'type'      => 'post',
				'post_type' => 'page',
				'std'       => ! empty( $woocommerce_terms_page ) ? $woocommerce_terms_page : '',
				'desc'      => esc_html__( 'Choose terms and conditions page or leave empty if you do not need it', 'lisner-core' ),
				'tooltip'   => array(
					'icon'     => 'help',
					'content'  => esc_html__( 'This is your terms & conditions page, it will be automatically created on user register form.', 'lisner-core' ),
					'position' => 'top'
				)
			),
		);

		$meta_boxes[] = array(
			'title'          => esc_html__( 'Pages', 'lisner-core' ),
			'settings_pages' => 'lisner-options-pages',
			'fields'         => $pages,
		);

		$apis = array(
			array(
				'id'      => 'api_google',
				'name'    => esc_html__( 'Google API key', 'lisner-core' ),
				'type'    => 'text',
				'desc'    => esc_html__( 'Enter your google api key for the proper working of the map and search.', 'lisner-core' ),
				'tooltip' => array(
					'icon'     => 'info',
					'content'  => esc_html__( 'If the field is empty then listing owner email address will be used instead', 'lisner-core' ),
					'position' => 'right'
				)
			),
		);

		// Settings / Pages Help
		$pages_help = array(
			array(
				'type' => 'custom_html',
				'std'  => sprintf( __( 'If Anything is not clear about page templates, please make sure to read the documentation part about it: %s', 'lisner-core' ), '<a href="javascript:">Lisner Documentation</a>' ),
			),
		);

		$meta_boxes[] = array(
			'title'          => esc_html__( 'Page Templates Help', 'lisner-core' ),
			'settings_pages' => 'lisner-options-pages',
			'fields'         => $pages_help,
			'context'        => 'side',
		);

		// Settings / Fallbacks
		$fallback_fields = array(
			array(
				'id'               => 'fallback-bg-page',
				'name'             => esc_html__( 'Page Image Fallback', 'lisner-core' ),
				'type'             => 'single_image',
				'desc'             => esc_html__( 'Please add fallback image which will be used when image it is not set for some page.', 'lisner-core' ),
				'max_file_uploads' => 1,
				'max_status'       => true,
				'tooltip'          => array(
					'icon'     => 'info',
					'content'  => esc_html__( 'If required page header image is missing this one will be used.', 'lisner-core' ),
					'position' => 'right'
				)
			),
			array(
				'id'               => 'fallback-bg-taxonomy',
				'name'             => esc_html__( 'Taxonomy Image Fallback', 'lisner-core' ),
				'type'             => 'single_image',
				'desc'             => esc_html__( 'Please add fallback image which will be used when image it is not set for some taxonomy.', 'lisner-core' ),
				'max_file_uploads' => 1,
				'max_status'       => true,
				'tooltip'          => array(
					'icon'     => 'info',
					'content'  => esc_html__( 'If location taxonomy does not have background image this one will be used.', 'lisner-core' ),
					'position' => 'right'
				)
			),
			array(
				'id'               => 'fallback-bg-listing',
				'name'             => esc_html__( 'Listing Image Fallback', 'lisner-core' ),
				'type'             => 'single_image',
				'desc'             => esc_html__( 'Please add fallback image which will be used when image it is not set for some listing.', 'lisner-core' ),
				'max_file_uploads' => 1,
				'max_status'       => true,
				'tooltip'          => array(
					'icon'     => 'info',
					'content'  => esc_html__( 'If listing does not have background image this one will be used.', 'lisner-core' ),
					'position' => 'right'
				)
			),
			array(
				'id'               => 'fallback-bg-video',
				'name'             => esc_html__( 'Listing Single Video Fallback Image', 'lisner-core' ),
				'type'             => 'single_image',
				'desc'             => esc_html__( 'Please add fallback image which will be used when image it is not set for video.', 'lisner-core' ),
				'max_file_uploads' => 1,
				'max_status'       => true,
				'tooltip'          => array(
					'icon'     => 'info',
					'content'  => esc_html__( 'If listing user has not added background image for listing video this one will be used.', 'lisner-core' ),
					'position' => 'right'
				)
			),
		);

		$meta_boxes[] = array(
			'title'          => esc_html__( 'Theme Fallbacks', 'lisner-core' ),
			'settings_pages' => 'lisner-options-fallbacks',
			'fields'         => $fallback_fields,
			'context'        => 'normal',
		);

		// Listings Appearance
		$listings_fields = array(
			// Listing Design
			array(
				'type' => 'heading',
				'name' => esc_html__( 'Listing Single Page Preferred Design', 'lisner-core' ),
				'desc' => esc_html__( 'Set settings that for proper listing functionality', 'lisner-core' ),
			),
			array(
				'id'      => 'listings-template',
				'name'    => esc_html__( 'Listing Template', 'lisner-core' ),
				'type'    => 'image_select',
				'desc'    => esc_html__( 'Choose default listing template that will be used for listing single page. This can be override in listing post type options for specific listings.', 'lisner-core' ),
				'options' => array(
					'1' => esc_url( LISNER_URL . 'assets/images/listing-single/1.png' ),
					'2' => esc_url( LISNER_URL . 'assets/images/listing-single/2.png' ),
					'3' => esc_url( LISNER_URL . 'assets/images/listing-single/3.png' ),
				),
				'std'     => '1',
			),
			array(
				'id'      => 'listings-sidebar-template',
				'name'    => esc_html__( 'Listing Sidebar Template', 'lisner-core' ),
				'type'    => 'image_select',
				'desc'    => esc_html__( 'Choose default listing sidebar template that will be used for listing single page. This can be override in listing post type options for specific listings.', 'lisner-core' ),
				'options' => array(
					'1' => esc_url( LISNER_URL . 'assets/images/listing-single/4.png' ),
					'2' => esc_url( LISNER_URL . 'assets/images/listing-single/5.png' ),
				),
				'std'     => '1',
			),
			// Listing Content Length
			array(
				'type' => 'heading',
				'name' => esc_html__( 'Listing Box Content Display', 'lisner-core' ),
				'desc' => esc_html__( 'Set listing box content how you prefer', 'lisner-core' ),
			),
			array(
				'id'      => 'listings-logo-display',
				'name'    => esc_html__( 'Listing Logo Display', 'lisner-core' ),
				'type'    => 'select',
				'desc'    => esc_html__( 'Choose whether you wish to display a logo of the listing where possible', 'lisner-core' ),
				'options' => array(
					0 => esc_html__( 'Hide', 'lisner-core' ),
					1 => esc_html__( 'Show', 'lisner-core' ),
				),
				'std'     => '0',
			),
			array(
				'id'          => 'listings-title-size',
				'name'        => esc_html__( 'Listing Box Title Size', 'lisner-core' ),
				'type'        => 'number',
				'std'         => '26',
				'desc'        => esc_html__( 'Enter numeric value representing listing box title size', 'lisner-core' ),
				'placeholder' => esc_html__( '26', 'lisner-core' ),
			),
			array(
				'id'      => 'listings-content-length-by',
				'name'    => esc_html__( 'Listing Box Content Limit By', 'lisner-core' ),
				'type'    => 'select',
				'options' => array(
					'characters' => esc_html__( 'Characters', 'lisner-core' ),
					'words'      => esc_html__( 'Words', 'lisner-core' ),
				),
				'std'     => 'characters',
				'desc'    => esc_html__( 'Choose whether you wish to limit content length by characters or by words', 'lisner-core' ),
			),
			array(
				'id'          => 'listings-content-length',
				'name'        => esc_html__( 'Listing Box Content Character/Words Limit', 'lisner-core' ),
				'type'        => 'number',
				'std'         => '170',
				'desc'        => esc_html__( 'Enter numeric value representing listing box content character/words limit. If you wish to hide content enter: 00', 'lisner-core' ),
				'placeholder' => esc_html__( '170', 'lisner-core' ),
			),
			array(
				'id'          => 'listings-preview-content-length',
				'name'        => esc_html__( 'Listing Preview Box Content Character/Words Limit', 'lisner-core' ),
				'type'        => 'number',
				'std'         => '340',
				'desc'        => esc_html__( 'Enter numeric value representing listing preview box content character/words limit.', 'lisner-core' ),
				'placeholder' => esc_html__( '340', 'lisner-core' ),
			),
			array(
				'id'          => 'listings-map-zoom',
				'name'        => esc_html__( 'Listing Map Zoom', 'lisner-core' ),
				'type'        => 'number',
				'std'         => '18',
				'desc'        => esc_html__( 'Enter numeric value to choose listing map zoom level', 'lisner-core' ),
				'placeholder' => esc_html__( '18', 'lisner-core' ),
			),
			// Listing Appearance
			array(
				'type' => 'heading',
				'name' => esc_html__( 'Listings Page Appearance', 'lisner-core' ),
				'desc' => esc_html__( 'Set settings that for proper listing single page appearance', 'lisner-core' ),
			),
			array(
				'id'      => 'listings-logo-page-display',
				'name'    => esc_html__( 'Listing Logo Display', 'lisner-core' ),
				'type'    => 'select',
				'desc'    => esc_html__( 'Choose whether you wish to display a logo on listing page', 'lisner-core' ),
				'options' => array(
					0 => esc_html__( 'Hide', 'lisner-core' ),
					1 => esc_html__( 'Show', 'lisner-core' ),
				),
				'std'     => '0',
			),
			array(
				'id'         => 'listings-appearance-video-height',
				'name'       => esc_html__( 'Listing Background Video Height', 'lisner-core' ),
				'type'       => 'slider',
				'js_options' => array(
					'min'  => 444,
					'max'  => 1000,
					'step' => 5,
				),
				'suffix'     => 'px',
				'desc'       => esc_html__( 'Choose listings single page background video height on desktops', 'lisner-core' ),
			),
			array(
				'id'      => 'listings-appearance-hide-phone',
				'name'    => esc_html__( 'Hide Phone Number', 'lisner-core' ),
				'type'    => 'select',
				'options' => array(
					'hide' => esc_html__( 'Hide last three digits', 'lisner-core' ),
					'show' => esc_html__( 'Display full number', 'lisner-core' ),
				),
				'desc'    => esc_html__( 'Choose whether you wish to hide the last three digits of the listing phone number.', 'lisner-core' ),
			),
			array(
				'id'      => 'listings-appearance-whatsapp',
				'name'    => esc_html__( 'Enable WhatsApp', 'lisner-core' ),
				'type'    => 'select',
				'options' => array(
					0 => esc_html__( 'Disable', 'lisner-core' ),
					1 => esc_html__( 'Enable', 'lisner-core' ),
				),
				'desc'    => esc_html__( 'Choose whether you wish to allow calls on whatsapp', 'lisner-core' ),
			),
			array(
				'id'      => 'listings-appearance-viber',
				'name'    => esc_html__( 'Enable Viber', 'lisner-core' ),
				'type'    => 'select',
				'options' => array(
					0 => esc_html__( 'Disable', 'lisner-core' ),
					1 => esc_html__( 'Enable', 'lisner-core' ),
				),
				'desc'    => esc_html__( 'Choose whether you wish to allow calls on viber', 'lisner-core' ),
			),
			// Listing Functionality
			array(
				'type' => 'heading',
				'name' => esc_html__( 'Listings Functionality', 'lisner-core' ),
				'desc' => esc_html__( 'Set settings that for proper listing functionality', 'lisner-core' ),
			),
			array(
				'id'      => 'listings-location-type',
				'name'    => esc_html__( 'Listing Location Type', 'lisner-core' ),
				'type'    => 'select',
				'options' => array(
					'state'   => esc_html__( 'State', 'lisner-core' ),
					'country' => esc_html__( 'Country', 'lisner-core' ),
				),
				'desc'    => esc_html__( 'Choose what would be top level location taxonomy. If listing location is Los Angeles then top level can be California or United States depending on this selection.', 'lisner-core' ),
			),
		);
		$meta_boxes[]    = array(
			'title'          => esc_html__( 'Listings', 'lisner-core' ),
			'settings_pages' => 'lisner-options-listings',
			'fields'         => $listings_fields,
			'context'        => 'normal',
		);

		// Listing Submission Fields
		$listing_fields = array(
			// Field / Address
			array(
				'type' => 'heading',
				'name' => esc_html__( 'Listing Field / Address', 'lisner-core' ),
				'desc' => esc_html__( 'Choose settings for the address field', 'lisner-core' ),
			),
			array(
				'id'         => 'listing-fields-address',
				'name'       => esc_html__( 'Enabled?', 'lisner-core' ),
				'type'       => 'switch',
				'on_label'   => esc_html__( 'Yes', 'lisner-core' ),
				'off_label'  => esc_html__( 'No', 'lisner-core' ),
				'std'        => 1,
				'value'      => 1,
				'attributes' => array(
					'data-option-lisner' => 'lisner'
				),
				'desc'       => esc_html__( 'Choose whether this field should be displayed.', 'lisner-core' ),
			),
			array(
				'id'        => 'listing-fields-address-required',
				'name'      => esc_html__( 'Required?', 'lisner-core' ),
				'type'      => 'switch',
				'on_label'  => esc_html__( 'Yes', 'lisner-core' ),
				'off_label' => esc_html__( 'No', 'lisner-core' ),
				'std'       => 1,
				'value'     => 1,
				'desc'      => esc_html__( 'Choose whether this field should be required when submitting a listing.', 'lisner-core' ),
			),
			array(
				'id'        => 'listing-fields-address-members',
				'name'      => esc_html__( 'Visible to non Members?', 'lisner-core' ),
				'type'      => 'switch',
				'on_label'  => esc_html__( 'Yes', 'lisner-core' ),
				'off_label' => esc_html__( 'No', 'lisner-core' ),
				'std'       => 1,
				'value'     => 1,
				'desc'      => esc_html__( 'Choose whether this field should be displayed to non members of the site.', 'lisner-core' ),
			),
			// Field / Categories
			array(
				'type' => 'heading',
				'name' => esc_html__( 'Listing Field / Categories', 'lisner-core' ),
				'desc' => esc_html__( 'Choose settings for the categories field', 'lisner-core' ),
			),
			array(
				'id'         => 'listing-fields-categories',
				'name'       => esc_html__( 'Enabled?', 'lisner-core' ),
				'type'       => 'switch',
				'on_label'   => esc_html__( 'Yes', 'lisner-core' ),
				'off_label'  => esc_html__( 'No', 'lisner-core' ),
				'std'        => 1,
				'value'      => 1,
				'attributes' => array(
					'data-option-lisner' => 'lisner'
				),
				'desc'       => esc_html__( 'Choose whether this field should be displayed.', 'lisner-core' ),
			),
			array(
				'id'        => 'listing-fields-categories-required',
				'name'      => esc_html__( 'Required?', 'lisner-core' ),
				'type'      => 'switch',
				'on_label'  => esc_html__( 'Yes', 'lisner-core' ),
				'off_label' => esc_html__( 'No', 'lisner-core' ),
				'std'       => 0,
				'value'     => 0,
				'desc'      => esc_html__( 'Choose whether this field should be required when submitting a listing.', 'lisner-core' ),
			),
			array(
				'id'        => 'listing-fields-categories-members',
				'name'      => esc_html__( 'Visible to non Members?', 'lisner-core' ),
				'type'      => 'switch',
				'on_label'  => esc_html__( 'Yes', 'lisner-core' ),
				'off_label' => esc_html__( 'No', 'lisner-core' ),
				'std'       => 1,
				'value'     => 1,
				'desc'      => esc_html__( 'Choose whether this field should be displayed to non members of the site.', 'lisner-core' ),
			),
			// Field / Email
			array(
				'type' => 'heading',
				'name' => esc_html__( 'Listing Field / Email', 'lisner-core' ),
				'desc' => esc_html__( 'Choose settings for the email field', 'lisner-core' ),
			),
			array(
				'id'         => 'listing-fields-email',
				'name'       => esc_html__( 'Enabled?', 'lisner-core' ),
				'type'       => 'switch',
				'on_label'   => esc_html__( 'Yes', 'lisner-core' ),
				'off_label'  => esc_html__( 'No', 'lisner-core' ),
				'std'        => 1,
				'value'      => 1,
				'attributes' => array(
					'data-option-lisner' => 'lisner'
				),
				'desc'       => esc_html__( 'Choose whether this field should be displayed.', 'lisner-core' ),
			),
			array(
				'id'        => 'listing-fields-email-required',
				'name'      => esc_html__( 'Required?', 'lisner-core' ),
				'type'      => 'switch',
				'on_label'  => esc_html__( 'Yes', 'lisner-core' ),
				'off_label' => esc_html__( 'No', 'lisner-core' ),
				'std'       => 0,
				'value'     => 0,
				'desc'      => esc_html__( 'Choose whether this field should be required when submitting a listing.', 'lisner-core' ),
			),
			array(
				'id'        => 'listing-fields-email-members',
				'name'      => esc_html__( 'Visible to non Members?', 'lisner-core' ),
				'type'      => 'switch',
				'on_label'  => esc_html__( 'Yes', 'lisner-core' ),
				'off_label' => esc_html__( 'No', 'lisner-core' ),
				'std'       => 1,
				'value'     => 1,
				'desc'      => esc_html__( 'Choose whether this field should be displayed to non members of the site.', 'lisner-core' ),
			),
			// Field / Website
			array(
				'type' => 'heading',
				'name' => esc_html__( 'Listing Field / Website', 'lisner-core' ),
				'desc' => esc_html__( 'Choose settings for the website field', 'lisner-core' ),
			),
			array(
				'id'         => 'listing-fields-website',
				'name'       => esc_html__( 'Enabled?', 'lisner-core' ),
				'type'       => 'switch',
				'on_label'   => esc_html__( 'Yes', 'lisner-core' ),
				'off_label'  => esc_html__( 'No', 'lisner-core' ),
				'std'        => 1,
				'value'      => 1,
				'attributes' => array(
					'data-option-lisner' => 'lisner'
				),
				'desc'       => esc_html__( 'Choose whether this field should be displayed.', 'lisner-core' ),
			),
			array(
				'id'        => 'listing-fields-website-required',
				'name'      => esc_html__( 'Required?', 'lisner-core' ),
				'type'      => 'switch',
				'on_label'  => esc_html__( 'Yes', 'lisner-core' ),
				'off_label' => esc_html__( 'No', 'lisner-core' ),
				'std'       => 0,
				'value'     => 0,
				'desc'      => esc_html__( 'Choose whether this field should be required when submitting a listing.', 'lisner-core' ),
			),
			array(
				'id'        => 'listing-fields-website-members',
				'name'      => esc_html__( 'Visible to non Members?', 'lisner-core' ),
				'type'      => 'switch',
				'on_label'  => esc_html__( 'Yes', 'lisner-core' ),
				'off_label' => esc_html__( 'No', 'lisner-core' ),
				'std'       => 1,
				'value'     => 1,
				'desc'      => esc_html__( 'Choose whether this field should be displayed to non members of the site.', 'lisner-core' ),
			),
			// Field / Phone
			array(
				'type' => 'heading',
				'name' => esc_html__( 'Listing Field / Phone', 'lisner-core' ),
				'desc' => esc_html__( 'Choose settings for the phone field', 'lisner-core' ),
			),
			array(
				'id'         => 'listing-fields-phone',
				'name'       => esc_html__( 'Enabled?', 'lisner-core' ),
				'type'       => 'switch',
				'on_label'   => esc_html__( 'Yes', 'lisner-core' ),
				'off_label'  => esc_html__( 'No', 'lisner-core' ),
				'std'        => 1,
				'value'      => 1,
				'attributes' => array(
					'data-option-lisner' => 'lisner'
				),
				'desc'       => esc_html__( 'Choose whether this field should be displayed.', 'lisner-core' ),
			),
			array(
				'id'        => 'listing-fields-phone-required',
				'name'      => esc_html__( 'Required?', 'lisner-core' ),
				'type'      => 'switch',
				'on_label'  => esc_html__( 'Yes', 'lisner-core' ),
				'off_label' => esc_html__( 'No', 'lisner-core' ),
				'std'       => 0,
				'value'     => 0,
				'desc'      => esc_html__( 'Choose whether this field should be required when submitting a listing.', 'lisner-core' ),
			),
			array(
				'id'        => 'listing-fields-phone-members',
				'name'      => esc_html__( 'Visible to non Members?', 'lisner-core' ),
				'type'      => 'switch',
				'on_label'  => esc_html__( 'Yes', 'lisner-core' ),
				'off_label' => esc_html__( 'No', 'lisner-core' ),
				'std'       => 1,
				'value'     => 1,
				'desc'      => esc_html__( 'Choose whether this field should be displayed to non members of the site.', 'lisner-core' ),
			),
			// Field / Amenities
			array(
				'type' => 'heading',
				'name' => esc_html__( 'Listing Field / Amenities', 'lisner-core' ),
				'desc' => esc_html__( 'Choose settings for the amenities field', 'lisner-core' ),
			),
			array(
				'id'         => 'listing-fields-amenities',
				'name'       => esc_html__( 'Enabled?', 'lisner-core' ),
				'type'       => 'switch',
				'on_label'   => esc_html__( 'Yes', 'lisner-core' ),
				'off_label'  => esc_html__( 'No', 'lisner-core' ),
				'std'        => 1,
				'value'      => 1,
				'attributes' => array(
					'data-option-lisner' => 'lisner'
				),
				'desc'       => esc_html__( 'Choose whether this field should be displayed.', 'lisner-core' ),
			),
			array(
				'id'        => 'listing-fields-amenities-required',
				'name'      => esc_html__( 'Required?', 'lisner-core' ),
				'type'      => 'switch',
				'on_label'  => esc_html__( 'Yes', 'lisner-core' ),
				'off_label' => esc_html__( 'No', 'lisner-core' ),
				'std'       => 0,
				'value'     => 0,
				'desc'      => esc_html__( 'Choose whether this field should be required when submitting a listing.', 'lisner-core' ),
			),
			array(
				'id'        => 'listing-fields-amenities-members',
				'name'      => esc_html__( 'Visible to non Members?', 'lisner-core' ),
				'type'      => 'switch',
				'on_label'  => esc_html__( 'Yes', 'lisner-core' ),
				'off_label' => esc_html__( 'No', 'lisner-core' ),
				'std'       => 1,
				'value'     => 1,
				'desc'      => esc_html__( 'Choose whether this field should be displayed to non members of the site.', 'lisner-core' ),
			),
			// Field / Tags
			array(
				'type' => 'heading',
				'name' => esc_html__( 'Listing Field / Tags', 'lisner-core' ),
				'desc' => esc_html__( 'Choose settings for the tags field', 'lisner-core' ),
			),
			array(
				'id'         => 'listing-fields-tags',
				'name'       => esc_html__( 'Enabled?', 'lisner-core' ),
				'type'       => 'switch',
				'on_label'   => esc_html__( 'Yes', 'lisner-core' ),
				'off_label'  => esc_html__( 'No', 'lisner-core' ),
				'std'        => 1,
				'value'      => 1,
				'attributes' => array(
					'data-option-lisner' => 'lisner'
				),
				'desc'       => esc_html__( 'Choose whether this field should be displayed.', 'lisner-core' ),
			),
			array(
				'id'        => 'listing-fields-tags-required',
				'name'      => esc_html__( 'Required?', 'lisner-core' ),
				'type'      => 'switch',
				'on_label'  => esc_html__( 'Yes', 'lisner-core' ),
				'off_label' => esc_html__( 'No', 'lisner-core' ),
				'std'       => 0,
				'value'     => 0,
				'desc'      => esc_html__( 'Choose whether this field should be required when submitting a listing.', 'lisner-core' ),
			),
			array(
				'id'        => 'listing-fields-tags-members',
				'name'      => esc_html__( 'Visible to non Members?', 'lisner-core' ),
				'type'      => 'switch',
				'on_label'  => esc_html__( 'Yes', 'lisner-core' ),
				'off_label' => esc_html__( 'No', 'lisner-core' ),
				'std'       => 1,
				'value'     => 1,
				'desc'      => esc_html__( 'Choose whether this field should be displayed to non members of the site.', 'lisner-core' ),
			),
			// Field / Pricing
			array(
				'type' => 'heading',
				'name' => esc_html__( 'Listing Field / Pricing', 'lisner-core' ),
				'desc' => esc_html__( 'Choose settings for the pricing field', 'lisner-core' ),
			),
			array(
				'id'         => 'listing-fields-pricing',
				'name'       => esc_html__( 'Enabled?', 'lisner-core' ),
				'type'       => 'switch',
				'on_label'   => esc_html__( 'Yes', 'lisner-core' ),
				'off_label'  => esc_html__( 'No', 'lisner-core' ),
				'std'        => 1,
				'value'      => 1,
				'attributes' => array(
					'data-option-lisner' => 'lisner'
				),
				'desc'       => esc_html__( 'Choose whether this field should be displayed.', 'lisner-core' ),
			),
			array(
				'id'        => 'listing-fields-pricing-required',
				'name'      => esc_html__( 'Required?', 'lisner-core' ),
				'type'      => 'switch',
				'on_label'  => esc_html__( 'Yes', 'lisner-core' ),
				'off_label' => esc_html__( 'No', 'lisner-core' ),
				'std'       => 0,
				'value'     => 0,
				'desc'      => esc_html__( 'Choose whether this field should be required when submitting a listing.', 'lisner-core' ),
			),
			array(
				'id'        => 'listing-fields-pricing-members',
				'name'      => esc_html__( 'Visible to non Members?', 'lisner-core' ),
				'type'      => 'switch',
				'on_label'  => esc_html__( 'Yes', 'lisner-core' ),
				'off_label' => esc_html__( 'No', 'lisner-core' ),
				'std'       => 1,
				'value'     => 1,
				'desc'      => esc_html__( 'Choose whether this field should be displayed to non members of the site.', 'lisner-core' ),
			),
			// Field / Working Hours
			array(
				'type' => 'heading',
				'name' => esc_html__( 'Listing Field / Working Hours', 'lisner-core' ),
				'desc' => esc_html__( 'Choose settings for the pricing field', 'lisner-core' ),
			),
			array(
				'id'         => 'listing-fields-working-hours',
				'name'       => esc_html__( 'Enabled?', 'lisner-core' ),
				'type'       => 'switch',
				'on_label'   => esc_html__( 'Yes', 'lisner-core' ),
				'off_label'  => esc_html__( 'No', 'lisner-core' ),
				'std'        => 1,
				'value'      => 1,
				'attributes' => array(
					'data-option-lisner' => 'lisner'
				),
				'desc'       => esc_html__( 'Choose whether this field should be displayed.', 'lisner-core' ),
			),
			array(
				'id'        => 'listing-fields-working-hours-required',
				'name'      => esc_html__( 'Required?', 'lisner-core' ),
				'type'      => 'switch',
				'on_label'  => esc_html__( 'Yes', 'lisner-core' ),
				'off_label' => esc_html__( 'No', 'lisner-core' ),
				'std'       => 0,
				'value'     => 0,
				'desc'      => esc_html__( 'Choose whether this field should be required when submitting a listing.', 'lisner-core' ),
			),
			array(
				'id'        => 'listing-fields-working-hours-members',
				'name'      => esc_html__( 'Visible to non Members?', 'lisner-core' ),
				'type'      => 'switch',
				'on_label'  => esc_html__( 'Yes', 'lisner-core' ),
				'off_label' => esc_html__( 'No', 'lisner-core' ),
				'std'       => 1,
				'value'     => 1,
				'desc'      => esc_html__( 'Choose whether this field should be displayed to non members of the site.', 'lisner-core' ),
			),
			// Field / Logo
			array(
				'type' => 'heading',
				'name' => esc_html__( 'Listing Field / Logo', 'lisner-core' ),
				'desc' => esc_html__( 'Choose settings for the logo field', 'lisner-core' ),
			),
			array(
				'id'         => 'listing-fields-logo',
				'name'       => esc_html__( 'Enabled?', 'lisner-core' ),
				'type'       => 'switch',
				'on_label'   => esc_html__( 'Yes', 'lisner-core' ),
				'off_label'  => esc_html__( 'No', 'lisner-core' ),
				'std'        => 1,
				'value'      => 1,
				'attributes' => array(
					'data-option-lisner' => 'lisner'
				),
				'desc'       => esc_html__( 'Choose whether this field should be displayed.', 'lisner-core' ),
			),
			array(
				'id'        => 'listing-fields-logo-required',
				'name'      => esc_html__( 'Required?', 'lisner-core' ),
				'type'      => 'switch',
				'on_label'  => esc_html__( 'Yes', 'lisner-core' ),
				'off_label' => esc_html__( 'No', 'lisner-core' ),
				'std'       => 0,
				'value'     => 0,
				'desc'      => esc_html__( 'Choose whether this field should be required when submitting a listing.', 'lisner-core' ),
			),
			array(
				'id'        => 'listing-fields-logo-members',
				'name'      => esc_html__( 'Visible to non Members?', 'lisner-core' ),
				'type'      => 'switch',
				'on_label'  => esc_html__( 'Yes', 'lisner-core' ),
				'off_label' => esc_html__( 'No', 'lisner-core' ),
				'std'       => 1,
				'value'     => 1,
				'desc'      => esc_html__( 'Choose whether this field should be displayed to non members of the site.', 'lisner-core' ),
			),
			// Field / Cover
			array(
				'type' => 'heading',
				'name' => esc_html__( 'Listing Field / Cover', 'lisner-core' ),
				'desc' => esc_html__( 'Choose settings for the cover field', 'lisner-core' ),
			),
			array(
				'id'         => 'listing-fields-cover',
				'name'       => esc_html__( 'Enabled?', 'lisner-core' ),
				'type'       => 'switch',
				'on_label'   => esc_html__( 'Yes', 'lisner-core' ),
				'off_label'  => esc_html__( 'No', 'lisner-core' ),
				'std'        => 1,
				'value'      => 1,
				'attributes' => array(
					'data-option-lisner' => 'lisner'
				),
				'desc'       => esc_html__( 'Choose whether this field should be displayed.', 'lisner-core' ),
			),
			array(
				'id'        => 'listing-fields-cover-required',
				'name'      => esc_html__( 'Required?', 'lisner-core' ),
				'type'      => 'switch',
				'on_label'  => esc_html__( 'Yes', 'lisner-core' ),
				'off_label' => esc_html__( 'No', 'lisner-core' ),
				'std'       => 0,
				'value'     => 0,
				'desc'      => esc_html__( 'Choose whether this field should be required when submitting a listing.', 'lisner-core' ),
			),
			array(
				'id'        => 'listing-fields-cover-members',
				'name'      => esc_html__( 'Visible to non Members?', 'lisner-core' ),
				'type'      => 'switch',
				'on_label'  => esc_html__( 'Yes', 'lisner-core' ),
				'off_label' => esc_html__( 'No', 'lisner-core' ),
				'std'       => 1,
				'value'     => 1,
				'desc'      => esc_html__( 'Choose whether this field should be displayed to non members of the site.', 'lisner-core' ),
			),
			// Field / Gallery
			array(
				'type' => 'heading',
				'name' => esc_html__( 'Listing Field / Gallery', 'lisner-core' ),
				'desc' => esc_html__( 'Choose settings for the gallery field', 'lisner-core' ),
			),
			array(
				'id'         => 'listing-fields-gallery',
				'name'       => esc_html__( 'Enabled?', 'lisner-core' ),
				'type'       => 'switch',
				'on_label'   => esc_html__( 'Yes', 'lisner-core' ),
				'off_label'  => esc_html__( 'No', 'lisner-core' ),
				'std'        => 1,
				'value'      => 1,
				'attributes' => array(
					'data-option-lisner' => 'lisner'
				),
				'desc'       => esc_html__( 'Choose whether this field should be displayed.', 'lisner-core' ),
			),
			array(
				'id'        => 'listing-fields-gallery-required',
				'name'      => esc_html__( 'Required?', 'lisner-core' ),
				'type'      => 'switch',
				'on_label'  => esc_html__( 'Yes', 'lisner-core' ),
				'off_label' => esc_html__( 'No', 'lisner-core' ),
				'std'       => 0,
				'value'     => 0,
				'desc'      => esc_html__( 'Choose whether this field should be required when submitting a listing.', 'lisner-core' ),
			),
			array(
				'id'        => 'listing-fields-gallery-members',
				'name'      => esc_html__( 'Visible to non Members?', 'lisner-core' ),
				'type'      => 'switch',
				'on_label'  => esc_html__( 'Yes', 'lisner-core' ),
				'off_label' => esc_html__( 'No', 'lisner-core' ),
				'std'       => 1,
				'value'     => 1,
				'desc'      => esc_html__( 'Choose whether this field should be displayed to non members of the site.', 'lisner-core' ),
			),
			// Field / Video
			array(
				'type' => 'heading',
				'name' => esc_html__( 'Listing Field / Video', 'lisner-core' ),
				'desc' => esc_html__( 'Choose settings for the video field', 'lisner-core' ),
			),
			array(
				'id'         => 'listing-fields-video',
				'name'       => esc_html__( 'Enabled?', 'lisner-core' ),
				'type'       => 'switch',
				'on_label'   => esc_html__( 'Yes', 'lisner-core' ),
				'off_label'  => esc_html__( 'No', 'lisner-core' ),
				'std'        => 1,
				'value'      => 1,
				'attributes' => array(
					'data-option-lisner' => 'lisner'
				),
				'desc'       => esc_html__( 'Choose whether this field should be displayed.', 'lisner-core' ),
			),
			array(
				'id'        => 'listing-fields-video-required',
				'name'      => esc_html__( 'Required?', 'lisner-core' ),
				'type'      => 'switch',
				'on_label'  => esc_html__( 'Yes', 'lisner-core' ),
				'off_label' => esc_html__( 'No', 'lisner-core' ),
				'std'       => 0,
				'value'     => 0,
				'desc'      => esc_html__( 'Choose whether this field should be required when submitting a listing.', 'lisner-core' ),
			),
			array(
				'id'        => 'listing-fields-video-members',
				'name'      => esc_html__( 'Visible to non Members?', 'lisner-core' ),
				'type'      => 'switch',
				'on_label'  => esc_html__( 'Yes', 'lisner-core' ),
				'off_label' => esc_html__( 'No', 'lisner-core' ),
				'std'       => 1,
				'value'     => 1,
				'desc'      => esc_html__( 'Choose whether this field should be displayed to non members of the site.', 'lisner-core' ),
			),
			// Field / Files
			array(
				'type' => 'heading',
				'name' => esc_html__( 'Listing Field / Files', 'lisner-core' ),
				'desc' => esc_html__( 'Choose settings for the files field', 'lisner-core' ),
			),
			array(
				'id'         => 'listing-fields-files',
				'name'       => esc_html__( 'Enabled?', 'lisner-core' ),
				'type'       => 'switch',
				'on_label'   => esc_html__( 'Yes', 'lisner-core' ),
				'off_label'  => esc_html__( 'No', 'lisner-core' ),
				'std'        => 1,
				'value'      => 1,
				'attributes' => array(
					'data-option-lisner' => 'lisner'
				),
				'desc'       => esc_html__( 'Choose whether this field should be displayed.', 'lisner-core' ),
			),
			array(
				'id'        => 'listing-fields-files-required',
				'name'      => esc_html__( 'Required?', 'lisner-core' ),
				'type'      => 'switch',
				'on_label'  => esc_html__( 'Yes', 'lisner-core' ),
				'off_label' => esc_html__( 'No', 'lisner-core' ),
				'std'       => 0,
				'value'     => 0,
				'desc'      => esc_html__( 'Choose whether this field should be required when submitting a listing.', 'lisner-core' ),
			),
			array(
				'id'        => 'listing-fields-files-members',
				'name'      => esc_html__( 'Visible to non Members?', 'lisner-core' ),
				'type'      => 'switch',
				'on_label'  => esc_html__( 'Yes', 'lisner-core' ),
				'off_label' => esc_html__( 'No', 'lisner-core' ),
				'std'       => 1,
				'value'     => 1,
				'desc'      => esc_html__( 'Choose whether this field should be displayed to non members of the site.', 'lisner-core' ),
			),
			// Field / Social
			array(
				'type' => 'heading',
				'name' => esc_html__( 'Listing Field / Social', 'lisner-core' ),
				'desc' => esc_html__( 'Choose settings for the social field', 'lisner-core' ),
			),
			array(
				'id'         => 'listing-fields-social',
				'name'       => esc_html__( 'Enabled?', 'lisner-core' ),
				'type'       => 'switch',
				'on_label'   => esc_html__( 'Yes', 'lisner-core' ),
				'off_label'  => esc_html__( 'No', 'lisner-core' ),
				'std'        => 1,
				'value'      => 1,
				'attributes' => array(
					'data-option-lisner' => 'lisner'
				),
				'desc'       => esc_html__( 'Choose whether this field should be displayed.', 'lisner-core' ),
			),
			array(
				'id'        => 'listing-fields-social-required',
				'name'      => esc_html__( 'Required?', 'lisner-core' ),
				'type'      => 'switch',
				'on_label'  => esc_html__( 'Yes', 'lisner-core' ),
				'off_label' => esc_html__( 'No', 'lisner-core' ),
				'std'       => 0,
				'value'     => 0,
				'desc'      => esc_html__( 'Choose whether this field should be required when submitting a listing.', 'lisner-core' ),
			),
			array(
				'id'        => 'listing-fields-social-members',
				'name'      => esc_html__( 'Visible to non Members?', 'lisner-core' ),
				'type'      => 'switch',
				'on_label'  => esc_html__( 'Yes', 'lisner-core' ),
				'off_label' => esc_html__( 'No', 'lisner-core' ),
				'std'       => 1,
				'value'     => 1,
				'desc'      => esc_html__( 'Choose whether this field should be displayed to non members of the site.', 'lisner-core' ),
			),
			// Field / Contact
			array(
				'type' => 'heading',
				'name' => esc_html__( 'Listing Single / Contact Listing Owner', 'lisner-core' ),
				'desc' => esc_html__( 'Choose settings for the contact listing owner section', 'lisner-core' ),
			),
			array(
				'id'        => 'listing-fields-contact-members',
				'name'      => esc_html__( 'Visible to non Members?', 'lisner-core' ),
				'type'      => 'switch',
				'on_label'  => esc_html__( 'Yes', 'lisner-core' ),
				'off_label' => esc_html__( 'No', 'lisner-core' ),
				'std'       => 1,
				'value'     => 1,
				'desc'      => esc_html__( 'Choose whether this section should be displayed to non members of the site.', 'lisner-core' ),
			),
		);
		$meta_boxes[]   = array(
			'title'          => esc_html__( 'Listing Fields', 'lisner-core' ),
			'settings_pages' => 'lisner-options-listing-fields',
			'fields'         => $listing_fields,
			'context'        => 'normal',
		);

		$statistics_fields = array(
			array(
				'id'        => 'listing-statistics-enable',
				'name'      => esc_html__( 'Enable Listing Statistics', 'lisner-core' ),
				'type'      => 'switch',
				'on_label'  => esc_html__( 'Yes', 'lisner-core' ),
				'off_label' => esc_html__( 'No', 'lisner-core' ),
				'std'       => 1,
				'value'     => 1,
				'desc'      => esc_html__( 'Choose whether you wish to enable listing statistics.', 'lisner-core' ),
			),
			//todo add option for keeping track of only promoted listings when promotions are done/start
			array(
				'id'        => 'listing-statistics-focus-enable',
				'name'      => esc_html__( 'Enable Listing Focus Statistics', 'lisner-core' ),
				'type'      => 'switch',
				'on_label'  => esc_html__( 'Yes', 'lisner-core' ),
				'off_label' => esc_html__( 'No', 'lisner-core' ),
				'std'       => 1,
				'value'     => 1,
				'desc'      => esc_html__( 'Choose to enable listing focus statistics that will record every time a single listing was in browser focus. Good for keeping track of promoted listings.', 'lisner-core' ),
				'hidden'    => array( 'listing-statistics-enable', '!=', 1 )
			),
			array(
				'id'        => 'listing-statistics-ctr-enable',
				'name'      => esc_html__( 'Enable Listing CTR Statistics', 'lisner-core' ),
				'type'      => 'switch',
				'on_label'  => esc_html__( 'Yes', 'lisner-core' ),
				'off_label' => esc_html__( 'No', 'lisner-core' ),
				'std'       => 1,
				'value'     => 1,
				'desc'      => esc_html__( 'Choose to enable listing ctr statistics that will record every time a single listing was in browser focus. Good for keeping track of promoted listings.', 'lisner-core' ),
				'hidden'    => array( 'listing-statistics-enable', '!=', 1 )
			),
			//todo add option for keeping track of only promoted listings when promotions are done /end
		);

		$meta_boxes[] = array(
			'title'          => esc_html__( 'Listing Statistics', 'lisner-core' ),
			'settings_pages' => 'lisner-options-listing-statistics',
			'fields'         => $statistics_fields,
			'context'        => 'normal',
		);

		// Maintenance & Coming Soon Mode
		$mailchimp_api      = '<a href="https://us18.admin.mailchimp.com/account/api/" target="_blank">' . __( 'Get MailChimp API', 'lisner-core' ) . '</a>';
		$mailchimp_list_id  = '<a href="https://kb.mailchimp.com/lists/manage-contacts/find-your-list-id" target="_blank">' . __( 'Find MailChimp List ID', 'lisner-core' ) . '</a>';
		$maintenance_fields = array(
			array(
				'id'               => 'maintenance-mode',
				'name'             => esc_html__( 'Enable Maintenance Mode', 'lisner-core' ),
				'type'             => 'select',
				'options'          => array(
					'disabled' => esc_html__( 'Disabled', 'lisner-core' ),
					'enabled'  => esc_html__( 'Enabled', 'lisner-core' ),
				),
				'desc'             => esc_html__( 'Please choose background image for the maintenance mode', 'lisner-core' ),
				'max_file_uploads' => 1,
				'max_status'       => true,
			),
			array(
				'id'               => 'maintenance-bg',
				'name'             => esc_html__( 'Maintenance Mode Background Image', 'lisner-core' ),
				'type'             => 'single_image',
				'desc'             => esc_html__( 'Please choose background image for the maintenance mode', 'lisner-core' ),
				'max_file_uploads' => 1,
				'max_status'       => true,
			),
			array(
				'id'            => 'maintenance-bg-overlay',
				'name'          => esc_html__( 'Background Image Overlay', 'lisner-core' ),
				'type'          => 'color',
				'desc'          => esc_html__( 'Choose if you wish to use image overlay', 'lisner-core' ),
				'alpha_channel' => true,
				'std'           => '',
				'js_options'    => array(
					'color'        => '',
					'defaultColor' => '',
					'palettes'     => false,
				),
			),
			array(
				'id'         => 'maintenance-date',
				'name'       => esc_html__( 'Maintenance Mode Date & Time', 'lisner-core' ),
				'type'       => 'datetime',
				'desc'       => esc_html__( 'Please choose date and time when the site will be back live', 'lisner-core' ),
				'js_options' => array(
					'stepMinute'      => 15,
					'showTimepicker'  => true,
					'controlType'     => 'select',
					'showButtonPanel' => false,
					'oneLine'         => true,
				),
			),
			array(
				'id'          => 'maintenance-title',
				'name'        => esc_html__( 'Maintenance Title', 'lisner-core' ),
				'type'        => 'text',
				'desc'        => esc_html__( 'Enter maintenance title, use [] to make word bolder', 'lisner-core' ),
				'placeholder' => esc_html__( 'Under [Construction]', 'lisner-core' ),
			),
			array(
				'id'          => 'maintenance-subtitle',
				'name'        => esc_html__( 'Maintenance Subtitle', 'lisner-core' ),
				'type'        => 'text',
				'desc'        => esc_html__( 'Enter maintenance subtitle', 'lisner-core' ),
				'placeholder' => esc_html__( 'Website will be live in:', 'lisner-core' ),
			),
			array(
				'id'   => 'maintenance-mailchimp',
				'name' => esc_html__( 'Maintenance Mailchimp Key', 'lisner-core' ),
				'type' => 'text',
				'desc' => esc_html__( 'Enter mailchimp api key to allow subscriptions: ', 'lisner-core' ) . $mailchimp_api,
			),
			array(
				'id'   => 'maintenance-mailchimp-list-id',
				'name' => esc_html__( 'Maintenance Mailchimp List Id', 'lisner-core' ),
				'type' => 'text',
				'desc' => esc_html__( 'Enter mailchimp list id: ', 'lisner-core' ) . $mailchimp_list_id,
			),
			array(
				'id'   => 'maintenance-facebook',
				'name' => esc_html__( 'Maintenance Facebook Profile', 'lisner-core' ),
				'type' => 'text',
				'desc' => esc_html__( 'Enter a link to your facebook profile', 'lisner-core' ),
			),
			array(
				'id'   => 'maintenance-google',
				'name' => esc_html__( 'Maintenance Google+ Profile', 'lisner-core' ),
				'type' => 'text',
				'desc' => esc_html__( 'Enter a link to your google+ profile', 'lisner-core' ),
			),
			array(
				'id'   => 'maintenance-twitter',
				'name' => esc_html__( 'Maintenance Twitter Profile', 'lisner-core' ),
				'type' => 'text',
				'desc' => esc_html__( 'Enter a link to your twitter profile', 'lisner-core' ),
			),
		);

		$meta_boxes[] = array(
			'title'          => esc_html__( 'Maintenance Mode', 'lisner-core' ),
			'settings_pages' => 'lisner-options-maintenance',
			'fields'         => $maintenance_fields,
			'context'        => 'normal',
		);

		// Theme Settings / Identity
		$identity = array(
			array(
				'id'      => 'site-direction',
				'name'    => esc_html__( 'Content Direction', 'lisner-core' ),
				'type'    => 'select',
				'options' => array(
					'ltr' => esc_html__( 'Left to Right', 'lisner-core' ),
					'rtl' => esc_html__( 'Right to Left', 'lisner-core' ),
				),
				'desc'    => esc_html__( 'Choose content direction of your site. If it is not showing after clicking on Save just refresh page once more.', 'lisner-core' ),
				'tooltip' => array(
					'icon'     => 'help',
					'content'  => esc_html__( 'Sometimes content won\'t change immediately after clicking save so you need to refresh page once more in order for content to switch.', 'lisner-core' ),
					'position' => 'top'
				),
				'tab'     => 'appearance_identity'
			),
			array(
				'id'               => 'site-logo',
				'name'             => esc_html__( 'Logo', 'lisner-core' ),
				'type'             => 'image_advanced',
				'desc'             => esc_html__( 'Choose the logo of your site', 'lisner-core' ),
				'max_file_uploads' => 1,
				'tab'              => 'appearance_identity'
			),
			array(
				'id'      => 'site-logo-padding',
				'name'    => esc_html__( 'Logo Padding', 'lisner-core' ),
				'type'    => 'fieldset_text',
				'desc'    => esc_html__( 'Position logo with additional padding. Value has to be numeric.', 'lisner-core' ),
				'options' => array(
					'top'    => esc_html__( 'Padding Top', 'lisner-core' ),
					'right'  => esc_html__( 'Padding Right', 'lisner-core' ),
					'bottom' => esc_html__( 'Padding Bottom', 'lisner-core' ),
					'left'   => esc_html__( 'Padding Left', 'lisner-core' ),
				),
				'tab'     => 'appearance_identity'
			),
			array(
				'id'   => 'site-logo-size',
				'name' => esc_html__( 'Logo Size', 'lisner-core' ),
				'type' => 'number',
				'desc' => esc_html__( 'Enter the exact width value of the logo', 'lisner-core' ),
				'min'  => 0,
				'tab'  => 'appearance_identity'
			),
		);

		// Theme Settings / Menu
		$appearance = array(
			// menu
			array(
				'id'      => 'header-template',
				'name'    => esc_html__( 'Menu Template', 'lisner-core' ),
				'type'    => 'image_select',
				'desc'    => esc_html__( 'Choose Main Menu Header Template', 'lisner-core' ),
				'options' => array(
					'1' => esc_url( LISNER_URL . 'assets/images/admin/listing_template_1.png' ),
					'2' => esc_url( LISNER_URL . 'assets/images/admin/listing_template_2.png' ),
				),
				'std'     => '1',
				'tab'     => 'appearance_header'
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
				'tab'           => 'appearance_header',
				'tooltip'       => array(
					'icon'     => 'info',
					'content'  => esc_html__( 'Used for sticky menu and for some header templates', 'lisner-core' ),
					'position' => 'top'
				)
			),
			array(
				'name'          => esc_html__( 'Menu Font Color', 'lisner-core' ),
				'id'            => 'color-menu-font',
				'type'          => 'color',
				'desc'          => esc_html__( 'Please set menu font color', 'lisner-core' ),
				'alpha_channel' => true,
				'std'           => '#fff',
				'js_options'    => array(
					'color'        => '#fff',
					'defaultColor' => '#fff',
					'palettes'     => false,
				),
				'tab'           => 'appearance_header',
				'tooltip'       => array(
					'icon'     => 'info',
					'content'  => esc_html__( 'Font color hover is set by changing primary color in "Theme Colors" tab of this section', 'lisner-core' ),
					'position' => 'top'
				)
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
				'tab'           => 'appearance_header',
				'tooltip'       => array(
					'icon'     => 'info',
					'content'  => esc_html__( 'Used for dropdown menu', 'lisner-core' ),
					'position' => 'top'
				)
			),
			// Sticky Menu
			array(
				'type' => 'heading',
				'name' => esc_html__( 'Sticky Menu Settings', 'lisner-core' ),
				'desc' => esc_html__( 'Please choose your sticky menu settings', 'lisner-core' ),
				'tab'  => 'appearance_header'
			),
			array(
				'name'    => esc_html__( 'Sticky Menu?', 'lisner-core' ),
				'id'      => 'menu-sticky',
				'type'    => 'select',
				'desc'    => esc_html__( 'Please choose whether you wish to use sticky menu or not.', 'lisner-core' ),
				'options' => array(
					'yes' => esc_html__( 'Yes', 'lisner-core' ),
					'no'  => esc_html__( 'No', 'lisner-core' )
				),
				'std'     => 'yes',
				'tab'     => 'appearance_header'
			),
			array(
				'name'          => esc_html__( 'Sticky Menu Background Color', 'lisner-core' ),
				'id'            => 'color-menu-sticky-bg',
				'type'          => 'color',
				'desc'          => esc_html__( 'Please set menu background color', 'lisner-core' ),
				'alpha_channel' => true,
				'std'           => '#37003c',
				'js_options'    => array(
					'color'        => '#37003c',
					'defaultColor' => '#37003c',
					'palettes'     => false,
				),
				'tab'           => 'appearance_header',
				'tooltip'       => array(
					'icon'     => 'info',
					'content'  => esc_html__( 'Used for sticky menu and for some header templates', 'lisner-core' ),
					'position' => 'top'
				),
				'hidden'        => array( 'menu-sticky', '!=', 'yes' )
			),
			array(
				'name'          => esc_html__( 'Sticky Menu Font Color', 'lisner-core' ),
				'id'            => 'color-menu-sticky-font',
				'type'          => 'color',
				'desc'          => esc_html__( 'Please set sticky menu font color', 'lisner-core' ),
				'alpha_channel' => true,
				'std'           => '#fff',
				'js_options'    => array(
					'color'        => '#fff',
					'defaultColor' => '#fff',
					'palettes'     => false,
				),
				'tab'           => 'appearance_header',
				'tooltip'       => array(
					'icon'     => 'info',
					'content'  => esc_html__( 'Font color hover is set by changing primary color in "Theme Colors" tab of this section', 'lisner-core' ),
					'position' => 'top'
				),
				'hidden'        => array( 'menu-sticky', '!=', 'yes' )
			),
		);

		// Theme Settings / theme colors
		$theme_colors = array(
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
				'tab'           => 'appearance_colors',
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
				'tab'           => 'appearance_colors',
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
				'tab'           => 'appearance_colors',
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
				'tab'           => 'appearance_colors',
			),
			// Footer colors
			array(
				'type' => 'heading',
				'name' => esc_html__( 'Footer Colors', 'lisner-core' ),
				'desc' => esc_html__( 'Colors for the footer section of the site', 'lisner-core' ),
				'tab'  => 'appearance_colors'
			),
			array(
				'name'          => esc_html__( 'Footer Background Color', 'lisner-core' ),
				'id'            => 'color-footer-bg',
				'type'          => 'color',
				'desc'          => esc_html__( 'Set footer background color', 'lisner-core' ),
				'alpha_channel' => true,
				'std'           => '#28002c',
				'js_options'    => array(
					'color'        => '#28002c',
					'defaultColor' => '#28002c',
				),
				'tab'           => 'appearance_colors',
			),
			array(
				'name'          => esc_html__( 'Footer Font Color', 'lisner-core' ),
				'id'            => 'color-footer-font',
				'type'          => 'color',
				'desc'          => esc_html__( 'Set footer font color', 'lisner-core' ),
				'alpha_channel' => true,
				'std'           => '#ffffff',
				'js_options'    => array(
					'color'        => '#ffffff',
					'defaultColor' => '#ffffff',
				),
				'tab'           => 'appearance_colors',
			),
			// Copyrights colors
			array(
				'type' => 'heading',
				'name' => esc_html__( 'Copyrights Colors', 'lisner-core' ),
				'desc' => esc_html__( 'Colors for the copyrights section of the site', 'lisner-core' ),
				'tab'  => 'appearance_colors'
			),
			array(
				'name'          => esc_html__( 'Copyrights Background Color', 'lisner-core' ),
				'id'            => 'color-copy-bg',
				'type'          => 'color',
				'desc'          => esc_html__( 'Set copyrights background color', 'lisner-core' ),
				'alpha_channel' => true,
				'std'           => '#210024',
				'js_options'    => array(
					'color'        => '#210024',
					'defaultColor' => '#210024',
				),
				'tab'           => 'appearance_colors',
			),
			array(
				'name'          => esc_html__( 'Copyrights Font Color', 'lisner-core' ),
				'id'            => 'color-copy-font',
				'type'          => 'color',
				'desc'          => esc_html__( 'Set copyrights font color', 'lisner-core' ),
				'alpha_channel' => true,
				'std'           => '#ffffff',
				'js_options'    => array(
					'color'        => '#ffffff',
					'defaultColor' => '#ffffff',
				),
				'tab'           => 'appearance_colors',
			),
		);

		// Theme Settings / theme fonts
		$theme_fonts = array(
			array(
				'name'          => esc_html__( 'Theme Font', 'lisner-core' ),
				'id'            => 'theme-font',
				'type'          => 'select_advanced',
				'desc'          => esc_html__( 'Choose theme font from the list of available ones', 'lisner-core' ),
				'alpha_channel' => true,
				'std'           => 'Assistant',
				'options'       => $this->get_google_fonts(),
				'tab'           => 'appearance_fonts',
			),
		);

		$appearance_fields = array_merge( $identity, $appearance, $theme_colors, $theme_fonts );
		$meta_boxes[]      = array(
			'title'          => esc_html__( 'Theme Appearance', 'lisner-core' ),
			'settings_pages' => 'lisner-options-appearance',
			'fields'         => $appearance_fields,
			'context'        => 'normal',
			'tabs'           => array(
				'appearance_identity' => array(
					'label' => esc_html__( 'Site Identity', 'lisner-core' ),
					'icon'  => 'dashicons-arrow-right'
				),
				'appearance_header'   => array(
					'label' => esc_html__( 'Header & Menu', 'lisner-core' ),
					'icon'  => 'dashicons-arrow-right'
				),
				'appearance_colors'   => array(
					'label' => esc_html__( 'Theme Colors', 'lisner-core' ),
					'icon'  => 'dashicons-arrow-right'
				),
				'appearance_fonts'    => array(
					'label' => esc_html__( 'Theme Fonts', 'lisner-core' ),
					'icon'  => 'dashicons-arrow-right'
				),
			),
			'tab_style'      => 'left',
			'tab_wrapper'    => true,
		);

		// Bookings Extension / bookings fields
		$bookings_fields = array(
			array(
				'name' => esc_html__( 'Booking Percentage', 'lisner-core' ),
				'id'   => 'booking-percentage',
				'type' => 'number',
				'min'  => '0',
				'max'  => '100',
				'desc' => esc_html__( 'Choose percentage that you will get from each booking sale on your site. Leave to 0 or empty to make booking sales free of your percentage. Changing this will not affect already calculated percentages.', 'lisner-core' ),
				'std'  => '0',
			),
			array(
				'name'    => esc_html__( 'Payout Information Display?', 'lisner-core' ),
				'id'      => 'booking-payout-display',
				'type'    => 'select',
				'options' => array(
					1 => esc_html__( 'Display', 'lisner-core' ),
					0 => esc_html__( 'Hide', 'lisner-core' ),
				),
				'desc'    => esc_html__( 'Choose whether you wish to display payout user information section', 'lisner-core' ),
				'std'     => '1',
			),
			array(
				'name'   => esc_html__( 'Payout Title', 'lisner-core' ),
				'id'     => 'booking-payout-title',
				'type'   => 'text',
				'desc'   => esc_html__( 'Enter the title of the Bookings Ordered section that is used to present users information about payout dates and site tax.', 'lisner-core' ),
				'hidden' => array( 'booking-payout-display', '!=', 1 )
			),
			array(
				'name'        => esc_html__( 'Payouts Description', 'lisner-core' ),
				'id'          => 'booking-payout-description',
				'type'        => 'textarea',
				'desc'        => esc_html__( 'Explain to users how you will send payouts to them and what is the percentage you will get from each booking sold on your site. To display percentage use following tag: [percentage]', 'lisner-core' ),
				'placeholder' => esc_html__( 'The site will gather {[percentage]% from each booking sale} that is made on one of your listings. PayPal fees for sending payouts are included in the tax. Payouts are sent once per day.', 'lisner-core' ),
				'hidden'      => array( 'booking-payout-display', '!=', 1 ),
				'tooltip'     => array(
					'icon'     => 'info',
					'content'  => esc_html__( 'You can use following tags: [percentage] and putting the word between {} will make it bolder.', 'lisner-core' ),
					'position' => 'top'
				),
			),
			array(
				'type' => 'heading',
				'name' => esc_html__( 'Bookings Appearance ', 'lisner-core' ),
				'desc' => esc_html__( 'Set bookings style on listing single page', 'lisner-core' ),
			),
			array(
				'name'    => esc_html__( 'Booking Form Display', 'lisner-core' ),
				'id'      => 'booking-display',
				'type'    => 'select',
				'options' => array(
					'top'     => esc_html__( 'As first widget in sidebar', 'lisner-core' ),
					'default' => esc_html__( 'Above listing contact form', 'lisner-core' ),
				),
				'desc'    => esc_html__( 'Choose whether you wish to display booking form as first widget on listing single form.', 'lisner-core' ),
				'std'     => 'default',
			),
		);

		$meta_boxes[] = array(
			'title'          => esc_html__( 'Bookings Extension Setup', 'lisner-core' ),
			'settings_pages' => 'lisner-options-bookings',
			'fields'         => $bookings_fields,
			'context'        => 'normal',
		);

		return $meta_boxes;
	}

	/**
	 * Update WooCommerce `terms_page_id` setting
	 * when terms page is set through our custom settings
	 *
	 * @param $null
	 * @param $field
	 * @param $new
	 * @param $old
	 * @param $post_id
	 */
	public function set_terms_page_template( $null, $field, $new, $old, $post_id ) {

		$woocommerce_terms_page = get_option( 'woocommerce_terms_page_id' );
		if ( $new != $old || $new != $woocommerce_terms_page ) {
			update_option( 'woocommerce_terms_page_id', $new ); // update woocommerce default terms page
		}
	}

	/**
	 * Create array of google fonts
	 *
	 * @return array
	 */
	public function get_google_fonts() {
		$google_fonts = include( LISNER_DIR . 'includes/google-fonts.php' );
		$font_list    = array();
		if ( isset( $google_fonts ) ) {
			foreach ( $google_fonts as $label => $name ) {
				$font_list[ $name ] = $label;
			}
		}

		return $font_list;
	}

	/**
	 * Get all country with their respective codes
	 *
	 * @return mixed|null
	 */
	public function get_country_codes() {
		$countryList = array(
			'AF' => 'Afghanistan',
			'AX' => 'Aland Islands',
			'AL' => 'Albania',
			'DZ' => 'Algeria',
			'AS' => 'American Samoa',
			'AD' => 'Andorra',
			'AO' => 'Angola',
			'AI' => 'Anguilla',
			'AQ' => 'Antarctica',
			'AG' => 'Antigua and Barbuda',
			'AR' => 'Argentina',
			'AM' => 'Armenia',
			'AW' => 'Aruba',
			'AU' => 'Australia',
			'AT' => 'Austria',
			'AZ' => 'Azerbaijan',
			'BS' => 'Bahamas the',
			'BH' => 'Bahrain',
			'BD' => 'Bangladesh',
			'BB' => 'Barbados',
			'BY' => 'Belarus',
			'BE' => 'Belgium',
			'BZ' => 'Belize',
			'BJ' => 'Benin',
			'BM' => 'Bermuda',
			'BT' => 'Bhutan',
			'BO' => 'Bolivia',
			'BA' => 'Bosnia and Herzegovina',
			'BW' => 'Botswana',
			'BV' => 'Bouvet Island (Bouvetoya)',
			'BR' => 'Brazil',
			'IO' => 'British Indian Ocean Territory (Chagos Archipelago)',
			'VG' => 'British Virgin Islands',
			'BN' => 'Brunei Darussalam',
			'BG' => 'Bulgaria',
			'BF' => 'Burkina Faso',
			'BI' => 'Burundi',
			'KH' => 'Cambodia',
			'CM' => 'Cameroon',
			'CA' => 'Canada',
			'CV' => 'Cape Verde',
			'KY' => 'Cayman Islands',
			'CF' => 'Central African Republic',
			'TD' => 'Chad',
			'CL' => 'Chile',
			'CN' => 'China',
			'CX' => 'Christmas Island',
			'CC' => 'Cocos (Keeling) Islands',
			'CO' => 'Colombia',
			'KM' => 'Comoros the',
			'CD' => 'Congo',
			'CG' => 'Congo the',
			'CK' => 'Cook Islands',
			'CR' => 'Costa Rica',
			'CI' => 'Cote d\'Ivoire',
			'HR' => 'Croatia',
			'CU' => 'Cuba',
			'CY' => 'Cyprus',
			'CZ' => 'Czech Republic',
			'DK' => 'Denmark',
			'DJ' => 'Djibouti',
			'DM' => 'Dominica',
			'DO' => 'Dominican Republic',
			'EC' => 'Ecuador',
			'EG' => 'Egypt',
			'SV' => 'El Salvador',
			'GQ' => 'Equatorial Guinea',
			'ER' => 'Eritrea',
			'EE' => 'Estonia',
			'ET' => 'Ethiopia',
			'FO' => 'Faroe Islands',
			'FK' => 'Falkland Islands (Malvinas)',
			'FJ' => 'Fiji the Fiji Islands',
			'FI' => 'Finland',
			'FR' => 'France, French Republic',
			'GF' => 'French Guiana',
			'PF' => 'French Polynesia',
			'TF' => 'French Southern Territories',
			'GA' => 'Gabon',
			'GM' => 'Gambia the',
			'GE' => 'Georgia',
			'DE' => 'Germany',
			'GH' => 'Ghana',
			'GI' => 'Gibraltar',
			'GR' => 'Greece',
			'GL' => 'Greenland',
			'GD' => 'Grenada',
			'GP' => 'Guadeloupe',
			'GU' => 'Guam',
			'GT' => 'Guatemala',
			'GG' => 'Guernsey',
			'GN' => 'Guinea',
			'GW' => 'Guinea-Bissau',
			'GY' => 'Guyana',
			'HT' => 'Haiti',
			'HM' => 'Heard Island and McDonald Islands',
			'VA' => 'Holy See (Vatican City State)',
			'HN' => 'Honduras',
			'HK' => 'Hong Kong',
			'HU' => 'Hungary',
			'IS' => 'Iceland',
			'IN' => 'India',
			'ID' => 'Indonesia',
			'IR' => 'Iran',
			'IQ' => 'Iraq',
			'IE' => 'Ireland',
			'IM' => 'Isle of Man',
			'IL' => 'Israel',
			'IT' => 'Italy',
			'JM' => 'Jamaica',
			'JP' => 'Japan',
			'JE' => 'Jersey',
			'JO' => 'Jordan',
			'KZ' => 'Kazakhstan',
			'KE' => 'Kenya',
			'KI' => 'Kiribati',
			'KP' => 'Korea',
			'KR' => 'Korea',
			'KW' => 'Kuwait',
			'KG' => 'Kyrgyz Republic',
			'LA' => 'Lao',
			'LV' => 'Latvia',
			'LB' => 'Lebanon',
			'LS' => 'Lesotho',
			'LR' => 'Liberia',
			'LY' => 'Libyan Arab Jamahiriya',
			'LI' => 'Liechtenstein',
			'LT' => 'Lithuania',
			'LU' => 'Luxembourg',
			'MO' => 'Macao',
			'MK' => 'Macedonia',
			'MG' => 'Madagascar',
			'MW' => 'Malawi',
			'MY' => 'Malaysia',
			'MV' => 'Maldives',
			'ML' => 'Mali',
			'MT' => 'Malta',
			'MH' => 'Marshall Islands',
			'MQ' => 'Martinique',
			'MR' => 'Mauritania',
			'MU' => 'Mauritius',
			'YT' => 'Mayotte',
			'MX' => 'Mexico',
			'FM' => 'Micronesia',
			'MD' => 'Moldova',
			'MC' => 'Monaco',
			'MN' => 'Mongolia',
			'ME' => 'Montenegro',
			'MS' => 'Montserrat',
			'MA' => 'Morocco',
			'MZ' => 'Mozambique',
			'MM' => 'Myanmar',
			'NA' => 'Namibia',
			'NR' => 'Nauru',
			'NP' => 'Nepal',
			'AN' => 'Netherlands Antilles',
			'NL' => 'Netherlands the',
			'NC' => 'New Caledonia',
			'NZ' => 'New Zealand',
			'NI' => 'Nicaragua',
			'NE' => 'Niger',
			'NG' => 'Nigeria',
			'NU' => 'Niue',
			'NF' => 'Norfolk Island',
			'MP' => 'Northern Mariana Islands',
			'NO' => 'Norway',
			'OM' => 'Oman',
			'PK' => 'Pakistan',
			'PW' => 'Palau',
			'PS' => 'Palestinian Territory',
			'PA' => 'Panama',
			'PG' => 'Papua New Guinea',
			'PY' => 'Paraguay',
			'PE' => 'Peru',
			'PH' => 'Philippines',
			'PN' => 'Pitcairn Islands',
			'PL' => 'Poland',
			'PT' => 'Portugal, Portuguese Republic',
			'PR' => 'Puerto Rico',
			'QA' => 'Qatar',
			'RE' => 'Reunion',
			'RO' => 'Romania',
			'RU' => 'Russian Federation',
			'RW' => 'Rwanda',
			'BL' => 'Saint Barthelemy',
			'SH' => 'Saint Helena',
			'KN' => 'Saint Kitts and Nevis',
			'LC' => 'Saint Lucia',
			'MF' => 'Saint Martin',
			'PM' => 'Saint Pierre and Miquelon',
			'VC' => 'Saint Vincent and the Grenadines',
			'WS' => 'Samoa',
			'SM' => 'San Marino',
			'ST' => 'Sao Tome and Principe',
			'SA' => 'Saudi Arabia',
			'SN' => 'Senegal',
			'RS' => 'Serbia',
			'SC' => 'Seychelles',
			'SL' => 'Sierra Leone',
			'SG' => 'Singapore',
			'SK' => 'Slovakia (Slovak Republic)',
			'SI' => 'Slovenia',
			'SB' => 'Solomon Islands',
			'SO' => 'Somalia, Somali Republic',
			'ZA' => 'South Africa',
			'GS' => 'South Georgia and the South Sandwich Islands',
			'ES' => 'Spain',
			'LK' => 'Sri Lanka',
			'SD' => 'Sudan',
			'SR' => 'Suriname',
			'SJ' => 'Svalbard & Jan Mayen Islands',
			'SZ' => 'Swaziland',
			'SE' => 'Sweden',
			'CH' => 'Switzerland, Swiss Confederation',
			'SY' => 'Syrian Arab Republic',
			'TW' => 'Taiwan',
			'TJ' => 'Tajikistan',
			'TZ' => 'Tanzania',
			'TH' => 'Thailand',
			'TL' => 'Timor-Leste',
			'TG' => 'Togo',
			'TK' => 'Tokelau',
			'TO' => 'Tonga',
			'TT' => 'Trinidad and Tobago',
			'TN' => 'Tunisia',
			'TR' => 'Turkey',
			'TM' => 'Turkmenistan',
			'TC' => 'Turks and Caicos Islands',
			'TV' => 'Tuvalu',
			'UG' => 'Uganda',
			'UA' => 'Ukraine',
			'AE' => 'United Arab Emirates',
			'GB' => 'United Kingdom',
			'US' => 'United States of America',
			'UM' => 'United States Minor Outlying Islands',
			'VI' => 'United States Virgin Islands',
			'UY' => 'Uruguay, Eastern Republic of',
			'UZ' => 'Uzbekistan',
			'VU' => 'Vanuatu',
			'VE' => 'Venezuela',
			'VN' => 'Vietnam',
			'WF' => 'Wallis and Futuna',
			'EH' => 'Western Sahara',
			'YE' => 'Yemen',
			'ZM' => 'Zambia',
			'ZW' => 'Zimbabwe'
		);

		$countries = array();
		foreach ( $countryList as $code => $country ) {
			$countries[ $code ] = $country;
		}

		return $countries;
	}

}

/** Instantiate class
 *
 * @return null|lisner_settings
 */
function lisner_settings() {
	return lisner_settings::instance();
}
