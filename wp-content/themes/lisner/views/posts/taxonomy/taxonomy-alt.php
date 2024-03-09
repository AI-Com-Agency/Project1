<?php
/**
 * Taxonomy Loop Layout Alternative
 *
 * @author   includes
 * @version  1.0.0
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( array( 'post-news', 'post-news-alternate' ) ); ?>
         itemscope itemtype="https://schema.org/Article">
	<?php tbm_shortcodes::get_view_part( 'media', array(
		'thumbnail'     => 'news-thumbnail-290x164',
		'video_overlay' => 'video-full',
		'news_template' => 4,
		'have_link'     => true
	) ); // get news media ?>
    <div class="post-content-wrapper">
		<?php tbm_shortcodes::get_view_part( 'title', array( 'video_overlay' => 'video-full' ) ); // get news title ?>
		<?php tbm_shortcodes::get_view_part( 'excerpt' ); // get news excerpt ?>
		<?php tbm_shortcodes::get_view_part( 'meta' ); // get news meta ?>
    </div>

</article>
