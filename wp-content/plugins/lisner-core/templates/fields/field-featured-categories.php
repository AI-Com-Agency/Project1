<?php
/**
 * Home / Hero search featured categories
 *
 * @author pebas
 * @package field/field-button
 * @version 1.0.0
 *
 * @param $args
 */
?>
<?php if ( isset( $args['featured_taxonomies'] ) ) : ?>
	<?php if ( ! isset( $args['select_categories'] ) ) : ?>
		<div class="hero-featured-taxonomies <?php echo esc_attr( "hero-featured-taxonomy-template-{$args['hero_template']}" ); ?>">
			<?php foreach ( $args['featured_taxonomies'] as $featured_taxonomy ) : ?>
				<?php $tax_link = lisner_taxonomy_link( $featured_taxonomy->term_id ); ?>
				<?php $icon = get_term_meta( $featured_taxonomy->term_id, 'term_icon', true ); ?>
				<a class="hero-featured-taxonomy <?php echo ! $icon ? esc_attr( 'no-icon' ) : ''; ?>"
				   href="<?php echo esc_url( $tax_link ); ?>">
					<?php echo isset( $icon ) && ! empty( $icon ) ? wp_kses_post( '<i class="material-icons">' . esc_html( $icon ) . '</i>' ) : ''; ?>
					<?php echo esc_html( $featured_taxonomy->name ); ?></a>
			<?php endforeach; ?>
		</div>
	<?php else: ?>
		<div class="dropdown featured-taxonomies-select">
			<button class="btn-link dropdown-toggle" type="button" id="selectCategories"
			        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<?php esc_html_e( 'Specific category?', 'lisner-core' ); ?>
			</button>
			<div class="dropdown-menu featured-taxonomies">
				<?php foreach ( $args['featured_taxonomies'] as $featured_taxonomy ) : ?>
					<?php $tax_link = lisner_taxonomy_link( $featured_taxonomy->term_id ); ?>
					<?php $icon = get_term_meta( $featured_taxonomy->term_id, 'term_icon', true ); ?>
					<a class="featured-taxonomy <?php echo ! $icon ? esc_attr( 'no-icon' ) : ''; ?>"
					   href="<?php echo esc_url( $tax_link ); ?>">
						<?php echo isset( $icon ) && ! empty( $icon ) ? wp_kses_post( '<i class="material-icons">' . esc_html( $icon ) . '</i>' ) : ''; ?>
						<?php echo esc_html( $featured_taxonomy->name ); ?></a>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>
<?php endif; ?>

