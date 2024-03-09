<?php
/**
 * Template Name: Single Post Meta
 * Description: Partial content for single post content
 *
 * @author pebas
 * @version 1.0.0
 * @package views/posts/single
 */

global $post;
?>
<div class="single-listing-main-meta">
	<?php if ( comments_open() ) : ?>
		<!-- Single Post / Comment Call -->
		<div class="single-listing-main-meta-action">
			<a class="animate" href="#respond"><?php esc_html_e( 'Leave Comment', 'lisner' ); ?><i
						class="material-icons rotate--90"><?php echo esc_attr( 'subdirectory_arrow_left' ); ?></i></a>
		</div>
	<?php endif; ?>
	<?php $categories = get_the_category(); ?>
	<?php $category = is_array( $categories ) ? array_shift( $categories ) : $category; ?>
	<?php $category_id = $category->term_id; ?>
	<?php if ( $category_id ) : ?>
		<!-- Single Post / Category -->
		<div class="lisner-listing-meta-category">
			<?php $category = get_term_by( 'id', $category_id, 'category' ); ?>
			<?php $icon = get_term_meta( $category_id, 'term_icon', true ); ?>
			<a href="<?php echo esc_url( get_term_link( $category->term_id ) ); ?>">
				<?php if ( $icon ) : ?>
					<i class="lisner-listing-meta-icon material-icons mf"><?php echo esc_html( $icon ); ?></i>
				<?php endif; ?>
				<?php echo esc_html( $category->name ); ?></a>
		</div>
		<!-- Single Post / Author -->
		<div class="single-post-author">
			<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"
			   class="single-post-user"><?php printf( esc_html__( 'by %s', 'lisner' ), get_the_author() ); ?></a>
			<span class="single-post-time"><?php echo human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) . esc_html__( ' ago', 'lisner' ); ?></span>
		</div>
	<?php endif; ?>
</div>
