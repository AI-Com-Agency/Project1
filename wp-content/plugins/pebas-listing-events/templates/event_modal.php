<?php
/**
 * Event Modal Display
 *
 * @author includes
 * @version 1.0.0
 * @package templates
 *
 * @var $events
 * @var $listing_id
 */
?>

<?php foreach ( $events as $event ) : ?>
	<?php if ( 'upcoming' == $event->_event_status ): ?>
		<div class="modal" id="modal-event-<?php echo esc_attr( $event->ID ); ?>" tabindex="-1" role="dialog"
		     aria-labelledby="modal-event" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-body">
						<?php if ( isset( $event->_event_description ) && ! empty( $event->_event_description ) ) : ?>
							<div class="event-description">
								<h4><?php esc_html_e( 'Event Description', 'pebas-listing-events' ); ?></h4>
							</div>
							<div class="event-content"><?php echo esc_html( $event->_event_description ); ?></div>
						<?php endif; ?>
					</div>
					<a class="dismiss-event-modal" href="javascript:"
					   data-dismiss="modal"><?php esc_html_e( 'Got it!', 'pebas-listing-events' ); ?><i
								class="material-icons mf"><?php echo esc_html( 'subdirectory_arrow_right' ); ?></i></a>
				</div>
			</div>
		</div>
	<?php endif; ?>
<?php endforeach; ?>