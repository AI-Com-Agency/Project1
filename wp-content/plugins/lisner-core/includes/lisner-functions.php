<?php
/**
 * Lisner core plugin functions
 *
 * @author pebas
 * @version 1.0.0
 */

if ( ! function_exists( 'lisner_default_options' ) ) {
	/**
	 * Get default theme options
	 *
	 * @param $id
	 *
	 * @return mixed|string
	 */
	function lisner_default_options( $id ) {
		$defaults = array(
			'site-direction'         => 'ltr',
			'auth-generate-username' => 'no',
			'auth-generate-password' => 'no',
			'units-clock'            => '24',
			'units-distance'         => 'ki',
			'listings-map-zoom'      => '18',
		);

		if ( isset( $defaults[ $id ] ) ) {
			return $defaults[ $id ];
		} else {

			return '';
		}
	}
}

if ( ! function_exists( 'lisner_days_of_week' ) ) {
	/**
	 * Get weekdays on locale language
	 * -------------------------------
	 *
	 * @return array
	 */
	function lisner_days_of_week() {
		global $wp_locale;
		$start_of_week = get_option( 'start_of_week' );
		$days          = $wp_locale->weekday;
		$new_days      = array_merge( array_slice( $days, $start_of_week ), array_slice( $days, 0, $start_of_week ) );
		$days_array    = array();
		foreach ( $new_days as $weekday ) {
			$days_array[ mb_strtolower( $weekday ) ] = esc_html__( mb_substr( $weekday, 0, 3 ), 'lisner-core' );
		};

		return $days_array;
	}
}

