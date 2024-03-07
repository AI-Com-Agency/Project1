<?php
/**
 * Pagination template
 *
 * @author pebas
 * @version 1.0.0
 */
?>
<?php $page_links = array(); ?>
<?php $pagination = is_single() ? pbs_theme_functions::format_link_pages() : pbs_theme_functions::format_pagination( $page_links ); ?>
<?php if ( $pagination && ! is_page() ): ?>
	<!-- Post Pagination -->
	<div class="row text-center pagination-row">
		<div class="col-sm-12 justify-content-center">
			<?php echo wp_kses_post( $pagination ); ?>
		</div>
	</div>
<?php else: ?>
	<div class="text-center d-flex w-100 page-pagination">
		<div class="justify-content-center">
			<?php
			$defaults = array(
				'before'           => '<div class="pagination listing-pagination post-pagination">' . __( 'Page:', 'lisner' ),
				'after'            => '</div>',
				'link_before'      => '<span class="paginated-element">',
				'link_after'       => '</span>',
				'next_or_number'   => 'number',
				'separator'        => ' ',
				'nextpagelink'     => __( 'Next page', 'lisner' ),
				'previouspagelink' => __( 'Previous page', 'lisner' ),
				'pagelink'         => '%',
				'echo'             => 1
			);
			?>
			<?php wp_link_pages( $defaults ) ?>
		</div>
	</div>
<?php endif; ?>
