<?php
/**
 * Shortcode Taxonomy / Layout 8
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

		<div class="row justify-content-center mt-4">
			<div class="col-lg-7">
				<div class="row justify-content-center">
					<!-- Section Taxonomy / Taxonomies -->
					<?php $full_count = count( $atts['taxonomy_terms'] ); ?>
					<div class="lisner-taxonomy-wrapper lisner-taxonomy-text">
						<div class="row">
							<?php $count = 1; ?>
							<?php if ( ! empty( $atts['taxonomy_terms'] ) ) : ?>
								<?php foreach ( $atts['taxonomy_terms'] as $id => $value ): ?>
									<?php $term = get_term_by( 'id', $id, $atts['taxonomy'] ); ?>
									<div class="col-sm-3 col-6">
										<div class="lisner-taxonomy-item">
											<?php $tax_link = lisner_taxonomy_link( $term->term_id, $atts['taxonomy'] ); ?>
											<a href="<?php echo esc_url( $tax_link ); ?>">
												<span class="lisner-taxonomy-item-title"><?php echo esc_html( $term->name ); ?></span>
												<?php if ( $atts['display_count'] ): ?>
													<?php $count_msg = _n_noop( '%d Listing', '%d Listings', 'lisner-core' ); ?>
													<span class="lisner-taxonomy-item-count"><?php echo sprintf( translate_nooped_plural( $count_msg, $term->count, 'lisner-core' ), $term->count ); ?></span>
												<?php endif; ?>
											</a>
										</div>
									</div>
								<?php endforeach; ?>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row justify-content-center mt-md-5">
			<div class="col-sm-12 text-center">
				<!-- Section Taxonomy / Button-->
				<?php include lisner_helper::get_template_part( 'button', 'shortcodes/partials', $atts ); ?>
			</div>
		</div>

	</div>
</div>
