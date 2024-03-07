<?php
/**
 * Shortcode Post / Post Box
 *
 * @author includes
 * @version 1.0.0
 * @package shortcodes/post/partials
 */
?>
<div class="lisner-post-item">
    <!-- Listing / Image -->
    <figure class="lisner-post-figure">
        <a href="<?php the_permalink(); ?>">
			<?php the_post_thumbnail( 'post_box' ); ?>
        </a>
    </figure>

    <!-- Listing / Content -->
    <div class="lisner-post-content">

        <!-- Listing / Top Meta -->
        <div class="lisner-post-meta">
			<?php $categories = get_the_category(); ?>
			<?php if ( $categories ): ?>
				<?php foreach ( $categories as $category ) : ?>
                    <a href="<?php echo esc_attr( get_term_link( $category->term_id ) ); ?>"><?php echo esc_html( $category->name ); ?></a>
				<?php endforeach; ?>
			<?php endif; ?>
        </div>

        <!-- Listing / Title -->
        <div class="lisner-post-title-block">
            <h4 class="lisner-post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
        </div>

        <!-- Listing / Description -->
        <div class="lisner-post-description">
			<?php $content = get_the_content(); ?>
			<?php $content = apply_filters( 'the_content', $content ); ?>
			<?php echo wp_trim_words( $content, 18, '' ); ?>
        </div>

    </div>
</div>

