<?php
/**
 * Template Name: Listing Single Location
 * Description: Partial content for single listing sidebar
 *
 * @author pebas
 * @version 1.0.0
 * @package listing/single/sidebar
 *
 * @var $loc_args
 */
global $post;
?>
<?php if ( $loc_args['has_title'] ) : ?>
	<?php $title = $loc_args['title']; ?>
	<?php include lisner_helper::get_template_part( 'single-section-title', 'listing/single/content' ); ?>
<?php endif; ?>
<div id="map-preview" class="map-preview" data-lat="<?php echo esc_attr( $post->geolocation_lat ) ?>"
     data-long="<?php echo esc_attr( $post->geolocation_long ); ?>"
     data-zoom="<?php echo lisner_get_option( 'listings-map-zoom', 18 ); ?>"></div>

<?php if ( '2' == lisner_get_var( $args['page_template'], 1 ) ) : ?>
	<?php include lisner_helper::get_template_part( 'single-address', 'listing/single/sidebar' ); ?>
<?php endif; ?>

