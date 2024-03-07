<?php
/**
 * Template Name: Listing Single Sidebar Template / 1
 * Description: Partial content for single listing sidebar
 *
 * @author pebas
 * @version 1.0.0
 * @package listing/single/sidebar/sidebar-template
 *
 */
$option          = get_option( 'pbs_option' );
$booking_display = isset( $option['booking-display'] ) ? $option['booking-display'] : 'default';
$author          = get_post_meta( get_the_ID(), '_job_author', true );
$author          = lisner_get_var( $author, 1 );
?>

<?php if ( 'top' == $booking_display ) : ?>
	<?php if ( class_exists( 'WC_Bookings' ) ) : // include WooCommerce bookings ?>
		<?php include lisner_helper::get_template_part( 'booking', 'listing/single/bookings', $args ); ?>
	<?php endif; ?>
<?php endif; ?>

<!-- Single Listing Widget / Location -->
<?php if ( 0 != $option['listing-fields-address'] && ( ! isset( $option['listing-fields-address-members'] ) || lisner_show_to_member( $option['listing-fields-address-members'] ) ) ): ?>
	<section class="listing-widget listing-widget-location pt-0 pb-0">
		<div class="d-flex location-information">
			<?php $loc_args = array(
				'has_title' => false,
				'title'     => esc_html__( 'Location', 'lisner-core' ),
			); ?>
			<?php if ( $loc_args['has_title'] ) : ?>
				<?php $title = $loc_args['title']; ?>
				<?php include lisner_helper::get_template_part( 'single-section-title', 'listing/single/content' ); ?>
				<?php $loc_args['has_title'] = false; ?>
			<?php endif; ?>
			<div class="listing-widget-directions ml-auto">
				<?php $direction = esc_url( 'https://maps.google.com/maps?daddr=' . $post->geolocation_lat . ',' . $post->geolocation_long ); ?>
				<a class="btn btn-secondary"
				   target="_blank"
				   href="<?php echo esc_url( $direction ); ?>"><?php esc_html_e( 'Get Directions', 'lisner-core' ) ?>
					<i class="material-icons mf"><?php echo esc_html( 'subdirectory_arrow_right' ); ?></i></a>
			</div>
		</div>
		<?php include lisner_helper::get_template_part( 'single-location', 'listing/single/sidebar', $loc_args ); ?>
	</section>
<?php endif; ?>

<?php $logo = get_user_meta( $author, '_listing_logo', true ); ?>
<?php $logo_main = get_post_thumbnail_id(); ?>
<?php if ( 0 != $option['listing-fields-logo'] && ( ! isset( $option['listing-fields-logo-members'] ) || lisner_show_to_member( $option['listing-fields-logo-members'] ) ) ): ?>
	<?php if ( isset( $option['listings-logo-page-display'] ) && ! empty( $option['listings-logo-page-display'] ) && ( isset( $logo ) && ! empty( $logo ) || isset( $logo_main ) && ! empty( $logo_main ) ) ) : ?>
		<?php $logo_data = has_post_thumbnail() ? wp_get_attachment_image_src( $logo_main, 'full' ) : wp_get_attachment_image_src( $logo, 'full' ); ?>
		<!-- Listing / Logo -->
		<section class="listing-widget listing-widget-brand">
			<div class="listing-brand listing-brand-single">
				<div class="listing-brand__logo">
					<img class="img-fluid" src="<?php echo esc_url( $logo_data[0] ); ?>"
					     alt="<?php esc_attr_e( 'Logo', 'lisner-core' ); ?>">
				</div>
			</div>
		</section>
	<?php endif; ?>
<?php endif; ?>

<?php if ( 0 != $option['listing-fields-website'] && ( ! isset( $option['listing-fields-website-members'] ) || lisner_show_to_member( $option['listing-fields-website-members'] ) ) ): ?>
	<!-- Single Listing Widget / Website -->
	<section class="listing-widget listing-widget-website">
		<?php $pr_args = array(
			'has_title' => false,
			'title'     => esc_html__( 'Website', 'lisner-core' ),
		); ?>
		<?php include lisner_helper::get_template_part( 'single-website', 'listing/single/sidebar', $pr_args ); ?>
	</section>
<?php endif; ?>

