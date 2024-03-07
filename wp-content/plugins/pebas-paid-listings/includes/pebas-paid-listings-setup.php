<?php
/**
 * Paid Listing Setup
 *
 * @author pebas
 * @ver 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

include_once( PEBAS_PL_DIR . '/includes/pebas-paid-listings-install.php' );
pebas_paid_listings_install();
include_once( PEBAS_PL_DIR . '/includes/pebas_products_meta.php' );
pebas_products_meta();
include_once( PEBAS_PL_DIR . '/includes/pebas_pl_package_product.php' );
include_once( PEBAS_PL_DIR . '/includes/pebas_wc_product_package.php' );
include_once( PEBAS_PL_DIR . '/includes/pebas_pl_admin.php');
include_once( PEBAS_PL_DIR . '/includes/pebas_pl_cart.php' );
include_once( PEBAS_PL_DIR . '/includes/pebas_pl_orders.php' );
include_once( PEBAS_PL_DIR . '/includes/pebas_pl_subscriptions.php' );
include_once( PEBAS_PL_DIR . '/includes/pebas_pl_package.php' );
include_once( PEBAS_PL_DIR . '/includes/pebas_pl_submit_listing_form.php' );
include_once( PEBAS_PL_DIR . '/includes/user-functions.php' );
include_once( PEBAS_PL_DIR . '/includes/package-functions.php' );

// Load 3rd party customizations
require_once( PEBAS_PL_DIR . '/includes/3rd-party/3rd-party.php' );

if ( class_exists( 'WC_Product_Subscription' ) ) {
	include_once( PEBAS_PL_DIR . '/includes/pebas_pl_subscription_product.php' );
	include_once( PEBAS_PL_DIR . '/includes/pebas_wc_product_subscription_package.php' );
}