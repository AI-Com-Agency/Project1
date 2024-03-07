<?php
/**
 * Template Name: Listing Single Sidebar Template / 2
 * Description: Partial content for single listing sidebar
 *
 * @author pebas
 * @version 1.0.0
 * @package listing/single/sidebar/sidebar-template
 *
 */
?>

<?php if ( 0 != $option['listing-fields-working-hours'] && ( ! isset( $option['listing-fields-working-hours-members'] ) || lisner_show_to_member( $option['listing-fields-working-hours-members'] ) ) ): ?>
	<!-- Single Listing Widget / Working Time -->
	<section class="listing-widget listing-widget-work-time">
		<?php $wt_args = array(
			'has_title' => true,
			'title'     => esc_html__( 'Work Time', 'lisner-core' ),
		); ?>
		<?php include lisner_helper::get_template_part( 'single-working-time', 'listing/single/sidebar', $wt_args ); ?>
	</section>
<?php endif; ?>

<?php if ( 0 != $option['listing-fields-address'] && ( ! isset( $option['listing-fields-address-members'] ) || lisner_show_to_member( $option['listing-fields-address-members'] ) ) ): ?>
	<!-- Single Listing Widget / Location -->
	<section class="listing-widget listing-widget-location">
		<div class="d-flex">
			<?php $loc_args = array(
				'has_title'    => true,
				'title'        => esc_html__( 'Location', 'lisner-core' ),
				'has_location' => true
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


<?php if ( 0 != $option['listing-fields-pricing'] && ( ! isset( $option['listing-fields-pricing-members'] ) || lisner_show_to_member( $option['listing-fields-pricing-members'] ) ) ): ?>
	<!-- Single Listing Widget / Price Range -->
	<section class="listing-widget listing-widget-price-range">
		<?php $pr_args = array(
			'has_title' => true,
			'title'     => esc_html__( 'Price Range', 'lisner-core' ),
		); ?>
		<?php include lisner_helper::get_template_part( 'single-price-range', 'listing/single/sidebar', $pr_args ); ?>
	</section>
<?php endif; ?>

<?php if ( lisner_helper::is_plugin_active( 'pebas-review-listings' ) ) : ?>
	<!-- Single Listing Widget / Reviews -->
	<section class="listing-widget listing-widget-reviews">
		<div class="d-flex">
			<?php $rev_args = array(
				'has_title' => true,
				'title'     => esc_html__( 'Reviews', 'lisner-core' ),
			); ?>
			<?php if ( $rev_args['has_title'] ) : ?>
				<?php $title = $rev_args['title']; ?>
				<?php include lisner_helper::get_template_part( 'single-section-title', 'listing/single/content' ); ?>
				<?php $rev_args['has_title'] = false; ?>
			<?php endif; ?>
			<div class="listing-widget-directions ml-auto">
				<a class="btn btn-primary animate"
				   target="_blank"
				   href="#respond"><?php esc_html_e( 'Leave Review', 'lisner-core' ) ?>
					<i class="material-icons mf rotate--90"><?php echo esc_html( 'subdirectory_arrow_left' ); ?></i></a>
			</div>
		</div>
		<?php include lisner_helper::get_template_part( 'single-reviews', 'listing/single/sidebar', $rev_args ); ?>
	</section>
<?php endif; ?>

<?php if ( ! isset( $option['listing-fields-contact-members'] ) || lisner_show_to_member( $option['listing-fields-contact-members'] ) ): ?>
	<!-- Single Listing Widget / Contact Listing  -->
	<section class="listing-widget listing-widget-contact-listing">
		<?php $con_args = array(
			'has_title' => true,
			'title'     => esc_html__( 'Contact', 'lisner-core' ),
		); ?>
		<?php include lisner_helper::get_template_part( 'single-contact', 'listing/single/sidebar', $con_args ); ?>
	</section>
<?php endif; ?>