<?php if ( 0 != $option['listing-fields-address'] && ( ! isset( $option['listing-fields-address-members'] ) || lisner_show_to_member( $option['listing-fields-address-members'] ) ) ): ?>
	<?php $location = get_the_job_location(); ?>
	<?php if ( ! empty( $location ) ) : ?>
		<!-- Single Listing Widget / Address -->
		<section class="listing-widget listing-widget-address">
			<?php $pr_args = array(
				'has_title' => false,
				'title'     => esc_html__( 'Address', 'lisner-core' ),
			); ?>
			<?php include lisner_helper::get_template_part( 'single-address', 'listing/single/sidebar', $pr_args ); ?>
		</section>
	<?php endif; ?>
<?php endif; ?>

<?php if ( 0 != $option['listing-fields-phone'] && ( ! isset( $option['listing-fields-phone-members'] ) || lisner_show_to_member( $option['listing-fields-phone-members'] ) ) ): ?>
	<?php if ( isset( $post->_listing_phone ) ) : ?>
		<!-- Single Listing Widget / Phone -->
		<section class="listing-widget listing-widget-phone">
			<?php $phone_args = array(
				'has_title' => false,
				'title'     => esc_html__( 'Phone', 'lisner-core' ),
			); ?>
			<?php include lisner_helper::get_template_part( 'single-phone', 'listing/single/sidebar', $phone_args ); ?>
		</section>
	<?php endif; ?>
<?php endif; ?>

<?php if ( lisner_helper::is_plugin_active( 'pebas-review-listings' ) ) : ?>
	<!-- Single Listing Widget / Reviews -->
	<section class="listing-widget listing-widget-reviews">
		<div class="d-flex">
			<?php $rev_args = array(
				'has_title' => false,
				'title'     => esc_html__( 'Reviews', 'lisner-core' ),
			); ?>
		</div>
		<?php include lisner_helper::get_template_part( 'single-reviews', 'listing/single/sidebar', $rev_args ); ?>
	</section>
<?php endif; ?>

<?php if ( 0 != $option['listing-fields-pricing'] && ( ! isset( $option['listing-fields-pricing-members'] ) || lisner_show_to_member( $option['listing-fields-pricing-members'] ) ) ): ?>
	<!-- Single Listing Widget / Price Range -->
	<section class="listing-widget listing-widget-price-range">
		<?php $pr_args = array(
			'has_title' => false,
			'title'     => esc_html__( 'Price Range', 'lisner-core' ),
		); ?>
		<?php include lisner_helper::get_template_part( 'single-price-range', 'listing/single/sidebar', $pr_args ); ?>
	</section>
<?php endif; ?>

<?php if ( 0 != $option['listing-fields-working-hours'] && ( ! isset( $option['listing-fields-working-hours-members'] ) || lisner_show_to_member( $option['listing-fields-working-hours-members'] ) ) ): ?>
	<?php $days = array(
		'monday'    => esc_html__( 'Monday', 'lisner-core' ),
		'tuesday'   => esc_html__( 'Tuesday', 'lisner-core' ),
		'wednesday' => esc_html__( 'Wednesday', 'lisner-core' ),
		'thursday'  => esc_html__( 'Thursday', 'lisner-core' ),
		'friday'    => esc_html__( 'Friday', 'lisner-core' ),
		'saturday'  => esc_html__( 'Saturday', 'lisner-core' ),
		'sunday'    => esc_html__( 'Sunday', 'lisner-core' )
	); ?>
	<?php $current_day = strtolower( date( 'l' ) ); ?>
	<?php $current_time = date( 'H:i' ); ?>
	<?php $open_settings = get_post_meta( get_the_ID(), "_listing_{$current_day}_hours_radio", true ); ?>
	<?php $open_hours = get_post_meta( get_the_ID(), "_listing_{$current_day}_hours_open" ); ?>
	<?php $close_hours = get_post_meta( get_the_ID(), "_listing_{$current_day}_hours_close" ); ?>
	<?php $open_hours = array_shift( $open_hours ); ?>
	<?php $close_hours = array_shift( $close_hours ); ?>
	<?php $hours =  is_array( $open_hours ) && is_array( $close_hours ) ? array_combine( $open_hours, $close_hours ) : ''; ?>
	<?php if ( isset( $open_settings ) ) : ?>
		<!-- Single Listing Widget / Working Time -->
		<section class="listing-widget listing-widget-work-time">
			<?php $wt_args = array(
				'has_title'     => false,
				'title'         => esc_html__( 'Work Time', 'lisner-core' ),
				'work_template' => 2,
				'hours'         => $hours
			); ?>
			<?php include lisner_helper::get_template_part( 'single-working-time', 'listing/single/sidebar', $wt_args ); ?>
		</section>
	<?php endif; ?>
