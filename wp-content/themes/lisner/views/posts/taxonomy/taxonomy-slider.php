<?php
/**
 * Taxonomy Slider Layout
 *
 * @author   includes
 * @version  1.0.0
 */
?>
<?php $slider_categories = get_categories( array( 'hide_empty' => true, 'parent' => 0 ) ); ?>
<?php $slider_cats = array(); ?>
<?php if ( $slider_categories ) : ?>
	<?php foreach ( $slider_categories as $slider_category ) : ?>
		<?php $slider_cats[] = $slider_category->term_id; ?>
	<?php endforeach; ?>
<?php endif; ?>

<?php if ( $slider_cats ) : ?>
    <!-- Taxonomy Slider Pagination -->
    <div class="swiper-button-next"><i
                class="material-icons"><?php echo esc_attr( 'keyboard_arrow_right' ); ?></i>
    </div>
    <div class="swiper-button-prev"><i
                class="material-icons"><?php echo esc_attr( 'keyboard_arrow_left' ); ?></i>
    </div>
    <!-- Taxonomy Slider -->
    <div class="swiper-container taxonomy-slider">
        <div class="taxonomy-wrapper swiper-wrapper">
			<?php foreach ( $slider_cats as $slider_cat ) : ?>
				<?php $slider_posts_args = array( 'posts_per_page' => 1, 'cat' => $slider_cat ); ?>
				<?php $slider_posts = new WP_Query( $slider_posts_args ); ?>
				<?php if ( $slider_posts->have_posts() ) : ?>
					<?php while ( $slider_posts->have_posts() ) : ?>
						<?php $slider_posts->the_post(); ?>
                        <div class="swiper-slide">
                            <article id="post-<?php the_ID(); ?>" <?php post_class( array(
								'post-news',
								'taxonomy-news-slider'
							) ); ?>
                                     itemscope itemtype="https://schema.org/Article">
								<?php tbm_shortcodes::get_view_part( 'media', array(
									'thumbnail'       => 'news-thumbnail-70x70',
									'video_overlay'   => '',
									'have_link'       => true,
									'taxonomy_slider' => true
								) ); // get news media ?>
                                <!-- Post Content -->
                                <div class="post-content-wrapper">
									<?php $cat = get_the_category_by_ID( $slider_cat ); ?>
                                    <span class="category"><?php echo esc_html( $cat ); ?></span>
                                    <div class="post-content">
                                        <h2>
                                            <a href="<?php the_permalink(); ?>"><?php echo wp_trim_words( get_the_title(), 7 ); ?></a>
                                        </h2>
                                    </div>
                                </div>

                            </article>
                        </div>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
				<?php endif; ?>
			<?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
