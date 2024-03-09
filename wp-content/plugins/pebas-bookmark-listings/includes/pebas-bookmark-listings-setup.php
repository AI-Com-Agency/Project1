<?php
/**
 * Bookmark Listings Setup
 *
 * @author pebas
 * @ver 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

include_once( PEBAS_BM_DIR . '/includes/pebas-bookmark-listings-install.php' );
pebas_bookmark_listings_install();

// bookmark
include_once( PEBAS_BM_DIR . '/includes/pebas_bookmark.php' );
pebas_bookmark();

// wp-admin
include_once( PEBAS_BM_DIR . '/includes/admin/pebas_bookmark_admin.php' );
include_once( PEBAS_BM_DIR . '/includes/admin/pebas_bookmark_meta.php' );
pebas_bookmark_admin();
pebas_bookmark_meta();


