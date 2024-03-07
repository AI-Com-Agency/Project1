<?php
/**
 * Event Display
 *
 * @author includes
 * @version 1.0.0
 * @package templates
 *
 * @var $events
 * @var $listing_id
 * @var $type
 */
?>

<section class="listing-widget listing-widget-event">
	<!-- Listing Coupons -->
	<div class="listing-single-events">
		<?php foreach ( $events as $event ) : ?>
			<?php if ( ! pebas_events()->has_event_started( $event->ID ) ) : ?>
				<?php include "event-item.php"; ?>
			<?php endif; ?>
		<?php endforeach; ?>
	</div>
</section>
