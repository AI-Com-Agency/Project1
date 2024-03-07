<?php
/**
 * Shortcodes Partials / How it works 3
 *
 * @author includes
 * @version 1.0.0
 * @package shortcodes/partials/
 */
$atts['title_custom'] = true;
$tab                  = vc_param_group_parse_atts( $atts['tab'] );
$tab_2                = vc_param_group_parse_atts( $atts['tab_2'] );
?>
<section class="how-it-works how-it-works-template-3">

	<?php if ( ! empty( $tab[0] ) && ! empty( $tab_2[0] ) ) : ?>
		<?php include lisner_helper::get_template_part( "tabs", 'shortcodes/how-it-works', $atts ); ?>
	<?php elseif ( ! empty( $tab[0] ) || ! empty( $tab_2[0] ) ) : ?>
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-lg-9 col-lg-9-cst-wrapper">

					<div class="row">
						<?php $tabs = ! empty( $tab[0] ) ? $tab : $tab_2; ?>
						<?php foreach ( $tabs as $tab ) : ?>
							<?php $image = isset( $tab['tab_image'] ) && ! empty( $tab['tab_image'] ) ? wp_get_attachment_image_src( $tab['tab_image'], 'full' ) : ''; ?>
							<?php $title = isset( $tab['tab_heading'] ) && ! empty( $tab['tab_heading'] ) ? $tab['tab_heading'] : ''; ?>
							<?php $text = isset( $tab['tab_text'] ) && ! empty( $tab['tab_text'] ) ? $tab['tab_text'] : ''; ?>
							<div class="col">
								<!-- How It Works / Element -->
								<div class="how-it-works-element text-center">
									<figure class="lisner-how-it-works-figure">
										<img src="<?php echo esc_url( $image[0] ); ?>" alt="">
									</figure>
									<?php if ( ! empty( $title ) ) : ?>
										<div class="how-it-works-title">
											<h4><?php echo esc_html( $title ); ?></h4>
										</div>
									<?php endif; ?>
									<?php if ( ! empty( $text ) ) : ?>
										<div class="how-it-works-text">
											<h6><?php echo esc_html( $text ); ?></h6>
										</div>
									<?php endif; ?>
								</div>
							</div>
						<?php endforeach; ?>
					</div>

				</div>
			</div>
		</div>
	<?php endif; ?>
</section>
