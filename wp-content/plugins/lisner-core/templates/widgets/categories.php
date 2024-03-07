<?php
/**
 * Widget Categories Template
 *
 * @author pebas
 * @version 1.0.0
 * @package templates/widgets/
 *
 * @var $instance
 */
?>

<div class="widget-categories">
	<?php $taxonomy = $instance['taxonomy'] ?>
	<?php
	switch ( $taxonomy ) {
		case 'job_listing_category':
			$include = $instance['cat_specific'];
			break;
		case 'listing_location':
			$include = $instance['location_specific'];
			break;
		case 'listing_amenity':
			$include = $instance['amenity_specific'];
			break;
		default:
			$include = '';
	}
	?>
	<?php $terms = get_terms(
		array( 'taxonomy' => $taxonomy, 'hide_empty' => false, 'include' => $include, 'number' => $instance['number'] )
	); ?>
	<?php if ( $terms ) : ?>
		<ul class="list-unstyled">
			<?php foreach ( $terms as $term ) : ?>
				<?php $tax_link = lisner_taxonomy_link( $term->term_id, $taxonomy ); ?>
				<li class="cat-item cat-item-<?php echo esc_attr( $term->term_id ); ?>"><a
							href="<?php echo esc_url( $tax_link ); ?>"><?php echo esc_html( $term->name ); ?></a>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</div>
