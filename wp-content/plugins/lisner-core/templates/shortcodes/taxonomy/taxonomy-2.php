<?php
/**
 * Shortcode Taxonomy / Layout 2
 *
 * @author includes
 * @version 1.0.0
 * @package shortcodes/taxonomy/
 */
?>

<div class="lisner-taxonomy theme-spacing lisner-taxonomy-template-<?php echo esc_attr( $atts['lisner_taxonomy_template'] ); ?>">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-6 text-center">
				<!-- Section Taxonomy / Title -->
				<?php include lisner_helper::get_template_part( 'title', 'shortcodes/partials', $atts ); ?>
			</div>
		</div>
	</div>

	<!-- Section Taxonomy / Taxonomies -->
	<?php $full_count = count( $atts['taxonomy_terms'] ); ?>
	<div class="lisner-taxonomy-wrapper lisner-terms-<?php echo esc_attr( $full_count ); ?> lisner-taxonomy-slick">
		<?php $count = 1; ?>
		<?php if ( ! empty( $atts['taxonomy_terms'] ) ) : ?>
			<?php foreach ( $atts['taxonomy_terms'] as $id => $value ): ?>
				<?php $term = get_term_by( 'id', $id, $atts['taxonomy'] ); ?>
				<?php $image = get_term_meta( $term->term_id, 'term_bg_image', true ); ?>
				<?php $image_fb = lisner_get_option( 'fallback-bg-category' ); ?>
				<?php $overlay = get_term_meta( $term->term_id, 'term_bg_overlay', true ); ?>
				<?php $opacity = get_term_meta( $term->term_id, 'term_bg_overlay_opacity', true ); ?>
				<?php $position_y = get_term_meta( $term->term_id, 'term_bg_position_y', true ); ?>
				<?php $position_x = get_term_meta( $term->term_id, 'term_bg_position_x', true ); ?>
				<?php $image = ! empty( $image ) ? wp_get_attachment_image_src( $image, 'full' ) : wp_get_attachment_image_src( $image_fb[0], 'full' ); ?>

				<div class="lisner-taxonomy-item lisner-taxonomy-item-1"
				     style="background-image: url(<?php echo ! empty( $image ) ? esc_url( $image[0] ) : ''; ?>); background-position-y: <?php echo isset( $position_y ) && ! empty( $position_y ) ? esc_attr( $position_y ) : ''; ?>; background-position-x: <?php echo isset( $position_x ) && ! empty( $position_x ) ? esc_attr( $position_x ) : ''; ?>;">
					<?php if ( ! empty( $overlay ) ) : ?>
						<span class="lisner-taxonomy-item-overlay"
						      style="background-color: <?php echo esc_attr( $overlay ); ?>; opacity: <?php echo esc_attr( $opacity ); ?>"></span>
					<?php endif; ?>
					<?php $tax_link = lisner_taxonomy_link( $term->term_id, $atts['taxonomy'] ); ?>
					<a href="<?php echo esc_url( $tax_link ); ?>">
						<span class="lisner-taxonomy-item-title"><?php echo esc_html( $term->name ); ?></span>
						<?php if ( $atts['display_count'] ): ?>
							<span class="lisner-taxonomy-item-count"><?php echo esc_html( $term->count ); ?></span>
						<?php endif; ?>
					</a>
				</div>

			<?php endforeach; ?>
		<?php endif; ?>
	</div>
</div>

