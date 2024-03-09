<?php
/**
 * Template Name: Single Post Loop / Audio
 * Description: Partial content for single post section
 *
 * @author pebas
 * @version 1.0.0
 * @package views/posts/media
 *
 */
?>
<?php $content = apply_filters( 'the_content', get_the_content() ); ?>
<?php $video = false; ?>
<?php
// Only get video from the content if a playlist isn't present.
if ( false === strpos( $content, 'wp-playlist-script' ) ) {
	$audio = get_media_embedded_in_content( $content, array( 'audio' ) );
}
?>
<?php if ( ! empty( $audio ) ) : ?>
    <figure class="post-media-audio">
		<?php echo wp_kses_post( $audio[0] ); ?>
    </figure>
<?php endif; ?>
