<?php
/**
 * Template Name: Profile Dashboard Payout Information
 * Description: Content for notifying user of incoming payouts
 * ------------------------------------------------
 *
 * @author pebas
 * @version 1.0.0
 * @package listing/bookings
 *
 * @var $title
 * @var $description
 */

?>
<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$option     = get_option( 'pbs_option' );
$percentage = isset( $option['booking-percentage'] ) && ! empty( $option['booking-percentage'] ) && 0 != $option['booking-percentage'] ? $option['booking-percentage'] : 0;
?>
<div class="lisner-bookings-payouts">
	<div class="lisner-bookings-payout-information">
		<?php if ( isset( $title ) && ! empty( $title ) ) : ?>
			<div class="lisner-bookings-payout-information--title">
				<h6><?php echo esc_html( $title ); ?></h6>
			</div>
		<?php endif; ?>
		<?php if ( isset( $description ) && ! empty( $description ) ) : ?>
			<div class="lisner-bookings-payout-information--description">
				<?php $description = str_replace( array( '{', '}' ), array(
					'<strong>',
					'</strong>'
				), $description ); ?>
				<?php $description = str_replace( '[percentage]', $percentage, $description ); ?>
				<p><?php echo wp_kses_post( $description ); ?></p>
			</div>
		<?php endif; ?>
	</div>
	<div class="lisner-bookings-payout-listing success">
		<h6><?php esc_html_e( 'Payments Received:', 'pebas-bookings-extension' ); ?></h6>
		<?php $amount_received = pebas_booking()->get_booking_payout_payments( array( 'paid' ), 'paid' ); ?>
		<?php echo wc_price( $amount_received ); ?>
	</div>
	<div class="lisner-bookings-payout-listing error">
		<?php $amount_due = pebas_booking()->get_booking_payout_payments(); ?>
		<h6><?php esc_html_e( 'Payments Due:', 'pebas-bookings-extension' ); ?></h6>
		<?php echo wc_price( $amount_due ); ?>
	</div>
</div>