<?php endif; ?>

<?php if ( 0 != $option['listing-fields-social'] && ( ! isset( $option['listing-fields-social-members'] ) || lisner_show_to_member( $option['listing-fields-social-members'] ) ) ): ?>
	<?php $social_icons = array( 'facebook', 'twitter', 'google', 'instagram', 'youtube', 'pinterest', 'linkedin' ) ?>
	<?php $icons = array(); ?>
	<?php foreach ( $social_icons as $social_icon ) : ?>
		<?php if ( null !== ( $post->_listing_social__ . $social_icon ) ) : ?>
			<?php $icons[ $social_icon ] = get_post_meta( $post->ID, "_listing_social__{$social_icon}", true ); ?>
		<?php endif; ?>
	<?php endforeach; ?>
	<?php if ( ! empty( $icons ) ) : ?>
		<!-- Single Listing Widget / Phone -->
		<section class="listing-widget listing-widget-social">
			<?php $social_args = array(
				'has_title' => false,
				'title'     => esc_html__( 'Social', 'lisner-core' ),
				'icons'     => $icons
			); ?>
			<?php include lisner_helper::get_template_part( 'single-social', 'listing/single/sidebar', $social_args ); ?>
		</section>
	<?php endif; ?>
<?php endif; ?>

<?php if ( 0 != $option['listing-fields-files'] && ( ! isset( $option['listing-fields-files-members'] ) || lisner_show_to_member( $option['listing-fields-files-members'] ) ) ): ?>
	<?php $files = rwmb_meta( '_listing_files', '', get_the_ID() ); ?>
	<?php if ( isset( $files ) && ! empty( $files ) ) : ?>
		<!-- Single Listing Widget / Working Time -->
		<section class="listing-widget listing-widget-work-time">
			<?php $files_args = array(
				'has_title' => false,
				'title'     => esc_html__( 'Documents', 'lisner-core' ),
				'files'     => $files
			); ?>
			<?php include lisner_helper::get_template_part( 'single-files', 'listing/single/sidebar', $files_args ); ?>
		</section>
	<?php endif; ?>
<?php endif; ?>

<?php if ( 'default' == $booking_display ) : ?>
	<?php if ( class_exists( 'WC_Bookings' ) ) : // include WooCommerce bookings ?>
		<?php include lisner_helper::get_template_part( 'booking', 'listing/single/bookings', $args ); ?>
	<?php endif; ?>
<?php endif; ?>

<?php if ( lisner_helper::is_plugin_active( 'pebas-listing-events' ) ) : ?>
	<!-- Single Listing Widget / Listing Events -->
	<?php do_action( 'lisner_listing_sidebar_events_before', get_the_ID() ); ?>
	<?php do_action( 'lisner_listing_sidebar_events', get_the_ID() ); ?>
	<?php do_action( 'lisner_listing_sidebar_events_after', get_the_ID() ); ?>
<?php endif; ?>

<?php if ( class_exists( 'Pebas_Listing_Coupons' ) ) : ?>
	<!-- Single Listing Widget / Listing Coupons -->
	<?php do_action( 'lisner_listing_sidebar_coupons_before', get_the_ID() ); ?>
	<?php do_action( 'lisner_listing_sidebar_coupons', get_the_ID() ); ?>
	<?php do_action( 'lisner_listing_sidebar_coupons_after', get_the_ID() ); ?>
<?php endif; ?>

<?php if ( ! isset( $option['listing-fields-contact-members'] ) || lisner_show_to_member( $option['listing-fields-contact-members'] ) ): ?>
	<!-- Single Listing Widget / Contact Listing  -->
	<section class="listing-widget listing-widget-contact-listing">
		<?php $con_args = array(
			'has_title' => false,
			'title'     => esc_html__( 'Contact', 'lisner-core' ),
		); ?>
		<?php include lisner_helper::get_template_part( 'single-contact', 'listing/single/sidebar', $con_args ); ?>
	</section>
<?php endif; ?>
