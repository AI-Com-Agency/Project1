<?php
/**
 * Shortcode Listing / Layout 3
 *
 * @author includes
 * @version 1.0.0
 * @package shortcodes/listing/
 */
?>
<div class="lisner-listing theme-spacing lisner-listing-slider">
	<div class="container-fluid">
		<div class="row">

			<div class="col-lg-11 offset-1">
				<div class="row justify-content center">
					<div class="col-lg-3">
						<!-- Section Listing / Title -->
						<?php include lisner_helper::get_template_part( 'title', 'shortcodes/partials', $atts ); ?>

						<!-- Section Listing / Button-->
						<?php include lisner_helper::get_template_part( 'button', 'shortcodes/partials', $atts ); ?>
					</div>

					<div class="col-lg-9">
						<!-- Section Listing / Listing -->
						<div class="lisner-listings-slick">
							<?php $query_args = lisner_adjust_query( $atts ); ?>
							<?php $listings = new WP_Query( $query_args ); ?>
							<?php if ( $listings->have_posts() ) : ?>
								<?php while ( $listings->have_posts() ) : ?>
									<?php $listings->the_post(); ?>
									<?php include lisner_helper::get_template_part( 'listing', 'shortcodes/listing/partials', $atts ); ?>
								<?php endwhile; ?>
							<?php endif; ?>
						</div>
					</div>

				</div>
			</div>

		</div>
	</div>
</div>
