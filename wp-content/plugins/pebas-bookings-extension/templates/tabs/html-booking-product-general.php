<?php
/**
 * Template Name: Booking Product General Tab Fields
 * Description: Content for bookings products creation
 *
 * @author pebas
 * @version 1.0.0
 * @package tabs
 */

?>
<div class="options_group show_if_booking">
	<?php
	$duration_type = $bookable_product->get_duration_type( 'edit' );
	$duration      = $bookable_product->get_duration( 'edit' );
	$duration_unit = $bookable_product->get_duration_unit( 'edit' );
	$title         = get_post_meta( $bookable_product->get_id(), '_wc_booking_custom_title', true );
	?>
	<p class="form-field">
		<label for="_wc_booking_custom_title"><?php esc_html_e( 'Booking Display Title', 'pebas-bookings-extension' ); ?></label>
		<input type="text" name="_wc_booking_custom_title" id="_wc_booking_custom_title"
		       value="<?php echo esc_html( $title ); ?>">
	</p>
	<p class="form-field">
		<label for="_wc_booking_duration_type"><?php _e( 'Booking duration', 'pebas-bookings-extension' ); ?></label>
		<select name="_wc_booking_duration_type" id="_wc_booking_duration_type" class=""
		        style="width: auto; margin-right: 7px;">
			<option value="fixed" <?php selected( $duration_type, 'fixed' ); ?>><?php _e( 'Fixed blocks of', 'pebas-bookings-extension' ); ?></option>
			<option value="customer" <?php selected( $duration_type, 'customer' ); ?>><?php _e( 'Customer defined blocks of', 'pebas-bookings-extension' ); ?></option>
		</select>
		<input type="number" name="_wc_booking_duration" id="_wc_booking_duration" value="<?php echo $duration; ?>"
		       step="1" min="1" style="margin-right: 7px; width: 4em;">
		<select name="_wc_booking_duration_unit" id="_wc_booking_duration_unit" class="short"
		        style="width: auto; margin-right: 7px;">
			<option value="month" <?php selected( $duration_unit, 'month' ); ?>><?php _e( 'Month(s)', 'pebas-bookings-extension' ); ?></option>
			<option value="day" <?php selected( $duration_unit, 'day' ); ?>><?php _e( 'Day(s)', 'pebas-bookings-extension' ); ?></option>
			<option value="hour" <?php selected( $duration_unit, 'hour' ); ?>><?php _e( 'Hour(s)', 'pebas-bookings-extension' ); ?></option>
			<option value="minute" <?php selected( $duration_unit, 'minute' ); ?>><?php _e( 'Minute(s)', 'pebas-bookings-extension' ); ?></option>
		</select>
	</p>

	<div id="min_max_duration">
		<?php

		woocommerce_wp_text_input( array(
			'id'                => '_wc_booking_min_duration',
			'label'             => __( 'Minimum duration', 'pebas-bookings-extension' ),
			'description'       => __( 'The minimum allowed duration the user can input.', 'pebas-bookings-extension' ),
			'value'             => $bookable_product->get_min_duration( 'edit' ),
			'desc_tip'          => true,
			'type'              => 'number',
			'custom_attributes' => array(
				'min'  => '',
				'step' => '1',
			),
		) );

		woocommerce_wp_text_input( array(
			'id'                => '_wc_booking_max_duration',
			'label'             => __( 'Maximum duration', 'pebas-bookings-extension' ),
			'description'       => __( 'The maximum allowed duration the user can input.', 'pebas-bookings-extension' ),
			'value'             => $bookable_product->get_max_duration( 'edit' ),
			'desc_tip'          => true,
			'type'              => 'number',
			'custom_attributes' => array(
				'min'  => '1',
				'step' => '1',
			),
		) );
		?>
		<div id="enable-range-picker">
			<?php
			woocommerce_wp_checkbox( array(
				'id'          => '_wc_booking_enable_range_picker',
				'value'       => $bookable_product->get_enable_range_picker( 'edit' ) ? 'yes' : 'no',
				'label'       => __( 'Enable Calendar Range Picker?', 'pebas-bookings-extension' ),
				'description' => __( 'Lets the user select a start and end date on the calendar - duration will be calculated automatically.', 'pebas-bookings-extension' ),
			) );
			?>
		</div>
	</div>

	<?php
	woocommerce_wp_select( array(
		'id'          => '_wc_booking_calendar_display_mode',
		'value'       => $bookable_product->get_calendar_display_mode( 'edit' ),
		'label'       => __( 'Calendar display mode', 'pebas-bookings-extension' ),
		'description' => __( 'Choose how the calendar is displayed on the booking form.', 'pebas-bookings-extension' ),
		'options'     => array(
			''               => __( 'Display calendar on click', 'pebas-bookings-extension' ),
			'always_visible' => __( 'Calendar always visible', 'pebas-bookings-extension' ),
		),
		'desc_tip'    => true,
		'class'       => 'select',
	) );

	woocommerce_wp_checkbox( array(
		'id'          => '_wc_booking_requires_confirmation',
		'value'       => $bookable_product->get_requires_confirmation( 'edit' ) ? 'yes' : 'no',
		'label'       => __( 'Requires confirmation?', 'pebas-bookings-extension' ),
		'description' => __( 'Check this box if the booking requires admin approval/confirmation. Payment will not be taken during checkout.', 'pebas-bookings-extension' ),
	) );

	woocommerce_wp_checkbox( array(
		'id'          => '_wc_booking_user_can_cancel',
		'value'       => $bookable_product->get_user_can_cancel( 'edit' ) ? 'yes' : 'no',
		'label'       => __( 'Can be cancelled?', 'pebas-bookings-extension' ),
		'description' => __( 'Check this box if the booking can be cancelled by the customer after it has been purchased. A refund will not be sent automatically.', 'pebas-bookings-extension' ),
	) );

	$cancel_limit      = $bookable_product->get_cancel_limit( 'edit' );
	$cancel_limit_unit = $bookable_product->get_cancel_limit_unit( 'edit' );
	?>
	<p class="form-field booking-cancel-limit">
		<label for="_wc_booking_cancel_limit"><?php _e( 'Booking can be cancelled until', 'pebas-bookings-extension' ); ?></label>
		<input type="number" name="_wc_booking_cancel_limit" id="_wc_booking_cancel_limit"
		       value="<?php echo $cancel_limit; ?>" step="1" min="1" style="margin-right: 7px; width: 4em;">
		<select name="_wc_booking_cancel_limit_unit" id="_wc_booking_cancel_limit_unit" class="short"
		        style="width: auto; margin-right: 7px;">
			<option value="month" <?php selected( $cancel_limit_unit, 'month' ); ?>><?php _e( 'Month(s)', 'pebas-bookings-extension' ); ?></option>
			<option value="day" <?php selected( $cancel_limit_unit, 'day' ); ?>><?php _e( 'Day(s)', 'pebas-bookings-extension' ); ?></option>
			<option value="hour" <?php selected( $cancel_limit_unit, 'hour' ); ?>><?php _e( 'Hour(s)', 'pebas-bookings-extension' ); ?></option>
			<option value="minute" <?php selected( $cancel_limit_unit, 'minute' ); ?>><?php _e( 'Minute(s)', 'pebas-bookings-extension' ); ?></option>
		</select>
		<span class="description"><?php _e( 'before the start date.', 'pebas-bookings-extension' ); ?></span>
	</p>
	<script type="text/javascript">
        jQuery('._tax_status_field').closest('.show_if_simple').addClass('show_if_booking');
        jQuery('select#_wc_booking_duration_unit, select#_wc_booking_duration_type, input#_wc_booking_duration').change(function () {
            if ('day' === jQuery('select#_wc_booking_duration_unit').val() && '1' == jQuery('input#_wc_booking_duration').val() && 'customer' === jQuery('select#_wc_booking_duration_type').val()) {
                jQuery('p._wc_booking_enable_range_picker_field').show();
            } else {
                jQuery('p._wc_booking_enable_range_picker_field').hide();
            }
        });
        jQuery('#_wc_booking_duration_unit').change();
	</script>
</div>
