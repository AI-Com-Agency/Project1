<?php
/**
 * Template Name: Header / Slider Template
 * Description: Partial content for single listing header
 *
 * @author pebas
 * @version 1.0.0
 * @package listing/single/header
 */

?>
<?php $thubmnail_size = isset( $atts['big_thumb'] ) ? 'listing_single_gallery_big' : 'listing_single_gallery'; ?>
<?php $gallery = rwmb_meta( '_listing_gallery', array( 'size' => $thubmnail_size ) ); ?>

<a href="javascript:" class="listing-gallery-call">
	<figure class="single-listing-header-image m-0">
		<div class="slider-loader-wrapper">
			<div class="slider-loader-item d-flex justify-content-center align-items-center"></div>
			<div class="slider-loader-item d-flex justify-content-center align-items-center"></div>
			<div class="slider-loader-item d-flex justify-content-center align-items-center"></div>
		</div>

		<div class="single-header-gallery">
			<!-- Slides -->
			<?php if ( $gallery ) : ?>
				<?php foreach ( $gallery as $image ) : ?>
					<?php $full_image = wp_get_attachment_image_src( $image['ID'], 'listing_single_popup' ); ?>
					<div class="single-header-gallery-item" data-image="<?php echo esc_url( $image['url'] ); ?>"
					     data-width="<?php echo esc_attr( $full_image[1] ); ?>"
					     data-height="<?php echo esc_attr( $full_image[2] ); ?>"><img
								src="<?php echo esc_url( $image['url'] ) ?>" alt=""></div>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>

	</figure>
</a>