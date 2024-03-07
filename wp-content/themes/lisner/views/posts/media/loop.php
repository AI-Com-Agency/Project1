<?php
/**
 * Template Name: Single Post Loop / Standard
 * Description: Partial content for single post section
 *
 * @author pebas
 * @version 1.0.0
 * @package views/posts/media
 *
 */
?>
<?php if ( has_post_thumbnail() ) : ?>
    <figure class="lisner-post-figure">
        <a href="<?php the_permalink(); ?>">
			<?php the_post_thumbnail( 'full'); ?>
        </a>
    </figure>
<?php endif; ?>
