<?php
/**
 * Listing pagination part
 *
 * @author pebas
 * @version 1.0.0
 * @package templates/partials
 * @var $pagination_args
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Calculate pages to output.
$end_size      = 3;
$mid_size      = 3;
$max_num_pages = $pagination_args['max_num_pages'];
$current_page  = $pagination_args['current_page'];
$start_pages   = range( 1, $end_size );
$end_pages     = range( $max_num_pages - $end_size + 1, $max_num_pages );
$mid_pages     = range( $current_page - $mid_size, $current_page + $mid_size );
$pages         = array_intersect( range( 1, $max_num_pages ), array_merge( $start_pages, $end_pages, $mid_pages ) );
$prev_page     = 0;
?>

<div class="pagination-wrapper">
	<?php if ( $max_num_pages > 1 ) : ?>
        <!-- Pagination -->
        <nav class="listing-pagination" aria-label="<?php esc_attr_e( 'Listing Navigation', 'lisner-core' ); ?>">
            <ul class="pagination">
				<?php if ( $current_page && $current_page > 1 ) : ?>
                    <li class="page-item"><a href="javascript:" class="page-link"
                                             data-page="<?php echo esc_attr( $current_page - 1 ); ?>"
                                             aria-label="<?php esc_attr_e( 'Previous', 'lisner-core' ); ?>"><i
                                    class="material-icons"><?php echo esc_attr( 'keyboard_arrow_left' ); ?></i></a>
                    </li>
				<?php endif; ?>

				<?php
				foreach ( $pages as $page ) {
					if ( $prev_page != $page - 1 ) {
						echo '<li class="page-item"><span class="page-link page-link-gap">' . esc_html( '...' ) . '</span></li>';
					}
					if ( $current_page == $page ) {
						echo '<li class="page-item active"><span class="page-link" data-page="' . esc_attr( $page ) . '">' . esc_html( $page ) . '</span></li>';
					} else {
						echo '<li class="page-item"><a href="javascript:" class="page-link" data-page="' . esc_attr( $page ) . '">' . esc_html( $page ) . '</a></li>';
					}
					$prev_page = $page;
				}
				?>

				<?php if ( $current_page && $current_page < $max_num_pages ) : ?>
                    <li class="page-item"><a href="javascript:" class="page-link"
                                             data-page="<?php echo esc_attr( $current_page + 1 ); ?>"
                                             aria-label="<?php esc_attr_e( 'Next', 'lisner-core' ); ?>"><i
                                    class="material-icons"><?php echo esc_attr( 'keyboard_arrow_right' ); ?></i></a>
                    </li>
				<?php endif; ?>
            </ul>
        </nav>

	<?php endif; ?>
	<?php do_action( 'lisner_pagination_after' ); ?>
</div>
