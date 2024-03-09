<?php
/**
 * Template Name: Listing Single Header
 * Description: Partial content for single listing post
 *
 * @author pebas
 * @version 1.0.1
 * @package listing/single
 */

?>
<!-- Page Hero -->
<?php $image = rwmb_meta( '_listing_cover', array( 'size' => 'listing_single_image' ) ); ?>
<?php $gallery = rwmb_meta( '_listing_gallery', array( 'size' => 'listing_single_gallery' ) ); ?>
<?php $template = get_post_meta( get_the_ID(), '_listing_banner_template', true ); ?>
<?php $template = isset( $template ) && ! empty( $template ) ? $template : 'image'; ?>
<?php $page_template = isset( $page_template ) ? $page_template : ''; ?>
<?php $video = get_post_meta( get_the_ID(), '_listing_video', true ); ?>
<?php $video_height = 'video' == $template && ! wp_is_mobile() && isset( $option['listings-appearance-video-height'] ) && ! empty( $option['listings-appearance-video-height'] ) ? 'style=height:' . $option['listings-appearance-video-height'] . 'px;' : ''; ?>
<header class="single-listing-header <?php echo 'video' == $template ? esc_attr( 'single-listing-header-video' ) : ''; ?> single-listing-header-style-<?php echo esc_attr( $page_template ); ?> <?php echo empty( $image ) && empty( $gallery ) ? esc_attr( 'single-listing-header-no-image' ) : '' ?>" <?php echo ! empty( $video_height ) ? esc_attr( $video_height ) : ''; ?>>
	<?php $header_template = 1 < count( $gallery ) ? 'slider' : 'default'; ?>
	<?php if ( 'image' == $template ) : ?>
		<?php include lisner_helper::get_template_part( "single-header-{$header_template}", 'listing/single/header' ); ?>
	<?php else: ?>
		<?php if ( $gallery ) : ?>
			<?php shuffle( $gallery ); ?>
			<?php $gallery = array_shift( $gallery ); ?>
			<?php $gallery_thumb = wp_get_attachment_image_src( $gallery['ID'], 'full' ); ?>
		<?php endif; ?>
		<?php $youtube_thumb = isset( $gallery_thumb ) ? $gallery_thumb[0] : lisner_hero::get_youtube_thumbnail( $video ); ?>
		<div class="single-listing-header-video-loader" <?php echo esc_attr( 'style=background-image:url(' . $youtube_thumb . ');' ); ?>></div>
		<?php include lisner_helper::get_template_part( "single-header-video", 'listing/single/header', array(
			'header_template' => $header_template,
			'video'           => $video
		) ); ?>
	<?php endif; ?>
</header>
