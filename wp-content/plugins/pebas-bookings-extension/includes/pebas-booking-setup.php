<?php
/**
 * Booking Listings Setup
 *
 * @author pebas
 * @ver 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// bookmark
include_once( PEBAS_BO_DIR . '/includes/pebas_booking.php' );
pebas_booking();

// admin payouts
include_once( PEBAS_BO_DIR . '/payouts/class-pebas-payouts-install.php' );
include_once( PEBAS_BO_DIR . '/payouts/class-pebas-payouts-meta.php' );
include_once( PEBAS_BO_DIR . '/payouts/class-paypal.php' );
include_once( PEBAS_BO_DIR . '/payouts/class-pebas-payouts-admin.php' );
pebas_payouts_install();
pebas_payouts_meta();
pebas_payouts_admin();
