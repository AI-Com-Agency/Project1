<?php
/**
 * Template Name: Listing Single WooCommerce Bookings
 * Description: Partial content for single listing content
 *
 * @author pebas
 * @version 1.0.0
 * @package listing/single/bookings
 */

?>

<?php $products_ids = get_post_meta( get_the_ID(), '_listing_products' ); ?>
<?php if ( isset( $products_ids ) && ! empty( $products_ids ) ) : ?>
	<?php $product_args = apply_filters( 'woocommerce_related_products_args', array(
		'post_type'           => 'product',
		'ignore_sticky_posts' => 1,
		'no_found_rows'       => 1,
		'posts_per_page'      => - 1,
		'post__in'            => $products_ids,
		'meta_query'          => array(
			array(
				'key'     => '_is_disabled',
				'value'   => 'yes',
				'compare' => '!='
			)
		)
	) );

	$products = new WP_Query( $product_args );
	?>
	<?php if ( $products->have_posts() ) : ?>
		<!-- Listing Sidebar / Booking -->
		<section class="listing-widget listing-widget-bookings">
			<?php while ( $products->have_posts() ) : $products->the_post(); ?>

				<?php woocommerce_template_single_add_to_cart(); ?>

			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
		</section>
	<?php endif; ?>
<?php endif; ?>

