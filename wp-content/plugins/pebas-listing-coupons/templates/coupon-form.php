<?php
/**
 * Coupon Form Template
 *
 * @author includes
 * @version 1.0.0
 * @package templates
 *
 * @var $coupon
 */
?>
<?php $form_id = isset( $coupon->ID ) ? $coupon->ID : rand(); ?>
<?php $title = isset( $coupon->_coupon_title ) ? $coupon->_coupon_title : ''; ?>
<?php $description = isset( $coupon->_coupon_description ) ? $coupon->_coupon_description : ''; ?>
<?php $discount = isset( $coupon->_coupon_discount ) ? $coupon->_coupon_discount : ''; ?>
<?php $button = isset( $coupon->_coupon_button ) ? $coupon->_coupon_button : ''; ?>
<?php $start_time = isset( $coupon->_coupon_start ) ? $coupon->_coupon_start : ''; ?>
<?php $end_time = isset( $coupon->_coupon_end ) ? $coupon->_coupon_end : ''; ?>
<?php $type = isset( $coupon->_coupon_type ) ? $coupon->_coupon_type : ''; ?>
<?php $code = isset( $coupon->_coupon_code ) ? $coupon->_coupon_code : ''; ?>
<?php $link = isset( $coupon->_coupon_link ) ? $coupon->_coupon_link : ''; ?>
<?php $print = isset( $coupon->_coupon_print ) ? $coupon->_coupon_print : ''; ?>
<?php $listing = get_post( $listing_id ); ?>

<?php $is_new = isset( $_REQUEST['action'] ) && 'add_coupon' == $_REQUEST['action'] ? true : false; ?>
<?php if ( $is_new ) : ?>
	<div class="lisner-listing-table-header">
		<h5><?php printf( __( 'Listing coupon: %s', 'pebas-listing-coupons' ), '<strong>' . esc_html( $listing->post_title ) . '</strong>' ); ?></h5>
		<?php $coupons_permalink = wc_get_endpoint_url( 'all-listings' ) . '?action=coupons&job_id=' . $listing_id; ?>
		<a href="<?php echo esc_url( $coupons_permalink ); ?>" class="return"><i
					class="material-icons mf"><?php echo esc_html( 'replay' ); ?></i><?php esc_html_e( 'All coupons', 'pebas-listing-coupons' ); ?>
		</a>
	</div>
