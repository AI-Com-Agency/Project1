<?php
/**
 * Shortcodes How It Works / Tabs
 *
 * @author includes
 * @version 1.0.0
 * @package shortcodes/partials/how-it-works
 */
$atts['title_custom'] = true;
$tabs                 = vc_param_group_parse_atts( $atts['tab'] );
$tabs_2               = vc_param_group_parse_atts( $atts['tab_2'] );
?>
<?php $nav_style = isset( $atts['tab_nav_style'] ) && ! empty( $atts['tab_nav_style'] ) ? $atts['tab_nav_style'] : 'default'; ?>
<?php $title = isset( $atts['tab_title'] ) && ! empty( $atts['tab_title'] ) ? $atts['tab_title'] : ''; ?>
<?php $title_2 = isset( $atts['tab_title_2'] ) && ! empty( $atts['tab_title_2'] ) ? $atts['tab_title_2'] : ''; ?>

<!-- How It Works / Tabs -->
<div class="how-it-works-tabs">
	<div class="container<?php echo 'stretched' == $nav_style ? esc_attr( '-fluid' ) : ''; ?>">
		<div class="row">
			<div class="col-sm-12">
				<ul class="nav nav-tabs" id="hiw-tab-<?php echo esc_attr( $atts['unique_id'] ); ?>" role="tablist">
					<li class="nav-item">
						<a class="nav-link active" id="hiw-one-<?php echo esc_attr( $atts['unique_id'] ); ?>"
						   data-toggle="tab"
						   href="#hiw-one-tab-<?php echo esc_attr( $atts['unique_id'] ); ?>" role="tab"
						   aria-selected="true"><?php echo esc_html( $title ); ?></a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="hiw-two-<?php echo esc_attr( $atts['unique_id'] ); ?>" data-toggle="tab"
						   href="#hiw-two-tab-<?php echo esc_attr( $atts['unique_id'] ); ?>" role="tab"
						   aria-selected="false"><?php echo esc_html( $title_2 ); ?></a>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-9 col-lg-9-cst-wrapper">
				<div class="tab-content" id="hiw-tab-content-<?php echo esc_attr( $atts['unique_id'] ); ?>">
					<div class="tab-pane fade show active"
					     id="hiw-one-tab-<?php echo esc_attr( $atts['unique_id'] ); ?>" role="tabpanel">
						<div class="row">
							<?php foreach ( $tabs as $tab ) : ?>
								<?php $image = isset( $tab['tab_image'] ) && ! empty( $tab['tab_image'] ) ? wp_get_attachment_image_src( $tab['tab_image'], 'full' ) : ''; ?>
								<?php $title = isset( $tab['tab_heading'] ) && ! empty( $tab['tab_heading'] ) ? $tab['tab_heading'] : ''; ?>
								<?php $text = isset( $tab['tab_text'] ) && ! empty( $tab['tab_text'] ) ? $tab['tab_text'] : ''; ?>
								<div class="col-sm">
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
					<div class="tab-pane fade" id="hiw-two-tab-<?php echo esc_attr( $atts['unique_id'] ); ?>"
					     role="tabpanel">
						<div class="row">
							<?php foreach ( $tabs_2 as $tab ) : ?>
								<?php $image = isset( $tab['tab_image_2'] ) && ! empty( $tab['tab_image_2'] ) ? wp_get_attachment_image_src( $tab['tab_image_2'], 'full' ) : ''; ?>
								<?php $title = isset( $tab['tab_heading_2'] ) && ! empty( $tab['tab_heading_2'] ) ? $tab['tab_heading_2'] : ''; ?>
								<?php $text = isset( $tab['tab_text_2'] ) && ! empty( $tab['tab_text_2'] ) ? $tab['tab_text_2'] : ''; ?>
								<div class="col-sm">
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
		</div>
	</div>
</div>
