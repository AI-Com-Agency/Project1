<?php
/**
 * Plugin Name: Lisner Core
 * Plugin URI: https://www.themeforest.net/user/pebas/lisner
 * Description: Lisner core plugin for extending Lisner directory theme
 * Version: 1.5.0
 * Author: pebas®
 * Author URI: https://www.themeforest.net/user/pebas
 * Requires at least: 4.1
 * Tested up to: 5.2
 * Text Domain: lisner-core
 * Domain Path: /locales/
 * License: GPL2+
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Lisner_Core class.
 */
class Lisner_Core {

	protected static $_instance = null;

	/**
	 * @return null|Lisner_Core
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Constructor - get the plugin hooked in and ready
	 */
	public function __construct() {

		// Define constants
		define( 'LISNER_NAME', $this::plugin_data( 'Plugin Name' ) );
		define( 'LISNER_SLUG', $this::plugin_data( 'Text Domain' ) );
		define( 'LISNER_VERSION', $this::plugin_data( 'Version' ) );
		define( 'LISNER_DIR', untrailingslashit( $this->plugin_path() ) . '/' );
		define( 'LISNER_URL',
			untrailingslashit( plugins_url( basename( $this->plugin_path() ), basename( __FILE__ ) ) ) . '/' );

		// Include required classes
		require_once LISNER_DIR . 'includes/lisner_rest.php';
		require_once LISNER_DIR . 'listings/lisner_statistics.php';
		require_once LISNER_DIR . 'listings/lisner_listings_post_type.php';
		require_once LISNER_DIR . 'listings/lisner_listings.php';
		require_once LISNER_DIR . 'listings/lisner_dashboard.php';
		require_once LISNER_DIR . 'listings/lisner_search.php';
		require_once LISNER_DIR . 'includes/templater/lisner_templater.php';

		// theme user authorization
		require_once LISNER_DIR . 'includes/lisner_auth.php';

		// theme && custom fields options
		require_once LISNER_DIR . 'includes/lisner_meta.php';
		require_once LISNER_DIR . 'includes/lisner_term_meta.php';
		require_once LISNER_DIR . 'includes/lisner_settings.php';

		require_once LISNER_DIR . 'includes/meta-box-extension/mb-includes.php';

		require_once LISNER_DIR . 'includes/lisner_rest.php';
		require_once LISNER_DIR . 'includes/lisner_helper.php';

		// shortcodes and widgets
		require_once LISNER_DIR . 'shortcodes/lisner_shortcodes.php';
		require_once LISNER_DIR . 'widgets/lisner_widgets.php';

		// wp menu extender
		require_once LISNER_DIR . 'includes/menu-extender/lisner_menu_fields.php';
		require_once LISNER_DIR . 'includes/menu-extender/lisner_menu_actions.php';

		// pages && page specifics
		require_once LISNER_DIR . 'includes/pages/lisner_hero.php';
		require_once LISNER_DIR . 'includes/pages/lisner_pages.php';


		// Activation - works with symlinks
		register_activation_hook( basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ), array(
			$this,
			'activate'
		) );

