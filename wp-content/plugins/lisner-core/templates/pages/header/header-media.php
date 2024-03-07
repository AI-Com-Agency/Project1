<?php
/**
 * Template Name: Header media
 * Description: Page header media template
 *
 * @author pebas
 * @version 1.0.0
 * @package pages/header
 */

$page_id         = get_queried_object_id();
$bg              = rwmb_meta( 'page_bg_image', array( 'size' => 'lisner_hero' ), $page_id );
$bg_overlay      = lisner_helper::get_hero_image_overlay_style( $page_id, 'page', 'overlay', 'background-color' );
$bg_opacity      = lisner_helper::get_hero_image_overlay_style( $page_id, 'page', 'overlay_opacity', 'opacity' );
$fallback_bg     = lisner_get_option( 'fallback-bg-page', '' );
$opacity         = ! empty( $fallback_bg ) && empty( $bg ) ? esc_attr( 'opacity: .8;' ) : '';
$bg              = ! empty( $bg ) ? array_shift( $bg )['url'] : ( ! empty( $fallback_bg ) ? wp_get_attachment_image_src( $fallback_bg, 'lisner_hero' )[0] : get_stylesheet_directory_uri() . '/assets/images/bg_pattern.jpg' );
$bg              = ! empty( $bg ) ? "background-image: url({$bg});{$bg_opacity}{$opacity}" : '';
$is_last_option  = strpos( $bg, '/assets/images/bg_pattern.jpg' );
$title_alignment = get_post_meta( $page_id, 'page_title_alignment', true );
$title_alignment = isset( $title_alignment ) && ! empty( $title_alignment ) ? $title_alignment : 'center';
?>
<?php if ( ! is_front_page() ) : ?>
	<!-- Page Hero -->
	<section class="header-hero <?php echo $is_last_option ? esc_attr( 'last-option' ) : ''; ?>"
	         style="<?php echo esc_attr( $bg_overlay ); ?>">
		<!-- Page Image -->
		<div class="header-hero-image" style="<?php echo esc_attr( $bg ); ?>">
		</div>
		<?php $title = get_post_meta( $page_id, 'page_title', true ); ?>
		<?php $title = lisner_get_var( $title, get_the_title( $page_id ) ); ?>
		<?php $subtitle = get_post_meta( $page_id, 'page_subtitle', true ); ?>
		<?php $subtitle = lisner_get_var( $subtitle, null ); ?>
		<?php $title = str_replace( array( '[', ']' ), array( '<strong>', '</strong>' ), $title ); ?>
		<?php if ( ! is_page_template( 'templates/tpl-contact.php' ) ) : ?>
			<!-- Page Title -->
			<div class="header-hero-inner container <?php echo is_home() ? esc_attr( 'container-default' ) : esc_attr( 'container' ); ?>">
				<div class="row">
					<div class="col-sm-12">
						<div class="header-hero-title" style="text-align: <?php echo esc_attr( $title_alignment ); ?>">
							<?php if ( is_archive() ) : ?>
								<h1 class="page-hero-title"><?php the_archive_title(); ?></h1>
							<?php else: ?>
								<h1 class="page-hero-title"><?php echo wp_kses_post( $title ); ?></h1>
							<?php endif; ?>
							<?php if ( $subtitle ) : ?>
								<?php $subtitle = str_replace( array( '[', ']' ), array(
									'<strong>',
									'</strong>'
								), $subtitle ); ?>
								<p class="page-hero-subtitle"><?php echo wp_kses_post( $subtitle ); ?></p>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</section>
<?php endif; ?>