if ( ! function_exists( 'lisner_days_of_week_normalize' ) ) {
	/**
	 * Get weekdays on english language so we can normalize
	 * submission form
	 * ----------------------------------------------------
	 *
	 * @return array
	 */
	function lisner_days_of_week_normalize() {
		$days          = [ 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday' ];
		$start_of_week = get_option( 'start_of_week' );
		$new_days      = array_merge( array_slice( $days, $start_of_week ), array_slice( $days, 0, $start_of_week ) );

		return $new_days;
	}
}

if ( ! function_exists( 'lisner_get_var' ) ) {
	/**
	 * Get data if set, otherwise return a default value or null. Prevents notices when data is not set.
	 *
	 * @param  mixed  $var  Variable.
	 * @param  string  $default  Default value.
	 *
	 * @return mixed
	 * @since  3.2.0
	 *
	 */
	function lisner_get_var( &$var, $default = null ) {
		return isset( $var ) && ! empty( $var ) ? $var : $default;
	}
}

if ( ! function_exists( 'lisner_get_option' ) ) {
	/**
	 * Get theme option
	 *
	 * @param $id
	 * @param  null  $default
	 *
	 * @return mixed|void
	 */
	function lisner_get_option( $id, $default = null ) {
		$option = get_option( 'pbs_option' );
		if ( isset( $option[ $id ] ) || isset( $option[ $default ] ) ) {
			$value = isset( $option[ $id ] ) ? $option[ $id ] : $option[ $default ];
			if ( isset( $value ) ) {
				return apply_filters( 'lisner_get_option', $value, $id );
			} else {
				return apply_filters( 'lisner_get_option', '', $id );
			}
		} else {
			return apply_filters( 'lisner_get_option', lisner_default_options( $id ), $id );
		}
	}
}

if ( ! function_exists( 'lisner_show_to_member' ) ) {
	/**
	 * Show field to members only
	 *
	 * @param $option
	 *
	 * @return bool
	 */
	function lisner_show_to_member( $option ) {
		if ( 0 == $option ) {
			if ( is_user_logged_in() ) {
				return true;
			}

			return false;
		}

		return true;
	}
}

// Override default wp job manager new user notification
function wp_job_manager_notify_new_user( $user_id, $password ) {
	global $wp_version;
	$user_data = get_user_by( 'ID', $user_id );

	$key = get_password_reset_key( $user_data );

	//todo implement real email system
	$permalink = add_query_arg( array(
		'key' => $key,
		'id'  => $user_data->ID
	), home_url( '/' ) );
	$link      = '<a class="link" href="' . esc_url( $permalink ) . '">' . __( 'Click here to reset your password',
			'lisner-core' ) . '</a>';
	$to        = $user_data->user_email;
	$subject   = sprintf( esc_html__( 'You requested password reset for site: %s', 'lisner-core' ),
		get_option( 'blogname' ) );
	$body      = sprintf( esc_html__( 'Your new password is on link %s', 'lisner-core' ), $link );
	$headers   = array( 'Content-Type: text/html; charset=UTF-8' );

	wp_mail( $to, $subject, $body, $headers );
}

add_action( 'pbs_footer_after', 'lisner_copyrights' );
add_action( 'lisner_pagination_after', 'lisner_copyrights' );
add_action( 'lisner_account_after', 'lisner_copyrights' );

if ( ! function_exists( 'lisner_copyrights' ) ) {
	/**
	 * Include theme copyrights
	 */
	function lisner_copyrights() {
		$html = '';
		$copy = lisner_get_option( 'copyrights-text' );
		if ( isset( $copy ) && ! empty( $copy ) ) {
			$html = '<footer class="footer-copyrights">' . $copy . '</footer>';
		}

		echo $html;
	}
}

if ( ! function_exists( 'lisner_fix_social_login_facebook_permissions' ) ) {
	add_filter( 'wsl_hook_alter_provider_scope', 'lisner_fix_social_login_facebook_permissions', 10, 2 );
	/**
	 * @param $provider_scope
	 * @param $provider
	 *
	 * @return mixed
	 */
	function lisner_fix_social_login_facebook_permissions( $provider_scope, $provider ) {
		if ( $provider === 'Facebook' ) {
			$provider_scope_new = str_replace( ', user_friends', '', $provider_scope );

			return $provider_scope_new;
		}

		return $provider_scope;
	}
}

if ( ! function_exists( 'lisner_format_website_link' ) ) {
	/**
	 * Format website link to include domain only
	 * ------------------------------------------
	 *
	 * @param $website
	 *
	 * @return mixed|void
	 */
	function lisner_format_website_link( $website ) {
		$website = trim( $website, '/' );

		if ( ! preg_match( '#^http(s)?://#', $website ) ) {
			$website = 'http://' . $website;
		}

		$urlParts = parse_url( $website );

		$domain = preg_replace( '/^www\./', '', $urlParts['host'] );

		return apply_filters( 'lisner__format_website_link', $domain );
	}
}

add_action( 'wp_footer', 'lisner_photo_gallery' );
if ( ! function_exists( 'lisner_photo_gallery' ) ) {
	/**
	 * Include single listing photo gallery template
	 */
	function lisner_photo_gallery() {
		if ( is_singular( 'job_listing' ) || is_page( get_option( 'job_manager_submit_job_form_page_id' ) ) ) :
			?>
            <!-- Root element of PhotoSwipe. Must have class pswp. -->
            <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">

                <!-- Background of PhotoSwipe.
					 It's a separate element as animating opacity is faster than rgba(). -->
                <div class="pswp__bg"></div>

                <!-- Slides wrapper with overflow:hidden. -->
                <div class="pswp__scroll-wrap">
                    <!-- Container that holds slides.
						PhotoSwipe keeps only 3 of them in the DOM to save memory.
						Don't modify these 3 pswp__item elements, data is added later on. -->
                    <div class="pswp__container">
                        <div class="pswp__item"></div>
                        <div class="pswp__item"></div>
                        <div class="pswp__item"></div>
                    </div>
                    <!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
                    <div class="pswp__ui pswp__ui--hidden">
                        <div class="pswp__top-bar">
                            <!--  Controls are self-explanatory. Order can be changed. -->
                            <div class="pswp__counter"></div>
                            <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
                            <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
                            <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
                            <!-- Preloader demo http://codepen.io/dimsemenov/pen/yyBWoR -->
                            <!-- element will get class pswp__preloader--active when preloader is running -->
                            <div class="pswp__preloader">
                                <div class="pswp__preloader__icn">
                                    <div class="pswp__preloader__cut">
                                        <div class="pswp__preloader__donut"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
                        </button>
                        <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
                        </button>
                        <div class="pswp__caption">
                            <div class="pswp__caption__center"></div>
                        </div>
                    </div>
                </div>

            </div>
		<?php
		endif;
	}
}

add_action( 'pbs_single_post_share', 'lisner_single_post_share' );
if ( ! function_exists( 'lisner_single_post_share' ) ) {
	/**
	 * Add views, likes and share to single post functionality
	 */
	function lisner_single_post_share() {
		ob_start();
		lisner_listings::set_listing_views_count( get_the_ID() );
		?>
        <div class="col-sm-6 text-right">
			<?php include lisner_helper::get_template_part( 'single-meta-actions', 'listing/single/content' ); ?>
        </div>
		<?php
		$share = ob_get_clean();

		echo $share;
	}
}

if ( ! function_exists( 'lisner_maintenance_mode' ) ) {
	add_action( 'template_redirect', 'lisner_maintenance_mode' );
	/**
	 * Include maintenance mode
	 */
	function lisner_maintenance_mode() {
		global $pagenow;
		$option           = get_option( 'pbs_option' );
		$maintenance_mode = isset( $option['maintenance-mode'] ) ? $option['maintenance-mode'] : 'disabled';
		if ( 'enabled' == $maintenance_mode && $pagenow !== 'wp-login.php' && ! current_user_can( 'manage_options' ) && ! is_admin() ) {
			if ( file_exists( LISNER_DIR . 'templates/pages/maintenance.php' ) ) {
				require_once( LISNER_DIR . 'templates/pages/maintenance.php' );
			}
			die();
		}
	}
}

if ( ! function_exists( 'lisner_taxonomy_link' ) ) {
	/**
	 * Get link of the taxonomy that admin specified
	 * in the theme options
	 *
	 * @param $id
	 * @param $search_taxonomy
	 *
	 * @return string|WP_Error
	 */
	function lisner_taxonomy_link( $id, $search_taxonomy = 'job_listing_category' ) {
		return lisner_listings()->get_taxonomy_link( $id, $search_taxonomy );
	}
}

if ( ! function_exists( 'lisner_get_report_modal' ) ) {
	add_action( 'wp_footer', 'lisner_get_report_modal' );
	/**
	 * Include report modal in the footer of the theme
	 */
	function lisner_get_report_modal() {
		include lisner_helper::get_template_part( 'modal-report', 'modals' );
	}
}

if ( ! function_exists( 'lisner_listing_box_style' ) ) :
	/**
	 * Get appropriate listing style box
	 * for search page template
	 */
	function lisner_listing_box_style( $post_id, $option ) {
		$style         = 'grid';
		$listing_style = get_post_meta( $post_id, $option, true );
		switch ( $listing_style ) {
			case 1:
				$style = 'grid';
				break;
			case 2:
				$style = 'list';
				break;
			case 3:
				$style = 'small';
				break;
			case 4:
			case 5:
				$style = 'promo';
				break;
			default:
				$style = 'grid';
		}

		return $style;
	}
endif;

if ( ! function_exists( 'lisner_hex2rgba' ) ) :
	function lisner_hex2rgba( $color, $opacity ) {
		return pbs_helpers::hex2rgba( $color, $opacity );
	}
endif;

if ( ! function_exists( 'lisner_home_menu_color' ) ) :
	/**
	 * Add custom color options for homepage menu
	 */
	add_action( 'wp_head', 'lisner_home_menu_color' );
	function lisner_home_menu_color() {
		$page_id       = get_the_ID();
		$css           = '';
		$menu_active   = get_post_meta( $page_id, 'home-menu-active', true );
		$menu_bg       = get_post_meta( $page_id, 'color-menu-bg', true );
		$menu_dropdown = get_post_meta( $page_id, 'color-menu-dropdown-hover-bg', true );

		$hex2rgba = 'lisner_hex2rgba';

		if ( $menu_active ) {
			$css = <<<CSS
			.header {
				background-color: {$menu_bg} !important;
			}
CSS;
			if ( isset( $menu_dropdown ) && ! empty( $menu_dropdown ) ) {
				$css .= <<<CSS
				.header .pbs-navbar.navbar li > .sub-menu li:hover {
					background-color:  {$hex2rgba( $menu_dropdown, 0.1 )} !important;
    		}
CSS;
			}
		}

		if ( ! empty( $css ) ) {
			echo '<style>' . $css . '</style>';
		}
	}
endif;

if ( ! function_exists( 'lisner_home_hero_color' ) ) :
	/**
	 * Add custom options for hero page
	 */
	add_action( 'wp_head', 'lisner_home_hero_color' );
	function lisner_home_hero_color() {
		$page_id    = get_the_ID();
		$css        = '';
		$font_color = get_post_meta( $page_id, 'home_bg_font_color', true );

		if ( isset( $font_color ) && ! empty( $font_color ) ) {
			$css = <<<CSS
		.hero-title strong,
		.hero-title h1,
		.hero-title h2,
		.hero-title h3,
		.hero-title h4,
		.hero-title h5,
		.hero-title h6,
		.hero-title p,
	 	.hero-title a,
	 	.hero-category-style-2 .hero-featured-taxonomies a:hover,
		.hero-category-style-3 .hero-featured-taxonomies a:hover {
			color: {$font_color};
			opacity: 1;
		}
		.hero-category-style-2 .hero-featured-taxonomies a,
		.hero-category-style-3 .hero-featured-taxonomies a {
			color: {$font_color};
			opacity: .8;
		}
CSS;
			if ( ! empty( $css ) ) {
				echo '<style>' . $css . '</style>';
			}
		}

	}
endif;

if ( ! function_exists( 'lisner_home_colors' ) ) :
	/**
	 * Add custom color options for demo display
	 */
	add_action( 'wp_head', 'lisner_home_colors' );
	function lisner_home_colors() {
		$page_id        = get_the_ID();
		$css            = '';
		$option         = array();
		$primary        = get_post_meta( $page_id, 'color-primary', true );
		$primary_font   = get_post_meta( $page_id, 'color-primary-font', true );
		$secondary      = get_post_meta( $page_id, 'color-secondary', true );
		$secondary_font = get_post_meta( $page_id, 'color-secondary-font', true );
		if ( isset( $primary ) && ! empty( $primary ) ) {
			$option['color-primary'] = $primary;
		}
		if ( isset( $primary_font ) && ! empty( $primary_font ) ) {
			$option['color-primary-font'] = $primary_font;
		}
		if ( isset( $secondary ) && ! empty( $secondary ) ) {
			$option['color-secondary'] = $secondary;
		}
		if ( isset( $secondary_font ) && ! empty( $secondary_font ) ) {
			$option['color-secondary-font'] = $secondary_font;
		}

		if ( isset( $option ) && ! empty( $option ) ) {
			ob_start();
			include lisner_helper::get_template_part( 'theme-colors', '../includes', $option );
			$css = ob_get_clean();
			echo '<style type="text/css">' . $css . '</style>';
		}

	}
endif;

if ( ! function_exists( 'lisner_theme_fonts' ) ) :
	/**
	 * Add custom color options for demo display
	 */
	add_action( 'wp_head', 'lisner_theme_fonts' );
	function lisner_theme_fonts() {
		$option  = get_option( 'pbs_option' );
		$page_id = get_the_ID();
		$css     = '';
		$font    = array();
		if ( isset( $option['theme-font'] ) && ! empty( $option['theme-font'] ) && 'Assistant' != $option['theme-font'] ) {
			$font = str_replace( '+', ' ', $option['theme-font'] );
			$css  .= <<<CSS
			body {
                font-family: "{$font}";
			}
CSS;
			echo '<style>' . $css . '</style>';
		}

	}
endif;

if ( ! function_exists( 'lisner_home_logo' ) ) :
	add_action( 'pbs_home_logo', 'lisner_home_logo' );
	/**
	 * Option to change logo for this homepage template
	 */
	function lisner_home_logo() {
		$option  = get_option( 'pbs_option' );
		$page_id = get_the_ID();
		$logo    = get_post_meta( $page_id, 'site-logo', true );
		$logo    = isset( $logo ) && ! empty( $logo ) ? $logo : ( isset( $option['site-logo'] ) ? $option['site-logo'] : '' );
		?>
		<?php if ( isset( $logo ) && ! empty( $logo ) ) : ?>
            <img src="<?php echo esc_url( wp_get_attachment_image_url( is_array( $logo ) ? $logo[0] : $logo,
				'full' ) ); ?>"
                 alt="<?php echo esc_attr( 'Logo', 'lisner-core' ); ?>">
		<?php else : ?>
            <p class="site-title"><?php echo esc_html( get_bloginfo( 'site_title' ) ); ?></p>
            <p class="site-description"><?php echo esc_html( get_bloginfo( 'description' ) ); ?></p>
		<?php endif; ?>
		<?php
	}
