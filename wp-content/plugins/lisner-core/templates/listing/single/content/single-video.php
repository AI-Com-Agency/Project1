<?php
/**
 * Template Name: Listing Single Video
 * Description: Partial content for single listing video
 *
 * @author pebas
 * @version 1.0.0
 * @package listing/single/content
 *
 * @var $video_args
 */

$image_fb = lisner_get_option( 'fallback-bg-video' );
$image_fb = wp_get_attachment_image_src( $image_fb, 'full' );
$video    = get_post_meta( get_the_ID(), '_listing_video', true );
?>
<?php if ( $video_args['has_title'] ) : ?>
	<?php $title = $video_args['title']; ?>
	<?php include lisner_helper::get_template_part( 'single-section-title', 'listing/single/content' ); ?>
<?php endif; ?>
<div class="single-listing-video-wrapper">
	<div class="video-call"
	     data-video="<?php echo esc_attr( $video ); ?>"><i
				class="material-icons mf"><?php echo esc_html( 'play_circle_outline' ); ?></i></div>
	<div class="video-preview  embed-responsive embed-responsive-16by9">
		<?php $embed = wp_oembed_get( isset( $video ) ? $video : '' ); ?>
		<?php echo $embed ? $embed : ( empty( $embed ) ? '' : esc_html__( 'Embed not available', 'lisner-core' ) ); ?>
	</div>
</div>
