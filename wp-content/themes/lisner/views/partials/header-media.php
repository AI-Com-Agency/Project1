<?php
/**
 * Template Name: Page Partial Content / Header Media
 * Description: Partial content for single post section
 *
 * @author pebas
 * @version 1.0.0
 * @package views/partials
 *
 * @var $title
 */

$page_id     = get_queried_object_id() ? get_queried_object_id() : '';
$page_id     = pbs_global::$is_woocommerce_installed && ( is_product() || is_shop() || is_product_taxonomy() ) ? get_option( 'woocommerce_shop_page_id' ) : $page_id;
$bg_fallback = get_stylesheet_directory_uri() . '/assets/images/bg_pattern.jpg';
$bg          = class_exists( 'Lisner_Core' ) ? rwmb_meta( 'page_bg_image', array( 'size' => 'wc_page_hero' ), $page_id ) : '';
$bg = !empty( $bg ) ? array_shift( $bg ) : $bg_fallback;
$bg_overlay  = '';
$bg_opacity  = '';
if ( class_exists( 'Lisner_Core' ) ) {
	$bg_overlay = lisner_helper::get_hero_image_overlay_style( $page_id, 'page', 'overlay', 'background-color' );
	$bg_opacity = lisner_helper::get_hero_image_overlay_style( $page_id, 'page', 'overlay_opacity', 'opacity' );
}
$bg =  is_array( $bg ) ? $bg['url'] : $bg;
$bg = ! empty( $bg ) ? "background-image: url({$bg});{$bg_opacity}" : '';
?>
<!-- Page Hero -->
<section class="header-hero single-listing-header <?php echo empty( $bg ) ? esc_attr( 'no-image' ) : ''; ?>"
         style="<?php echo esc_attr( $bg_overlay ); ?>">
	<!-- Page Image -->
	<div class="header-hero-image" style="<?php echo esc_attr( $bg ); ?>">
	</div>
</section>