endif;

if ( ! function_exists( 'lisner_use_fontawesome_icons' ) ) :
	add_action( 'init', 'lisner_call_fontawesome_social_login_filter' );
	/**
	 * Use fontawesome icons for social login providers
	 *
	 * @param $provider_id
	 * @param $provider_name
	 * @param $authenticate_url
	 */
	function lisner_use_fontawesome_icons( $provider_id, $provider_name, $authenticate_url ) {
		$id = '';
		switch ( $provider_id ) {
			case 'Facebook':
				$id = $provider_id . '-f';
				break;
			case 'Google':
				$id = $provider_id . '-plus-g';
				break;
			default:
				$id = $provider_id;
		}
		?>
        <a rel="nofollow"
           href="<?php echo $authenticate_url; ?>"
           data-provider="<?php echo $provider_id ?>"
           class="wp-social-login-provider wp-social-login-provider-<?php echo strtolower( $provider_id ); ?>">
		<span class="provider-icon"><i
                    class="fab fa-<?php echo strtolower( $id ); ?> fa-fw"></i></span><span><?php printf( esc_html__( 'Connect with %s' ),
					$provider_name ); ?></span>
        </a>
		<?php
	}
endif;

if ( ! function_exists( 'lisner_call_fontawesome_social_login_filter' ) ) :
	/**
	 * Call social login filter to include fontawesome icons
	 */
	function lisner_call_fontawesome_social_login_filter() {
		if ( ! lisner_is_wplogin() ) :
			add_filter( 'wsl_render_auth_widget_alter_provider_icon_markup', 'lisner_use_fontawesome_icons', 10, 3 );
		endif;
	}
