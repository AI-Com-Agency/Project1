<?php
/**
 * Template Name: Profile Dashboard Booking Products
 * Description: Content for managing booking products
 *
 * @author pebas
 * @version 1.0.0
 * @package listing/single/bookings
 */

?>
<?php
?>
<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
wp_enqueue_script( 'pbs_bookings_writepanel_js' );
$product_id    = get_post_meta( $listing_id, '_listing_products', true );
$listing_owner = get_post_meta( $listing_id, '_job_author', true );
?>
<?php if ( get_current_user_id() == $listing_owner || current_user_can( 'administrator' ) ) : ?>
	<?php if ( isset( $product_id ) ) : ?>
		<!-- Booking Products -->
		<div class="lisner-booking-products">

		<!-- Booking Products / Form -->
		<form class="form-booking" method="post">

			<!-- Booking Products / Header -->
			<div class="booking-products-header">
				<?php $virtual = get_post_meta( $product_id, '_virtual', true ); ?>
				<?php $has_persons = get_post_meta( $product_id, '_wc_booking_has_persons', true ); ?>
				<?php $has_resources = get_post_meta( $product_id, '_wc_booking_has_resources', true ); ?>
				<?php $disabled = get_post_meta( $product_id, '_is_disabled', true ); ?>
				<?php $listing_name = get_the_title( $_GET['job_id'] ); ?>
				<div class="booking-products-title">
					<h6><?php echo esc_html( $listing_name ); ?></h6>
					<p class="product-title-description"><?php esc_html_e( 'Bookable Product', 'pebas-bookings-extension' ); ?></p>
				</div>
				<div class="booking-products-product-data">
					<span><?php esc_html_e( 'You can select:', 'pebas-bookings-extension' ); ?></span>
					<div class="booking-checkbox" data-hide-tab="is-virtual">
						<label for="_virtual"><?php esc_attr_e( 'Virtual', 'pebas-bookings-extension' ); ?>
							<input type="checkbox" name="_virtual" id="_virtual"
							       value="yes" <?php echo isset( $virtual ) && 'yes' == $virtual ? esc_attr( 'checked="checked"' ) : ''; ?>>
						</label>
					</div>
					<div class="booking-checkbox" data-product-data="has-persons">
						<label for="_wc_booking_has_persons"><?php esc_attr_e( 'Has Persons', 'pebas-bookings-extension' ); ?>
							<input type="checkbox" name="_wc_booking_has_persons"
							       id="_wc_booking_has_persons" <?php echo isset( $has_persons ) && ! empty( $has_persons ) ? esc_attr( 'checked="checked"' ) : ''; ?>>
						</label>
					</div>
					<div class="booking-checkbox" data-product-data="has-resources">
						<label for="_wc_booking_has_resources"><?php esc_attr_e( 'Has Resources', 'pebas-bookings-extension' ); ?>
							<input type="checkbox" name="_wc_booking_has_resources"
							       id="_wc_booking_has_resources" <?php echo isset( $has_resources ) && ! empty( $has_resources ) ? esc_attr( 'checked="checked"' ) : ''; ?>>
						</label>
					</div>
					<div class="booking-checkbox">
						<label for="_is_disabled"><?php esc_attr_e( 'Disabled', 'pebas-bookings-extension' ); ?>
							<input type="checkbox" name="_is_disabled" id="_is_disabled"
							       value="yes" <?php echo ! isset( $disabled ) || 'no' != $disabled ? esc_attr( 'checked="checked"' ) : ''; ?>>
						</label>
					</div>
					<div class="booking-checkbox booking-checkbox-visit-listing">
						<?php $listing_permalink = get_permalink( $_GET['job_id'] ); ?>
						<a href="<?php echo esc_url( $listing_permalink ); ?>" target="_blank" class="visit-listing"><i
									class="material-icons mf"><?php echo esc_html( 'remove_red_eye' ); ?></i></a>
					</div>
				</div>
			</div>

			<div class="booking-products">
				<!-- Booking Products / Tabs -->
				<div class="booking-products__tabs">
					<div data-booking-tab="general" class="booking-products__tab active">
						<i class="dashicons dashicons-admin-tools"></i>
						<a href="javascript:"><?php esc_html_e( 'General', 'pebas-bookings-extension' ); ?></a>
					</div>
					<div data-hide-tab="is-virtual" data-booking-tab="shipping" class="booking-products__tab">
						<i class="wcicon-truck-2"></i>
						<a href="javascript:"><?php esc_html_e( 'Shipping', 'pebas-bookings-extension' ); ?></a>
					</div>
					<div data-show-tab="has-resources" data-booking-tab="resources"
					     class="booking-products__tab hidden">
						<i class="wcicon-windows"></i>
						<a href="javascript:"><?php esc_html_e( 'Resources', 'pebas-bookings-extension' ); ?></a>
					</div>
					<div data-booking-tab="availability" class="booking-products__tab">
						<i class="wcicon-calendar"></i>
						<a href="javascript:"><?php esc_html_e( 'Availability', 'pebas-bookings-extension' ); ?></a>
					</div>
					<div data-booking-tab="costs" class="booking-products__tab">
						<i class="wcicon-card"></i>
						<a href="javascript:"><?php esc_html_e( 'Costs', 'pebas-bookings-extension' ); ?></a>
					</div>
					<div data-show-tab="has-persons" data-booking-tab="persons" class="booking-products__tab hidden">
						<i class="wcicon-user2"></i>
						<a href="javascript:"><?php esc_html_e( 'Persons', 'pebas-bookings-extension' ); ?></a>
					</div>
				</div>

				<!-- Booking Products / Tabs Content -->
				<div class="booking-products__content_wrapper">
					<!-- Booking Products / Tab -->
					<div data-booking-content="general" class="booking-products__content active">
						<?php include 'tabs/html-booking-product-general.php'; ?>
					</div>
					<!-- Booking Products / Tab -->
					<div data-booking-content="shipping" class="booking-products__content">
						<?php include 'tabs/html-booking-product-shipping.php'; ?>
					</div>
					<!-- Booking Products / Tab -->
					<div data-booking-content="resources" class="booking-products__content">
						<?php include 'tabs/html-booking-product-resources.php'; ?>
					</div>
					<!-- Booking Products / Tab -->
					<div data-booking-content="availability" class="booking-products__content">
						<?php include 'tabs/html-booking-product-availability.php'; ?>
					</div>
					<!-- Booking Products / Tab -->
					<div data-booking-content="costs" class="booking-products__content">
						<?php include 'tabs/html-booking-product-pricing.php'; ?>
					</div>
					<!-- Booking Products / Tab -->
					<div data-booking-content="persons" class="booking-products__content">
						<?php include 'tabs/html-booking-product-persons.php'; ?>
					</div>
				</div>

			</div>

			<div class="booking-products-footer">
				<?php if ( pbs_is_demo() ) : ?>
					<a href="javascript:"
					   class="btn btn-primary demo-notice-call"><?php esc_html_e( 'Update Booking Product', 'pebas-bookings-extension' ); ?></a>
				<?php else: ?>
					<button class="btn btn-primary"
					        type="submit"><?php esc_html_e( 'Update Booking Product', 'pebas-bookings-extension' ); ?></button>
				<?php endif; ?>
				<input type="hidden" name="action" value="update_booking_product" />
				<input type="hidden" name="post_id" value="<?php echo esc_attr( $product_id ); ?>" />
				<input type="hidden" name="listing_id" value="<?php echo esc_attr( $listing_id ); ?>" />
				<?php wp_nonce_field( 'pebas_booking_nonce', 'pebas_booking_nonce' ); ?>
			</div>
		</form>
	<?php endif; ?>
	</div>
<?php else: ?>
	<div class="lisner-booking-products">
		<div class="booking-products-header">
			<h6><?php esc_html_e( 'The listing product is not existant', 'pebas-bookings-extension' ); ?></h6>
		</div>
	</div>
<?php endif; ?>
