<?php
/**
 * Template Name: Profile Dashboard Booking Order
 * Description: Content for managing booking order
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

<?php if ( $booking ) : ?>
	<div class="lisner-listing-table-header">
		<h5><?php printf( esc_html__( 'Booking #%d', 'pebas-bookings-extension' ), $booking->get_id() ); ?></h5>
		<?php $my_account = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ); ?>
		<?php $booking_orders_permalink = $my_account . wp_basename( get_permalink( get_option( 'job_manager_job_dashboard_page_id' ) ) ); ?>
		<div class="lisner-bookings-old">
			<a href="<?php echo esc_url( $booking_orders_permalink . '/booking-orders' ); ?>"><?php esc_html_e( 'Booking Orders', 'pebas-bookings-extension' ); ?></a>
		</div>
	</div>
	<div class="lisner-booking-order">
		<div class="row">
			<div class="col-md-4">
				<p class="lisner-booking-order-title"><?php esc_html_e( 'General Details', 'pebas-bookings-order' ); ?></p>
				<div class="lisner-booking-content">
					<div class="lisner-booking-content-item">
						<span class="lisner-booking-content-label"><?php esc_html_e( 'Order ID:', 'pebas-bookings-extension' ); ?></span>
						<span class="lisner-booking-content-el"><?php esc_html( $booking->get_order_id() ); ?></span>
					</div>
					<div class="lisner-booking-content-item">
						<span class="lisner-booking-content-label"><?php esc_html_e( 'Booking status:', 'pebas-bookings-extension' ); ?></span>
						<span class="lisner-booking-content-el"><?php esc_html( $booking->get_status() ); ?></span>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>
