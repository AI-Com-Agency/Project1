<?php
/**
 * Coupon Item
 *
 * @author includes
 * @version 1.0.0
 * @package templates
 *
 * @var $coupon
 * @var $listing_id
 * @var $type
 */
?>
<!-- Listing Coupon -->
<div class="listing-single-coupon">
	<?php if ( isset( $coupon->_coupon_discount ) && ! empty( $coupon->_coupon_discount ) ) : ?>
		<?php $discount_html = sprintf( __( '<strong>%d%%</strong> Off', 'pebas-listing-coupons' ), $coupon->_coupon_discount ); ?>
		<!-- Listing Coupon / Discount -->
		<span class="coupon-discount"><?php echo wp_kses_post( $discount_html ); ?></span>
	<?php endif; ?>
	<?php if ( isset( $coupon->_coupon_title ) && ! empty( $coupon->_coupon_title ) ) : ?>
		<!-- Listing Coupon / Title -->
		<div class="coupon-title"><h4><?php echo esc_html( $coupon->_coupon_title ); ?></h4></div>
	<?php endif; ?>
	<!-- Listing Coupon / Button -->
	<?php $button_text = isset( $coupon->_coupon_button ) && ! empty( $coupon->_coupon_button ) ? $coupon->_coupon_button : esc_html__( 'Show Coupon', 'pebas-lsiting-coupons' ); ?>
	<?php if ( 'code' == $coupon->_coupon_type ) : ?>
		<div class="coupon-button">
			<a data-toggle="modal" data-target="#modal-coupon-<?php echo esc_attr( $coupon->ID ); ?>"
			   href="javascript:"><i class="fas fa-cut"></i><?php echo esc_html( $button_text ); ?></a>
		</div>
	<?php elseif ( 'link' == $coupon->_coupon_type ) : ?>
		<div class="coupon-button">
			<a href="<?php echo esc_html( $coupon->_coupon_link ); ?>" target="_blank"><i
						class="fas fa-link"></i><?php echo esc_html( $button_text ); ?></a>
		</div>
	<?php else: ?>
		<div class="coupon-button">
			<a href="javascript:" class="print-coupon"
			   data-print="print-coupon-<?php echo esc_attr( $coupon->ID ); ?>"><i
						class="fas fa-print"></i><?php echo esc_html( $button_text ); ?></a>
		</div>
		<?php $image = $coupon->_coupon_print; ?>
		<?php $image_src = wp_get_attachment_image_src( $image, 'full' ); ?>
		<div id="print-coupon-<?php echo esc_attr( $coupon->ID ); ?>" class="coupon-to-print hidden">
			<img
					src="<?php echo esc_url( $image_src[0] ); ?>"></div>
	<?php endif; ?>

	<?php $end_date = isset( $coupon->_coupon_end ) && ! empty( $coupon->_coupon_end ) ? $coupon->_coupon_end : ''; ?>
	<!-- Listing Coupon / Timer -->
	<?php if ( ! empty( $end_date ) ) : ?>
		<div id="coupon-countdown-<?php echo esc_attr( rand() ); ?>" class="coupon-countdown"
		     data-date="<?php echo esc_attr( str_replace( '-', '/', $end_date ) ); ?>"></div>
	<?php endif; ?>
</div>
