<?php
/**
 * Template Name: Listing Single Claim
 * Description: Partial content for single listing sidebar
 *
 * @author pebas
 * @version 1.0.0
 * @package listing/single/sidebar
 *
 * @var $claim_args
 */
global $post;
?>
<?php if ( $claim_args['has_title'] ) : ?>
	<?php $title = $claim_args['title']; ?>
	<?php include lisner_helper::get_template_part( 'single-section-title', 'listing/single/content' ); ?>
<?php endif; ?>

<section class="listing-widget listing-widget-claim-listing d-flex align-items-center">
    <span class="claim-label"><?php esc_html_e( 'Own this place?', 'lisner-core' ); ?></span>
	<?php $claim_link = get_permalink( get_option( 'job_manager_claim_listing_page_id' ) ) . '?listing_id=' . get_the_ID(); ?>
    <a href="<?php echo esc_url( $claim_link ); ?>"
       class="claim-link"><?php esc_html_e( 'Claim It Now!', 'lisner-core' ); ?></a>
</section>