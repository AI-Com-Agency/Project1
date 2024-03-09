<?php
/**
 * Shortcode Post / Layout 1
 *
 * @author includes
 * @version 1.0.0
 * @package shortcodes/post/
 */
?>
<div class="lisner-post theme-spacing">
    <div class="container">
        <div class="row justify-content-center">

            <div class="col-sm-12 text-center margin-bottom-30">
                <!-- Section Listing / Title -->
				<?php include lisner_helper::get_template_part( 'title', 'shortcodes/partials', $atts ); ?>
            </div>

            <!-- Section Listing / Listing -->
			<?php $query_args = lisner_post_query( $atts ); ?>
			<?php $posts = new WP_Query( $query_args ); ?>
			<?php if ( $posts->have_posts() ) : ?>
                <div class="col-xl-9 col-xs-10">
                    <div class="row">
						<?php while ( $posts->have_posts() ) : ?>
							<?php $posts->the_post(); ?>
                            <div class="col-sm-4">
								<?php include lisner_helper::get_template_part( 'post', 'shortcodes/post/partials', $atts ); ?>
                            </div>
						<?php endwhile; ?>
                    </div>
                </div>
			<?php endif; ?>
        </div>

        <div class="col-sm-12 text-center margin-top-60">
			<?php $atts['btn_center'] = true; ?>
            <!-- Section Listing / Button-->
			<?php include lisner_helper::get_template_part( 'button', 'shortcodes/partials', $atts ); ?>
        </div>
    </div>
</div>
