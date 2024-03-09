<?php
/**
 * Shortcode Listing / Listing Box
 *
 * @author includes
 * @version 1.0.0
 * @package shortcodes/listing/partials
 */
$option = get_option( 'pbs_option' );
$style  = isset( $atts['listing_style'] ) ? $atts['listing_style'] : ( isset( $listing_style ) ? $listing_style : 'grid' );

?>
<?php $listing_id = isset( $atts['listing_id'] ) ? $atts['listing_id'] : get_the_ID(); ?>
<?php $author = get_post_meta( $listing_id, '_job_author', true ); ?>
<?php $author = lisner_get_var( $author, 1 ); ?>
<?php $price_range = lisner_helper::pricing_range_render( $listing_id ); ?>
<?php $image_size = isset( $atts['listing_preview'] ) ? 'listing_preview_box' : ( isset( $args['thumbnail_size'] ) ? $args['thumbnail_size'] : 'listing_box' ); ?>
<?php $banner = rwmb_meta( '_listing_cover', array( 'size' => $image_size ), $listing_id ); ?>
<?php $gallery = rwmb_meta( '_listing_gallery', array( 'size' => $image_size ), $listing_id ); ?>
<?php $fallback_image = lisner_get_option( 'fallback-bg-listing', null ); ?>
<?php $fallback_image = wp_get_attachment_image_src( $fallback_image, $image_size ); ?>
<?php $image = isset( $banner ) && ! empty( $banner ) ? array_shift( $banner ) : ( isset( $gallery ) && ! empty( $gallery ) ? array_shift( $gallery ) : ( isset( $fallback_image ) && ! empty( $fallback_image ) ? array_shift( $fallback_image ) : '' ) ); ?>
<?php $logo = get_user_meta( $author, '_listing_logo', true ); ?>
<?php $logo_main = get_post_thumbnail_id(); ?>
<?php $logo_data = has_post_thumbnail() ? wp_get_attachment_image_src( $logo_main,
	'full' ) : wp_get_attachment_image_src( $logo, 'full' ); ?>
<?php
// listing stats
if ( lisner_statistics::is_stat_enabled( 'listing-statistics-focus-enable' ) ) :
	lisner_statistics::add_listing_focus( $author, $listing_id );