endif;

if ( ! function_exists( 'lisner_is_wplogin' ) ) :
	/**
	 * Custom function to check whether we are on default
	 * WordPress authentication page
	 */
	function lisner_is_wplogin() {
		$ABSPATH_MY = str_replace( array( '\\', '/' ), DIRECTORY_SEPARATOR, ABSPATH );

		return ( ( in_array( $ABSPATH_MY . 'wp-login.php',
					get_included_files() ) || in_array( $ABSPATH_MY . 'wp-register.php',
					get_included_files() ) ) || ( isset( $_GLOBALS['pagenow'] ) && $GLOBALS['pagenow'] === 'wp-login.php' ) || $_SERVER['PHP_SELF'] == '/wp-login.php' );
	}

endif;

if ( ! function_exists( 'lisner_remove_script_version' ) ) :
	add_filter( 'script_loader_src', 'lisner_remove_script_version', 15, 1 );
	add_filter( 'style_loader_src', 'lisner_remove_script_version', 15, 1 );
	/**
	 * Remove scripts version to import page speed
	 *
	 * @param $src
	 *
	 * @return mixed
	 */
	function lisner_remove_script_version( $src ) {
		$parts = explode( '?', $src );
		if ( strstr( $parts[0], 'google' ) ) {
			return $src;
		}

		return $parts[0];
	}
