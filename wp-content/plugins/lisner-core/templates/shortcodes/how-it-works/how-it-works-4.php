<?php
/**
 * Shortcodes Partials / How it works 4
 *
 * @author includes
 * @version 1.0.0
 * @package shortcodes/partials/
 */
// tab 1
$image = lisner_get_var( $atts['tab_bg_image'], '' );
// tab 2
$image_2 = lisner_get_var( $atts['tab_bg_image_2'], '' );
$style   = isset( $atts['template_4_style'] ) && ! empty( $atts['template_4_style'] ) ? $atts['template_4_style'] : '1';
?>
<!-- How It Works / Tabs -->
<section class="how-it-works how-it-works-template-4 how-it-works-template-4-style-<?php echo esc_attr( $style ); ?>">
	<?php if ( ! empty( $image ) && ! empty( $image_2 ) ) : ?>
		<div class="tab-wrapper">
			<?php $tab['title'] = lisner_get_var( $atts['tab_alt_title'], '' ); ?>
			<?php $tab['subtitle'] = lisner_get_var( $atts['tab_alt_subtitle'], '' ); ?>
			<?php $tab['link'] = lisner_get_var( $atts['tab_alt_link'], '' ); ?>
			<?php $image = wp_get_attachment_image_src( $image, 'full' ); ?>
			<!-- How It Works / Tab -->
			<a href="<?php echo esc_url( $tab['link'] ); ?>">
				<div class="tab-part" style="background-image: url(<?php echo esc_url( $image[0] ); ?>);">
					<?php $title = str_replace( array( '.-', '-.' ), array( '<b>', '</b>' ), $tab['title'] ); ?>
					<?php if ( '1' == $style ) : ?>
						<p class="tab-alt-subtitle"><?php echo esc_html( $tab['subtitle'] ); ?></p>
						<h1 class="tab-alt-title"><?php echo wp_kses_post( $title ); ?></h1>
					<?php else: ?>
						<h1 class="tab-alt-title"><?php echo wp_kses_post( $title ); ?></h1>
						<p class="tab-alt-subtitle"><?php echo esc_html( $tab['subtitle'] ); ?></p>
					<?php endif; ?>
				</div>
			</a>

			<span class="tab-divider"><?php esc_html_e( 'or', 'lisner-core' ); ?></span>

			<?php $tab_2['title'] = lisner_get_var( $atts['tab_alt_title_2'], '' ); ?>
			<?php $tab_2['subtitle'] = lisner_get_var( $atts['tab_alt_subtitle_2'], '' ); ?>
			<?php $tab_2['link'] = lisner_get_var( $atts['tab_alt_link_2'], '' ); ?>
			<?php $image_2 = wp_get_attachment_image_src( $image_2, 'full' ); ?>
			<!-- How It Works / Tab -->
			<a href="<?php echo esc_url( $tab_2['link'] ); ?>">
				<div class="tab-part" style="background-image: url(<?php echo esc_url( $image_2[0] ); ?>);">
					<?php $title = str_replace( array( '.-', '-.' ), array( '<b>', '</b>' ), $tab_2['title'] ); ?>
					<?php if ( '1' == $style ) : ?>
						<p class="tab-alt-subtitle"><?php echo esc_html( $tab_2['subtitle'] ); ?></p>
						<h1 class="tab-alt-title"><?php echo wp_kses_post( $title ); ?></h1>
					<?php else: ?>
						<h1 class="tab-alt-title"><?php echo wp_kses_post( $title ); ?></h1>
						<p class="tab-alt-subtitle"><?php echo esc_html( $tab_2['subtitle'] ); ?></p>
					<?php endif; ?>
				</div>
			</a>
		</div>

	<?php elseif ( ! empty( $image ) || ! empty( $image_2 ) ) : ?>
		<?php $tab['title'] = lisner_get_var( $atts['tab_alt_title'], lisner_get_var( $atts['tab_alt_title_2'], '' ) ); ?>
		<?php $tab['subtitle'] = lisner_get_var( $atts['tab_alt_subtitle'], lisner_get_var( $atts['tab_alt_subtitle_2'], '' ) ); ?>
		<?php $tab['link'] = lisner_get_var( $atts['tab_alt_link'], lisner_get_var( $atts['tab_alt_link_2'], '' ) ); ?>
		<?php $tab['image'] = lisner_get_var( $atts['tab_bg_image'], lisner_get_var( $atts['tab_bg_image_2'], '' ) ); ?>
		<?php $image = wp_get_attachment_image_src( $tab['image'], 'full' ); ?>
		<a href="<?php echo esc_url( $tab['link'] ); ?>">
			<div class="tab-full" style="background-image: url(<?php echo esc_url( $image[0] ); ?>);">
				<?php $title = str_replace( array( '.-', '-.' ), array( '<b>', '</b>' ), $tab['title'] ); ?>
				<?php if ( '1' == $style ) : ?>
					<p class="tab-alt-subtitle"><?php echo esc_html( $tab['subtitle'] ); ?></p>
					<h1 class="tab-alt-title"><?php echo wp_kses_post( $title ); ?></h1>
				<?php else: ?>
					<h1 class="tab-alt-title"><?php echo wp_kses_post( $title ); ?></h1>
					<p class="tab-alt-subtitle"><?php echo esc_html( $tab['subtitle'] ); ?></p>
				<?php endif; ?>
			</div>
		</a>
	<?php endif; ?>
</section>
