<?php
/**
 * Claim Listing / Package selection
 *
 * @author pebas
 * @package templates/listing
 * @version 1.0.0
 *
 * Params are defined in 'includes/pebas-paid-listings/pebas_pl_claim_form.php
 *
 * @var $packages
 * @var $user_packages
 */
?>
<?php if ( $packages ): ?>
    <script>
        // package selection container background color fix
        if (jQuery('#pebas_claim_submit').length > -1) {
            jQuery('#pebas_claim_submit').parent().addClass('lisner-packages');
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
				<?php include pebas_claim_listings_get_template_part( 'package-element', '' ); ?>
            </div>

		<?php endforeach; ?>
	<?php endif; // end of listing packages / new ?>

<?php else: ?>
    <p><?php esc_html_e( 'No packages found', 'pebas-paid-listings' ); ?></p>
<?php endif; ?>
