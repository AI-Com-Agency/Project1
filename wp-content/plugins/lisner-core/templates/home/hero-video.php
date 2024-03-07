<?php
/**
 * Home / Hero images template
 *
 * @author pebas
 * @package home/hero-images
 * @version 1.0.0
 *
 * @param $args
 */
$bg_opacity = get_post_meta( $args['page_id'], 'home_bg_video_overlay_opacity', 'opacity' );
$bg_opacity = isset( $bg_opacity ) && ! empty( $bg_opacity ) ? 'opacity: ' . $bg_opacity . ';' : 1;
$image      = get_post_meta( $args['page_id'], 'home_bg_video_image', true );
$image      = isset( $image ) && ! empty( $image ) ? wp_get_attachment_image_src( $image, 'full_url' ) : '';
$video      = get_post_meta( $args['page_id'], 'home_bg_video', true );
$video      = lisner_hero::get_youtube_id( $video );
$loop       = get_post_meta( $args['page_id'], 'home_bg_video_loop', true );
$loop       = isset( $loop ) && ! empty( $loop ) ? $loop : false;
$start      = get_post_meta( $args['page_id'], 'home_bg_video_start', true );
$start      = isset( $start ) && ! empty( $start ) ? $start : false;
$end        = get_post_meta( $args['page_id'], 'home_bg_video_end', true );
$end        = isset( $end ) && ! empty( $end ) ? $end : false;
?>
<?php if ( ! empty( $image ) ) : ?>
	<figure id="hero-video" class="hero-image" data-video="<?php echo esc_attr( $video ); ?>"
	        data-loop="<?php echo esc_attr( $loop ); ?>"
	        data-start="<?php echo esc_attr( $start ); ?>"
	        data-end="<?php echo esc_attr( $end ); ?>"
	        style="background-image: url(<?php echo esc_url( $image[0] ); ?>); <?php echo esc_attr( $bg_opacity ); ?>">
		<div id="player"></div>
	</figure>
<?php else: ?>
	<figure id="hero-video" class="hero-image" data-video="<?php echo esc_attr( $video ); ?>"
	        data-loop="<?php echo esc_attr( $loop ); ?>"
	        data-start="<?php echo esc_attr( $start ); ?>"
	        data-end="<?php echo esc_attr( $end ); ?>" style="<?php echo esc_attr( $bg_opacity ); ?>">
		<div id="player"></div>
	</figure>
<?php endif; ?>
