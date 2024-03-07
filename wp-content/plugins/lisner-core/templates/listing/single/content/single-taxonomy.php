<?php
/**
 * Template Name: Listing Single Taxonomy
 * Description: Partial content for single listing taxonomy
 *
 * @author pebas
 * @version 1.0.0
 * @package listing/single/content
 *
 * @var $amenity_args
 */
?>
<?php $terms = get_the_terms( get_the_ID(), $amenity_args['taxonomy'] ); ?>
<?php if ( $terms ) : ?>
	<?php if ( $amenity_args['has_title'] ) : ?>
		<?php $title = $amenity_args['title']; ?>
		<?php include lisner_helper::get_template_part( 'single-section-title', 'listing/single/content' ); ?>
	<?php endif; ?>

	<div class="single-listing-taxonomies taxonomy-<?php echo esc_attr( $amenity_args['taxonomy'] ); ?>">
		<?php foreach ( $terms as $term ) : ?>
			<!-- Single Listing / Taxonomy -->
			<div class="single-listing-taxonomy">
				<?php if ( 'listing_tag' != $amenity_args['taxonomy'] ) : ?>
					<?php $icon = get_term_meta( $term->term_id, 'term_icon', true ); ?>
					<?php $icon = lisner_get_var( $icon, null ); ?>
					<?php if ( $icon ) : ?>
						<i class="single-listing-taxonomy-icon material-icons mf"><?php echo esc_html( $icon ); ?></i>
					<?php endif ?>
				<?php endif; ?>
				<?php $tax_link = lisner_taxonomy_link( $term->term_id, $amenity_args['taxonomy'] ); ?>
				<a href="<?php echo esc_url( $tax_link ); ?>"
				   class="single-listing-taxonomy-name"><?php echo esc_html( $term->name ); ?></a>
			</div>
		<?php endforeach; ?>
	</div>
<?php endif; ?>
