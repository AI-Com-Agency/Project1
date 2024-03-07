<?php
/**
 * Home / Hero images template
 *
 * @author pebas
 * @package home/hero-images
 * @version 1.0.0
 *
 * @param $args
 */
$bg_opacity = lisner_hero()->get_home_hero_images_overlay_style( $args['page_id'], 'overlay_opacity', 'opacity' );
?>
<?php if ( isset( $args['images'] ) ) : ?>
	<?php foreach ( $args['images'] as $image ) : ?>
		<figure class="hero-image"
		        style="background-image: url(<?php echo esc_url( $image['full_url'] ); ?>); <?php echo esc_attr( $bg_opacity ); ?>"></figure>
	<?php endforeach; ?>
<?php else: ?>
	<?php $color = get_post_meta( $args['page_id'], 'home_bg_color', true ); ?>
	<figure class="hero-image"
	        style="background-color: <?php echo esc_attr( $color ); ?>;"></figure>
<?php endif; ?>

