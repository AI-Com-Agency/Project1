<?php
/**
 * Shows the map form field on event
 *
 * @author      pebas
 * @package     templates/
 * @category    template
 * @version     1.0.0
 *
 * @var $event
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$option  = get_option( 'pbs_option' );
$event_id = isset( $event ) ? $event->ID : null;
$address = rwmb_get_value( '_event_address', '', $event_id );
$map     = rwmb_get_value( '_event_address_map', '', $event_id );
if ( isset( $map ) ) {
	$lat = $map['latitude'];
	$lon = $map['longitude'];
} else {
	$address = get_post_meta( $event->ID, '_event_address', true );
	$lat     = get_post_meta( $event->ID, 'location_lat', true );
	$lon     = get_post_meta( $event->ID, 'location_long', true );
}
$default_address = isset( $option['map-default-address'] ) ? $option['map-default-address'] : esc_html( '5th Ave, New York, NY, USA' );
$default_lat     = isset( $option['map-default-latitude'] ) ? $option['map-default-latitude'] : esc_html( '40.7314123' );
$default_lon     = isset( $option['map-default-longitude'] ) ? $option['map-default-longitude'] : esc_html( '-73.9969848' );
?>
<div class="event-map">
	<div class="input-group">
		<label for="event-address-<?php echo esc_attr( $form_id ); ?>"><?php esc_html_e( 'Event Address', 'pebas-listing-events' ); ?></label>
		<input id="event_address<?php echo isset( $event_id ) ? esc_attr( '-' . $event_id ) : ''; ?>" type="text"
		       name="_event_address"
		       class="form-control"
		       placeholder="<?php esc_html_e( 'e.g. "London"', 'pebas-listing-events' ); ?>" autocomplete="off"
		       value="<?php echo ! empty( $address ) ? esc_attr( $address ) : ''; ?>">
		<input id="location_lat<?php echo isset( $event_id ) ? esc_attr( '-' . $event_id ) : ''; ?>" type="hidden"
		       name="location_lat"
		       value="<?php echo ! empty( $lat ) ? esc_attr( $lat ) : esc_attr( $default_lat ); ?>">
		<input id="location_long<?php echo isset( $event_id ) ? esc_attr( '-' . $event_id ) : ''; ?>" type="hidden"
		       name="location_long"
		       value="<?php echo ! empty( $lon ) ? esc_attr( $lon ) : esc_attr( $default_lon ); ?>">
		<span class="input-group-append">
            <i class="material-icons geolocate-submit"><?php echo esc_html( 'gps_not_fixed' ); ?></i>
        </span>
	</div>

	<div id="event-map-instance<?php echo isset( $event_id ) ? esc_attr( '-' . $event_id ) : ''; ?>"
	     class="event-map-instance"></div>
</div>
