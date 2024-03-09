<?php
/**
 * Template Name: Header / Video Template
 * Description: Partial content for single listing header
 * ---------------------------------------------------------
 *
 * @author pebas
 * @version 1.0.1
 * @package listing/single/header
 *
 */

$option = get_option( 'pbs_option' );
?>
<?php if ( ! empty( $video ) && ! wp_is_mobile() ) : ?>
	<?php $video = lisner_hero::get_youtube_id( $video ); ?>
	<figure id="hero-video" class="hero-image" data-video="<?php echo esc_attr( $video ); ?>">
		<div id="player"></div>
	</figure>
<?php else: ?>
	<?php include lisner_helper::get_template_part( "single-header-{$header_template}", 'listing/single/header' ); ?>
<?php endif; ?>


