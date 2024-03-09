<?php
/**
 * Header template / header default
 *
 * @author pebas
 * @version 1.0.0
 * @package views/header/
 */
?>
<?php $option = get_option( 'pbs_option' ); ?>
<div class="container-fluid">
	<div class="row align-items-center <?php echo wp_is_mobile() ? esc_attr( 'flex-row-reverse' ) : ''; ?>">

		<nav class="navbar navbar-expand-xl w-100 align-items-center pbs-navbar">

			<?php if ( class_exists( 'Lisner_Core' ) && wp_is_mobile() && ! is_page_template( 'templates/tpl-home.php' ) ) : ?>
				<div class="search-on-mobiles hidden">
					<div class="menu-search-wrapper">
						<!-- Main Navigation -->
						<button class="mobile-search-call" type="button" data-toggle="search-bar"
						        data-target="#search-bar"
						        aria-label="<?php esc_html_e( 'Toggle Search', 'lisner' ); ?>">
							<i class="material-icons mf"><?php echo esc_html( 'search' ); ?></i>
						</button>
						<div class="menu-search-inner">
							<?php if ( lisner_helper::is_search_page() ) : ?>
								<?php lisner_search()->add_main_search(); ?>
							<?php else : ?>
								<?php lisner_pages()->add_main_search_to_navigation(); ?>
							<?php endif; ?>
						</div>
					</div>
				</div>
			<?php endif; ?>

			<!-- Logo -->
			<?php $default_logo = get_theme_mod( 'custom_logo' ); ?>
			<?php $logo = class_exists( 'Lisner_Core' ) && isset( $option['site-logo'] ) && ! empty( $option['site-logo'] ) ? wp_get_attachment_image_src( $option['site-logo'][0], 'full' ) : ( isset( $default_logo ) && ! empty( $default_logo ) ? wp_get_attachment_image_src( $default_logo, 'full' ) : '' ); ?>
			<a class="navbar-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php if ( class_exists( 'Lisner_Core' ) && is_page_template( 'templates/tpl-home.php' ) ) : ?>
					<?php do_action( 'pbs_home_logo' ); ?>
				<?php elseif ( ! empty( $logo ) ) : ?>
					<?php if ( is_array( $logo ) ) : ?>
						<img src="<?php echo esc_url( $logo[0] ); ?>"
						     alt="<?php echo esc_attr__( 'Logo', 'lisner' ); ?>">
					<?php else: ?>
						<img src="<?php echo esc_url( $logo ); ?>"
						     alt="<?php echo esc_attr__( 'Logo', 'lisner' ); ?>">
					<?php endif; ?>
				<?php else : ?>
					<p class="site-title"><?php echo esc_html( get_bloginfo( 'site_title' ) ); ?></p>
					<p class="site-description"><?php echo esc_html( get_bloginfo( 'description' ) ); ?></p>
				<?php endif; ?>
			</a>

			<?php if ( ! is_front_page() && ! is_page_template( 'templates/tpl-home.php' ) && ! wp_is_mobile() ) : ?>
				<div class="hidden-on-mobile">
					<?php do_action( 'pbs_nav' ); ?>
				</div>
			<?php endif; ?>

			<?php if ( has_nav_menu( 'top_menu' ) ) : ?>
			<!-- Main Navigation -->
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-menu"
			        aria-controls="main-menu" aria-expanded="false"
			        aria-label="<?php esc_html_e( 'Toggle Navigation', 'lisner' ); ?>">
				<i class="material-icons"><?php echo esc_html( 'menu' ); ?></i>
			</button>
			<?php
			wp_nav_menu( array(
				'theme_location'  => 'top_menu',
				'container'       => 'div',
				'container_id'    => 'main-menu',
				'container_class' => 'collapse navbar-collapse' . ( wp_is_mobile() ? esc_attr( ' mobile-nav' ) : '' ),
				'items_wrap'      => '<ul class="navbar-nav ml-auto">%3$s</ul>',
				'walker'          => new pbs_nav(),
			) );
			?>
		</nav>
		<?php endif; ?>

	</div>
</div>
