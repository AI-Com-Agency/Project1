<?php
/**
 * Coupon Modal Display
 *
 * @author includes
 * @version 1.0.0
 * @package templates
 *
 * @var $coupons
 * @var $listing_id
 */
?>

<?php foreach ( $coupons as $coupon ) : ?>
	<?php if ( 'expired' != $coupon->_coupon_status ) : ?>
		<div class="modal" id="modal-coupon-<?php echo esc_attr( $coupon->ID ); ?>" tabindex="-1" role="dialog"
		     aria-labelledby="modal-coupon" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<?php $logo = get_post_meta( $listing_id, '_listing_logo', true ); ?>
					<?php if ( isset( $logo ) && ! empty( $logo ) ) : ?>
						<div class="modal-header">
						</div>
					<?php endif; ?>
					<div class="modal-body">
						<?php if ( isset( $coupon->_coupon_title ) && ! empty( $coupon->_coupon_title ) ) : ?>
							<!-- Listing Coupon / Title -->
							<div class="coupon-title"><h4><?php echo esc_html( $coupon->_coupon_title ); ?></h4></div>
						<?php endif; ?>
						<div class="coupon-main">
							<div data-clipboard-target="#code-copy-<?php echo esc_attr( $coupon->ID ); ?>"
							     class="coupon-button copy"><i
										class="fa fa-cut"></i><span
										id="code-copy-<?php echo esc_attr( $coupon->ID ); ?>"
										class="code-to-copy"><?php echo esc_html( $coupon->_coupon_code ); ?></span>
							</div>
							<span class="coupon-description"
							      data-code-copied="<?php esc_attr_e( 'Code copied!', 'pebas-listing-coupons' ); ?>"><?php esc_html_e( 'Click the code to copy.', 'pebas-listing-coupons' ); ?></span>
						</div>
						<?php if ( isset( $coupon->_coupon_description ) && ! empty( $coupon->_coupon_description ) ) : ?>
							<div class="coupon-content"><?php echo esc_html( $coupon->_coupon_description ); ?></div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>
<?php endforeach; ?>