<?php
/**
 * Report Listings Setup
 *
 * @author pebas
 * @ver 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

include_once( PEBAS_RL_DIR . '/includes/pebas-report-listings-install.php' );
pebas_report_listings_install();

// report
include_once( PEBAS_RL_DIR . '/includes/pebas_report.php' );

// wp-admin
include_once( PEBAS_RL_DIR . '/includes/admin/pebas_report_admin.php' );
include_once( PEBAS_RL_DIR . '/includes/admin/pebas_report_meta.php' );
pebas_report_admin();
pebas_report_meta();


