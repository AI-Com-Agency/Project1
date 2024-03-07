<?php
/**
 * Mega Menu Video Template
 *
 * @author pebas
 * @version 1.0.0
 * @package views/
 *
 * @var $atts
 */
?>

<?php $image = is_array( $image ) ? wp_get_attachment_image_src( array_shift( $atts['image'] ), 'mega-menu-thumbnail' ) : ''; ?>
<?php $video_link = isset( $image ) && ! empty( $image ) ? '' : $video; ?>
<figure class="mega-menu-item-figure mega-menu-item-video">
	<div class="lisner-video-ajax">
		<?php if ( ! empty( $image ) ) : ?>
			<span class="video-overlay video-call"
			      data-video="<?php echo esc_attr( $video ); ?>"
			      style="background-image: url(<?php echo esc_url( $image[0] ); ?>);"><i
						class="material-icons mf"><?php echo esc_html( 'play_circle_outline' ); ?></i></span>
		<?php endif; ?>
		<div class="video-preview <?php echo ! empty( $video_link ) ? esc_attr( 'video-preview-loaded' ) : ''; ?>">
			<?php $embed = wp_oembed_get( isset( $video_link ) ? $video_link : '' ); ?>
			<?php echo $embed ? $embed : ( empty( $embed ) ? '' : esc_html__( 'Embed not available', 'lisner-core' ) ); ?>
		</div>
	</div>
</figure>
