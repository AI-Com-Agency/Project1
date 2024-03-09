<?php
/**
 * Template Name: Header / Single Images
 * Description: Partial content for single listing header
 *
 * @author pebas
 * @version 1.0.0
 * @package listing/single/header
 */

?>
<?php $image = rwmb_meta( '_listing_cover', array( 'size' => 'full' ) ); ?>
<?php $gallery = rwmb_meta( '_listing_gallery', array( 'size' => 'full' ) ); ?>
<?php $image = isset( $image ) && ! empty( $image ) ? array_shift( $image ) : array_shift( $gallery ); ?>

<a href="javascript:" class="listing-gallery-call listing-gallery-call-single-image">
    <figure class="single-listing-header-image m-0">
		<?php if ( $image ) : ?>
            <img src="<?php echo esc_url( $image['url'] ) ?>" alt="">
		<?php endif; ?>
    </figure>
	<?php if ( $gallery ) : ?>
        <div class="listing-gallery hidden">
			<?php foreach ( $gallery as $image ) : ?>
                <span class="listing-data-images" data-image="<?php echo esc_url( $image['url'] ); ?>"></span>
			<?php endforeach; ?>
        </div>
	<?php endif; ?>
</a>


