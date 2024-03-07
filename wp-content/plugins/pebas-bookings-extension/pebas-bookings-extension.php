<?php
/**
 * Plugin Name: pebas® Bookings Extension
 * Plugin URI: https://www.themeforest.net/user/pebas/pebas-bookings-extension
 * Description: Pebas Bookings Listings extension plugin for WooCommerce Bookings that enables frontend administration used for our listing themes ( Lisner, Ager )
 * Version: 1.0.4
 * Author: pebas
 * Author URI: https://www.themeforest.net/user/pebas
 * Requires at least: 4.1
 * Tested up to: 4.9.8
 * Text Domain: pebas-bookings-extension
 * Domain Path: /locales/
 * License: GPL2+
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Pebas_Bookings class.
 */
class Pebas_Bookings {

	protected static $_instance = null;

	/**
	 * @return null|Pebas_Bookings
	 */
	public static function instance() {
		return null === self::$_instance ? ( self::$_instance = new self ) : self::$_instance;
	}

	/**
	 * Constructor - get the plugin hooked in and ready
	 */
	public function __construct() {
		// Define constants
		define( 'PEBAS_BO_NAME', $this::plugin_data( 'Plugin Name' ) );
		define( 'PEBAS_BO_SLUG', $this::plugin_data( 'Text Domain' ) );
		define( 'PEBAS_BO_VERSION', $this::plugin_data( 'Version' ) );
		define( 'PEBAS_BO_DIR', untrailingslashit( $this->plugin_path() ) . '/' );
		define( 'PEBAS_BO_URL', untrailingslashit( plugins_url( basename( $this->plugin_path() ), basename( __FILE__ ) ) ) . '/' );

		// Plugins loaded
		add_action( 'admin_notices', array( $this, 'no_parent_plugin_notice' ), 10 );
		add_action( 'plugins_loaded', array( $this, 'init_plugin' ), 13 );

		// Dismissible notice
		add_action( 'wp_ajax_pbe_dismiss_notice', array( $this, 'pbe_dismiss_notice' ) );
	}

	/**
	 * No parent plugin notice display
	 */
	public function no_parent_plugin_notice() {
		$option = get_option( 'pbe_dismiss_notice' );
		if ( ! $option ) {
			if ( ! class_exists( 'WP_Job_Manager' ) || ! class_exists( 'WooCommerce' ) || ! class_exists( 'Lisner_Core' ) || ! class_exists( 'WC_Bookings' ) ) {
				?>
				<div class="notice notice-warning is-dismissible pbe-dismiss">
					<p><?php _e( '<strong>pebas® bookings extension</strong> requires <strong>Lisner Core, WooCommerce & WooCommerce Bookings</strong> plugins to be activated in order to work properly. You can freely dismiss this notice & deactivate <strong>pebas® bookings extension</strong> if you don\'t plan to use it.', 'pebas-paid-listings' ); ?></p>
				</div>
				<script>
                    jQuery('.pbe-dismiss').on('click', '.notice-dismiss', function () {
                        let $this = jQuery(this);
                        jQuery.ajax({
                            url: ajaxurl,
                            method: 'POST',
                            data: {
                                action: 'pbe_dismiss_notice'
                            },
                            success: function (result) {
                            },
                        });
                    });
				</script>
				<?php
			}
		}
	}

	/**
	 * Set notice value as dismissed
	 */
	public function pbe_dismiss_notice() {
		$option                       = update_option( 'pbe_dismiss_notice', 1 );
		$result['pbe_dismiss_notice'] = $option;

		wp_send_json_success( $option );
	}

	/**
	 * Initialize the plugin
	 */
	public function init_plugin() {
		if ( ! class_exists( 'WP_Job_Manager' ) || ! class_exists( 'WooCommerce' ) || ! class_exists( 'Lisner_Core' ) || ! class_exists( 'WC_Bookings' ) ) {
			return;
		}

		// Include required files
		require_once PEBAS_BO_DIR . 'includes/pebas-booking-setup.php';

		include_once( WP_CONTENT_DIR . '/plugins/woocommerce-bookings/woocommerce-bookings.php' );
		include_once WP_CONTENT_DIR . '/plugins/woocommerce/includes/admin/wc-meta-box-functions.php';
		include_once WP_CONTENT_DIR . '/plugins/woocommerce-bookings/includes/admin/class-wc-bookings-admin.php';


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

		// Instantiate necessary classes

	}

	/**
	 * Called on plugin activation
	 */
	public function activate() {

		if ( ! self::is_plugin_active( 'wp-job-manager' ) ) {
			deactivate_plugins( plugin_basename( __FILE__ ) );

			wp_die( esc_html__( 'This plugin requires WP Job Manager installed.', 'pebas-report-listings' ) );
		}
	}