endif;

add_action( 'pbs_custom_container_width', 'lisner_custom_container_width' );
function lisner_custom_container_width( $page_id ) {
	$container_width = get_post_meta( $page_id, 'page_container_width', true );
	if ( isset( $container_width ) && ! empty( $container_width ) && '710' != $container_width ) {
		echo '<style type="text/css">
	@media (min-width: 1024px) {
		.container-custom {
			max-width: ' . $container_width . 'px;
		}
	}
</style>';
	}

}

function generated_rewrite_rules() {
	$permalinks        = WP_Job_Manager_Post_Types::get_permalink_structure();
	$lisner_permalinks = lisner_listings_post_type::get_permalink_structure();
	add_rewrite_rule(
		"^{$permalinks['job_base']}/(.*)/(.*)/(.*)/?$",
		'index.php?post_type=job_listing&name=$matches[2]&name=$matches[3]',
		'top'
	);
}

//add_action( 'init', 'generated_rewrite_rules' );
function lisner_relist() {
	$option = get_option( 'pbs_option' );
	if ( ! isset( $option['is-demo'] ) || $option['is-demo'] != 1 ) {
		return;
	}
	$new_date = date( 'Y-m-d', strtotime( '+30 days', current_time( 'timestamp' ) ) );
	$listings = get_posts( array(
		'post_type'      => 'job_listing',
		'posts_per_page' => - 1,
		'meta_query'     => [
			[
				'key'     => '_job_expires',
				'value'   => date( 'Y-m-d', strtotime( '+3 days', current_time( 'timestamp' ) ) ),
				'compare' => '<',
			]
		],
	) );

	if ( ! empty( $listings ) && 0 != count( $listings ) ) {
		foreach ( $listings as $listing ) {
			update_post_meta( $listing->ID, '_job_expires', $new_date );
			update_post_meta( $listing->ID, '_job_duration', $new_date );

		}
	}
	$coupons = get_posts( array(
		'post_type'      => 'listing_coupon',
		'posts_per_page' => - 1,
		'meta_query'     => array(
			[
				'key'     => '_coupon_end',
				'value'   => date( 'Y-m-d H:i', strtotime( '+3 days', current_time( 'timestamp' ) ) ),
				'compare' => '<',
			]
		),
		'fields'         => 'ids',
	) );

	if ( ! empty( $coupons ) ) {
		foreach ( $coupons as $coupon ) {
			update_post_meta( $coupon, '_coupon_end',
				date( 'Y-m-d H:i', strtotime( '+30 days', current_time( 'timestamp' ) ) ) );
			update_post_meta( $coupon, '_coupon_status', 'active' );
		}
	}

	$events = get_posts( array(
		'post_type'      => 'listing_event',
		'posts_per_page' => - 1,
		'meta_query'     => array(
			[
				'key'     => '_event_start',
				'value'   => date( 'Y-m-d H:i', strtotime( '+3 days', current_time( 'timestamp' ) ) ),
				'compare' => '<',
			]
		),
		'fields'         => 'ids',
	) );

	if ( ! empty( $events ) ) {
		foreach ( $events as $event ) {
			update_post_meta( $event, '_event_start',
				date( 'Y-m-d H:i', strtotime( '+30 days', current_time( 'timestamp' ) ) ) );
			update_post_meta( $event, '_event_status', 'upcoming' );
		}
	}
}

add_action( 'lisner__demo_cron_daily', 'lisner_relist' );
