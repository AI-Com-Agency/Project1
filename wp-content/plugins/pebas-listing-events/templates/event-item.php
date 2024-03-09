<?php
/**
 * Event Item
 *
 * @author includes
 * @version 1.0.0
 * @package templates
 *
 * @var $event
 * @var $listing_id
 * @var $type
 */
?>
<?php if ( 'upcoming' == $event->_event_status ) : ?>
	<?php $events_count = pebas_events()->count_events( get_the_ID() ); ?>
	<!-- Listing Coupon -->
	<div class="listing-single-event">
		<?php if ( isset( $event->_event_image ) && ! empty( $event->_event_image ) ) : ?>
			<?php $image = wp_get_attachment_image_src( $event->_event_image, 'full' ); ?>
			<!-- Listing Event / Image -->
			<figure class="listing-single-event-figure">
				<img src="<?php echo esc_url( $image[0] ); ?>">
			</figure>
		<?php endif; ?>

		<!-- Listing Event / Content -->
		<div class="listing-single-event-content">
			<!-- Listing Event / Date -->
			<div class="listing-single-event-content__date">
				<?php $start_date = isset( $event->_event_start ) && ! empty( $event->_event_start ) ? $event->_event_start : ''; ?>
				<?php if ( ! empty( $start_date ) ) : ?>
					<?php $month = date( 'M', strtotime( $start_date ) ); ?>
					<?php $day = date( 'd', strtotime( $start_date ) ); ?>
					<?php $time = date( 'H:i', strtotime( $start_date ) ); ?>
					<div class="listing-single-event-date-wrapper">
						<div class="date__content">
							<span class="date__month"><?php echo esc_html( $month ); ?></span>
							<span class="date__day"><?php echo esc_html( $day ); ?></span>
						</div>
						<span class="date__time"><?php echo esc_html( $time ); ?></span>
					</div>
				<?php endif; ?>
			</div>

			<!-- Listing Event / Content -->
			<div class="listing-single-event-content__inner">
				<div class="listing-single-event-content__info">
					<?php if ( isset( $event->_event_title ) && ! empty( $event->_event_title ) ) : ?>
						<?php $title = 60 <= strlen( $event->_event_title ) ? substr( $event->_event_title, 0, 60 ) . esc_html( '...' ) : $event->_event_title; ?>
						<!-- Listing Event / Title -->
						<div class="listing-single-event-content__title" data-toggle="tooltip"
						     title="<?php echo 60 <= strlen( $event->_event_title ) ? esc_attr( $event->_event_title ) : ''; ?>">
							<h5><?php echo esc_html( $title ); ?></h5>
						</div>
					<?php endif; ?>
				</div>
				<?php if ( isset( $event->_event_description ) && ! empty( $event->_event_description ) ) : ?>
					<!-- Listing Event / Title -->
					<div class="listing-single-event-content__description">
						<?php $description = 18 < str_word_count( $event->_event_description ) ? sprintf( __( '%s <a href="javascript:" data-toggle="modal" data-target="#modal-event-' . esc_attr( $event->ID ) . '" class="read-more-link">Read more <i class="material-icons mf">' . esc_html( 'subdirectory_arrow_right' ) . '</i></a>', 'lisner-core' ), wp_trim_words( $event->_event_description, 18 ) ) : $event->_event_description; ?>
						<p><?php echo _( $description ); ?></p>
					</div>
				<?php endif; ?>

			</div>

			<div class="listing-single-event-footer">
				<!-- Listing Event / Actions -->
				<div class="listing-single-event-content__actions">
					<!-- Listing Event / Attendees -->
					<div class="listing-single-event-content__attendees">
						<?php if ( pebas_events::has_user_attending_event( $event->ID ) ) : ?>
							<a href="javascript:"
							   data-event-id="<?php echo esc_attr( $event->ID ); ?>"
							   class="btn attendees-call active"><i
										class="material-icons mf thumb-up"><?php echo esc_html( 'thumb_up' ); ?></i><span
										class="event-call-text"><?php esc_html_e( 'You\'re going!', 'pebas-listing-events' ); ?></span>
								<?php $attending = isset( $event->_event_attendees ) ? $event->_event_attendees : 0; ?>
								<span class="going-count"><?php echo esc_attr( $attending ); ?></span>
							</a>
						<?php else: ?>
							<a href="javascript:"
							   data-event-id="<?php echo esc_attr( $event->ID ); ?>"
							   class="btn attendees-call"><i
										class="material-icons mf thumb-up"><?php echo esc_html( 'thumb_up' ); ?></i><span
										class="event-call-text"><?php esc_html_e( 'I\'m going!', 'pebas-listing-events' ); ?></span>
								<?php $attending = isset( $event->_event_attendees ) ? $event->_event_attendees : 0; ?>
								<span class="going-count"><?php echo esc_attr( $attending ); ?></span>
							</a>
						<?php endif; ?>
					</div>
					<?php if ( isset( $event->_event_ticket_url ) && ! empty( $event->_event_ticket_url ) ) : ?>
						<!-- Listing Event / Tickets -->
						<div class="listing-single-event-content__tickets">
							<a href="<?php echo esc_html( $event->_event_ticket_url ); ?>"
							   class="btn" target="_blank"><i
										class="material-icons mf"><?php echo esc_html( 'local_play' ); ?></i><?php esc_html_e( 'Get tickets now', 'pebas-listing-events' ); ?>
							</a>
						</div>
					<?php endif; ?>
				</div>
			</div>

		</div>
	</div>
<?php endif; ?>
