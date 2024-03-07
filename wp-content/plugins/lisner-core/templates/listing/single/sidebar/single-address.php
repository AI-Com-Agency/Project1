<?php
/**
 * Template Name: Listing Single Address
 * Description: Partial content for single listing sidebar
 *
 * @author pebas
 * @version 1.0.0
 * @package listing/single/sidebar
 *
 * @var $loc_args
 */
global $post;
?>
<?php if ( isset( $loc_args ) && $loc_args['has_title'] ) : ?>
	<?php $title = $loc_args['title']; ?>
	<?php include lisner_helper::get_template_part( 'single-section-title', 'listing/single/content' ); ?>
<?php endif; ?>

<?php if ( '2' == lisner_get_var( $args['page_template'], 1 ) ) : ?>
	<address class="address d-flex">
    <span class="address-label d-flex align-items-center"><i
			    class="material-icons mf"><?php echo esc_attr( 'place' ); ?></i><?php esc_html_e( 'Address:', 'lisner-core' ); ?></span>
		<span class="address-map ml-auto"><?php the_job_location(); ?></span>
	</address>
<?php else: ?>
	<address class="address d-flex mb-0">
		<div class="reviews-meta d-flex align-items-center mb-0">
			<div class="widget-price-range-data d-flex align-items-center">
				<div class="widget-label d-flex align-items-center">
					<i class="material-icons mf"><?php echo esc_attr( 'place' ); ?></i>
					<span><?php esc_html_e( 'Address:', 'lisner-core' ) ?></span>
				</div>
			</div>
		</div>
		<span class="address-map ml-auto"><?php the_job_location(); ?></span>
	</address>
<?php endif; ?>
