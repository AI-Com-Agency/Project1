<?php
/**
 * Plugin Name: pebas® Paid Listings
 * Plugin URI: https://www.themeforest.net/user/pebas/pebas-paid-listings
 * Description: pebas® Paid Listings plugin used for hour listing themes ( Lisner, Ager )
 * Version: 1.0.2
 * Author: pebas
 * Author URI: https://www.themeforest.net/user/pebas
 * Requires at least: 4.1
 * Tested up to: 4.9.8
 * Text Domain: pebas-paid-listings
 * Domain Path: /locales/
 * License: GPL2+
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Pebas_Paid_Listings class.
 */
class Pebas_Paid_Listings {

	protected static $_instance = null;

	/**
	 * @return null|Pebas_Paid_Listings
	 */
	public static function instance() {
		return null === self::$_instance ? ( self::$_instance = new self ) : self::$_instance;
	}

	/**
	 * Constructor - get the plugin hooked in and ready
	 */
	public function __construct() {
		// Define constants
		define( 'PEBAS_PL_NAME', $this::plugin_data( 'Plugin Name' ) );
		define( 'PEBAS_PL_SLUG', $this::plugin_data( 'Text Domain' ) );
		define( 'PEBAS_PL_VERSION', $this::plugin_data( 'Version' ) );
		define( 'PEBAS_PL_DIR', untrailingslashit( $this->plugin_path() ) . '/' );
		define( 'PEBAS_PL_URL', untrailingslashit( plugins_url( basename( $this->plugin_path() ), basename( __FILE__ ) ) ) . '/' );

		// Plugins loaded
		add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ), 12 );
		add_action( 'admin_notices', array( $this, 'no_parent_plugin_notice' ), 10 );
		add_action( 'plugins_loaded', array( $this, 'init_plugin' ), 13 );
	}

	public function no_parent_plugin_notice() {
		if ( ! class_exists( 'WP_Job_Manager' ) || ! class_exists( 'WooCommerce' ) || ! class_exists( 'Lisner_Core' ) ) {
			?>
			<div class="error notice">
				<p><?php _e( '<strong>pebas® paid listings</strong> requires <strong>Lisner Core & WooCommerce</strong> plugins to be installed in order to be used.', 'pebas-paid-listings' ); ?></p>
			</div>
			<?php
		}
	}

	/**
	 * Initialize the plugin
	 */
	public function init_plugin() {
		if ( ! class_exists( 'WP_Job_Manager' ) || ! class_exists( 'WooCommerce' ) || ! class_exists( 'Lisner_Core' ) ) {
			return;
		}

		// Include required classes
		require_once PEBAS_PL_DIR . 'includes/pebas-paid-listings-setup.php';

		// Activation - works with symlinks
		register_activation_hook( basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ), array(
			$this,
			'activate'
		) );

		// Switch theme
		add_action( 'after_switch_theme', 'flush_rewrite_rules', 15 );

		// Actions
		add_action( 'admin_init', array( $this, 'updater' ) );
		add_action( 'after_setup_theme', array( $this, 'include_functions' ), 11 );
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

	}

	/**
	 * Called on plugin activation
	 */
	public function activate() {
	}

	/**
	 * Handle Updates
	 */
	public function updater() {
		$version = get_option( 'ppl_version' );
		if ( version_compare( PEBAS_PL_VERSION, $version, '>' ) ) {
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
		load_plugin_textdomain( 'pebas-paid-listings', false, basename( dirname( __FILE__ ) ) . '/locales' );
	}

	/**
	 * Register and enqueue scripts and css
	 */
	public function frontend_scripts() {
		// styles

		// scripts
		$this->localize_vars();
	}

	/**
	 * Localize lister-theme vars
	 */
	public function localize_vars() {
		wp_localize_script( 'pebas-pl-theme', 'pebas_data', array(
			'url'       => get_template_directory_uri(),
			'ajaxurl'   => admin_url( 'admin-ajax.php' ),
			'is_mobile' => wp_is_mobile() ? true : false
		) );
	}

	/**
	 * Register and enqueue scripts and css
	 */
	public function admin_scripts() {
		$screen = get_current_screen();
		// styles
		//wp_enqueue_style( 'lister-theme-admin', PEBAS_PL_URL . 'assets/styles/admin.css', '', '1.0.0', 'all' );

		// scripts
		//wp_enqueue_script( 'lister-theme-admin-js', PEBAS_PL_URL . 'assets/scripts/admin.js', array( 'jquery' ), '', true );
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

	/**
	 * Check if plugin has been activated
	 *
	 * @param $plugin_dir
	 * @param string $plugin_filename - Leave empty if plugin filename is the same as directory it's it
	 *
	 * @return bool
	 */
	public static function is_plugin_active( $plugin_dir, $plugin_filename = '' ) {
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		$plugin = $plugin_dir . '/' . ( ! empty( $plugin_filename ) ? $plugin_filename : $plugin_dir ) . '.php';
		if ( is_plugin_active( $plugin ) ) {
			return true;
		}

		return false;
	}

}

/**
 * Instantiate class
 *
 * @return Pebas_Paid_Listings|null
 */
function pebas_paid_listings() {
	return Pebas_Paid_Listings::instance();
}

pebas_paid_listings();
