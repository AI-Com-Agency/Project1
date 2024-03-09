<?php
/**
 * Template Name: Single Post / Page Breadcrumbs
 * Description: Partial content for single post content
 *
 * @author pebas
 * @version 1.0.0
 * @package views/posts/single
 */

global $post;
?>
<div class="single-listing-breadcrumbs">
	<nav aria-label="breadcrumb" class="single-listing-breadcrumb breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item home-cat"><a
						href="<?php echo esc_url( home_url( '/' ) ); ?>"><i
							class="material-icons mf"><?php echo esc_html( 'home' ) ?></i><?php esc_html_e( 'Home', 'lisner' ); ?>
				</a></li>
			<li class="breadcrumb-item"><span><?php echo esc_html( pbs_global::$is_woocommerce_installed && is_shop() ? woocommerce_page_title() : ( isset( $post->post_title ) ? $post->post_title : '' ) ); ?>
                </span></li>
		</ol>
	</nav>
</div>
