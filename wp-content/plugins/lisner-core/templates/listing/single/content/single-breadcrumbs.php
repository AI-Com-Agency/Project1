<?php
/**
 * Template Name: Listing Single Breadcrumbs
 * Description: Partial content for single listing content
 *
 * @author pebas
 * @version 1.0.0
 * @package listing/single/content
 */

?>
<!-- Single Listing / Breadcrumbs -->
<div class="single-listing-breadcrumbs">
	<?php $categories = get_the_terms( get_the_ID(), 'job_listing_category' ); ?>
	<?php $category_parents = array(); ?>
	<?php $category_childs = array(); ?>
	<?php if ( $categories ) : ?>
		<?php foreach ( $categories as $category ) : ?>
			<?php if ( 0 == $category->parent ) : ?>
				<?php $category_parents[] = $category; ?>
			<?php endif; ?>
		<?php endforeach; ?>
	<?php endif; ?>
    <nav aria-label="breadcrumb" class="single-listing-breadcrumb breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item home-cat"><a
                        href="<?php echo esc_url( home_url( '/' ) ); ?>"><i
                            class="material-icons mf"><?php echo esc_html( 'home' ) ?></i><?php esc_html_e( 'Home', 'lisner-core' ); ?>
                </a></li>
			<?php if ( $category_parents ) : ?>
				<?php $category = array_shift( $category_parents ); ?>
				<?php foreach ( $categories as $category_child ) : ?>
					<?php if ( $category->term_id == $category_child->parent ) : ?>
						<?php $category_childs[] = $category_child; ?>
					<?php endif; ?>
				<?php endforeach; ?>
                <li class="breadcrumb-item"><a
                            href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>"><?php echo esc_html( $category->name ); ?></a>
                </li>
			<?php endif; ?>
			<?php if ( $category_childs ) : ?>
				<?php foreach ( $category_childs as $category ) : ?>
                    <li class="breadcrumb-item"><a
                                href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>"><?php echo esc_html( $category->name ); ?></a>
                    </li>
				<?php endforeach; ?>
			<?php endif; ?>
        </ol>
    </nav>
</div>
