<?php
/**
 * Template Name: Post Partial Template / Loop
 *
 * @author pebas
 * @version 1.0.0
 * @package view/posts/loop
 */
?>
<?php $sticky_class = is_sticky() ? esc_attr( 'post-sticky' ) : ''; ?>

<!-- Post -->
<article id="post-<?php the_ID(); ?>" <?php post_class( array( 'post-default', 'lisner-post-item', $sticky_class ) ); ?>
         itemscope itemtype="https://schema.org/Article">

	<?php get_template_part( 'views/posts/loop/loop', 'media' ); // get post media ?>

	<!-- Post / Content -->
	<div class="lisner-post-content">
		<?php get_template_part( 'views/posts/loop/loop', 'meta' ); // get post meta ?>
		<?php get_template_part( 'views/posts/loop/loop', 'title' ); // get post title ?>
		<?php get_template_part( 'views/posts/loop/loop', 'content' ); // get post content ?>
	</div>

</article>
