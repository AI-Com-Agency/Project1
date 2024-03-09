<?php
/**
 * Widget News Template Query - Style 1
 *
 * @author pebas
 * @version 1.0.0
 * @package widgets/views/news-query
 *
 * @var $instance
 * @var $query
 */
?>

<?php $count = 1; ?>
<?php $listing = new WP_Query( $query ); ?>
<?php if ( $listing->have_posts() ) : ?>
	<?php while ( $listing->have_posts() ) : ?>
		<?php $listing->the_post(); ?>
		<?php include lisner_helper::get_template_part( "widget-promo-{$instance['style']}", '/widgets/promo' ); ?>
	<?php endwhile; ?>
	<?php wp_reset_postdata(); ?>
<?php endif; ?>
