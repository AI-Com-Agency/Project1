<?php
/**
 * Taxonomy Loop Layout
 *
 * @author   includes
 * @version  1.0.0
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( array( 'post-news', 'post-news-main' ) ); ?>
         itemscope itemtype="https://schema.org/Article">
	<?php tbm_shortcodes::get_view_part( 'media', array(
		'thumbnail'     => 'tbm-single-news-alt',
		'video_overlay' => 'video-full',
		'have_link'     => true
	) ); // get news media ?>
    <div class="post-content-wrapper archive-wrapper">
		<?php tbm_shortcodes::get_view_part( 'title', array( 'video_overlay' => 'video-full' ) ); // get news title ?>
		<?php tbm_shortcodes::get_view_part( 'excerpt' ); // get news excerpt ?>
		<?php tbm_shortcodes::get_view_part( 'meta' ); // get news meta ?>
    </div>
</article>
