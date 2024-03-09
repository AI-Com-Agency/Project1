<?php
/**
 * Listing query part
 *
 * @author pebas
 * @version 1.0.0
 */

?>
<?php $template = get_post_meta( $args['page_id'], 'search_template', true ); ?>
<?php $listing_style = lisner_listing_box_style( $args['page_id'], 'search_box_template' ); ?>
<div class="<?php echo 2 != $template ? esc_attr( 'container-fluid' ) : esc_attr( 'container' ); ?>">
    <div class="row row-cst-margin">
		<?php while ( $listings->have_posts() ): ?>
			<?php $listings->the_post(); ?>
			<?php
			// listing data options
			$lat = get_post_meta( get_the_ID(), 'geolocation_lat', true );
			$lon = get_post_meta( get_the_ID(), 'geolocation_long', true );
			if ( empty( $lat ) || empty( $lon ) ) {
				$coords = get_post_meta( get_the_ID(), '_job_location_map', true );
				if ( ! empty( $coords ) ) {
					$coords = explode( ',', $coords );
					$lat    = $coords[0];
					$lon    = $coords[1];
				}
			}
			?>
			<?php $category_id = lisner_helper::get_single_category_id( get_the_ID() ); ?>
            <div class="listing-el listing-el-<?php echo esc_attr( $listing_style ); ?> no-padding col-custom <?php echo in_array( $listing_style, array(
				'list',
			) ) ? esc_attr( 'col-sm-12' ) : esc_attr( 'col-sm-6' ); ?>"
                 data-id="<?php echo esc_attr( get_the_ID() ); ?>"
                 data-icon="<?php echo esc_attr( get_term_meta( $category_id, 'term_icon', true ) ); ?>"
                 data-lat="<?php echo esc_attr( $lat ); ?>"
                 data-lng="<?php echo esc_attr( $lon ); ?>">
				<?php $args['thumbnail_size'] = 'listing_box_search'; ?>
				<?php if ( 'small' == $listing_style ) : ?>
					<?php include lisner_helper::get_template_part( 'listing-small', 'shortcodes/listing/partials', $args ); ?>
				<?php elseif ( 'promo' == $listing_style ) : ?>
					<?php include lisner_helper::get_template_part( 'listing-promo', 'shortcodes/listing/partials', $args ); ?>
				<?php else: ?>
					<?php include lisner_helper::get_template_part( 'listing', 'shortcodes/listing/partials', $args ); ?>
				<?php endif; ?>
            </div>
		<?php endwhile; ?>
    </div>
</div>
