<?php
/**
 * Shows the `map` form field on listing forms.
 *
 * @author      pebas
 * @package     templates/listing/form-fields
 * @category    template
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$option          = get_option( 'pbs_option' );
$classes         = array( 'input-text' );
$post_id         = isset( $_REQUEST['job_id'] ) ? $_REQUEST['job_id'] : '';
$address         = get_post_meta( $post_id, 'geolocation_formatted_address', true );
$address_alt     = get_post_meta( $post_id, '_job_location', true );
$lat             = get_post_meta( $post_id, 'geolocation_lat', true );
$lon             = get_post_meta( $post_id, 'geolocation_long', true );
$default_address = isset( $option['map-default-address'] ) ? $option['map-default-address'] : esc_html( '5th Ave, New York, NY, USA' );
$default_lat     = isset( $option['map-default-latitude'] ) ? $option['map-default-latitude'] : esc_html( '40.7314123' );
$default_lon     = isset( $option['map-default-longitude'] ) ? $option['map-default-longitude'] : esc_html( '-73.9969848' );
?>
<div class="lisner-map">
	<div class="lisner-map-field input-group">
		<input id="job_location" type="text" name="job_location"
		       class="form-control"
		       placeholder="<?php esc_html_e( 'e.g. "London"', 'lisner-core' ); ?>" autocomplete="off"
		       value="<?php echo ! empty( $address ) ? esc_attr( $address ) : ( ! empty( $address_alt ) ? esc_attr( $address_alt ) : esc_attr( $default_address ) ); ?>">
		<input id="location_lat" type="hidden"
		       name="location_lat"
		       value="<?php echo ! empty( $lat ) ? esc_attr( $lat ) : esc_attr( $default_lat ); ?>">
		<input id="location_long" type="hidden"
		       name="location_long"
		       value="<?php echo ! empty( $lon ) ? esc_attr( $lon ) : esc_attr( $default_lon ); ?>">
		<span class="input-group-append">
            <i class="material-icons geolocate-submit"><?php echo esc_html( 'gps_not_fixed' ); ?></i>
        </span>
	</div>

	<div id="lisner-map-instance" class="lisner-map-instance"></div>
</div>
