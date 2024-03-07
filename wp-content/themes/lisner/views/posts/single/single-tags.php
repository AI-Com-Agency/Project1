<?php
/**
 * Template Name: Single Post Section / Tags
 * Description: Partial content for single post section
 *
 * @author pebas
 * @version 1.0.0
 * @package views/posts/single
 *
 * @var $title
 */
?>
<?php $terms = get_the_terms( get_the_ID(), 'post_tag' ); ?>
<?php if ( $terms ) : ?>
    <div class="single-listing-taxonomies taxonomy-listing_tag">
		<?php foreach ( $terms as $term ) : ?>
            <!-- Single Post / Tags -->
            <div class="single-listing-taxonomy">
                <a href="<?php echo esc_url( get_category_link( $term->term_id ) ); ?>"
                   class="single-listing-taxonomy-name"><?php echo esc_html( $term->name ); ?></a>
            </div>
		<?php endforeach; ?>
    </div>
<?php endif; ?>
