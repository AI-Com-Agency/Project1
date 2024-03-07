<?php
/**
 * Template Name: Single Post Section / Media
 * Description: Partial content for single post section
 *
 * @author pebas
 * @version 1.0.0
 * @package views/posts/single
 *
 * @var $title
 */
?>
<?php if ( class_exists( 'Lisner_Core' ) && has_post_thumbnail() ) : ?>
	<figure class="single-listing-header-image m-0">
		<?php the_post_thumbnail( 'full' ) ?>
	</figure>
<?php endif; ?>
