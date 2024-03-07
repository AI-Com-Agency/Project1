<?php
/**
 * Template Name: Single Post Loop / Gallery
 * Description: Partial content for single post section
 *
 * @author pebas
 * @version 1.0.0
 * @package views/posts/media
 *
 * @var $title
 */
?>
<?php $images = get_post_gallery_images( get_the_ID() ); ?>
<?php $image_count = 1; ?>
<?php if ( $images ) : ?>
	<div id="carousel-gallery" class="carousel slide" data-ride="carousel">
		<div class="carousel-inner" role="listbox">
			<?php foreach ( $images as $image ) : ?>
					<div class="carousel-item <?php echo 1 == $image_count ++ ? esc_attr( 'active' ) : ''; ?>">
						<img class="" src="<?php echo esc_url( $image ); ?>">
					</div>
			<?php endforeach; ?>
		</div>
		<?php if ( 1 < count( $images ) ) : ?>
			<a class="carousel-control-prev" href="#carousel-gallery" role="button" data-slide="prev">
				<span class="carousel-control-prev-icon" aria-hidden="true"></span>
				<span class="sr-only"><?php esc_html_e( 'Previous', 'lisner' ); ?></span>
			</a>
			<a class="carousel-control-next" href="#carousel-gallery" role="button" data-slide="next">
				<span class="carousel-control-next-icon" aria-hidden="true"></span>
				<span class="sr-only"><?php esc_html_e( 'Next', 'lisner' ); ?></span>
			</a>
		<?php endif; ?>
	</div>
<?php endif; ?>
