<?php
/**
 * Template Name: Profile Dashboard Booking Orders
 * Description: Content for managing booking orders
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
$option = get_option( 'pbs_option' );
$count  = 0;

$action = isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : '';
?>

<?php if ( ! empty( $bookings ) ) : ?>
	<?php if ( 'confirm_booking' != $action ) : ?>
		<div class="lisner-listing-table-header">
			<p><?php echo esc_html( $title ); ?></p>
			<?php global $wp; ?>
			<div class="lisner-bookings-old">
				<a href="<?php echo esc_url( home_url( $wp->request . '/?find=old_bookings' ) ); ?>"><?php esc_html_e( 'Past Bookings', 'pebas-bookings-extension' ); ?></a>
			</div>
		</div>
	<?php endif; ?>
	<table class="table table-borderless lisner-table table-responsive">
		<thead>
		<tr>
			<th scope="col"
			    class="booking-status"><?php _e( '<span class="status_head tips" data-tip="' . esc_attr__( 'Status', 'pebas-bookings-extension' ) . '">' . esc_attr__( 'Status', 'woocommerce-bookings' ) . '</span>' ); ?></th>
			<th scope="col" class="booking-id"><?php _e( 'ID', 'pebas-bookings-extension' ); ?></th>
			<th scope="col" class="booked-product"><?php _e( 'Booked', 'pebas-bookings-extension' ); ?></th>
			<th scope="col" class="booked-persons"><?php _e( '# Persons', 'pebas-bookings-extension' ); ?></th>
			<th scope="col" class="booked-by"><?php _e( 'Booked By', 'pebas-bookings-extension' ); ?></th>
			<th scope="col" class="booking-status"><?php _e( 'Order Status', 'pebas-bookings-extension' ); ?></th>
			<th scope="col" class="booking-start-date"><?php _e( 'Start Date', 'pebas-bookings-extension' ); ?></th>
			<th scope="col" class="booking-end-date"><?php _e( 'End Date', 'pebas-bookings-extension' ); ?></th>
			<th scope="col" class="booking-payment"><?php _e( 'Amount Due', 'pebas-bookings-extension' ); ?></th>
			<th scope="col" class="booking-actions"><?php _e( 'Actions', 'pebas-bookings-extension' ); ?></th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ( $bookings as $booking_product ) : ?>
			<?php foreach ( $booking_product as $booking ) : ?>
				<?php $now = strtotime( date( 'Y-m-d H:i' ) ); ?>
				<?php $end = strtotime( $booking->get_end_date() ); ?>
				<?php if ( $now < $end ) : ?>
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
							} else {
								echo esc_html( array_sum( $booking->get_person_counts() ) );
							}
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
						<td class="booking-end-date">
							<?php
							$payout        = pebas_booking()->get_payout_status( $booking->get_id() );
							$payout_status = get_post_meta( $payout->ID, '_payout_status', true );
							$percentage    = pebas_payouts_admin()->calculate_payment_tax( $option['booking-percentage'], $payout->ID );
							$amount        = $booking->get_cost();
							$percentage    = ! empty( $percentage ) ? ( $amount * $percentage ) / 100 : '';
							$full_amount   = ! empty( $percentage ) ? $amount - $percentage : $amount;
							?>
							<div class="booking-amount">
								<span class="booking-amount--base"><?php printf( __( 'Base: %s', 'pebas-bookings-extension' ), wc_price( $amount ) ); ?></span>
								<span class="booking-amount--plus"><?php echo esc_html( '-' ); ?></span>
								<span class="booking-amount--percentage"><?php printf( __( 'Site Tax: %s', 'pebas-bookings-extension' ), wc_price( $percentage ) ); ?></span>
							</div>
							<?php if ( 'paid' == $payout_status ) : ?>
								<div class="booking-amount--total success"><?php printf( __( 'Payment Received: <strong> %s </strong>', 'pebas-bookings-extension' ), wc_price( $full_amount ) ); ?></div>
							<?php else: ?>
								<div class="booking-amount--total error"><?php printf( __( 'Total Due: <strong> %s </strong>', 'pebas-bookings-extension' ), wc_price( $full_amount ) ); ?></div>
							<?php endif; ?>
						</td>
						<td class="booking-actions">
							<div class="listing-actions-content">
								<ul class="list-unstyled mb-0">
									<?php
									global $wp;
									$booking_permalink = home_url( $wp->request . '/?booking_id=' . $booking->get_id() );
									/*todo add at a later date $actions           = array(
										'view' => array(
											'url'    => $booking_permalink,
											'name'   => __( 'View', 'pebas-bookings-extension' ),
											'action' => 'view',
											'icon'   => 'remove_red_eye',
										),
									);*/
									if ( in_array( $booking->get_status(), array( 'pending-confirmation' ) ) ) {
										$actions['confirm'] = array(
											'url'    => 'javascript:',
											'name'   => __( 'Confirm Availability', 'pebas-bookings-extension' ),
											'action' => 'confirm_booking',
											'icon'   => 'done_outline'
										);
									}
									if ( isset( $actions ) ) {
										foreach ( $actions as $action ) {
											$nonce = wp_create_nonce( 'confirm_booking' );
											if ( 'confirm_booking' == $action['action'] && in_array( $booking->get_status(), array( 'pending-confirmation' ) ) ) {
												printf( '<li><a class="tips %s" data-id="%d" data-nonce="%s" href="javascript:" data-toggle="tooltip" title="%s"><i class="material-icons mf">' . esc_html( $action['icon'] ) . '</i></a></li>', esc_attr( $action['action'] ), esc_attr( $booking->get_id() ), esc_attr( $nonce ), esc_attr( $action['name'] ), esc_attr( $action['name'] ) );
											}
										}
									} else {
										echo '<li>' . '' . '</li>';
									}
									?>
								</ul>
							</div>
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
