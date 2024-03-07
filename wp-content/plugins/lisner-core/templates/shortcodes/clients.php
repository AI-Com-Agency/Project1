<?php
/**
 * Shortcode Clients
 *
 * @author includes
 * @version 1.0.0
 * @package shortcodes/clients
 */
?>
<div class="lisner-clients">
    <div class="container">
        <div class="row align-items-center justify-content-center">
			<?php if ( $atts['clients_images'] ) : ?>
				<?php $images = explode( ',', $atts['clients_images'] ); ?>
				<?php foreach ( $images as $image => $image_id ) : ?>
					<?php $image_src = wp_get_attachment_image_src( $image_id ); ?>
                    <div class="col col-sm-2">
                        <div class="lisner-clients-item">
                            <img src="<?php echo esc_url( $image_src[0] ); ?>" alt="client-image">
                        </div>
                    </div>
				<?php endforeach; ?>
			<?php endif; ?>
        </div>
    </div>
</div>
