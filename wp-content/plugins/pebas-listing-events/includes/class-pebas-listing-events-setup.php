<?php
/**
 * Listing Events Setup
 *
 * @author pebas
 * @ver 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

include_once( PEBAS_LE_DIR . '/includes/class-pebas-listing-events-install.php' );
pebas_listing_events_install();

// coupons
include_once( PEBAS_LE_DIR . '/includes/class-pebas-events.php' );
pebas_events();

// wp-admin
include_once( PEBAS_LE_DIR . '/includes/admin/class-pebas-events-admin.php' );
include_once( PEBAS_LE_DIR . '/includes/admin/class-pebas-events-meta.php' );
pebas_events_admin();
pebas_events_meta();


