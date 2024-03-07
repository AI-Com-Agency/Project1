<?php
/**
 * Listing Coupons Setup
 *
 * @author pebas
 * @ver 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

include_once( PEBAS_LC_DIR . '/includes/pebas-listing-coupons-install.php' );
pebas_listing_coupons_install();

// coupons
include_once( PEBAS_LC_DIR . '/includes/pebas_coupons.php' );
pebas_coupons();

// wp-admin
include_once( PEBAS_LC_DIR . '/includes/admin/pebas_coupons_admin.php' );
include_once( PEBAS_LC_DIR . '/includes/admin/pebas_coupons_meta.php' );
pebas_coupons_admin();
pebas_coupons_meta();


