<?php

/**
 *
 * Global configuration file
 *
 * @author pebas
 * @version 1.0.0
 */

if ( ! class_exists( 'pbs_config' ) ) :
	class pbs_config {
		// Access any method or var of the class with class_name::$instance -> var or method():
		protected static $_instance = null;

		public static $theme_name;

		/**
		 * @return null| pbs_config
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		function __construct() {

			/* GET THEME INFORMATION FROM STYLE.CSS */
			// get theme_data version wp 3.4+
			if ( function_exists( 'wp_get_theme' ) ) {
				// get WP_Theme object of theme
				$pbs_theme = wp_get_theme();

				// get info of parent theme if using child theme
				$pbs_theme = $pbs_theme->parent() ? $pbs_theme->parent() : $pbs_theme;

				$pbs_theme['prefix']     = $pbs_theme['title'] = $pbs_theme->name;
				$pbs_theme['author_uri'] = $pbs_theme->{'Author URI'};
			} // get theme_data for lower versions of WordPress
			else {
				$pbs_theme           = call_user_func( 'get_' . 'theme_data', get_stylesheet_directory() . '/style.css' );
				$pbs_theme['prefix'] = $pbs_theme['title'];
			}

			self::$theme_name = sanitize_file_name( strtolower( $pbs_theme['title'] ?? 'lisner' ) );

			// define theme version
			if ( ! defined( 'PBS_THEME_VERSION' ) ) {
				define( 'PBS_THEME_VERSION', $pbs_theme->version );
			}
			// define root server path of the parent theme
			if ( ! defined( 'PBS_THEME' ) ) {
				define( 'PBS_THEME', get_template_directory() . '/' );
			}
			// define root server path of the child theme
			if ( ! defined( 'PBS_THEME_CHILD' ) ) {
				define( 'PBS_THEME_CHILD', get_stylesheet_directory() . '/' );
			}
			// define http url of the loaded parent theme
			if ( ! defined( 'PBS_THEME_URL' ) ) {
				define( 'PBS_THEME_URL', get_template_directory_uri() . '/' );
			}
			// define http or url of the loaded child theme
			if ( ! defined( 'PBS_THEME_URL_CHILD' ) ) {
				define( 'PBS_THEME_URL_CHILD', get_stylesheet_directory_uri() . '/' );
			}
			// define name of the currently loaded theme
			if ( ! defined( 'PBS_THEME_NAME' ) ) {
				define( 'PBS_THEME_NAME', 'lisner' );
			}
			// define home website of the theme
			if ( ! defined( 'PBS_WEBSITE' ) ) {
				define( 'PBS_WEBSITE', $pbs_theme['author_uri'] );
			}

			// load demo configuration
			$stage = ''; // leave this empty to disable demo environment
			if ( 'demo' == $stage ) {
				define( 'PBS_DEMO', 'demo' );
				update_option( 'pbs_is_demo', true );
			} else {
				update_option( 'pbs_is_demo', false );
			}

			// check if it is demo site
			if ( ! function_exists( 'pbs_is_demo' ) ) {
				function pbs_is_demo() {
					if ( PBS_THEME_NAME == get_option( 'template' ) ) {
						if ( current_user_can( 'administrator' ) ) {
							return false;
						}
						if ( defined( 'PBS_DEMO' ) ) {
							return true;
						}
					}

					return false;
				}
			}

		} // end of construct

	} // end of class
endif;

// instantiate class
function pbs_config() {
	return pbs_config::instance();
}

pbs_config();
