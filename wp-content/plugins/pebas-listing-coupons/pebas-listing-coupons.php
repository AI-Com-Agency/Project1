<?php
/**
 * Plugin Name: pebas® Listing Coupons
 * Plugin URI: https://www.themeforest.net/user/pebas/pebas-bookmark-listings
 * Description: pebas® Listing Coupons plugin used for pebas® listing themes
 * Version: 1.0.4
 * Author: pebas
 * Author URI: https://www.themeforest.net/user/pebas
 * Requires at least: 4.1
 * Tested up to: 4.9.8
 * Text Domain: pebas-listing-coupons
 * Domain Path: /locales/
 * License: GPL2+
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Pebas_Listing_Coupons class.
 */
class Pebas_Listing_Coupons {

	protected static $_instance = null;

	/**
	 * @return null|Pebas_Listing_Coupons
	 */
	public static function instance() {
		return null === self::$_instance ? ( self::$_instance = new self ) : self::$_instance;
	}

	/**
	 * Constructor - get the plugin hooked in and ready
	 */
	public function __construct() {
		// Define constants
		define( 'PEBAS_LC_NAME', $this::plugin_data( 'Plugin Name' ) );
		define( 'PEBAS_LC_SLUG', $this::plugin_data( 'Text Domain' ) );
		define( 'PEBAS_LC_VERSION', $this::plugin_data( 'Version' ) );
		define( 'PEBAS_LC_DIR', untrailingslashit( $this->plugin_path() ) . '/' );
		define( 'PEBAS_LC_URL', untrailingslashit( plugins_url( basename( $this->plugin_path() ), basename( __FILE__ ) ) ) . '/' );

		// Plugins loaded
		add_action( 'admin_notices', array( $this, 'no_parent_plugin_notice' ), 10 );
		add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ), 12 );
		add_action( 'plugins_loaded', array( $this, 'init_plugin' ), 13 );
	}

	public function no_parent_plugin_notice() {
		if ( ! class_exists( 'Lisner_Core' ) ) {
			?>
			<div class="error notice">
				<p><?php _e( '<strong>pebas® claim listings</strong> requires <strong>Lisner Core & WooCommerce</strong> plugins to be installed in order to be used.', 'pebas-paid-listings' ); ?></p>
			</div>
			<?php
		}
	}

	/**
	 * Initialize the plugin
	 */
	public function init_plugin() {
		if ( ! class_exists( 'Lisner_Core' ) ) {
			return;
		}

		// set coupons cronjob
		if ( ! wp_next_scheduled( 'plc_hourly_event' ) ) {
			wp_schedule_event( time(), 'hourly', 'plc_hourly_event' );
		}

		// Include required files
		require_once PEBAS_LC_DIR . 'includes/pebas-listing-coupons-setup.php';

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
		add_action( 'after_switch_theme', 'flush_rewrite_rules', 15 );

		// Actions
		add_action( 'admin_init', array( $this, 'updater' ) );
		add_action( 'after_setup_theme', array( $this, 'include_functions' ), 11 );
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

		// Instantiate necessary classes
	}

	/**
	 * Called on plugin activation
	 */
	public function activate() {
	}

	public function deactivate() {
		// set coupons cronjob
		wp_clear_scheduled_hook( 'plc_hourly_event' );
	}

	/**
	 * Handle Updates
	 */
	public function updater() {
		$version = get_option( 'pebas_listing_coupons_version' );
		if ( version_compare( PEBAS_LC_VERSION, $version, '>' ) ) {
			flush_rewrite_rules();
		}
	}

	/**
	 * Load functions
	 */
	public function include_functions() {
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
		load_plugin_textdomain( 'pebas-listing-coupons', false, basename( dirname( __FILE__ ) ) . '/locales' );
	}

	/**
	 * Register and enqueue scripts and css
	 */
	public function frontend_scripts() {
		if ( isset( $_REQUEST['action'] ) && ( 'coupons' == $_REQUEST['action'] || 'add_coupon' == $_REQUEST['action'] ) ) {
			wp_enqueue_media();

			// styles
			wp_enqueue_style( 'pebas-lc-timepicker-css', PEBAS_LC_URL . 'assets/styles/timepicker.css', '', '', 'all' );

			// scripts
			wp_enqueue_script( 'pebas-lc-media-uploader', PEBAS_LC_URL . 'assets/scripts/media-uploader.js', array( 'jquery' ), '', true );
			wp_enqueue_script( 'pebas-lc-timepicker', PEBAS_LC_URL . 'assets/scripts/timepicker.js', array( 'jquery' ), '', true );
		}
		if ( is_singular( 'job_listing' ) ) {
			wp_enqueue_script( 'pebas-lc-clipboard', PEBAS_LC_URL . 'assets/scripts/clipboard.min.js', array( 'jquery' ), '', true );
			wp_enqueue_script( 'pebas-lc-print', PEBAS_LC_URL . 'assets/scripts/printThis.js', array( 'jquery' ), '', true );
		}
		if ( isset( $_REQUEST['action'] ) && ( 'coupons' == $_REQUEST['action'] || 'add_coupon' == $_REQUEST['action'] ) || is_singular( 'job_listing' ) ) {
			wp_enqueue_script( 'pebas-lc-theme', PEBAS_LC_URL . 'assets/scripts/listing-coupons.js', array( 'jquery' ), '', true );
		}

		$this->localize_vars();
	}

	/**
	 * Localize lisner-theme vars
	 */
	public function localize_vars() {
		wp_localize_script( 'pebas-lc-theme', 'lc_data', array(
			'url'            => get_template_directory_uri(),
			'ajaxurl'        => admin_url( 'admin-ajax.php' ),
			'lisner_ajaxurl' => esc_url( LISNER_URL . 'includes/lisner-ajax.php' ),
			'is_mobile'      => wp_is_mobile() ? true : false,
			'days'           => esc_html__( 'd', 'pebas-listing-coupons' ),
			'hours'          => esc_html__( 'h', 'pebas-listing-coupons' ),
			'minutes'        => esc_html__( 'm', 'pebas-listing-coupons' ),
			'seconds'        => esc_html__( 's', 'pebas-listing-coupons' ),
		) );
	}

	/**
	 * Register and enqueue scripts and css
	 */
	public function admin_scripts() {
		$screen = get_current_screen();
		// styles
		//wp_enqueue_style( 'lister-theme-admin', PEBAS_LC_URL . 'assets/styles/admin.css', '', '1.0.0', 'all' );

		// scripts
		//wp_enqueue_script( 'lister-theme-admin-js', PEBAS_LC_URL . 'assets/scripts/admin.js', array( 'jquery' ), '', true );
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

//todo move this to function file later
function pbs_count_coupons( $listing_id ) {
	return pebas_coupons()->count_coupons( $listing_id );
}

function pbs_count_active_coupons( $listing_id ) {
	return pebas_coupons()->get_active_coupons( $listing_id );
}

/**
 * Instantiate class
 *
 * @return Pebas_Listing_Coupons|null
 */
function pebas_listing_coupons() {
	return Pebas_Listing_Coupons::instance();
}

pebas_listing_coupons();
