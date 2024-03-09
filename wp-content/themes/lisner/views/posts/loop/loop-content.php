<?php
/**
 * Template Name: Post Partial Template / Content
 *
 * @author pebas
 * @version 1.0.0
 * @package view/posts/loop
 */
?>
<!-- Post / Content -->
<div class="lisner-post-description">
	<?php $content = get_the_content(); ?>
	<?php $content = apply_filters( 'the_content', $content ); ?>
	<?php echo wp_trim_words( $content, 18, '' ); ?>
</div>
