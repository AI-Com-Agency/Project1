<?php
/**
 * No Packages
 *
 * Shows packages on the account page
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="lisner-listing-table">
	<div class="lisner-listing-table-header mb-0">
		<p class="mb-0"><?php esc_html_e( 'You have no active packages.', 'pebas-paid-listings' ); ?></p>
		<?php $permalink = get_option( 'job_manager_submit_job_form_page_id' ); ?>
		<a href="<?php echo esc_url( get_permalink( $permalink ) ); ?>"><?php esc_html_e( 'Submit Listing', 'pebas-paid-listings' ); ?>
			<i class="material-icons mf"><?php echo esc_html( 'add_circle_outline' ); ?></i></a>
	</div>
</div>
