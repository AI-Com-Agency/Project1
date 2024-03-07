<?php
/**
 * Shortcode Listing / Listing Box Promo
 *
 * @author includes
 * @version 1.0.0
 * @package shortcodes/listing/partials
 */
$option = get_option( 'pbs_option' );
$style  = isset( $atts['listing_style'] ) ? $atts['listing_style'] : 'grid';
?>
<?php $listing_id = isset( $atts['listing_id'] ) ? $atts['listing_id'] : get_the_ID(); ?>
<?php $price_range = lisner_helper::pricing_range_render( $listing_id ); ?>
<?php $image_size = isset( $atts['listing_preview'] ) ? 'listing_preview_box' : ( isset( $args['thumbnail_size'] ) ? $args['thumbnail_size'] : 'listing_box' ); ?>
<?php $image_size = isset( $atts['thumbnail_vertical'] ) && $atts['thumbnail_vertical'] ? 'listing_box_vertical' : $image_size; ?>
<?php $banner = rwmb_meta( '_listing_cover', array( 'size' => $image_size ), $listing_id ); ?>
<?php $gallery = rwmb_meta( '_listing_gallery', array( 'size' => $image_size ), $listing_id ); ?>
<?php $fallback_image = lisner_get_option( 'fallback-bg-listing', null ); ?>
<?php $fallback_image = wp_get_attachment_image_src( $fallback_image, $image_size ); ?>
<?php $image = isset( $banner ) && ! empty( $banner ) ? array_shift( $banner ) : ( isset( $gallery ) && ! empty( $gallery ) ? array_shift( $gallery ) : ( isset( $fallback_image ) && ! empty( $fallback_image ) ? array_shift( $fallback_image ) : '' ) ); ?>
<?php $claimed = get_post_meta( $listing_id, '_claimed', true ); ?>
<?php $claimed_render = ''; ?>
<?php $author = get_post_meta( $listing_id, '_job_author', true ); ?>
<?php $author = lisner_get_var( $author, 1 ); ?>
<?php $logo = get_user_meta( $author, '_listing_logo', true ); ?>
<?php if ( $claimed ) : ?>
	<?php $claimed_render = '<span class="lisner-listing-claimed material-icons mf">' . esc_html( 'check_circle' ) . '</span>'; ?>
<?php endif; ?>
<div class="lisner-listing-item-promo">
	<a href="<?php the_permalink(); ?>" class="widget-promo-wrapper" target="_blank">
		<figure class="widget-promo-image">
			<img src="<?php echo esc_url( isset( $image['url'] ) ? $image['url'] : $image ) ?>" alt="promo">
		</figure>
		<div class="widget-promo-content <?php echo isset( $logo ) && ! empty( $logo ) ? esc_attr( 'has-logo' ) : ''; ?>">

			<div class="widget-promo-content-info">
				<?php $featured = get_post_meta( $listing_id, '_featured', true ); ?>
				<?php if ( $featured ) : ?>
					<span class="lisner-listing-promoted lisner-listing-featured color-warning"><?php echo esc_html__( 'Featured', 'lisner-core' ); ?></span>
				<?php endif; ?>
				<!-- <span class="widget-promo-ad"><?php // esc_html_e( 'AD', 'lisner-core' ); ?></span> -->
				<span class="widget-promo-address"><i
							class="material-icons mf"><?php echo esc_html( 'place' ); ?></i><?php the_job_location( false ); ?></span>
				<h3 class="widget-promo-title"><?php the_title(); ?><?php echo wp_kses_post( $claimed_render ); ?></h3>
			</div>
		</div>
	</a>
	<?php if ( ! isset( $atts['listing_preview'] ) ) : ?>
		<!-- Listing / Quick Preview -->
		<div class="lisner-listing-preview-call" data-listing-id="<?php echo esc_attr( $listing_id ); ?>">
			<a class="listing-preview-call" rel="nofollow"
			   href="javascript:"><span><?php esc_html_e( 'Quick Preview', 'lisner-core' ); ?></span>
				<i class="material-icons mf"><?php echo esc_html( 'crop_free' ); ?></i></a>
		</div>
	<?php endif; ?>
</div>