endif;
?>
<div class="lisner-listing-item listing-style-<?php echo esc_attr( $style ); ?>"
     data-listing-id="<?php echo esc_attr( $listing_id ); ?>" data-author-id="<?php echo esc_attr( $author ); ?>"
     data-nonce="<?php echo esc_attr( wp_create_nonce( 'ctr-nonce' ) ); ?>">
	<!-- Listing / Image -->
	<figure class="lisner-listing-figure"
	        data-figure="<?php echo esc_url( isset( $image['url'] ) ? $image['url'] : $image ); ?>"
	        data-logo="<?php echo esc_url( $logo_data[0] ); ?>">
		<a class="figure-call" href="<?php echo esc_url( get_the_permalink( $listing_id ) ); ?>">
			<img src="<?php echo esc_url( isset( $image['url'] ) ? $image['url'] : $image ); ?>"
			     alt="listing-box-image">
		</a>
		<?php if ( class_exists( 'Pebas_Listing_Coupons' ) ) : ?>
			<?php $discounts = pebas_coupons()->get_coupons_label_values( $listing_id ); ?>
			<?php if ( ! empty( $discounts[0] ) ) : ?>
				<!-- Listing / Coupons-->
				<div class="lisner-listing-coupons">
					<?php if ( 1 < count( $discounts ) ) : ?>
						<span class="lisner-listing-coupons-discount"><?php printf( esc_html__( '%s - %s%%',
								'lisner-core' ), $discounts[0], $discounts[1] ); ?></span>
					<?php else: ?>
						<span class="lisner-listing-coupons-discount"><?php printf( esc_html__( '%s%%', 'lisner-core' ),
								$discounts[0] ); ?></span>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		<?php endif; ?>
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
				<a class="listing-preview-call" rel="nofollow"
				   href="javascript:"><span><?php esc_html_e( 'Quick Preview', 'lisner-core' ); ?></span>
					<i><?php echo esc_html( '→' ); ?></i></a>
			</div>
		<?php endif; ?>

		<?php $featured = get_post_meta( $listing_id, '_featured', true ); ?>
		<?php if ( $featured ) : ?>
			<span class="lisner-listing-promoted lisner-listing-featured color-warning <?php echo $logo_data ? esc_attr( 'has-logo' ) : ''; ?>"><?php echo esc_html__( 'Featured',
					'lisner-core' ); ?></span>
		<?php endif; ?>
	</figure>

	<!-- Listing / Content -->
	<div class="lisner-listing-content">

		<?php if ( 0 != $option['listing-fields-logo'] && ( ! isset( $option['listing-fields-logo-members'] ) || lisner_show_to_member( $option['listing-fields-logo-members'] ) ) ): ?>
			<?php if ( isset( $option['listings-logo-display'] ) && ! empty( $option['listings-logo-display'] ) && ( isset( $logo ) && ! empty( $logo ) || isset( $logo_main ) && ! empty( $logo_main ) ) ) : ?>
				<?php $logo_data = has_post_thumbnail() ? wp_get_attachment_image_src( $logo_main,
					'full' ) : wp_get_attachment_image_src( $logo, 'full' ); ?>
				<!-- Listing / Logo -->
				<div class="listing-brand listing-brand-<?php echo esc_attr( $style ); ?>">
					<div class="listing-brand__logo">
						<img class="img-fluid" src="<?php echo esc_url( $logo_data[0] ); ?>"
						     alt="<?php esc_attr_e( 'Logo', 'lisner-core' ); ?>">
					</div>
				</div>
			<?php endif; ?>
		<?php endif; ?>

		<!-- Listing / Top Meta -->
		<div class="lisner-listing-meta">
			<?php if ( 0 != $option['listing-fields-address'] && ( ! isset( $option['listing-fields-address-members'] ) || lisner_show_to_member( $option['listing-fields-address-members'] ) ) ): ?>
				<?php $city = get_post_meta( $listing_id, 'geolocation_city', true ); ?>
				<?php $city = isset( $city ) && ! empty( $city ) ? $city : get_post_meta( $listing_id,
					'geolocation_city_name_short', true ); ?>
				<?php $country = get_post_meta( $listing_id, 'geolocation_country_short', true ); ?>
				<?php if ( empty( $city ) && empty( $country ) ) : ?>
					<?php $location = get_post_meta( $listing_id, '_job_location', true ); ?>
					<?php $location = explode( ',', $location ); ?>
					<?php if ( isset( $location[3] ) ) : ?>
					<?php endif; ?>
					<?php if ( isset( $location[1] ) ) : ?>
						<?php unset( $location[0] ); ?>
						<?php $location[2] = isset( $location[2] ) ? preg_replace( '/[0-9]+/', '',
							$location[2] ) : ''; ?>
						<?php $address = preg_replace( '/[0-9]+/', '', $location[1] ) . ',' . $location[2]; ?>
					<?php endif; ?>
				<?php else: ?>
					<?php $address = $city . ', ' . $country; ?>
					<?php $address = isset( $address ) && ! empty( $address ) ? $address : get_post_meta( $listing_id,
						'_job_location', true ); ?>
				<?php endif; ?>
			<?php endif; ?>
			<?php if ( isset( $address ) && ! empty( $address ) ) : ?>
				<?php if ( 2 < mb_strlen( $address ) ) : ?>
					<?php if ( 0 != $option['listing-fields-address'] && ( ! isset( $option['listing-fields-address-members'] ) || lisner_show_to_member( $option['listing-fields-address-members'] ) ) ): ?>
						<address>
							<i class="material-icons mf"><?php echo esc_attr( 'room' ); ?></i><?php echo esc_html( $address ); ?>
						</address>
					<?php endif; ?>
				<?php endif; ?>
				<?php if ( 'grid' == $style ) : ?>
					<?php if ( 0 != $option['listing-fields-pricing'] && ( ! isset( $option['listing-fields-pricing-members'] ) || lisner_show_to_member( $option['listing-fields-pricing-members'] ) ) ): ?>
						<?php echo wp_kses( $price_range, array(
							'span' => array( 'class' => array() ),
							'i'    => array( 'class' => array() )
						) ); ?>
					<?php endif; ?>
				<?php endif; ?>
			<?php endif; ?>
		</div>

		<!-- Listing / Title -->
		<div class="lisner-listing-title-block">
			<?php //todo add real check for promoted listings ?>
			<?php $promoted = get_post_meta( $listing_id, '_promoted', true ); ?>
			<?php if ( $promoted ) : ?>
				<span class="lisner-listing-promoted color-warning"><?php echo esc_html__( 'Ad',
						'lisner-core' ); ?></span>
			<?php endif; ?>

			<?php $claimed = get_post_meta( $listing_id, '_claimed', true ); ?>

			<h4 class="lisner-listing-title"><a
						href="<?php echo get_the_permalink( $listing_id ); ?>"><?php echo get_the_title( $listing_id ); ?>
					<?php if ( $claimed ) : ?>
						<span class="lisner-listing-claimed material-icons color-success" data-toggle="tooltip"
						      data-title="<?php echo esc_attr__( 'Claimed',
							      'lisner-core' ) ?>"><?php echo esc_html( 'check_circle' ) ?></span>
					<?php endif; ?>
				</a>
			</h4>

		</div>

		<?php $content_limit_type = isset( $option['listings-content-length-by'] ) ? $option['listings-content-length-by'] : 'characters'; ?>
		<?php $preview_length = isset( $option['listings-preview-content-length'] ) ? $option['listings-preview-content-length'] : 340; ?>
		<?php $length = isset( $option['listings-content-length'] ) ? $option['listings-content-length'] : 170; ?>
		<?php $length = isset( $atts['listing_preview'] ) && 00 != $preview_length ? lisner_get_var( $preview_length,
			340 ) : $length; ?>
		<?php $content = get_post_field( 'post_content', $listing_id ); ?>
		<?php if ( ! empty( $content ) ) : ?>
			<?php $content = wp_strip_all_tags( $content ); ?>
			<?php if ( 00 != $length ) : ?>
				<?php if ( 'characters' == $content_limit_type ) : ?>
					<!-- Listing / Description -->
					<div class="lisner-listing-description">
						<?php if ( mb_strlen( $content ) >= $length ) : ?>
							<?php echo mb_substr( $content, 0, $length ) . esc_html( '...' ); ?>
						<?php else: ?>
							<?php echo mb_substr( $content, 0, $length ); ?>
						<?php endif; ?>
					</div>
				<?php else: ?>
					<!-- Listing / Description -->
					<div class="lisner-listing-description">
						<?php echo wp_trim_words( $content, $length, esc_html( '...' ) ); ?>
					</div>
				<?php endif; ?>
			<?php endif; ?>
		<?php endif; ?>

		<!-- Listing / Bottom Meta -->
		<div class="lisner-listing-meta">

			<?php $has_review = false; ?>
			<?php if ( lisner_helper::is_plugin_active( 'pebas-review-listings' ) ): ?>
				<?php $has_review = true; ?>
				<!-- Listing / Rating -->
				<?php $avg_rating = pebas_review_listings_functions::get_average_rating( $listing_id ); ?>
				<div class="lisner-listing-meta-rating color-<?php echo '0.0' == $avg_rating ? esc_attr( 'disabled' ) : esc_attr( 'success' ); ?>">
					<?php echo esc_html( $avg_rating ); ?>
				</div>
			<?php endif; ?>

			<?php if ( 0 != $option['listing-fields-categories'] && lisner_show_to_member( $option['listing-fields-categories-members'] ) ): ?>
				<?php $category_id = lisner_helper::get_single_category_id( $listing_id ); ?>
				<?php if ( $category_id ) : ?>
					<!-- Listing / Category -->
					<div class="lisner-listing-meta-category <?php echo $has_review ? esc_attr( 'has-review' ) : ''; ?>">
						<?php $tax_link = lisner_taxonomy_link( $category_id, 'job_listing_category' ); ?>
						<?php $category = get_term_by( 'id', $category_id, 'job_listing_category' ); ?>
						<?php $icon = get_term_meta( $category_id, 'term_icon', true ); ?>
						<a href="<?php echo esc_url( $tax_link ); ?>">
							<?php if ( $icon ) : ?>
								<i class="lisner-listing-meta-icon material-icons mf"><?php echo esc_html( $icon ); ?></i>
							<?php endif; ?>
							<span class="category-name"><?php echo esc_html( $category->name ); ?></span></a>
					</div>
				<?php endif; ?>
			<?php endif; ?>

			<?php if ( 0 != $option['listing-fields-working-hours'] && lisner_show_to_member( $option['listing-fields-working-hours-members'] ) ): ?>
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
		</div>
	</div>
</div>

