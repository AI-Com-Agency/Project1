<?php
/**
 * Title template part
 *
 * @author includes
 * @version 1.0.0
 *
 * Passed arguments
 * @var $video_overlay
 * @var $news_template
 */
$video_overlay = isset( $video_overlay ) ? $video_overlay : '';
$news_template = ! isset( $news_template ) ? '' : $news_template;
?>
<!-- Post Title -->
<div class="post-content">
	<?php if ( 'video-image' == $video_overlay ) : ?>
		<?php if ( 'video' == get_post_format() ): ?>
			<?php tbm_news::get_video_button(); ?>
		<?php endif; ?>
	<?php endif; ?>
	<h2>
		<?php if ( 5 == $news_template && tbm_news::has_post_video() ) : ?>
			<!-- Post Category -->
			<span class="post-category">
	        <?php $category = get_the_category(); ?>
				<?php if ( ! empty( $category ) ): ?>
					<?php $cat = ! empty( $category ) ? $category[0] : ''; ?>
					<?php $cat_id = get_cat_ID( $cat->name ) ?><?php echo esc_attr( $meta_divider ); ?>
					<a href="<?php echo esc_url( get_category_link( $cat_id ) ); ?>"><?php echo $cat->name; ?></a>
				<?php endif; ?><?php echo esc_attr( $meta_divider ); ?>
        </span>
		<?php endif; ?>
		<a href="<?php the_permalink(); ?>">
			<?php the_title(); ?>
			<?php if ( 'video' == get_post_format() && 'video-small' == $video_overlay ) : ?>
				<span class="material-icons"><?php echo esc_attr( 'play_circle_outline' ); ?></span>
			<?php endif; ?>
		</a>
	</h2>
</div>

