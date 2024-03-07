<?php

/**
 * Usage: Creating Custom Menu Section
 * User: PowerThemes
 */
class pbs_menu {
	function __construct() {
		add_action( 'admin_menu', array( $this, 'pbs_register_pebas_menu' ) );

		add_filter( 'upload_mimes', 'upload_types' );
		function upload_types( $mime_types ) {
			$mime_types['woff'] = 'application/x-font-woff';

			return $mime_types;
		}

	}

	/**
	 * register our theme panel via the hook
	 */
	function pbs_register_pebas_menu() {
		add_theme_page( 'Theme panel', 'Pebas', "edit_posts", "pbs_welcome", array(
			$this,
			"pbs_welcome",
		) );

	}

	function pbs_welcome() {
		require_once "pages/pbs_welcome.php";
	}
}

new pbs_menu();