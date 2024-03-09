<?php
/**
 * Paid Packages / package element user package alternate
 *
 * @author pebas
 * @package templates/packages
 * @version 1.0.0
 *
 */
?>

<!-- Listing User Package / New Package -->
<div class="listing-user-package">
    <a href="javascript:" class="package-call">
        <!-- Listing User Package / Title -->
        <div class="listing-user-package-title">
			<?php if ( $package->get_limit() ) : ?>
				<?php $limit_info = sprintf( _n( '%1$s out of %2$d', '%1$s out of %2$d', $package->get_count(), 'pebas-paid-listings' ), '<span>' . $package->get_count() . '</span>', $package->get_limit() ); ?>
			<?php else: ?>
				<?php $limit_info = sprintf( _n( '%s listing posted', '%s listings posted', $package->get_count(), 'pebas-paid-listings' ), '<span>' . $package->get_count() . '</span>' ); ?>
			<?php endif; ?>
            <div class="listing-user-package-title-inner">
                <h6><?php echo esc_html( $product->get_title() ); ?></h6>
                <p class="listing-package-title-user"><?php echo wp_kses_post( $limit_info ); ?></p>
            </div>
            <div class="listing-user-package-title-icon">
                <i class="material-icons mf"><?php echo esc_html( 'keyboard_arrow_right' ); ?></i>
            </div>
        </div>
    </a>
    <input class="hidden" type="radio" <?php checked( $checked, 1 ); ?> name="job_package"
           value="user-<?php echo $key; ?>" id="user-package-<?php echo $package->get_id(); ?>"/>
	<?php $checked = 0; ?>
</div>