		register_deactivation_hook( basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ), array(
			$this,
			'deactivate'
		) );

		// Switch theme
		add_action( 'current_screen', array( $this, 'conditional_includes' ) );
		add_action( 'after_switch_theme', 'flush_rewrite_rules', 15 );

		// Plugins loaded
		add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );

		// Actions
		add_action( 'admin_init', array( $this, 'updater' ) );
		add_action( 'after_setup_theme', array( $this, 'include_functions' ), 11 );
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

		// Instantiate necessary classes
		lisner_statistics();
		lisner_listings_post_type();
		lisner_listings();
		lisner_dashboard();
		lisner_search();
		lisner_templater();
		lisner_shortcodes();
		lisner_widgets();
		lisner_meta();
		lisner_term_meta();
		lisner_settings();
		lisner_menu_fields();
		lisner_menu_actions();
		lisner_auth();
		lisner_hero();
		lisner_pages();
		lisner_rest();
	}

	/**
	 * Include admin files conditionally.
	 */
	public function conditional_includes() {
		$screen = get_current_screen();
		if ( ! $screen ) {
			return;
		}
		switch ( $screen->id ) {
			case 'options-permalink':
				include LISNER_DIR . 'listings/lisner_listings_permalink_settings.php';
				break;
		}
	}

	/**
	 * Called on plugin activation
	 */
	public function activate() {
		wp_schedule_event( time(), 'daily', 'lisner__demo_cron_daily' );
	}

	public function deactivate() {
		wp_clear_scheduled_hook( 'lisner__demo_cron_daily' );
	}

	/**
	 * Handle Updates
	 */
	public function updater() {
		if ( version_compare( LISNER_VERSION, get_option( 'tbm_version' ), '>' ) ) {
			flush_rewrite_rules();
		}
	}

	/**
	 * Load functions
	 */
	public function include_functions() {
		require_once LISNER_DIR . 'includes/lisner-functions.php';

	}

	/**
	 * Widgets init
	 */
	public function widgets_init() {
	}

	/**
	 * Load textdomain
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'lisner-core', false, basename( dirname( __FILE__ ) ) . '/locales' );
	}

	/**
	 * Register and enqueue scripts and css
	 */
	public function frontend_scripts() {
		$option   = get_option( 'pbs_option' );
		$language = get_locale();
		$language = explode( '_', $language );
		$language = isset( $language[0] ) ? $language[0] : 'en';
		$api      = isset( $option['map-google-api'] ) ? $option['map-google-api'] : '';
		// styles
		if ( isset( $option['theme-font'] ) && ! empty( $option['theme-font'] ) && 'Assistant' != $option['theme-font'] ) {
			wp_enqueue_style( 'pbs-theme-fonts',
				"https://fonts.googleapis.com/css?family={$option['theme-font']}:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i",
				'', PBS_THEME_VERSION, 'all' );
		}

		if ( isset( $_REQUEST['action'] ) && ( 'coupons' == $_REQUEST['action'] || 'add_coupon' == $_REQUEST['action'] ) ) {
			wp_enqueue_style( 'jquery-ui' );
			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_enqueue_script( 'jquery-ui-slider' );
		}

		if ( get_option( 'woocommerce_myaccount_page_id' ) ) {
			wp_enqueue_media();
			wp_enqueue_style( 'lisner-chart-style', LISNER_URL . 'assets/styles/morris.css' );

			wp_enqueue_script( 'lisner-media-uploader', LISNER_URL . 'assets/scripts/media-uploader.js',
				array( 'jquery' ), '', true );
			wp_enqueue_script( 'lisner-raphael', LISNER_URL . 'assets/scripts/raphael.min.js', array( 'jquery' ), '',
				true );
			wp_enqueue_script( 'lisner-charts', LISNER_URL . 'assets/scripts/morris.min.js', array( 'jquery' ), '',
				true );
		}

		wp_enqueue_style( 'lisner-map', LISNER_URL . 'assets/styles/leaflet.css' );
		wp_enqueue_style( 'lisner-theme-timepicker-style', LISNER_URL . 'assets/styles/jquery.timepicker.min.css', '',
			'1.0.0', 'all' );
		wp_enqueue_style( 'lisner-select2', LISNER_URL . 'assets/styles/select2.min.css' );
		wp_enqueue_style( 'lisner-chosen', LISNER_URL . 'assets/styles/chosen.min.css' );

		// scripts
		//todo: call only on necessary pages and not everywhere
		wp_enqueue_script( 'lisner-theme-chosen', LISNER_URL . 'assets/scripts/chosen.jquery.min.js', array( 'jquery' ),
			'', true );
		wp_enqueue_script( 'lisner-theme-select2', LISNER_URL . 'assets/scripts/select2.min.js', array( 'jquery' ), '',
			true );
		wp_enqueue_script( 'lisner-theme-timepicker', LISNER_URL . 'assets/scripts/jquery.timepicker.min.js',
			array( 'jquery' ), '', true );
		wp_enqueue_script( 'lisner-theme-sticky', LISNER_URL . 'assets/scripts/sticky.js', array( 'jquery' ), '',
			true );
		wp_enqueue_script( 'lisner-theme-map', LISNER_URL . 'assets/scripts/leaflet.js', array( 'jquery' ), '', true );
		wp_enqueue_script( 'lisner-theme-google-map',
			"https://maps.googleapis.com/maps/api/js?key={$api}&libraries=places&language={$language}",
			array( 'jquery' ), '', true );

		// search page scripts
		wp_register_style( 'lisner-theme-map-cluster-style', LISNER_URL . 'assets/styles/marker-cluster.css', '', '',
			'all' );
		wp_register_script( 'lisner-theme-map-cluster', LISNER_URL . 'assets/scripts/leaflet.markercluster.js',
			array( 'jquery' ), '', true );
		wp_register_script( 'lisner-theme-search', LISNER_URL . 'assets/scripts/search.js', array( 'jquery' ), '',
			true );

		// coming soon and coupons script
		wp_register_script( 'lisner-theme-countdown', LISNER_URL . 'assets/scripts/jquery.countdown.min.js',
			array( 'jquery' ), '', true );

		// profile page styles & scripts
		wp_register_script( 'lisner-dashboard-theme', LISNER_URL . 'assets/scripts/profile/theme-profile.js',
			array( 'jquery' ), '', true );

		// toast notification
		wp_enqueue_style( 'lisner-theme-toast-style', LISNER_URL . 'assets/styles/toast/iziToast.min.css', '', '',
			'all' );
		wp_enqueue_script( 'lisner-theme-toast', LISNER_URL . 'assets/scripts/toast/iziToast.min.js', array( 'jquery' ),
			'', true );

		// single page scripts
		if ( is_singular( 'job_listing' ) || is_page( get_option( 'job_manager_submit_job_form_page_id' ) ) ) {
			wp_enqueue_style( 'lisner-theme-magnific-popup-style', LISNER_URL . 'assets/styles/magnific-popup.css', '',
				'1.0.0', 'all' );
			wp_enqueue_style( 'lisner-theme-photoswipe-style', LISNER_URL . 'assets/styles/photoswipe.css', '', '1.0.0',
				'all' );
			wp_enqueue_style( 'lisner-theme-photoswipe-style-default',
				LISNER_URL . 'assets/styles/photoswipe/default-skin/default-skin.css', '', '1.0.0', 'all' );
			wp_enqueue_style( 'lisner-theme-slick', LISNER_URL . 'assets/styles/slick/slick.css', '', '1.0.0', 'all' );
			wp_enqueue_style( 'lisner-theme-slick-theme', LISNER_URL . 'assets/styles/slick/slick-theme.css', '',
				'1.0.0', 'all' );

			wp_enqueue_script( 'lisner-magnific-popup', LISNER_URL . 'assets/scripts/jquery.magnific-popup.min.js',
				array( 'jquery' ), '', true );
			wp_enqueue_script( 'lisner-photoswipe-popup', LISNER_URL . 'assets/scripts/photoswipe.min.js',
				array( 'jquery' ), '', true );
			wp_enqueue_script( 'lisner-photoswipe-default', LISNER_URL . 'assets/scripts/photoswipe-ui-default.min.js',
				array( 'jquery' ), '', true );
			wp_enqueue_script( 'lisner-slick', LISNER_URL . 'assets/scripts/slick.min.js', array( 'jquery' ), '',
				true );
			wp_enqueue_script( 'lisner-single', LISNER_URL . 'assets/scripts/theme-single.js', array( 'jquery' ), '',
				true );
		}

		wp_enqueue_script( 'lisner-theme', LISNER_URL . 'assets/scripts/theme.js', array( 'jquery' ), '', true );

		// localize variables
		$this->localize_vars();
	}

	/**
	 * Localize lister-theme vars
	 */
	public function localize_vars() {
		$option          = get_option( 'pbs_option' );
		$rtl             = isset( $option['site-direction'] ) && 'rtl' == $option['site-direction'] ? true : false;
		$map_style       = lisner_get_option( 'map-style-id', '' );
		$map_style_api   = lisner_get_option( 'map-style-api', '' );
		$mapbox_username = lisner_get_option( 'map-mapbox-username', '' );
		$mapbox_url      = ! empty( $map_style ) && $map_style_api ? "https://api.mapbox.com/styles/v1/{$mapbox_username}/{$map_style}/tiles/256/{z}/{x}/{y}?access_token={$map_style_api}" : '';
		$ajax_url        = lisner_search::get_endpoint();
		$vars            = array(
			'is_demo'              => function_exists( 'pbs_is_demo' ) && pbs_is_demo() ? true : false,
			'is_rtl'               => $rtl,
			'url'                  => get_template_directory_uri(),
			'ajaxurl'              => admin_url( 'admin-ajax.php' ),
			'ajax_url'             => isset( $ajax_url ) ? $ajax_url : esc_url( LISNER_URL . 'includes/lisner-ajax.php' ),
			'lisner_ajaxurl'       => esc_url( LISNER_URL . 'includes/lisner-ajax.php' ),
			'is_custom_tax'        => is_tax( array(
				'listing_location',
				'listing_amenity',
				'listing_tag',
				'job_listing_category'
			) ),
			'resturl'              => esc_url( home_url( '/wp-json/' . lisner_rest()->namespace ) ),
			'is_mobile'            => wp_is_mobile() ? true : false,
			'is_home'              => is_front_page() ? true : false,
			'is_home_template'     => is_page_template( 'templates/tpl-home.php' ),
			'is_profile'           => class_exists( 'WooCommerce' ) && is_account_page() ? true : false,
			'is_single_listing'    => is_singular( 'job_listing' ) ? true : false,
			'is_search'            => lisner_helper::is_search_page() ? true : false,
			'mapbox_url'           => $mapbox_url,
			'is_multi_category'    => lisner_hero()->allow_multi_category_search( get_the_ID() ) ? true : false,
			'user_ip'              => lisner_helper()->get_client_ip(),
			'is_logged'            => is_user_logged_in() ? true : false,
			'is_listing'           => is_singular( 'job_listing' ) ? true : false,
			'is_submit'            => is_page( get_option( 'job_manager_submit_job_form_page_id' ) ) ? true : false,
			'days'                 => esc_html__( 'Days', 'lisner-core' ),
			'hours'                => esc_html__( 'Hours', 'lisner-core' ),
			'minutes'              => esc_html__( 'Minutes', 'lisner-core' ),
			'seconds'              => esc_html__( 'Seconds', 'lisner-core' ),
			'location_no_results'  => esc_html__( 'No locations found', 'lisner-core' ),
			'listing_map_zoom'     => lisner_get_option( 'listings-map-zoom', 18 ),
			'country_restriction'  => isset( $option['map-country-restriction'] ) ? $option['map-country-restriction'] : false,
			'geolocation_provider' => isset( $option['map-geolocation-provider'] ) ? $option['map-geolocation-provider'] : 'ipapi',
			'autocomplete_format'  => isset( $option['general-location-autocomplete-return'] ) ? $option['general-location-autocomplete-return'] : 'full',
			'ipapi_api'            => isset( $option['map-ipapi-key'] ) ? $option['map-ipapi-key'] : false,
			'color_primary'        => isset( $option['color-primary'] ) ? $option['color-primary'] : false,
			'time_format'          => isset( $option['units-clock'] ) ? $option['units-clock'] : '24',
			'stats_ctr'            => lisner_statistics::is_stat_enabled( 'listing-statistics-ctr-enable' ) ? true : false,
			'show_address'         => is_user_logged_in() || lisner_show_to_member( $option['listing-fields-address-members'] ) ? true : false,
		);
		wp_localize_script( 'lisner-theme', 'lisner_data', $vars );
		wp_localize_script( 'lisner-single', 'lisner_data', $vars );
		wp_localize_script( 'lisner-theme-search', 'lisner_data', $vars );
	}

	/**
	 * Register and enqueue scripts and css
	 */
	public function admin_scripts() {
		// styles
		wp_enqueue_style( 'lisner-material-icons', 'https://fonts.googleapis.com/icon?family=Material+Icons', '',
			PBS_THEME_VERSION, 'all' );
		wp_enqueue_style( 'lisner-theme-admin', LISNER_URL . 'assets/styles/admin.css', '', '1.0.0', 'all' );

		// scripts
		wp_enqueue_script( 'lisner-theme-admin-js', LISNER_URL . 'assets/scripts/admin.js', array( 'jquery' ), '',
			true );
		$this->localize_vars();
	}

	/**
	 * Path to plugin
	 *
	 * @return string
	 */
	public function plugin_path() {
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Get specific plugin data
	 *
	 * @param $name
	 *
	 * @return array
	 */
	public static function plugin_data( $name ) {
		$data = get_file_data( __FILE__, array( $name ), 'plugin' );

		return array_shift( $data );
	}

}

/**
 * Instantiate class
 *
 * @return Lisner_Core|null
 */
function lisner_core() {
	return Lisner_Core::instance();
}

lisner_core();
