<?php
/**
 * Template Name: Post Partial Template / Meta
 *
 * @author pebas
 * @version 1.0.0
 * @package view/posts/loop
 */
?>
<!-- Post / Top Meta -->
<div class="lisner-post-meta">
	<?php $categories = get_the_category(); ?>
	<?php if ( $categories ): ?>
		<?php foreach ( $categories as $category ) : ?>
            <a href="<?php echo esc_attr( get_term_link( $category->term_id ) ); ?>"><?php echo esc_html( $category->name ); ?></a>
		<?php endforeach; ?>
	<?php endif; ?>
</div>
