<?php
/**
 * Shortcode Listing / Listing Box Small
 *
 * @author includes
 * @version 1.0.0
 * @package shortcodes/listing/partials
 */
$option = get_option( 'pbs_option' );
$style  = isset( $atts['listing_style'] ) ? $atts['listing_style'] : ( isset( $listing_style ) ? $listing_style : 'grid' );
?>
<?php $listing_id = isset( $atts['listing_id'] ) ? $atts['listing_id'] : get_the_ID(); ?>
<?php $price_range = lisner_helper::pricing_range_render( $listing_id ); ?>
<?php $image_size = isset( $atts['listing_preview'] ) ? 'listing_preview_box' : ( isset( $args['thumbnail_size'] ) ? $args['thumbnail_size'] : 'listing_box' ); ?>
<?php $banner = rwmb_meta( '_listing_cover', array( 'size' => $image_size ), $listing_id ); ?>
<?php $gallery = rwmb_meta( '_listing_gallery', array( 'size' => $image_size ), $listing_id ); ?>
<?php $fallback_image = lisner_get_option( 'fallback-bg-listing', null ); ?>
<?php $fallback_image = wp_get_attachment_image_src( $fallback_image, $image_size ); ?>
<?php $image = isset( $banner ) && ! empty( $banner ) ? array_shift( $banner ) : ( isset( $gallery ) && ! empty( $gallery ) ? array_shift( $gallery ) : ( isset( $fallback_image ) && ! empty( $fallback_image ) ? array_shift( $fallback_image ) : '' ) ); ?>
<div class="lisner-listing-item listing-style-<?php echo esc_attr( $style ); ?>">
	<!-- Listing / Image -->
	<figure class="lisner-listing-figure"
	        data-figure="<?php echo esc_url( isset( $image['url'] ) ? $image['url'] : $image ); ?>">
		<a href="<?php echo esc_url( get_the_permalink( $listing_id ) ); ?>">
			<img src="<?php echo esc_url( isset( $image['url'] ) ? $image['url'] : $image ); ?>"
			     alt="listing-box-image">
		</a>
		<!-- Listing / Likes -->
		<div class="lisner-listing-likes">
			<?php $likes_count = get_post_meta( $listing_id, 'listing_likes', true ); ?>
			<?php $likes_count = lisner_get_var( $likes_count, 0 ); ?>
			<?php $has_liked = lisner_helper::has_user_liked_listing( $listing_id ); ?>
			<a data-listing-id="<?php echo esc_attr( $listing_id ); ?>" rel="nofollow" href="javascript:"
			   class="listing-likes-call <?php echo $has_liked ? esc_attr( 'activated' ) : ''; ?>"><i
						class="material-icons"><?php echo esc_html( 'favorite_border' ); ?></i></a>
			<span class="listing-likes-count"><?php echo esc_html( $likes_count ); ?></span>
		</div>
		<?php if ( ! isset( $atts['listing_preview'] ) ) : ?>
			<!-- Listing / Quick Preview -->
			<div class="lisner-listing-preview-call" data-listing-id="<?php echo esc_attr( $listing_id ); ?>">
				<?php if ( isset( $atts['with_preview'] ) ) : ?>
					<a class="listing-preview-call" rel="nofollow"
					   href="javascript:"><?php esc_html_e( 'Quick Preview', 'lisner-core' ); ?>
						<i><?php echo esc_html( '→' ); ?></i></a>
				<?php else: ?>
					<a class="listing-preview-call" rel="nofollow"><i><?php echo esc_html( '→' ); ?></i></a>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</figure>

	<!-- Listing / Content -->
	<div class="lisner-listing-content">

		<!-- Listing / Top Meta -->
		<div class="lisner-listing-meta">
			<?php $city = get_post_meta( $listing_id, 'geolocation_city', true ); ?>
			<?php $city = isset( $city ) && ! empty( $city ) ? $city : get_post_meta( $listing_id, 'geolocation_city_name_short', true ); ?>
			<?php $country = get_post_meta( $listing_id, 'geolocation_country_short', true ); ?>
			<?php if ( empty( $city ) && empty( $country ) ) : ?>
				<?php $location = get_post_meta( $listing_id, '_job_location', true ); ?>
				<?php $location = explode( ',', $location ); ?>
				<?php if ( isset( $location[3] ) ) : ?>
				<?php endif; ?>
				<?php if ( isset( $location[1] ) ) : ?>
					<?php unset( $location[0] ); ?>
					<?php $location[2] = isset( $location[2] ) ? preg_replace( '/[0-9]+/', '', $location[2] ) : ''; ?>
					<?php $address = preg_replace( '/[0-9]+/', '', $location[1] ) . ',' . $location[2]; ?>
				<?php endif; ?>
			<?php else: ?>
				<?php $address = $city . ', ' . $country; ?>
				<?php $address = isset( $address ) && ! empty( $address ) ? $address : get_post_meta( $listing_id, '_job_location', true ); ?>
			<?php endif; ?>
			<?php if ( isset( $address ) && ! empty( $address ) ) : ?>
				<?php if ( 2 < mb_strlen( $address ) ) : ?>
					<address>
						<i class="material-icons mf"><?php echo esc_attr( 'room' ); ?></i><?php echo esc_html( $address ); ?>
					</address>
				<?php endif; ?>
				<?php if ( 'grid' == $style ) : ?>
					<?php echo wp_kses( $price_range, array(
						'span' => array( 'class' => array() ),
						'i'    => array( 'class' => array() )
					) ); ?>
				<?php endif; ?>
			<?php endif; ?>

			<?php if ( isset( $atts['with_working_time'] ) ) : ?>
				<?php $open = lisner_helper()->is_open_now_render( $listing_id ) ?>
				<!-- Listing / Open -->
				<div class="lisner-listing-meta-open <?php echo $open ? esc_attr( 'is-open' ) : esc_attr( 'is-closed' ); ?>">
					<i class="material-icons mf"><?php echo esc_html( 'query_builder' ); ?></i>
					<?php $open ? esc_html_e( 'Open', 'lisner-core' ) : esc_html_e( 'Closed', 'lisner-core' ); ?>
				</div>
				<?php if ( 'list' == $style ) : ?>
					<?php echo wp_kses( $price_range, array(
						'span' => array( 'class' => array() ),
						'i'    => array( 'class' => array() )
					) ); ?>
				<?php endif; ?>
			<?php endif; ?>

			<?php $has_review = false; ?>
			<?php if ( lisner_helper::is_plugin_active( 'pebas-review-listings' ) && isset( $atts['with_rating'] ) ): ?>
				<?php $has_review = true; ?>
				<!-- Listing / Rating -->
				<?php $avg_rating = pebas_review_listings_functions::get_average_rating( $listing_id ); ?>
				<div class="lisner-listing-meta-rating color-<?php echo '0.0' == $avg_rating ? esc_attr( 'disabled' ) : esc_attr( 'success' ); ?>">
					<?php echo esc_html( $avg_rating ); ?>
				</div>
			<?php endif; ?>

		</div>

		<!-- Listing / Title -->
		<div class="lisner-listing-title-block">
			<?php //todo add real check for promoted listings ?>
			<?php $promoted = get_post_meta( $listing_id, '_promoted', true ); ?>
			<?php if ( $promoted ) : ?>
				<span class="lisner-listing-promoted color-warning"><?php echo esc_html__( 'Ad', 'lisner-core' ); ?></span>
			<?php endif; ?>

			<?php $claimed = get_post_meta( $listing_id, '_claimed', true ); ?>
			<?php $claimed_render = ''; ?>
			<?php if ( $claimed ) : ?>
				<?php $claimed_render = '<span class="lisner-listing-claimed material-icons color-success">' . esc_html( 'check_circle' ) . '</span>'; ?>
			<?php endif; ?>

			<h4 class="lisner-listing-title"><a
						href="<?php echo get_the_permalink( $listing_id ); ?>"><?php echo get_the_title( $listing_id ); ?><?php echo wp_kses_post( $claimed_render ); ?></a>
			</h4>

		</div>

	</div>
</div>

