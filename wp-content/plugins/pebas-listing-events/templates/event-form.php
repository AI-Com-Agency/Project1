<?php
/**
 * Event Form Template
 *
 * @author includes
 * @version 1.0.0
 * @package templates
 *
 * @var $event
 */
?>
<?php $form_id = isset( $event->ID ) ? $event->ID : rand(); ?>
<?php $title = isset( $event->_event_title ) ? $event->_event_title : ''; ?>
<?php $description = isset( $event->_event_description ) ? $event->_event_description : ''; ?>
<?php $start_time = isset( $event->_event_start ) ? $event->_event_start : ''; ?>
<?php $address = isset( $event->_event_address ) ? $event->_event_address : ''; ?>
<?php $coords = isset( $event->_event_address_map ) ? $event->_event_address_map : ''; ?>
<?php $image = isset( $event->_event_image ) ? $event->_event_image : ''; ?>
<?php $tickets = isset( $event->_event_ticket_url ) ? $event->_event_ticket_url : ''; ?>
<?php $listing = get_post( $listing_id ); ?>

<?php $is_new = isset( $_REQUEST['action'] ) && 'add_event' == $_REQUEST['action'] ? true : false; ?>
<?php if ( $is_new ) : ?>
	<div class="lisner-listing-table-header">
		<h5><?php printf( __( 'Listing event: %s', 'pebas-listing-events' ), '<strong>' . esc_html( $listing->post_title ) . '</strong>' ); ?></h5>
		<?php $events_permalink = wc_get_endpoint_url( 'all-listings' ) . '?action=events&job_id=' . $listing_id; ?>
		<a href="<?php echo esc_url( $events_permalink ); ?>" class="return"><i
					class="material-icons mf"><?php echo esc_html( 'replay' ); ?></i><?php esc_html_e( 'All Events', 'pebas-listing-events' ); ?>
		</a>
	</div>
