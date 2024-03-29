<?php
/**
 * Plugin Name: Meta Box Include Exclude
 * Plugin URI: https://metabox.io/plugins/meta-box-include-exclude/
 * Description: Easily show/hide meta boxes by ID, page template, taxonomy or custom defined function.
 * Version: 1.0.9
 * Author: Rilwis
 * Author URI: http://www.deluxeblogtips.com
 * License: GPL2+
 *
 * @package Meta Box
 * @subpage Meta Box Include Exclude
 */

if ( defined( 'ABSPATH' ) && is_admin() && ! class_exists( 'MB_Include_Exclude' ) ) {
	require LISNER_DIR . 'includes/meta-box-extension/meta-box-include-exclude/class-mb-include-exclude.php';
	add_filter( 'rwmb_show', array( 'MB_Include_Exclude', 'check' ), 10, 2 );
}
