<?php
/**
 * News Block Template Part 1
 *
 * @author includes
 * @version 1.0.0
 * @package shortcode/views/
 */
?>
<?php $news = new WP_Query( tbm_adjust_query( $atts ) ); ?>
<div class="news-block news-block-1">
    <h6 class="block-title">
        <span><?php echo $atts['title'] ?></span>
		<?php tbm_shortcodes::category_filter( $atts ); ?>
    </h6>
    <div class="news-block-wrapper" data-news-block-id="1"
         id="<?php echo esc_attr( 'shortcode-id-' . $atts['unique_id'] ); ?>">
        <div class="row">
			<?php if ( $news->have_posts() ): ?>
				<?php $news_count = 1; ?>
				<?php while ( $news->have_posts() ): ?>
					<?php $news->the_post(); ?>
					<?php $thumbnail = 1 != $news_count ? 'news-thumbnail-70x70' : 'news-thumbnail-790x420'; ?>
					<?php $video_overlay = 1 == $news_count ? 'video-full' : 'video-small'; ?>
                    <div class="news col-sm-<?php echo 1 == $news_count ++ ? esc_attr( '12' ) : esc_attr( '6 news-list' ); ?>">
                        <article <?php post_class( [ 'post-news' ] ); ?>
                                 itemscope itemtype="https://schema.org/Article">
							<?php tbm_shortcodes::get_view_part( 'media', array(
								'thumbnail'     => $thumbnail,
								'video_overlay' => $video_overlay,
								'have_link'     => true
							) ); // get news media ?>
                            <div class="post-content-wrapper">
								<?php tbm_shortcodes::get_view_part( 'title', array( 'video_overlay' => $video_overlay ) ); // get news title ?>
								<?php tbm_shortcodes::get_view_part( 'excerpt' ); // get news excerpt ?>
								<?php tbm_shortcodes::get_view_part( 'meta' ); // get news meta ?>
                            </div>

                        </article>
                    </div>
					<?php $news_count ++; ?>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			<?php endif; ?>
        </div>
    </div>
</div>
