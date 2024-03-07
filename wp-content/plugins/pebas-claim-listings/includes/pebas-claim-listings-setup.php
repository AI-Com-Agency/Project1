<?php
/**
 * Include && instantiate necessary classes
 *
 * @author pebas
 * @ver 1.1.0
 */

// Claim Post Types
require_once PEBAS_CL_DIR . 'includes/claim/pebas_claim_install.php';
require_once PEBAS_CL_DIR . 'includes/claim/pebas_claim_admin.php';
require_once PEBAS_CL_DIR . 'includes/claim/pebas_claim.php';
require_once PEBAS_CL_DIR . 'includes/claim/pebas_claim_meta.php';

pebas_claim_install(); // install listing claims post types and taxonomies
pebas_claim_admin(); // listing claims wp-admin dashboard and functionality
pebas_claim(); // listing claims class
pebas_claim_meta(); // listing claims meta

// Listing Post Types
require_once PEBAS_CL_DIR . 'includes/listing/pebas_listing_meta.php';
require_once PEBAS_CL_DIR . 'includes/listing/pebas_listing_admin.php';
require_once PEBAS_CL_DIR . 'includes/listing/pebas_listing_claim.php';
pebas_listing_meta(); // listing meta
pebas_listing_admin(); // listing admin
pebas_listing_claim(); // listing claim

// Claim Submit
require_once PEBAS_CL_DIR . 'includes/claim-submit/pebas_claim_submit_claim.php';
require_once PEBAS_CL_DIR . 'includes/claim-submit/pebas_claim_submit_form.php';
pebas_claim_submit_claim(); // submit claim

// Paid Listings Post Types
require_once PEBAS_CL_DIR . 'includes/pebas-paid-listings/pebas_pl_meta.php';
require_once PEBAS_CL_DIR . 'includes/pebas-paid-listings/pebas_pl_claim.php';
require_once PEBAS_CL_DIR . 'includes/pebas-paid-listings/pebas_pl_claim_order.php';
require_once PEBAS_CL_DIR . 'includes/pebas-paid-listings/pebas_pl_claim_form.php';
require_once PEBAS_CL_DIR . 'includes/pebas-paid-listings/pebas_pl_claim_checkout.php';
pebas_pl_meta(); // paid listings meta
pebas_pl_claim(); // paid listings claim
pebas_claim_form(); // paid listings claim form
pebas_pl_claim_order(); // paid listings claim order
pebas_pl_claim_checkout(); // paid listings claim checkout

// Claim Listings Notification
require_once PEBAS_CL_DIR . 'includes/notification/pebas_claim_notification.php';
pebas_claim_notification(); // claim listing notifications

// Claim Listings Settings
require_once PEBAS_CL_DIR . 'includes/settings/pebas_claim_listings_settings.php';
pebas_claim_listings_settings(); // claim listings WP Job Manager settings extension

// Include Functions
require_once PEBAS_CL_DIR . 'includes/claim-functions.php';
