<?php

/**
 * Storage of the global state of the theme
 *
 * @author pebas
 * @version 1.0.0
 */

if ( ! class_exists( 'pbs_global' ) ):
	class pbs_global {

		static $is_woocommerce_installed = false; // check if the WooCommerce is installed

		static $is_vc_installed = false; // check if the Visual Composer is installed

		static $http_or_https = 'http'; // check if secured protocol is used

		static $theme_plugins = array();

	}

	// check what server protocol is used
	if ( is_ssl() ) {
		pbs_global::$http_or_https = 'https';
	}


	// check whether is WooCommerce activated
	if ( class_exists( 'WooCommerce' ) ) {
		pbs_global::$is_woocommerce_installed = true;
	}

	// check whether is WPBakery Visual Composer activated
	if ( class_exists( 'Vc_Manager' ) ) {
		pbs_global::$is_vc_installed = true;
	}


endif;