	/**
	 * Handle Updates
	 */
	public function updater() {
		$version = get_option( 'pebas_report_listings_version' );
		if ( version_compare( PEBAS_BO_VERSION, $version, '>' ) ) {
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
		load_plugin_textdomain( 'pebas-bookings-extension', false, basename( dirname( __FILE__ ) ) . '/locales' );
	}

	/**
	 * Register and enqueue scripts and css
	 */
	public function frontend_scripts() {
		global $post;
		// styles
		wp_enqueue_style( 'pebas-bookings-wc-icons', PEBAS_BO_URL . 'assets/styles/style.css' );

		// scripts
		wp_register_script( 'pbs_bookings_writepanel_js', PEBAS_BO_URL . '/assets/scripts/writepanel.js', array(
			'jquery',
			'jquery-ui-datepicker'
		), '', true );

		$params = array(
			'i18n_remove_person'     => esc_js( __( 'Are you sure you want to remove this person type?', 'pebas-bookings-extension' ) ),
			'nonce_unlink_person'    => wp_create_nonce( 'unlink-bookable-person' ),
			'nonce_add_person'       => wp_create_nonce( 'add-bookable-person' ),
			'i18n_remove_resource'   => esc_js( __( 'Are you sure you want to remove this resource?', 'pebas-bookings-extension' ) ),
			'nonce_delete_resource'  => wp_create_nonce( 'delete-bookable-resource' ),
			'nonce_add_resource'     => wp_create_nonce( 'add-bookable-resource' ),
			'i18n_minutes'           => esc_js( __( 'minutes', 'pebas-bookings-extension' ) ),
			'i18n_hours'             => esc_js( __( 'hours', 'pebas-bookings-extension' ) ),
			'i18n_days'              => esc_js( __( 'days', 'pebas-bookings-extension' ) ),
			'i18n_new_resource_name' => esc_js( __( 'Enter a name for the new resource', 'pebas-bookings-extension' ) ),
			'post'                   => isset( $post->ID ) ? $post->ID : '',
			'plugin_url'             => WC()->plugin_url(),
			'ajax_url'               => admin_url( 'admin-ajax.php' ),
			'calendar_image'         => WC()->plugin_url() . '/assets/images/calendar.png',
		);

		wp_localize_script( 'pbs_bookings_writepanel_js', 'wc_bookings_writepanel_js_params', $params );
		wp_enqueue_script( 'pebas-bo-theme', PEBAS_BO_URL . 'assets/scripts/theme-booking.js', array(
			'jquery',
			'jquery-ui-sortable'
		), '', true );

		$this->localize_vars();
	}

	/**
	 * Localize lisner-theme vars
	 */
	public function localize_vars() {
		wp_localize_script( 'pebas-bo-theme', 'pebas_bo_data', array(
			'url'            => get_template_directory_uri(),
			'ajaxurl'        => admin_url( 'admin-ajax.php' ),
			'lisner_ajaxurl' => esc_url( LISNER_URL . 'includes/lisner-ajax.php' ),
			'is_mobile'      => wp_is_mobile() ? true : false,
			'is_demo'        => pbs_is_demo() ? true : false,
		) );
		wp_localize_script( 'pebas-bo-theme-admin', 'pebas_bo_data', array(
			'url'                => get_template_directory_uri(),
			'ajaxurl'            => admin_url( 'admin-ajax.php' ),
			'lisner_ajaxurl'     => esc_url( LISNER_URL . 'includes/lisner-ajax.php' ),
			'is_mobile'          => wp_is_mobile() ? true : false,
			'is_demo'            => pbs_is_demo() ? true : false,
			'processing_payment' => esc_html__( 'Processing...' )
		) );
	}

	/**
	 * Register and enqueue scripts and css
	 */
	public function admin_scripts() {
		$screen    = get_current_screen();
		$type_name = pebas_payouts_install::$type_name;
		// styles

		// scripts
		if ( "edit-{$type_name}" == $screen->id ) {
			wp_enqueue_style( 'pbe-theme-toast-style', PEBAS_BO_URL . 'assets/styles/toast/iziToast.min.css', '', '', 'all' );
			wp_enqueue_script( 'pbe-theme-toast', PEBAS_BO_URL . 'assets/scripts/toast/iziToast.min.js', array( 'jquery' ), '', true );
		}
		wp_enqueue_script( 'pebas-bo-theme-admin', PEBAS_BO_URL . 'assets/scripts/theme-booking-admin.js', array( 'jquery' ), '', true );
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
 * @return Pebas_Bookings|null
 */
function pebas_bookings() {
	return Pebas_Bookings::instance();
}

pebas_bookings();
