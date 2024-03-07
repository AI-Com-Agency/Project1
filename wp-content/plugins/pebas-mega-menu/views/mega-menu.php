<?php
/**
 * Mega Menu Container Part
 *
 * @author pebas
 * @version 1.0.0
 * @package views/
 *
 * @var $item
 */
?>
<?php $menu_id = $item->object_id; ?>
<?php $menu_options = rwmb_meta( 'mega_menu', '', $menu_id ); ?>
<div class="mega-menu-container">
	<?php if ( $menu_options ) : ?>
		<div class="mega-menu-nav">
			<div class="container">
				<div class="row">
					<div class="col-3">
						<ul class="nav nav-pills" id="pills-mega-menu-tab-<?php echo esc_attr( $item->ID ); ?>"
						    role="tablist">
							<?php $nav_count = 1; ?>
							<?php foreach ( $menu_options as $menu_option ) : ?>
								<?php $nav_title = isset( $menu_option['mega_menu_title'] ) && ! empty( $menu_option['mega_menu_title'] ) ? $menu_option['mega_menu_title'] : ''; ?>
								<?php $nav_title = ! empty( $menu_option['mega_menu_title'] ) ? $nav_title : ( isset( $menu_option['mega_menu_post_title'] ) && ! empty( $menu_option['mega_menu_post_title'] ) ? $menu_option['mega_menu_post_title'] : sprintf( esc_html__( 'News %s', 'pebas-mega-menu' ), $nav_count ) ); ?>
								<?php $atts['link'] = isset( $menu_option['mega_menu_link'] ) && ! empty( $menu_option['mega_menu_link'] ) ? $menu_option['mega_menu_link'] : ''; ?>
								<?php $label = isset( $menu_option['mega_menu_promo'] ) && ! empty( $menu_option['mega_menu_promo'] ) ? $menu_option['mega_menu_promo'] : ''; ?>
								<?php $label = ! empty( $label ) ? '<small>' . $label . '</small>' : ''; ?>
								<li class="nav-item">
									<a class="nav-link <?php echo 1 == $nav_count ? esc_attr( 'active' ) : ''; ?>"
									   id="pills-nav-tab-mm-<?php echo esc_attr( $item->ID . '-' . $nav_count ); ?>"
									   data-toggle="tab"
									   data-href="<?php echo esc_url( $atts['link'] ); ?>"
									   href="#pills-tab-mm-<?php echo esc_attr( $item->ID . '-' . $nav_count ); ?>"
									   role="tab"
									   aria-selected="true"><?php echo esc_html( $nav_title ); ?><?php echo wp_kses_post( $label ); ?></a>
								</li>
								<?php $nav_count ++; ?>
							<?php endforeach; ?>
						</ul>
					</div>
					<div class="col-9 d-flex align-items-center">
						<div class="tab-content mega-menu-item"
						     id="pills-mega-menu-<?php echo esc_attr( rand() ); ?>">
							<?php $menu_count = 1; ?>
							<?php foreach ( $menu_options as $menu_option ) : ?>
								<?php $type = $menu_option['mega_menu_type']; ?>
								<div class="tab-pane <?php echo 1 == $menu_count ? esc_attr( 'show active' ) : ''; ?>"
								     id="pills-tab-mm-<?php echo esc_attr( $item->ID . '-' . $menu_count ); ?>"
								     role="tabpanel">

									<!-- MegaMenu Slider -->
									<?php if ( 'custom' == $type ) : // if custom mega menu ?>
										<?php $title = isset( $menu_option['mega_menu_title'] ) && ! empty( $menu_option['mega_menu_title'] ) ? $menu_option['mega_menu_title'] : esc_html__( 'Mega Menu', 'pebas-mega-menu' ); ?>
										<?php $atts['type'] = isset( $menu_option['mega_menu_background_type'] ) && ! empty( $menu_option['mega_menu_background_type'] ) ? $menu_option['mega_menu_background_type'] : ''; ?>
										<?php $image = isset( $menu_option['mega_menu_background_image'] ) && ! empty( $menu_option['mega_menu_background_image'] ) ? $menu_option['mega_menu_background_image'] : ''; ?>
										<?php $video = isset( $menu_option['mega_menu_background_video'] ) && ! empty( $menu_option['mega_menu_background_video'] ) ? $menu_option['mega_menu_background_video'] : ''; ?>
										<?php $atts['link'] = isset( $menu_option['mega_menu_link'] ) && ! empty( $menu_option['mega_menu_link'] ) ? $menu_option['mega_menu_link'] : ''; ?>
										<?php if ( 'image' == $atts['type'] ) : ?>
											<?php $atts['image'] = $image; ?>
											<?php require pebas_mega_menu_helper::get_view( 'mega-menu-image', $atts ); ?>
										<?php elseif ( 'video' == $atts['type'] )  : ?>
											<?php $image = isset( $menu_option['mega_menu_video_background_image'] ) && ! empty( $menu_option['mega_menu_video_background_image'] ) ? $menu_option['mega_menu_video_background_image'] : ''; ?>
											<?php $atts['image'] = $image; ?>
											<?php $atts['video_link'] = $video; ?>
											<?php require pebas_mega_menu_helper::get_view( 'mega-menu-video', $atts ); ?>
										<?php endif; ?>
									<?php endif; ?>

									<?php $menu_count ++; ?>
								</div>
								<?php wp_reset_postdata(); ?>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>
</div>
