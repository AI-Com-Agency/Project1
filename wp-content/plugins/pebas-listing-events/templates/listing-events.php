<?php
/**
 * All Coupons template
 *
 * @author includes
 * @version 1.0.0
 * @package templates
 *
 * @var $events
 */
?>
<div class="lisner-coupons">
	<div class="lisner-listing-table-header">
		<p><?php esc_html_e( 'Events for this listing are displayed below', 'pebas-listing-coupons' ); ?></p>
		<?php $add_coupon_permalink = wc_get_endpoint_url( 'all-listings' ) . '?action=add_event&job_id=' . $listing_id; ?>
		<a href="<?php echo esc_url( $add_coupon_permalink ); ?>"><?php esc_html_e( 'Add Event', 'pebas-listing-events' ); ?>
			<i class="material-icons mf"><?php echo esc_html( 'add_circle_outline' ); ?></i></a>
	</div>
	<?php foreach ( $events as $event ) : ?>
		<?php if ( pebas_events()->has_event_started( $event->ID ) && 'started' != $event->_event_status ) : ?>
			<?php update_post_meta( $event->ID, '_event_status', 'started' ); ?>
		<?php endif; ?>
		<div class="lisner-coupon <?php echo esc_attr( $event->_event_status ); ?> event-el"
		     data-coupon-id="<?php echo esc_attr( $event->ID ); ?>">
			<?php $icon = 'upcoming' == $event->_event_status ? esc_attr( 'calendar-alt' ) : esc_attr( 'calendar-check' ); ?>
			<span class="lisner-coupon-icon fas fa-<?php echo esc_html( $icon ); ?> fa-fw"></span>
			<!-- Coupon / Title -->
			<div class="lisner-coupon-title">
				<a href="<?php echo esc_url( get_permalink( $listing_id ) ); ?>" target="_blank"
				   class="title"><?php echo esc_html( $event->post_title ); ?></a>
				<?php $description = 35 <= strlen( $event->_event_description ) ? substr( $event->_event_description, 0, 35 ) . esc_html( '...' ) : $event->_coupon_description; ?>
				<span class="description lighter"><?php echo esc_html( $description ); ?></span>
			</div>
			<!-- Coupon / Status -->
			<div class="lisner-coupon-type">
				<span class="status"><?php esc_html_e( 'Address', 'pebas-listing-events' ); ?></span>
				<?php if ( isset( $event->_event_address ) && ! empty( $event->_event_address ) ) : ?>
					<span class="code lighter"><?php echo esc_html( $event->_event_address ); ?></span>
				<?php endif; ?>
			</div>
			<!-- Coupon / Status -->
			<div class="lisner-coupon-status">
				<span class="status"><?php echo ucfirst( esc_html( $event->_event_status ) ); ?></span>
				<?php if ( isset( $event->_event_start ) && ! empty( $event->_event_start ) ) : ?>
					<span class="code lighter"><?php echo esc_html( $event->_event_start ); ?></span>
				<?php endif; ?>
			</div>
			<!-- Coupon / Actions -->
			<div class="lisner-coupon-actions">
				<ul class="coupon-actions list-unstyled m-0">
					<li class="coupon-action coupon-action__view"><a href="javascript:"
					                                                 class="event-action-view"><i
									class="material-icons mf"><?php echo esc_html( 'keyboard_arrow_down' ); ?></i></a>
					</li>
				</ul>
			</div>

			<!-- Coupon / Coupon Form -->
			<?php include 'event-form.php'; ?>

		</div>
	<?php endforeach; ?>
</div>