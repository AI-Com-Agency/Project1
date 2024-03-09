<?php
/**
 * Claim Listings / package element
 *
 * @author pebas
 * @package templates/packages
 * @version 1.0.0
 *
 */
?>

<!-- Listing Package / New Package -->
<div class="listing-package-wrapper <?php echo $distinctive ? esc_attr( 'listing-package-wrapper-distinctive' ) : ''; ?>">
    <div class="listing-package">
        <!-- Listing Package / Title -->
        <div class="listing-package-title">
            <p><?php echo esc_html( $product->get_title() ); ?></p>
			<?php $price = $product->get_price_html(); ?>
			<?php $price = '' == $price ? __( 'Free', 'pebas-paid-listings' ) : $price; ?>
            <h2 class="<?php echo $distinctive ? esc_attr( 'distinctive' ) : ''; ?>"><?php echo wp_kses_post( $price ); ?></h2>
        </div>

		<?php $description = get_post_meta( $post->ID, 'package_description', true ); ?>
		<?php $description = str_replace( array( '[b]', '[/b]', '[duration]', '[limit]' ), array(
			'<strong>',
			'</strong>',
			$product->get_duration(),
			$product->get_limit() ? $product->get_limit() : esc_html__( 'unlimited', 'pebas-paid-listings' )
		), $description ); ?>
		<?php if ( isset( $description ) && ! empty( $description ) ): ?>
            <!-- Listing Package / Description -->
            <div class="listing-package-description"><?php echo wp_kses_post( $description ); ?></div>
		<?php endif; ?>
		<?php $features = get_post_meta( $post->ID, 'package_features', true ); ?>
		<?php if ( isset( $features ) && ! empty( $features ) ) : ?>
            <!-- Listing Package / Content -->
            <div class="listing-package-content">
                <ul class="list-unstyled">
					<?php foreach ( $features as $feature ) : ?>
						<?php $feature = str_replace( array( '[', ']' ), array(
							'<strong>',
							'</strong>'
						), $feature ); ?>
                        <li>
                            <i class="material-icons color-secondary mf"><?php echo esc_html( 'check_circle_outline' ); ?></i>
							<?php echo wp_kses_post( $feature ); ?>
                        </li>
					<?php endforeach ?>
                </ul>
            </div>
		<?php endif; ?>
        <a href="javascript:"
           class="package-call btn btn-primary <?php echo $distinctive ? '' : esc_attr( 'btn-primary-bordered' ); ?>"><?php esc_html_e( 'Claim now', 'pebas-paid-listings' ); ?></a>
        <input class="hidden" type="radio" <?php checked( $checked, 1 );
		$checked = 0; ?> name="job_package" value="<?php echo $product->get_id(); ?>"
               id="package-<?php echo $product->get_id(); ?>"/>
    </div>
</div>
