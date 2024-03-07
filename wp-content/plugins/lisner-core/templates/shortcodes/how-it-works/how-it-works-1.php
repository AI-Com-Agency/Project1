<?php
/**
 * Shortcodes Partials / How it works 1
 *
 * @author includes
 * @version 1.0.0
 * @package shortcodes/partials/
 */
$atts['title_custom'] = true;
$image                = isset( $atts['hiw_image'] ) && ! empty( $atts['hiw_image'] ) ? wp_get_attachment_image_src( $atts['hiw_image'], 'full' ) : '';
$video_link           = isset( $atts['hiw_video'] ) && ! empty( $atts['hiw_video'] ) ? $atts['hiw_video'] : '';
$video_image          = isset( $atts['hiw_video_image'] ) && ! empty( $atts['hiw_video_image'] ) ? wp_get_attachment_image_src( $atts['hiw_video_image'], 'full' ) : '';
$video                = isset( $_REQUEST['hiw_video_load'] ) && ! empty( $_REQUEST['hiw_video_load'] ) ? $_REQUEST['hiw_video_load'] : '';
?>
<section class="how-it-works how-it-works-template-1 theme-spacing"
         style="background-image:url(<?php echo isset( $image[0] ) ? esc_url( $image[0] ) : ''; ?>);">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-10">

				<div class="row">
					<div class="col-md-5">
						<p>
						<?php echo $content; ?>
						</p>

						<!-- Section Taxonomy / Button-->
						<?php include lisner_helper::get_template_part( 'button', 'shortcodes/partials', $atts ); ?>
					</div>

					<?php if ( ! empty( $video_link ) ) : ?>
						<div class="col-md-7">
							<div class="lisner-video-ajax input-group">
							<span class="hiw-video-overlay"
							      data-video="<?php echo esc_attr( $video_link ); ?>"
							      style="background-image: url(<?php echo esc_url( $video_image[0] ); ?>);"><i
										class="material-icons mf"><?php echo esc_html( 'play_circle_outline' ); ?></i></span>
								<div class="video-preview <?php echo ! empty( $video ) ? esc_attr( 'video-preview-loaded' ) : ''; ?>">
									<?php $embed = wp_oembed_get( isset( $video ) ? $video : '' ); ?>
									<?php echo $embed ? $embed : ( empty( $embed ) ? '' : esc_html__( 'Embed not available', 'lisner-core' ) ); ?>
								</div>
							</div>
						</div>
					<?php endif; ?>

				</div>

			</div>

		</div>
	</div>
</section>