<?php endif; ?>
<div class="lisner-coupon-form <?php echo ! $is_new ? esc_attr( 'hidden' ) : ''; ?>">
	<form class="form-coupon" method="post">
		<!-- Coupon Form / Coupon Title -->
		<div class="input-group input-group-full">
			<label for="coupon_title-<?php echo esc_attr( $form_id ); ?>"><?php esc_html_e( 'Coupon Title', 'pebas-listing-coupons' ); ?></label>
			<input type="text" id="coupon_title-<?php echo esc_attr( $form_id ); ?>" class="form-control"
			       placeholder="<?php esc_html_e( '25% off discount with this awesome coupon', 'pebas-listing-coupons' ) ?>"
			       name="_coupon_title" value="<?php echo esc_attr( $title ); ?>">
			<span class="coupon-description"><?php esc_html_e( 'Enter coupon title', 'pebas-listing-coupons' ); ?></span>
		</div>
		<!-- Coupon Form / Coupon Description -->
		<div class="input-group input-group-full">
			<label for="coupon_description-<?php echo esc_attr( $form_id ); ?>"><?php esc_html_e( 'Coupon Description', 'pebas-listing-coupons' ) ?></label>
			<textarea id="coupon_description-<?php echo esc_attr( $form_id ); ?>" class="form-control pt-2 pb-2"
			          name="_coupon_description"
			          placeholder="<?php esc_html_e( 'Claim this deal and get 25% cash back rewards', 'pebas-listing-coupons' ); ?>"><?php echo esc_html( $description ); ?></textarea>
			<span class="coupon-description"><?php esc_html_e( 'Enter coupon description.', 'pebas-listing-coupons' ); ?></span>
		</div>
		<!-- Coupon Form / Coupon Discount -->
		<div class="input-group">
			<label for="coupon_discount-<?php echo esc_attr( $form_id ); ?>"><?php esc_html_e( 'Coupon Discount', 'pebas-listing-coupons' ); ?></label>
			<input type="number" id="coupon_discount-<?php echo esc_attr( $form_id ); ?>" class="form-control"
			       min="0"
			       max="100"
			       placeholder="<?php esc_html_e( '25', 'pebas-listing-coupons' ) ?>"
			       name="_coupon_discount" value="<?php echo esc_attr( $discount ); ?>">
			<span class="coupon-description"><?php esc_html_e( 'Enter coupon discount, only numbers are allowed.', 'pebas-listing-coupons' ); ?></span>
		</div>
		<!-- Coupon Form / Coupon Button Text -->
		<div class="input-group">
			<label for="coupon_button-<?php echo esc_attr( $form_id ); ?>"><?php esc_html_e( 'Coupon Button Text', 'pebas-listing-coupons' ); ?></label>
			<input type="text" id="coupon_button-<?php echo esc_attr( $form_id ); ?>" class="form-control"
			       placeholder="<?php esc_html_e( 'Click Here!', 'pebas-listing-coupons' ) ?>"
			       name="_coupon_button" value="<?php esc_attr_e( $button ); ?>">
			<span class="coupon-description"><?php esc_html_e( 'Enter coupon button text.', 'pebas-listing-coupons' ); ?></span>
		</div>
		<!-- Coupon Form / Coupon Start -->
		<div class="input-group">
			<label for="coupon_start-<?php echo esc_attr( $form_id ); ?>"><?php esc_html_e( 'Coupon Start Time', 'pebas-listing-coupons' ); ?></label>
			<input type="text" id="coupon_start-<?php echo esc_attr( $form_id ); ?>"
			       class="form-control coupon-timepicker"
			       name="_coupon_start" value="<?php echo esc_attr( $start_time ); ?>" autocomplete="off">
			<span class="coupon-description"><?php esc_html_e( 'Choose coupon start time or leave empty to start immediately.', 'pebas-listing-coupons' ); ?></span>
		</div>
		<!-- Coupon Form / Coupon End -->
		<div class="input-group">
			<label for="coupon_end-<?php echo esc_attr( $form_id ); ?>"><?php esc_html_e( 'Coupon End Time', 'pebas-listing-coupons' ); ?></label>
			<input type="text" id="coupon_end-<?php echo esc_attr( $form_id ); ?>"
			       class="form-control coupon-timepicker"
			       name="_coupon_end" value="<?php echo esc_attr( $end_time ); ?>" autocomplete="off">
			<span class="coupon-description"><?php esc_html_e( 'Choose coupon end time or leave empty make it unlimited.', 'pebas-listing-coupons' ); ?></span>
		</div>
		<!-- Coupon Form / Coupon Type -->
		<div class="input-group input-group-full">
			<label for="coupon_type-<?php echo esc_attr( $form_id ); ?>"><?php esc_html_e( 'Coupon Type', 'pebas-listing-coupons' ) ?></label>
			<select name="_coupon_type" id="coupon_type-<?php echo esc_attr( $form_id ); ?>" class="coupon-select">
				<option value="code" <?php echo 'code' == $type ? esc_attr( 'selected="selected"' ) : ''; ?>><?php esc_html_e( 'Code', 'pebas-listing-coupons' ); ?></option>
				<option value="link" <?php echo 'link' == $type ? esc_attr( 'selected="selected"' ) : ''; ?>><?php esc_html_e( 'Link', 'pebas-listing-coupons' ); ?></option>
				<option value="print" <?php echo 'print' == $type ? esc_attr( 'selected="selected"' ) : ''; ?>><?php esc_html_e( 'Print', 'pebas-listing-coupons' ); ?></option>
			</select>
			<span class="coupon-description"><?php esc_html_e( 'Choose coupon type.', 'pebas-listing-coupons' ); ?></span>
		</div>
		<div class="coupon-type-wrapper hidden" data-coupon-type="code">
			<!-- Coupon Form / Coupon Code -->
			<div class="input-group">
				<label for="coupon_code-<?php echo esc_attr( $form_id ); ?>"><?php esc_html_e( 'Coupon Code', 'pebas-listing-coupons' ); ?></label>
				<input type="text" id="coupon_code-<?php echo esc_attr( $form_id ); ?>" class="form-control"
				       placeholder="<?php esc_html_e( 'CODE25', 'pebas-listing-coupons' ) ?>"
				       name="_coupon_code" value="<?php echo esc_attr( $code ); ?>">
				<span class="coupon-description"><?php esc_html_e( 'Enter coupon code.', 'pebas-listing-coupons' ); ?></span>
			</div>
		</div>
		<div class="coupon-type-wrapper hidden" data-coupon-type="link">
			<!-- Coupon Form / Coupon Link -->
			<div class="input-group">
				<label for="coupon_link-<?php echo esc_attr( $form_id ); ?>"><?php esc_html_e( 'Coupon Link', 'pebas-listing-coupons' ); ?></label>
				<input type="url" id="coupon_link-<?php echo esc_attr( $form_id ); ?>" class="form-control"
				       placeholder="<?php esc_html_e( 'https://google.com', 'pebas-listing-coupons' ) ?>"
				       name="_coupon_link" value="<?php echo esc_attr( $link ); ?>">
				<span class="coupon-description"><?php esc_html_e( 'Enter coupon link.', 'pebas-listing-coupons' ); ?></span>
			</div>
		</div>
		<div class="coupon-type-wrapper hidden" data-coupon-type="print">

			<!-- Coupon Form / Coupon Print -->
			<div class="input-group">
				<div class="coupon-image-wrapper">
					<label for="coupon_print-<?php echo esc_attr( $form_id ); ?>"><?php esc_html_e( 'Coupon Print Image', 'pebas-listing-coupons' ); ?></label>
					<div class="coupon-print-image <?php echo empty( $print ) ? esc_attr( 'hidden' ) : ''; ?>">
						<?php if ( ! empty( $print ) ) : ?>
							<?php $image = wp_get_attachment_image_src( $print, 'full' ); ?>
							<?php if ( pbs_is_demo() ) : ?>
								<span class="remove-style material-icons mf"><?php echo esc_html( 'delete' ); ?></span>
							<?php else: ?>
								<span class="remove-image material-icons mf"><?php echo esc_html( 'delete' ); ?></span>
							<?php endif; ?>
							<img src="<?php echo esc_url( $image[0] ); ?>">
						<?php endif; ?>
					</div>
					<?php if ( pbs_is_demo() ) : ?>
						<a href="javascript:"
						   class="coupon-uploader"><?php esc_html_e( 'Upload Image', 'pebas-listing-coupons' ); ?></a>
					<?php else: ?>
						<a href="javascript:"
						   class="coupon-print-uploader"><?php esc_html_e( 'Upload Image', 'pebas-listing-coupons' ); ?></a>
					<?php endif; ?>
					<input type="hidden" id="coupon_print-<?php echo esc_attr( $form_id ); ?>"
					       class="form-control coupon-print-uploader"
					       name="_coupon_print" value="<?php echo esc_attr( $print ); ?>">
					<span class="coupon-description"><?php esc_html_e( 'Upload coupon image.', 'pebas-listing-coupons' ); ?></span>
				</div>
			</div>
		</div>

		<?php if ( pbs_is_demo() ) : ?>
			<button class="btn btn-primary confirm-button"><?php esc_html_e( 'Save Coupon', 'pebas-listing-coupons' ); ?></button>
		<?php else: ?>
			<div class="form-coupon-actions">
				<button type="submit"
				        class="btn btn-primary save-coupon"><?php esc_html_e( 'Save Coupon', 'pebas-listing-coupons' ); ?></button>
				<?php if ( isset( $_REQUEST['action'] ) && 'add_coupon' != $_REQUEST['action'] ) : ?>
					<a href="javascript:"
					   data-confirm="<?php esc_attr_e( 'Are you sure you wish to delete coupon?', 'pebas-listing-coupons' ); ?>"
					   class="btn btn-alt remove-coupon" title="<?php esc_attr_e( 'Remove Coupon' ); ?>"><i
								class="material-icons mf"><?php echo esc_attr( 'delete' ); ?></i></a>
				<?php endif; ?>
				<input type="hidden" name="action" value="save_coupon">
				<input type="hidden" name="listing_id" value="<?php echo esc_attr( $listing_id ); ?>">
				<input type="hidden" name="coupon_id"
				       value="<?php echo isset( $coupon->ID ) ? esc_attr( $coupon->ID ) : ''; ?>">
				<input type="hidden" name="permalink"
				       value="<?php echo isset( $coupons_permalink ) ? $coupons_permalink : ''; ?>">
				<?php echo wp_nonce_field( 'save_coupon_nonce', 'save_coupon_nonce' ); ?>
			</div>
		<?php endif; ?>

	</form>
</div>
