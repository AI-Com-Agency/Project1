<?php
/**
 * Template Name: Widget Promo Template / 1
 * Description: Partial content for listing widgets
 *
 * @author pebas
 * @version 1.0.0
 * @package widgets/promo
 *
 */
global $post;
?>
<?php $banner = rwmb_meta( '_listing_cover', array( 'size' => 'promo_image' ), get_the_ID() ); ?>
<?php $gallery = rwmb_meta( '_listing_gallery', array( 'size' => 'promo_image' ), get_the_ID() ); ?>
<?php $fallback_image = lisner_get_option( 'fallback-bg-listing', null ); ?>
<?php $fallback_image = wp_get_attachment_image_src( $fallback_image, 'promo_image' ); ?>
<?php $image = isset( $banner ) && ! empty( $banner ) ? array_shift( $banner ) : ( isset( $gallery ) && ! empty( $gallery ) ? array_shift( $gallery ) : ( isset( $fallback_image ) && ! empty( $fallback_image ) ? array_shift( $fallback_image ) : '' ) ); ?>
<?php $claimed = $post->_claimed; ?>
<?php $claimed_render = ''; ?>
<?php if ( $claimed ) : ?>
	<?php $claimed_render = '<span class="lisner-listing-claimed material-icons mf">' . esc_html( 'check_circle' ) . '</span>'; ?>
<?php endif; ?>
<a href="<?php the_permalink(); ?>" class="widget-promo-wrapper">
	<figure class="widget-promo-image">
		<img src="<?php echo esc_url( isset( $image['url'] ) ? $image['url'] : $image ) ?>" alt="promo">
	</figure>
	<div class="widget-promo-content">
		<span class="widget-promo-ad"><?php esc_html_e( 'AD', 'lisner-core' ); ?></span>
		<span class="widget-promo-address"><i
					class="material-icons mf"><?php echo esc_html( 'place' ); ?></i><?php the_job_location( false ); ?></span>
		<h3 class="widget-promo-title"><?php the_title(); ?><?php echo wp_kses_post( $claimed_render ); ?></h3>
	</div>
</a>
