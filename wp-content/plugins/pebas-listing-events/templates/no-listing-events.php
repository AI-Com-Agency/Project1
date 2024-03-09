<?php
/**
 * No Event template
 *
 * @author includes
 * @version 1.0.0
 * @package templates
 */
?>
<?php $listing = get_post( $listing_id ); ?>
<div class="lisner-listing-table">
	<div class="lisner-listing-table-header mb-0">
		<p class="mb-0"><?php printf( __( '%s has no events.', 'pebas-listing-events' ), '<strong>' . esc_html( $listing->post_title ) . '</strong>' ); ?></p>
		<?php $coupons_permalink = wc_get_endpoint_url( 'all-listings' ) . '?action=add_event&job_id=' . $_GET['job_id']; ?>
		<a href="<?php echo esc_url( $coupons_permalink ); ?>"><?php esc_html_e( 'Add Event' ); ?><i
				class="material-icons mf"><?php echo esc_html( 'add_circle_outline' ); ?></i></a></div>
</div>
