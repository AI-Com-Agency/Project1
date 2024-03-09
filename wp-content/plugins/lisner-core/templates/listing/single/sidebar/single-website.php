<?php
/**
 * Template Name: Listing Single Address
 * Description: Partial content for single listing sidebar
 *
 * @author pebas
 * @version 1.0.0
 * @package listing/single/sidebar
 *
 * @var $loc_args
 */
global $post;
?>
<?php if ( isset( $loc_args ) && $loc_args['has_title'] ) : ?>
	<?php $title = $loc_args['title']; ?>
	<?php include lisner_helper::get_template_part( 'single-section-title', 'listing/single/content' ); ?>
<?php endif; ?>

<div class="website d-flex mb-0">
    <div class="d-flex align-items-center mb-0">
        <div class="d-flex align-items-center">
            <div class="widget-label d-flex align-items-center">
                <i class="material-icons mf"><?php echo esc_attr( 'language' ); ?></i>
                <span><?php esc_html_e( 'Website:', 'lisner-core' ) ?></span>
            </div>
        </div>
    </div>
    <?php $site_link = lisner_format_website_link( $post->_listing_website ); ?>
    <a href="<?php echo esc_url( $post->_listing_website ); ?>"
       target="_blank"
       class="address-map ml-auto"><?php echo esc_html( $site_link ); ?></a>
</div>
