<?php
/**
 * All Coupons template
 *
 * @author includes
 * @version 1.0.0
 * @package templates
 *
 * @var $coupons
 */
?>
<div class="lisner-coupons">
	<div class="lisner-listing-table-header">
		<p><?php esc_html_e( 'Coupons for this listing are displayed below', 'pebas-listing-coupons' ); ?></p>
		<?php $add_coupon_permalink = wc_get_endpoint_url( 'all-listings' ) . '?action=add_coupon&job_id=' . $listing_id; ?>
		<a href="<?php echo esc_url( $add_coupon_permalink ); ?>"><?php esc_html_e( 'Add Coupon', 'pebas-listing-coupons' ); ?>
			<i
					class="material-icons mf"><?php echo esc_html( 'add_circle_outline' ); ?></i></a></div>
	<?php foreach ( $coupons as $coupon ) : ?>
		<?php if ( pebas_coupons()->has_coupon_expired( $coupon->ID ) && 'expired' != $coupon->_coupon_status ) : ?>
			<?php update_post_meta( $coupon->ID, '_coupon_status', 'expired' ); ?>
		<?php endif; ?>
		<div class="lisner-coupon <?php echo esc_attr( $coupon->_coupon_status ); ?>"
		     data-coupon-id="<?php echo esc_attr( $coupon->ID ); ?>">
			<?php $icon = 'print' == $coupon->_coupon_type ? esc_attr( 'print' ) : ( 'link' == $coupon->_coupon_type ? esc_attr( 'shopping-bag' ) : esc_attr( 'cut' ) ); ?>
			<span class="lisner-coupon-icon fas fa-<?php echo esc_html( $icon ); ?> fa-fw"></span>
			<!-- Coupon / Title -->
			<div class="lisner-coupon-title">
				<a href="<?php echo esc_url( get_permalink( $listing_id ) ); ?>" target="_blank"
				   class="title"><?php echo esc_html( $coupon->post_title ); ?></a>
				<?php $description = 35 <= strlen( $coupon->_coupon_description ) ? substr( $coupon->_coupon_description, 0, 35 ) . esc_html( '...' ) : $coupon->_coupon_description; ?>
				<span class="description lighter"><?php echo esc_html( $description ); ?></span>
			</div>
			<!-- Coupon / Type -->
			<div class="lisner-coupon-type">
				<span class="type"><?php printf( esc_html__( 'Type: %s', 'pebas-listing-coupons' ), ucfirst( esc_html( $coupon->_coupon_type ) ) ); ?> </span>
				<?php if ( 'code' == $coupon->_coupon_type ) : ?>
					<span class="code lighter"><?php echo esc_html( $coupon->_coupon_code ); ?></span>
				<?php endif; ?>
			</div>
			<!-- Coupon / Status -->
			<div class="lisner-coupon-status">
				<span class="status"><?php echo ucfirst( esc_html( $coupon->_coupon_status ) ); ?></span>
				<?php if ( isset( $coupon->_coupon_end ) && ! empty( $coupon->_coupon_end ) ) : ?>
					<span class="code lighter"><?php echo esc_html( $coupon->_coupon_end ); ?></span>
				<?php else: ?>
					<span class="code lighter"><?php esc_html_e( 'Unlimited', 'pebas-listing-coupons' ); ?></span>
				<?php endif; ?>
			</div>
			<!-- Coupon / Actions -->
			<div class="lisner-coupon-actions">
				<ul class="coupon-actions list-unstyled m-0">
					<li class="coupon-action coupon-action__view"><a href="javascript:"
					                                                 class="coupon-action-view"><i
									class="material-icons mf"><?php echo esc_html( 'keyboard_arrow_down' ); ?></i></a>
					</li>
				</ul>
			</div>

			<!-- Coupon / Coupon Form -->
			<?php include 'coupon-form.php'; ?>

		</div>
	<?php endforeach; ?>
</div>