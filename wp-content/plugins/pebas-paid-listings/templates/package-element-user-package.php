<?php
/**
 * Paid Packages / package element user package
 *
 * @author pebas
 * @package templates/packages
 * @version 1.0.0
 *
 */
?>

<!-- Listing Package / New Package -->
<div class="listing-package-wrapper">
    <div class="listing-package">
        <!-- Listing Package / Title -->
        <div class="listing-package-title">
			<?php if ( $package->get_limit() ) : ?>
				<?php $limit_info = sprintf( _n( '%1$s listing posted out of %2$d', '%1$s listings posted out of %2$d', $package->get_count(), 'pebas-paid-listings' ), '<span>' . $package->get_count() . '</span>', $package->get_limit() ); ?>
			<?php else: ?>
				<?php $limit_info = sprintf( _n( '%s listing posted', '%s listings posted', $package->get_count(), 'pebas-paid-listings' ), '<span>' . $package->get_count() . '</span>' ); ?>
			<?php endif; ?>
            <p class="listing-package-title-user"><?php echo wp_kses_post( $limit_info ); ?></p>
            <h2><?php echo esc_html( $product->get_title() ); ?></h2>
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
           class="package-call btn btn-primary"><?php esc_html_e( 'Continue', 'pebas-paid-listings' ); ?></a>
        <input class="hidden" type="radio" <?php checked( $checked, 1 ); ?> name="job_package"
               value="user-<?php echo $key; ?>" id="user-package-<?php echo $package->get_id(); ?>"/>
		<?php $checked = 0; ?>
    </div>
</div>
