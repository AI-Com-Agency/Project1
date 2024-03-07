<?php
/**
 * Template Name: Listing Single Template / 3
 * Description: Template for single listing page
 *
 * @author pebas
 * @version 1.0.0
 * @package listing/single
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $post;
?>

<!-- Single Listing / Gallery -->
<?php $image = rwmb_meta( '_listing_cover', array( 'size' => 'listing_single_image' ) ); ?>
<?php $gallery = rwmb_meta( '_listing_gallery', array( 'size' => 'listing_single_gallery' ) ); ?>
<header
        class="single-listing-header single-listing-header-style-<?php echo esc_attr( $page_template ); ?> <?php echo empty( $image ) && empty( $gallery ) ? esc_attr( 'single-listing-header-no-image' ) : '' ?>">
	<?php $header_template = rwmb_meta( '_lisner_header_template' ); ?>
	<?php $header_template = lisner_get_var( $header_template, 'slider' ); ?>
	<?php $atts['big_thumb'] = true; ?>
	<?php include lisner_helper::get_template_part( "single-header-{$header_template}", 'listing/single/header', $atts ); ?>
</header>

<!-- Single Listing / Meta -->
<section class="single-listing-meta">
    <div class="row">
        <div class="col-sm-6">
			<?php include lisner_helper::get_template_part( 'single-breadcrumbs', 'listing/single/content' ); ?>
        </div>
        <div class="col-sm-6">
			<?php include lisner_helper::get_template_part( 'single-meta-actions', 'listing/single/content' ); ?>
        </div>
    </div>
</section>

<!-- Single Listing / Main -->
<section class="single-listing-main">
	<?php include lisner_helper::get_template_part( 'single-title', 'listing/single/content' ); ?>
	<?php include lisner_helper::get_template_part( 'single-meta', 'listing/single/content' ); ?>
	<?php include lisner_helper::get_template_part( 'single-description', 'listing/single/content' ); ?>
</section>

<?php if ( 0 != $option['listing-fields-video'] && ( ! isset( $option['listing-fields-video-members'] ) || lisner_show_to_member( $option['listing-fields-video-members'] ) ) ): ?>
	<?php $video_args = array(
		'has_title' => true,
		'title'     => esc_html__( 'Watch Video', 'lisner-core' ),
	); ?>
	<?php $video = get_post_meta( get_the_ID(), '_listing_video', true ); ?>
	<?php if ( isset( $video ) && ! empty( $video ) ) : ?>
        <!-- Single Listing / Video -->
        <section class="single-listing-video">
			<?php include lisner_helper::get_template_part( 'single-video', 'listing/single/content', $video_args ); ?>
        </section>
	<?php endif; ?>
<?php endif; ?>

<?php if ( 0 != $option['listing-fields-amenities'] && ( ! isset( $option['listing-fields-amenities-members'] ) || lisner_show_to_member( $option['listing-fields-amenities-members'] ) ) ): ?>
	<?php $amenity_args = array(
		'taxonomy'  => 'listing_amenity',
		'has_title' => true,
		'title'     => esc_html__( 'Amenities', 'lisner-core' ),
	); ?>
	<?php $amenities = get_the_terms( get_the_ID(), $amenity_args['taxonomy'] ); ?>
	<?php if ( isset( $amenities ) && ! empty( $amenities ) ) : ?>
        <!-- Single Listing / Amenities -->
        <section class="single-listing-taxonomies-wrapper">
			<?php include lisner_helper::get_template_part( 'single-taxonomy', 'listing/single/content', $amenity_args ); ?>
        </section>
	<?php endif; ?>
<?php endif; ?>

<?php if ( 0 != $option['listing-fields-tags'] && ( ! isset( $option['listing-fields-tags-members'] ) || lisner_show_to_member( $option['listing-fields-tags-members'] ) ) ): ?>
	<?php $amenity_args = array(
		'taxonomy'  => 'listing_tag',
		'has_title' => true,
		'title'     => esc_html__( 'Tags', 'lisner-core' ),
	); ?>
	<?php $tags = get_the_terms( get_the_ID(), $amenity_args['taxonomy'] ); ?>
    <!-- Single Listing / Tags -->
    <section class="single-listing-taxonomies-wrapper">
	<?php if ( isset( $tags ) && ! empty( $tags ) ) : ?>
		<?php include lisner_helper::get_template_part( 'single-taxonomy', 'listing/single/content', $amenity_args ); ?>
        </section>
	<?php endif; ?>
<?php endif; ?>


<!-- Single Listing / Reviews -->
<?php do_action( 'lisner_review_comments_template' ); // defined in pbs-review-listings plugin ?>
