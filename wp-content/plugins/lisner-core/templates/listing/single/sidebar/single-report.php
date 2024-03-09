<?php
/**
 * Template Name: Listing Single Report
 * Description: Partial content for single listing sidebar
 *
 * @author pebas
 * @version 1.0.0
 * @package listing/single/sidebar
 *
 * @var $claim_args
 */
global $post;
?>
<a href="javascript:" data-toggle="modal" data-target="#modal-report"
   class="claim-link"><?php esc_html_e( 'Report Listing!', 'lisner-core' ); ?></a>
