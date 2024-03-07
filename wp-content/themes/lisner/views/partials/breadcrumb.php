<?php
/**
 * Template Name: Page Breadcrumbs
 * Description: Partial content for page
 *
 * @author pebas
 * @version 1.0.0
 * @package views/partials
 */

?>
<!-- Single Listing / Breadcrumbs -->
<div class="single-listing-breadcrumbs">
    <nav aria-label="breadcrumb" class="single-listing-breadcrumb breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item home-cat"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><i
                            class="material-icons mf"><?php echo esc_html( 'home' ) ?></i><?php esc_html_e( 'Home', 'lisner' ); ?>
                </a></li>
			<?php if ( is_404() ): ?>
                <li class="breadcrumb-item"><a href=""><?php esc_html_e( 'Error 404', 'lisner' ); ?></a></li>
			<?php else: ?>
                <li class="breadcrumb-item"><a href=""><?php echo get_the_title( get_the_ID() ); ?></a></li>
			<?php endif; ?>
        </ol>
    </nav>
</div>
