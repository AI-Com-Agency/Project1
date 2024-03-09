<?php
/**
 * Shortcode Listing / Layout 8
 *
 * @author includes
 * @version 1.0.0
 * @package shortcodes/listing/
 */
?>
<div class="lisner-listing theme-spacing lisner-listing-template-<?php echo esc_attr( $atts['listing_template'] ); ?>">
	<div class="container">

		<div class="row justify-content-center">
			<div class="col-lg-5 text-center">
				<!-- Section Taxonomy / Title -->
				<?php include lisner_helper::get_template_part( 'title', 'shortcodes/partials', $atts ); ?>
			</div>
		</div>

		<div class="row justify-content-center mt-3">
			<!-- Section Listing / Listing -->
			<?php $query_args = lisner_adjust_query( $atts ); ?>
			<?php $listings = new WP_Query( $query_args ); ?>
			<?php $atts['listing_style'] = 'small'; ?>
			<?php $atts['with_rating'] = true; ?>
			<?php $atts['with_preview'] = true; ?>
			<?php $atts['with_working_time'] = true; ?>
			<?php if ( $listings->have_posts() ) : ?>
				<div class="col-lg-8">
					<div class="row">
						<?php while ( $listings->have_posts() ) : ?>
							<?php $listings->the_post(); ?>
							<div class="col-sm-6 list-col-6">
								<?php include lisner_helper::get_template_part( 'listing-promo', 'shortcodes/listing/partials', $atts ); ?>
							</div>
						<?php endwhile; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>

		<div class="row justify-content-center mt-5">
			<div class="col-sm-12 text-center">
				<!-- Section Taxonomy / Button-->
				<?php include lisner_helper::get_template_part( 'button', 'shortcodes/partials', $atts ); ?>
			</div>
		</div>

	</div>
</div>
