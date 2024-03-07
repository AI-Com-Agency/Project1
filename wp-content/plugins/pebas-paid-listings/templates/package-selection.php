<?php
/**
 * Add Listing / Package selection
 *
 * @author pebas
 * @package templates/listing
 * @version 1.0.0
 *
 * Params are defined in 'includes/class-pebas-paid-listings-submit-listing-form.php
 *
 * @var $packages
 * @var $user_packages
 */
?>
<?php if ( $packages || $user_packages ): ?>
    <script>
        // package selection container background color fix
        if (jQuery('#listing_packages_form').length > -1) {
            jQuery('#listing_packages_form').parent().addClass('lisner-packages');
            jQuery('.container').addClass('container-packages');
        }
    </script>

	<?php $checked = 1; ?>
	<?php if ( $packages ) : ?>
        <!-- Listing Packages / New Packages -->
		<?php foreach ( $packages as $key => $package ) :

			$product = wc_get_product( $package );
			if ( ! $product->is_type( array(
					'job_package',
					'job_package_subscription'
				) ) || ! $product->is_purchasable() ) {
				continue;
			}
			/* @var $product WC_Product_Job_Package|WC_Product_Job_Package_Subscription */
			if ( $product->is_type( 'variation' ) ) {
				$post = get_post( $product->get_parent_id() );
			} else {
				$post = get_post( $product->get_id() );
			}
			?>
			<?php $distinctive = rwmb_meta( 'package_distinctive', '', $post->ID ); ?>

            <div class="col-sm-4 p-0">
				<?php include pebas_paid_listings_get_template_part( 'package-element', '' ); ?>
            </div>

		<?php endforeach; ?>
	<?php endif; // end of listing packages / new ?>

    <!-- Listing Packages / User Packages -->
	<?php if ( $user_packages ) : ?>
		<?php if ( $packages ) : ?>
            <div class="listing-user-packages">
            <div class="listing-user-packages-title">
                <h6><?php esc_html_e( 'Your packages', 'pebas-paid-listings' ); ?></h6>
                <i class="material-icons mf listing-user-packages-call"><?php echo esc_html( 'keyboard_arrow_down' ); ?></i>
            </div>
		<?php endif; ?>
		<?php foreach ( $user_packages as $key => $package ) : ?>
			<?php $package = pebas_pl_get_package( $package ); ?>
			<?php $product = wc_get_product( $package->get_product_id() ); ?>
			<?php if ( $product->is_type( 'variation' ) ) {
				$post = get_post( $product->get_parent_id() );
			} else {
				$post = get_post( $product->get_id() );
			} ?>
			<?php if ( ! $packages ) : ?>
                <div class="col-sm-4 p-0">
					<?php include pebas_paid_listings_get_template_part( 'package-element-user-package', '' ); ?>
                </div>
			<?php else: ?>
				<?php include pebas_paid_listings_get_template_part( 'package-element-user-package-alt', '' ); ?>
			<?php endif; ?>
		<?php endforeach; ?>
		<?php if ( $packages ) : ?>
            </div>
		<?php endif; ?>
	<?php endif ?>
<?php else: ?>
    <p><?php esc_html_e( 'No packages found', 'pebas-paid-listings' ); ?></p>
<?php endif; ?>
