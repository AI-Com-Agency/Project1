<?php
/**
 * Template Name: Post Partial Template / Title
 *
 * @author pebas
 * @version 1.0.0
 * @package view/posts/loop
 */
?>
<?php $title = get_the_title(); ?>
<!-- Post / Title -->
<div class="lisner-post-title-block">
	<?php if ( ! empty( $title ) ) : ?>
		<h4 class="lisner-post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
	<?php else: ?>
		<h4 class="lisner-post-title"><a href="<?php the_permalink(); ?>"><?php echo esc_html( the_date() ); ?></a></h4>
	<?php endif; ?>
</div>
