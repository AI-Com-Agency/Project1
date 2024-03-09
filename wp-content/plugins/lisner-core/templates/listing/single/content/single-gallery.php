<?php
/**
 * Template Name: Listing Single Gallery
 * Description: Partial content for single listing gallery in content
 *
 * @author pebas
 * @version 1.0.0
 * @package listing/single/content
 *
 * @var $gallery_args
 */

?>
<?php if ( $gallery_args['has_title'] ) : ?>
	<?php $title = $gallery_args['title']; ?>
	<?php include lisner_helper::get_template_part( 'single-section-title', 'listing/single/content' ); ?>
<?php endif; ?>
<div class="single-listing-gallery-wrapper gallery-in-content">

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
</div>
