<?php
/**
 * Template Name: Listing Single Reviews
 * Description: Partial content for single listing sidebar
 *
 * @author pebas
 * @version 1.0.0
 * @package listing/single/sidebar
 *
 * @var $rev_args
 */
global $post;
?>
<?php if ( $rev_args['has_title'] ) : ?>
	<?php $title = $rev_args['title']; ?>
	<?php include lisner_helper::get_template_part( 'single-section-title', 'listing/single/content' ); ?>
<?php endif; ?>

<?php if ( '2' == lisner_get_var( $args['page_template'], 1 ) ) : ?>
    <div class="reviews-meta">
        <!-- Listing / Rating -->
		<?php $comments_count = wp_count_comments( get_the_ID() ) ?>
		<?php $avg_rating = pebas_review_listings_functions::get_average_rating( get_the_ID() ); ?>
        <div class="lisner-listing-meta-rating color-<?php echo '0.0' == $avg_rating ? esc_attr( 'disabled' ) : esc_attr( 'success' ); ?>">
			<?php echo esc_html( $avg_rating ); ?>
        </div>
        <div class="reviews-rating">
            <span class="reviews-rating-stars"><?php echo pebas_review_listings_functions::generate_review_starts( get_the_ID() ); ?></span>
            <span class="reviews-rating-count">
                                    (<?php echo esc_html( $comments_count->approved ); ?>)</span>
        </div>
    </div>
<?php else: ?>
    <div class="reviews-meta d-flex align-items-center">
        <div class="widget-price-range-data d-flex align-items-center">
            <div class="widget-label d-flex align-items-center">
                <i class="material-icons mf"><?php echo esc_attr( 'star_border' ); ?></i>
                <span><?php esc_html_e( 'Rating:', 'lisner-core' ) ?></span>
            </div>
        </div>
        <!-- Listing / Rating -->
		<?php $comments_count = wp_count_comments( get_the_ID() ) ?>
		<?php $avg_rating = pebas_review_listings_functions::get_average_rating( get_the_ID() ); ?>
        <div class="widget-reviews-wrapper d-flex align-items-center ml-auto">
            <div class="lisner-listing-meta-rating color-<?php echo '0.0' == $avg_rating ? esc_attr( 'disabled' ) : esc_attr( 'success' ); ?>">
				<?php echo esc_html( $avg_rating ); ?>
            </div>
            <div class="reviews-rating">
                <span class="reviews-rating-stars"><?php echo pebas_review_listings_functions::generate_review_starts( get_the_ID() ); ?></span>
                <span class="reviews-rating-count">
                                    (<?php echo esc_html( $comments_count->approved ); ?>)</span>
            </div>
        </div>
    </div>
<?php endif; ?>
