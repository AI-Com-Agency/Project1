<?php
/**
 * Template Name: Listing Single Meta
 * Description: Partial content for single listing content
 *
 * @author pebas
 * @version 1.0.0
 * @package listing/single/content
 */
?>
<div class="single-listing-main-meta">
	<?php if ( lisner_helper::is_plugin_active( 'pebas-review-listings' ) ): ?>
		<div class="single-listing-main-meta-action">
			<a class="animate" href="#respond"><?php esc_html_e( 'Leave Review', 'lisner-core' ); ?><i
						class="material-icons rotate--90"><?php echo esc_attr( 'subdirectory_arrow_left' ); ?></i></a>
		</div>
	<?php endif; ?>
	<!-- Listing / Bottom Meta -->
	<div class="lisner-listing-meta">

		<?php $has_review = false; ?>
		<?php if ( lisner_helper::is_plugin_active( 'pebas-review-listings' ) ): ?>
			<?php $has_review = true; ?>
			<!-- Listing / Rating -->
			<?php $avg_rating = pebas_review_listings_functions::get_average_rating( get_the_ID() ); ?>
			<div class="lisner-listing-meta-rating color-<?php echo '0.0' == $avg_rating ? esc_attr( 'disabled' ) : esc_attr( 'success' ); ?>">
				<?php echo esc_html( $avg_rating ); ?>
			</div>
		<?php endif; ?>

		<?php if ( 0 != $option['listing-fields-categories'] && lisner_show_to_member( $option['listing-fields-categories-members'] ) ): ?>
			<?php $category_id = lisner_helper::get_single_category_id( get_the_ID() ); ?>
			<?php if ( $category_id ) : ?>
				<!-- Listing / Category -->
				<div class="lisner-listing-meta-category <?php echo $has_review ? esc_attr( 'has-review' ) : ''; ?>">
					<?php $category = get_term_by( 'id', $category_id, 'job_listing_category' ); ?>
					<?php $tax_link = lisner_taxonomy_link( $category->term_id ); ?>
					<?php $icon = get_term_meta( $category_id, 'term_icon', true ); ?>
					<a href="<?php echo esc_url( $tax_link ); ?>">
						<?php if ( $icon ) : ?>
							<i class="lisner-listing-meta-icon material-icons mf"><?php echo esc_html( $icon ); ?></i>
						<?php endif; ?>
						<?php echo esc_html( $category->name ); ?></a>
				</div>
			<?php endif; ?>
		<?php endif; ?>

		<?php if ( 0 != $option['listing-fields-working-hours'] && lisner_show_to_member( $option['listing-fields-working-hours-members'] ) ): ?>
			<?php $open = lisner_helper()->is_open_now_render( get_the_ID() ) ?>
			<!-- Listing / Open -->
			<div class="lisner-listing-meta-open <?php echo $open ? esc_attr( 'is-open' ) : esc_attr( 'is-closed' ); ?>">
				<i class="material-icons mf"><?php echo esc_html( 'query_builder' ); ?></i>
				<?php $open ? esc_html_e( 'Open', 'lisner-core' ) : esc_html_e( 'Closed', 'lisner-core' ); ?>
			</div>
		<?php endif; ?>
		<?php if ( 0 != $option['listing-fields-pricing'] && lisner_show_to_member( $option['listing-fields-pricing-members'] ) ): ?>
			<!-- Single Listing / Price Range -->
			<div class="lisner-listing-meta-item">
				<?php $price_range = lisner_helper::pricing_range_render( get_the_ID() ); ?>
				<?php echo wp_kses( $price_range, array(
					'span' => array( 'class' => array() ),
					'i'    => array( 'class' => array() )
				) ); ?>
			</div>
		<?php endif; ?>
		<?php if ( 0 != $option['listing-fields-address'] && lisner_show_to_member( $option['listing-fields-address-members'] ) ): ?>
			<!-- Single Listing / Address -->
			<div class="lisner-listing-meta-item last-item">
				<?php $city = get_post_meta( get_the_ID(), 'geolocation_city', true ); ?>
				<?php $country = get_post_meta( get_the_ID(), 'geolocation_country_short', true ); ?>
				<?php $address = $city . ', ' . $country; ?>
				<?php $address = isset( $address ) && ! empty( $address ) ? $address : get_post_meta( get_the_ID(), '_job_location', true ); ?>
				<address>
					<i class="material-icons mf"><?php echo esc_attr( 'room' ); ?></i><?php the_job_location( true ); ?>
					<i
							class="material-icons mf color-primary"><?php echo esc_attr( 'subdirectory_arrow_right' ); ?></i>
				</address>
			</div>
		<?php endif; ?>
	</div>
</div>
