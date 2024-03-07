<?php
/**
 * Widget Pages Template
 *
 * @author pebas
 * @version 1.0.0
 * @package templates/widgets/
 *
 * @var $instance
 */
?>

<div class="widget-categories">
	<?php $args = array(); ?>
	<?php $args['number'] = isset( $instance['number'] ) ? $instance['number'] : - 1; ?>
	<?php $args['include'] = isset( $instance['specific_pages'] ) ? $instance['specific_pages'] : ''; ?>
	<?php $pages = get_pages( $args ); ?>
	<?php if ( $pages ) : ?>
		<ul class="list-unstyled">
			<?php foreach ( $pages as $page ) : ?>
				<li class="cat-item cat-item-<?php echo esc_attr( $page->ID ); ?>"><a
							href="<?php echo esc_url( get_permalink( $page->ID ) ); ?>"><?php echo esc_html( $page->post_title ); ?></a>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</div>
<!--todo fix this aside, cannot be like that-->
</aside>
