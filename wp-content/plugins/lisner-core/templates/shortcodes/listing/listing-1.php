<?php
/**
 * Shortcode Listing / Layout 1
 *
 * @author includes
 * @version 1.0.0
 * @package shortcodes/listing/
 */
?>
<div class="lisner-listing theme-spacing">
    <div class="container">
        <div class="row">

            <div class="col-lg-3">
                <!-- Section Listing / Title -->
				<?php include lisner_helper::get_template_part( 'title', 'shortcodes/partials', $atts ); ?>

                <!-- Section Listing / Button-->
				<?php include lisner_helper::get_template_part( 'button', 'shortcodes/partials', $atts ); ?>
            </div>

            <!-- Section Listing / Listing -->
			<?php $query_args = lisner_adjust_query( $atts ); ?>
			<?php $listings = new WP_Query( $query_args ); ?>
			<?php if ( $listings->have_posts() ) : ?>
                <div class="col-lg-9">
                    <div class="row">
						<?php while ( $listings->have_posts() ) : ?>
							<?php $listings->the_post(); ?>
                            <div class="col-xl-4 col-sm-6">
								<?php include lisner_helper::get_template_part( 'listing', 'shortcodes/listing/partials', $atts ); ?>
                            </div>
						<?php endwhile; ?>
                    </div>
                </div>
			<?php endif; ?>
        </div>
    </div>
</div>
