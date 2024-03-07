<?php
/**
 * Template Name: Profile Dashboard Bookings Orders Past
 * Description: Content for managing bookings orders
 *
 * @author pebas
 * @version 1.0.0
 * @package listing/bookings
 */

?>
<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$count = 0;

$action = isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : '';
?>

<?php if ( ! empty( $bookings ) ) : ?>
	<?php if ( empty( $_REQUEST['action'] ) ): ?>
		<div class="lisner-listing-table-header">
			<?php global $wp; ?>
			<p><?php echo esc_html( $title ); ?></p>
			<div class="lisner-bookings-old">
				<a href="<?php echo esc_url( home_url( $wp->request ) ); ?>"><?php esc_html_e( 'Upcoming Bookings', 'pebas-bookings-extension' ); ?></a>
			</div>
		</div>
	<?php endif; ?>
	<table class="table table-borderless lisner-table table-responsive-md">
		<thead>
		<tr>
			<th scope="col"
			    class="booking-status"><?php _e( '<span class="status_head tips" data-tip="' . esc_attr__( 'Status', 'pebas-bookings-extension' ) . '">' . esc_attr__( 'Status', 'woocommerce-bookings' ) . '</span>' ); ?></th>
			<th scope="col" class="booking-id"><?php _e( 'ID', 'pebas-bookings-extension' ); ?></th>
			<th scope="col" class="booked-product"><?php _e( 'Booked', 'pebas-bookings-extension' ); ?></th>
			<th scope="col" class="booked-persons"><?php _e( '# of Persons', 'pebas-bookings-extension' ); ?></th>
			<th scope="col" class="booked-by"><?php _e( 'Booked By', 'pebas-bookings-extension' ); ?></th>
			<th scope="col" class="booking-status"><?php _e( 'Order Status', 'pebas-bookings-extension' ); ?></th>
			<th scope="col" class="booking-start-date"><?php _e( 'Start Date', 'pebas-bookings-extension' ); ?></th>
			<th scope="col" class="booking-end-date"><?php _e( 'End Date', 'pebas-bookings-extension' ); ?></th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ( $bookings as $booking_product ) : ?>
			<?php foreach ( $booking_product as $booking ) : ?>
				<?php $now = strtotime( date( 'Y-m-d H:i' ) ); ?>
				<?php $end =strtotime( $booking->get_end_date() ); ?>
				<?php if ( $now > $end || 'cancelled' == $booking->get_status() || 'completed' == $booking->get_status() ) : ?>
					<?php $product = $booking->get_product(); ?>
					<?php $resource = $booking->get_resource(); ?>
					<?php $count ++; ?>
					<tr>
						<td class="booking-status">
							<?php echo '<span class="status-' . esc_attr( $booking->get_status() ) . ' tips" data-toggle="tooltip" title="' . esc_attr( wc_bookings_get_status_label( $booking->get_status() ) ) . '">' . esc_html( wc_bookings_get_status_label( $booking->get_status() ) ) . '</span>'; ?>
						</td>
						<td class="booking-id"><?php echo esc_html( $booking->get_id() ); ?></td>
						<td class="booked-product">
							<?php
							$my_account        = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
							$all_listings      = $my_account . wp_basename( get_permalink( get_option( 'job_manager_job_dashboard_page_id' ) ) );
							$product_permalink = $all_listings . '/?action=edit_booking_product&job_id=' . $product->get_id();
							if ( $product ) {
								echo '<a href="' . esc_url( $product_permalink ) . '">' . $product->get_title() . '</a>';

								if ( $resource ) {
									echo ' (<a href="' . esc_url( $product_permalink ) . '">' . $resource->get_name() . '</a>)';
								}
							} else {
								echo '-';
							}
							?>
						</td>
						<td class="booked-persons">
							<?php
							if ( ! is_object( $product ) || ! $product->has_persons() ) {
								esc_html_e( 'N/A', 'pebas-bookings-extension' );
								break;
							}
							echo esc_html( array_sum( $booking->get_person_counts() ) );
							?>
						</td>
						<td class="booked-by">
							<?php
							$customer      = $booking->get_customer();
							$customer_name = esc_html( $customer->name ? : '-' );

							if ( $customer->email ) {
								$customer_name = '<a href="mailto:' . esc_attr( $customer->email ) . '">' . $customer_name . '</a>';
							}

							echo $customer_name;
							?>
						</td>
						<td class="booking-order"><?php
							$order = $booking->get_order();
							if ( $order ) {
								echo esc_html( wc_get_order_status_name( $order->get_status() ) );
							} else {
								echo '-';
							}
							?></td>
						<?php $time_format = lisner_get_option( 'units-clock' ); ?>
						<?php $time_format = 'am_pm' == $time_format ? 'h:i A' : 'H:i'; ?>
						<td class="booking-start-date">
							<?php $date = date( 'F d, Y', strtotime( $booking->get_start_date() ) ); ?>
							<?php $time = date( $time_format, strtotime( $booking->get_start_date() ) ); ?>
							<span class="date"><span><?php esc_html_e( 'Date:', 'pebas-bookings-extension' ); ?></span><?php echo esc_html( $date ); ?></span>
							<span class="time"><span><?php esc_html_e( 'Time:', 'pebas-bookings-extension' ); ?></span><?php echo esc_html( $time ); ?></span>
						</td>
						<td class="booking-end-date">
							<?php $date = date( 'F d, Y', strtotime( $booking->get_end_date() ) ); ?>
							<?php $time = date( $time_format, strtotime( $booking->get_end_date() ) ); ?>
							<span class="date"><span><?php esc_html_e( 'Date:', 'pebas-bookings-extension' ); ?></span><?php echo esc_html( $date ); ?></span>
							<span class="time"><span><?php esc_html_e( 'Time:', 'pebas-bookings-extension' ); ?></span><?php echo esc_html( $time ); ?></span>
						</td>
					</tr>
				<?php endif; ?>
			<?php endforeach; ?>
		<?php endforeach; ?>
		</tbody>
	</table>

	<?php do_action( 'woocommerce_before_account_bookings_pagination' ); ?>

	<div class="woocommerce-pagination woocommerce-pagination--without-numbers woocommerce-Pagination">
		<?php if ( 1 !== $page ) : ?>
			<a class="woocommerce-button woocommerce-button--previous woocommerce-Button woocommerce-Button--previous button"
			   href="<?php echo esc_url( wc_get_endpoint_url( 'bookings', $page - 1 ) ); ?>"><?php _e( 'Previous', 'woocommerce' ); ?></a>
		<?php endif; ?>

		<?php if ( $count >= $bookings_per_page ) : ?>
			<a class="woocommerce-button woocommerce-button--next woocommerce-Button woocommerce-Button--next button"
			   href="<?php echo esc_url( wc_get_endpoint_url( 'bookings', $page + 1 ) ); ?>"><?php _e( 'Next', 'woocommerce' ); ?></a>
		<?php endif; ?>
	</div>

	<?php do_action( 'woocommerce_after_account_bookings_pagination' ); ?>

<?php else : ?>
	<div class="woocommerce-Message woocommerce-Message--info woocommerce-info">
		<?php esc_html_e( 'No bookings available yet.', 'pebas-bookings-extension' ); ?>
	</div>
<?php endif; ?>
