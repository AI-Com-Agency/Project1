<?php
/**
 * Plugin Name: pebas® Mega Menu
 * Plugin URI: https://www.themeforest.net/user/pebas/
 * Description: Mega menu plugin for themes created by pebas®
 * Version: 1.0.0
 * Author: pebas
 * Author URI: https://www.themeforest.net/user/pebas
 * Requires at least: 4.1
 * Tested up to: 4.9.8
 * Text Domain:pebas-mega-menu
 * Domain Path: /languages/
 * License: GPL2+
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * pebas_mega_menu class.
 */
class pebas_mega_menu {

	protected static $_instance = null;

	/**
	 * @return null|pebas_mega_menu
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
		define( 'PEBAS_MM_NAME', $this::plugin_data( 'Plugin Name' ) );
		define( 'PEBAS_MM_SLUG', $this::plugin_data( 'Text Domain' ) );
		define( 'PEBAS_MM_VERSION', $this::plugin_data( 'Version' ) );
		define( 'PEBAS_MM_DIR', untrailingslashit( $this->plugin_path() ) . '/' );
		define( 'PEBAS_MM_URL', untrailingslashit( plugins_url( basename( $this->plugin_path() ), basename( __FILE__ ) ) ) . '/' );

		// Include required classes
		require_once PEBAS_MM_DIR . 'includes/class-pebas-mega-menu-meta.php';
		require_once PEBAS_MM_DIR . 'includes/class-pebas-post_type.php';
		require_once PEBAS_MM_DIR . 'includes/class-pebas-mega-menu-helper.php';
		require_once PEBAS_MM_DIR . 'includes/nav/class-pebas-mega-menu-nav.php';


		// Activation - works with symlinks
		register_activation_hook( basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ), array(
			$this,
			'activate'
		) );

		// Switch theme
		add_action( 'after_switch_theme', 'flush_rewrite_rules', 15 );

		// Plugins loaded
		add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );

		// Actions
		add_action( 'admin_init', array( $this, 'updater' ) );
		add_action( 'after_setup_theme', array( $this, 'include_functions' ), 11 );
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );

		// Instantiate necessary classes
		pebas_mega_menu_post_type();
		pebas_mega_menu_meta();

		// add image sizes
		add_image_size( 'mega-menu-thumbnail', 685, 285, true );
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
		if ( version_compare( PEBAS_MM_VERSION, get_option( 'tbm_mm_version' ), '>' ) ) {
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
		load_plugin_textdomain( 'pebas-mega-menu', false, basename( dirname( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Register and enqueue scripts and css
	 */
	public function frontend_scripts() {
		// styles
		wp_enqueue_style( 'lisner-theme-slick', PEBAS_MM_URL . 'assets/styles/slick/slick.css', '', '1.0.0', 'all' );
		wp_enqueue_style( 'lisner-theme-slick-theme', PEBAS_MM_URL . 'assets/styles/slick/slick-theme.css', '', '1.0.0', 'all' );

		// scripts
		wp_enqueue_script( 'lisner-slick', PEBAS_MM_URL . 'assets/scripts/slick.min.js', array( 'jquery' ), '', true );
		wp_enqueue_script( 'mega-menu-theme', PEBAS_MM_URL . 'assets/scripts/theme.js', array( 'jquery' ), '', true );
		$this->localize_vars();
	}

	public function localize_vars() {
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

function pebas_mega_menu() {
	return pebas_mega_menu::instance();
}

pebas_mega_menu();
