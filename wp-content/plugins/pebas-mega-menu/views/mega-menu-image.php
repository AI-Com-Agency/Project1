<?php
/**
 * Mega Menu Image Template
 *
 * @author pebas
 * @version 1.0.0
 * @package views/
 *
 * @var $atts
 */
?>

<?php if ( isset( $image ) && ! empty( $image ) ) : ?>
	<?php if ( 1 < count( $image ) ) : ?>
		<div class="mega-menu-gallery">
			<?php foreach ( $image as $img ) : ?>
				<?php $img = wp_get_attachment_image_src( $img, 'mega-menu-thumbnail' ); ?>
				<a href="<?php echo esc_url( $atts['link'] ); ?>">
					<figure class="mega-menu-item-figure">
						<img src="<?php echo esc_url( $img[0] ); ?>" alt="">
					</figure>
				</a>
			<?php endforeach; ?>
		</div>
	<?php else : ?>
		<?php $image = wp_get_attachment_image_src( array_shift( $image ), 'mega-menu-thumbnail' ); ?>
		<a href="<?php echo esc_url( $atts['link'] ); ?>">
			<figure class="mega-menu-item-figure">
				<span class="mega-menu-item-preloader"></span>
				<img class="mega-menu-image" src="<?php echo esc_url( $image[0] ); ?>" alt="">
			</figure>
		</a>
	<?php endif; ?>
<?php endif; ?>