<?php endif; ?>
<div class="lisner-coupon-form <?php echo ! $is_new ? esc_attr( 'hidden' ) : ''; ?>">
	<form class="form-event" method="post">
		<!-- Coupon Form / Coupon Title -->
		<div class="input-group input-group-full">
			<label for="event_title-<?php echo esc_attr( $form_id ); ?>"><?php esc_html_e( 'Event Title', 'pebas-listing-events' ); ?></label>
			<input type="text" id="event_title-<?php echo esc_attr( $form_id ); ?>" class="form-control"
			       placeholder="<?php esc_html_e( 'Friday Night Party!', 'pebas-listing-events' ) ?>"
			       name="_event_title" value="<?php echo esc_attr( $title ); ?>">
			<span class="coupon-description"><?php esc_html_e( 'Enter event title', 'pebas-listing-events' ); ?></span>
		</div>
		<!-- Coupon Form / Coupon Description -->
		<div class="input-group input-group-full">
			<label for="event_description-<?php echo esc_attr( $form_id ); ?>"><?php esc_html_e( 'Event Description', 'pebas-listing-events' ) ?></label>
			<textarea id="event_description-<?php echo esc_attr( $form_id ); ?>" class="form-control pt-2 pb-2"
			          name="_event_description"
			          placeholder="<?php esc_html_e( 'The very best party is coming this Friday!', 'pebas-listing-events' ); ?>"><?php echo esc_html( $description ); ?></textarea>
			<span class="coupon-description"><?php esc_html_e( 'Enter event description.', 'pebas-listing-events' ); ?></span>
		</div>
		<!-- Coupon Form / Coupon Start -->
		<div class="input-group">
			<label for="event_start-<?php echo esc_attr( $form_id ); ?>"><?php esc_html_e( 'Event Start Date/Time', 'pebas-listing-events' ); ?></label>
			<input type="text" id="event_start-<?php echo esc_attr( $form_id ); ?>"
			       class="form-control event-timepicker"
			       name="_event_start" value="<?php echo esc_attr( $start_time ); ?>" autocomplete="off">
			<span class="coupon-description"><?php esc_html_e( 'Choose start time of the event.', 'pebas-listing-events' ); ?></span>
		</div>

		<!-- Coupon Form / Event Tickets -->
		<div class="input-group">
			<label for="event_tickets-<?php echo esc_attr( $form_id ); ?>"><?php esc_html_e( 'Event Tickets URL', 'pebas-listing-events' ); ?></label>
			<input type="url" id="event_tickets-<?php echo esc_attr( $form_id ); ?>" class="form-control"
			       placeholder=""
			       name="_event_ticket_url" value="<?php echo esc_attr( $tickets); ?>">
			<span class="coupon-description"><?php esc_html_e( 'Enter event ticket site url.', 'pebas-listing-events' ); ?></span>
		</div>

		<!-- Coupon Form / Event Address -->
		<div class="input-group">
			<?php include 'event-map-field.php'; ?>
		</div>

		<!-- Coupon Form / Coupon Print -->
		<div class="input-group">
			<div class="coupon-image-wrapper">
				<label for="event_image-<?php echo esc_attr( $form_id ); ?>"><?php esc_html_e( 'Event Print Image', 'pebas-listing-events' ); ?></label>
				<div class="event-print-image <?php echo empty( $image ) ? esc_attr( 'hidden' ) : ''; ?>">
					<?php if ( ! empty( $image ) ) : ?>
						<?php $image_src = wp_get_attachment_image_src( $image, 'full' ); ?>
						<?php if ( pbs_is_demo() ) : ?>
							<span class="remove-style material-icons mf"><?php echo esc_html( 'delete' ); ?></span>
						<?php else: ?>
							<span class="remove-image material-icons mf"><?php echo esc_html( 'delete' ); ?></span>
						<?php endif; ?>
						<img src="<?php echo esc_url( $image_src[0] ); ?>">
					<?php endif; ?>
				</div>
				<?php if ( pbs_is_demo() ) : ?>
					<a href="javascript:"
					   class="event-uploader"><?php esc_html_e( 'Upload Image', 'pebas-listing-events' ); ?></a>
				<?php else: ?>
					<a href="javascript:"
					   class="event-image-uploader"><?php esc_html_e( 'Upload Image', 'pebas-listing-events' ); ?></a>
				<?php endif; ?>
				<input type="hidden" id="event_image-<?php echo esc_attr( $form_id ); ?>"
				       class="form-control event-image-uploader"
				       name="_event_image" value="<?php echo esc_attr( $image ); ?>">
				<span class="coupon-description"><?php esc_html_e( 'Upload event image.', 'pebas-listing-events' ); ?></span>
			</div>
		</div>

		<?php if ( pbs_is_demo() ) : ?>
			<button class="btn btn-primary confirm-button"><?php esc_html_e( 'Save Event', 'pebas-listing-events' ); ?></button>
		<?php else: ?>
			<div class="form-coupon-actions">
				<button type="submit"
				        class="btn btn-primary save-event"><?php esc_html_e( 'Save Event', 'pebas-listing-events' ); ?></button>
				<?php if ( isset( $_REQUEST['action'] ) && 'add_coupon' != $_REQUEST['action'] ) : ?>
					<a href="javascript:"
					   data-confirm="<?php esc_attr_e( 'Are you sure you wish to delete event?', 'pebas-listing-events' ); ?>"
					   class="btn btn-alt remove-event" title="<?php esc_attr_e( 'Remove Event' ); ?>"><i
								class="material-icons mf"><?php echo esc_attr( 'delete' ); ?></i></a>
				<?php endif; ?>
				<input type="hidden" name="action" value="save_event">
				<input type="hidden" name="listing_id" value="<?php echo esc_attr( $listing_id ); ?>">
				<input type="hidden" name="event_id"
				       value="<?php echo isset( $event->ID ) ? esc_attr( $event->ID ) : ''; ?>">
				<input type="hidden" name="permalink"
				       value="<?php echo isset( $events_permalink ) ? $events_permalink : ''; ?>">
				<?php echo wp_nonce_field( 'save_event_nonce', 'save_event_nonce' ); ?>
			</div>
		<?php endif; ?>

	</form>
</div>
