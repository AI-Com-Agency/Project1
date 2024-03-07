<?php

/*
	Our portfolio: https://themeforest.net/user/pebas
	pebas- 2018
 */

// load configuration file
require_once get_parent_theme_file_path( 'pbs_config.php' );

// load helper functions
require_once get_parent_theme_file_path( 'includes/pbs_helpers.php' );

// load theme includes
require_once get_parent_theme_file_path( 'includes/pbs_functions.php' );

// load theme related functions
require_once get_parent_theme_file_path( 'includes/pbs_theme_functions.php' );
require_once get_parent_theme_file_path( 'includes/pbs_post_functions.php' );
if ( pbs_global::$is_woocommerce_installed ) {
	require_once get_parent_theme_file_path( 'woocommerce/pbs_woocommerce_functions.php' );
}
