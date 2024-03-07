<?php
/**
 * Coupon Display
 *
 * @author includes
 * @version 1.0.0
 * @package templates
 *
 * @var $coupons
 * @var $listing_id
 * @var $type
 */
wp_enqueue_script( 'lisner-theme-countdown' );
?>

<section class="listing-widget listing-widget-coupons">
	<!-- Listing Coupons -->
	<div class="listing-single-coupons">
		<?php foreach ( $coupons as $coupon ) : ?>
			<?php if ( pebas_coupons()->has_coupon_started( $coupon->ID ) && ! pebas_coupons()->has_coupon_expired( $coupon->ID ) ) : ?>
				<?php include "coupon-item.php"; ?>
			<?php endif; ?>
		<?php endforeach; ?>
	</div>
</section>
