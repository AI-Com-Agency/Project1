<?php
/**
 * Template Name: Single Post Loop / Video
 * Description: Partial content for single post section
 *
 * @author pebas
 * @version 1.0.0
 * @package views/posts/media
 *
 * @var $title
 */
?>
<?php $content = apply_filters( 'the_content', get_the_content() ); ?>
<?php $video = false; ?>
<?php
// Only get video from the content if a playlist isn't present.
if ( false === strpos( $content, 'wp-playlist-script' ) ) {
	$video = get_media_embedded_in_content( $content, array( 'video', 'object', 'embed', 'iframe' ) );
}
?>
<?php if ( ! empty( $video ) ) : ?>
    <figure class="post-media-video">
		<?php echo wp_kses_post( $video[0] ); ?>
    </figure>
<?php endif; ?>
