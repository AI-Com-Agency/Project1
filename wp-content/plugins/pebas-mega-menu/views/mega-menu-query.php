<?php
/**
 * Mega Menu Query Template
 *
 * @author pebas
 * @version 1.0.0
 * @package views/
 *
 * @var $news
 */
?>
<?php if ( $news->have_posts() ) : ?>
	<?php while ( $news->have_posts() ): ?>
		<?php $news->the_post(); ?>
        <div class="mega-menu-post swiper-slide">
			<?php if ( has_post_thumbnail() ) : ?>
                <!-- Post Image -->
                <figure class="mega-menu-post-thumbnail">
                    <a href="<?php the_permalink(); ?>">
						<?php the_post_thumbnail( 'mega-menu-320x170', array( 'class' => 'img-fluid' ) ); ?>
                    </a>
                </figure>
			<?php endif; ?>
            <div class="mega-menu-post-content">
                <!-- Post Title -->
                <h5 class="mega-menu-post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                <!-- Post Meta -->
                <div class="mega-menu-post-meta">
					<?php $author = get_the_author_meta( 'ID' ); ?>
					<?php $author_data = get_userdata( $author ); ?>
                    <a class="post-author" href="<?php echo esc_url( get_author_posts_url( $author ) ); ?>">
						<?php echo sprintf( esc_html__( 'by %s', 'thebigmagazine-mega-menu' ), esc_html( $author_data->display_name ) ); ?>
                    </a>
                    <span class="divider"><?php echo esc_html( '-' ); ?></span>
                    <!-- Post Time -->
                    <span class="post-time">
                    <?php tbm_news::post_date( get_the_ID() ); ?>
                </span>
                </div>
            </div>
        </div>
	<?php endwhile; ?>
<?php endif; ?>
