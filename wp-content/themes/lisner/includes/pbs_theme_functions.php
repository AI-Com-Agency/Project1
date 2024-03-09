<?php

/**
 * Theme Function Files
 *
 * @author pebas
 * @ver 1.0.1
 */
// theme files
require_once get_parent_theme_file_path( 'includes/pbs_global.php' );

if ( ! class_exists( 'pbs_theme_functions' ) ) {
	class pbs_theme_functions {

		protected static $_instance = null;

		/**
		 * @return null|pbs_theme_functions
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		function __construct() {
			add_action( 'after_setup_theme', array( $this, 'load_textdomain' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'additional_css' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_theme_colors' ) );
			// require tgmpa activation plugin
			if ( ! class_exists( 'TGM_Plugin_Activation' ) ) {
				require_once get_parent_theme_file_path( 'includes/class-tgm-plugin-activation.php' );
			}
			add_action( 'tgmpa_register', array( $this, 'required_plugins' ) );
			if ( ! function_exists( '_wp_render_title_tag' ) ) {
				add_action( 'wp_head', array( $this, 'theme_slug_render_title' ) );
			}
			add_filter( 'body_class', array( $this, 'custom_body_class' ), 10 );
			remove_action( 'shutdown', 'wp_ob_end_flush_all', 1 );
			add_filter( 'widget_tag_cloud_args', function ( $args ) {
				if ( is_active_widget( false, false, 'tag_cloud' ) && ( is_active_sidebar( 'sidebar-footer' ) || is_active_sidebar( 'sidebar-footer-2' ) || is_active_sidebar( 'sidebar-footer-3' ) || is_active_sidebar( 'sidebar-footer-4' ) ) ) {
					$args['unit']      = 'px';
					$args['smallest']  = '16';
					$args['largest']   = '16';
					$args['separator'] = wp_kses_post( '<span class="tag-separator">,</span>' );
				}

				return $args;
			} );
		}

		/**
		 * Load theme textdomain
		 */
		public function load_textdomain() {
			load_theme_textdomain( 'lisner', get_template_directory() . '/languages' );
		}

		public function required_plugins() {
			$plugins = array(
				array(
					'name'               => esc_html__( 'Lisner Core', 'lisner' ),
					'slug'               => 'lisner-core',
					'source'             => get_template_directory() . '/lib/lisner-core.zip',
					'required'           => true,
					'version'            => '1.5.0',
					'force_activation'   => false,
					'force_deactivation' => false,
					'external_url'       => '',
				),
				array(
					'name'               => esc_html__( 'Meta Box', 'lisner' ),
					'slug'               => 'meta-box',
					'source'             => 'https://downloads.wordpress.org/plugin/meta-box.4.15.9.zip',
					'required'           => true,
					'version'            => '5.2.4',
					'force_activation'   => false,
					'force_deactivation' => false,
					'external_url'       => 'https://downloads.wordpress.org/plugin/meta-box.4.15.9.zip',
				),
				array(
					'name'               => esc_html__( 'WpBakery Page Builder', 'lisner' ),
					'slug'               => 'js_composer',
					'source'             => get_template_directory() . '/lib/js_composer.zip',
					'required'           => true,
					'version'            => '6.1',
					'force_activation'   => false,
					'force_deactivation' => false,
					'external_url'       => '',
				),
				array(
					'name'               => esc_html__( 'One Click Demo Import', 'lisner' ),
					'slug'               => 'one-click-demo-import',
					'source'             => 'https://downloads.wordpress.org/plugin/one-click-demo-import.2.5.1.zip',
					'required'           => true,
					'version'            => '2.5.2',
					'force_activation'   => false,
					'force_deactivation' => false,
					'external_url'       => 'https://downloads.wordpress.org/plugin/one-click-demo-import.2.5.1.zip',
				),
				array(
					'name'               => esc_html__( 'WP Job Manager', 'lisner' ),
					'slug'               => 'wp-job-manager',
					'source'             => 'http://downloads.wordpress.org/plugin/wp-job-manager.latest-stable.zip',
					'required'           => true,
					'version'            => '1.34.1',
					'force_activation'   => false,
					'force_deactivation' => false,
					'external_url'       => 'http://downloads.wordpress.org/plugin/wp-job-manager.latest-stable.zip',
				),
				array(
					'name'               => esc_html__( 'WooCommerce', 'lisner' ),
					'slug'               => 'woocommerce',
					'source'             => 'https://downloads.wordpress.org/plugin/woocommerce.3.5.3.zip',
					'required'           => true,
					'version'            => '3.8.1',
					'force_activation'   => false,
					'force_deactivation' => false,
					'external_url'       => 'https://downloads.wordpress.org/plugin/woocommerce.3.5.3.zip',
				),
				array(
					'name'               => esc_html__( 'Contact Form 7', 'lisner' ),
					'slug'               => 'contact-form-7',
					'source'             => 'https://downloads.wordpress.org/plugin/contact-form-7.5.1.1.zip',
					'required'           => false,
					'version'            => '5.1.6',
					'force_activation'   => false,
					'force_deactivation' => false,
					'external_url'       => 'https://downloads.wordpress.org/plugin/contact-form-7.5.1.1.zip',
				),
				array(
					'name'               => esc_html__( 'Envato Market', 'lisner' ),
					'slug'               => 'envato-market',
					'source'             => 'https://envato.github.io/wp-envato-market/dist/envato-market.zip',
					'required'           => false,
					'version'            => '2.0.3',
					'force_activation'   => false,
					'force_deactivation' => false,
					'external_url'       => 'https://envato.github.io/wp-envato-market/dist/envato-market.zip',
				),
				array(
					'name'               => esc_html__( 'pebas® Paid Listings', 'lisner' ),
					'slug'               => 'pebas-paid-listings',
					'source'             => get_template_directory() . '/lib/pebas-paid-listings.zip',
					'required'           => true,
					'version'            => '1.0.2',
					'force_activation'   => false,
					'force_deactivation' => false,
					'external_url'       => '',
				),
				array(
					'name'               => esc_html__( 'pebas® Claim Listings', 'lisner' ),
					'slug'               => 'pebas-claim-listings',
					'source'             => get_template_directory() . '/lib/pebas-claim-listings.zip',
					'required'           => true,
					'version'            => '1.2.0',
					'force_activation'   => false,
					'force_deactivation' => false,
					'external_url'       => '',
				),
				array(
					'name'               => esc_html__( 'pebas® Report Listings', 'lisner' ),
					'slug'               => 'pebas-report-listings',
					'source'             => get_template_directory() . '/lib/pebas-report-listings.zip',
					'required'           => false,
					'version'            => '1.0.1',
					'force_activation'   => false,
					'force_deactivation' => false,
					'external_url'       => '',
				),
				array(
					'name'               => esc_html__( 'pebas® Review Listings', 'lisner' ),
					'slug'               => 'pebas-review-listings',
					'source'             => get_template_directory() . '/lib/pebas-review-listings.zip',
					'required'           => false,
					'version'            => '1.0.2',
					'force_activation'   => false,
					'force_deactivation' => false,
					'external_url'       => '',
				),
				array(
					'name'               => esc_html__( 'pebas® Bookmark Listings', 'lisner' ),
					'slug'               => 'pebas-bookmark-listings',
					'source'             => get_template_directory() . '/lib/pebas-bookmark-listings.zip',
					'required'           => false,
					'version'            => '1.0.1',
					'force_activation'   => false,
					'force_deactivation' => false,
					'external_url'       => '',
				),
				array(
					'name'               => esc_html__( 'pebas® Mega Menu', 'lisner' ),
					'slug'               => 'pebas-mega-menu',
					'source'             => get_template_directory() . '/lib/pebas-mega-menu.zip',
					'required'           => true,
					'version'            => '1.0.0',
					'force_activation'   => false,
					'force_deactivation' => false,
					'external_url'       => '',
				),
				array(
					'name'               => esc_html__( 'pebas® Listing Coupons', 'lisner' ),
					'slug'               => 'pebas-listing-coupons',
					'source'             => get_template_directory() . '/lib/pebas-listing-coupons.zip',
					'required'           => true,
					'version'            => '1.0.4',
					'force_activation'   => false,
					'force_deactivation' => false,
					'external_url'       => '',
				),
				array(
					'name'               => esc_html__( 'pebas® Listing Events', 'lisner' ),
					'slug'               => 'pebas-listing-events',
					'source'             => get_template_directory() . '/lib/pebas-listing-events.zip',
					'required'           => true,
					'version'            => '1.0.2',
					'force_activation'   => false,
					'force_deactivation' => false,
					'external_url'       => '',
				),
				array(
					'name'               => esc_html__( 'pebas® Bookings Extension', 'lisner' ),
					'slug'               => 'pebas-bookings-extension',
					'source'             => get_template_directory() . '/lib/pebas-bookings-extension.zip',
					'required'           => false,
					'recommended'        => true,
					'version'            => '1.0.4',
					'force_activation'   => false,
					'force_deactivation' => false,
					'external_url'       => '',
				),
				array(
					'name'               => esc_html__( 'WP User Avatars', 'lisner' ),
					'slug'               => 'wp-user-avatars',
					'source'             => 'https://downloads.wordpress.org/plugin/wp-user-avatars.zip',
					'required'           => false,
					'version'            => '1.4.0',
					'force_activation'   => false,
					'force_deactivation' => false,
					'external_url'       => 'https://downloads.wordpress.org/plugin/wp-user-avatars.zip',
				),
				array(
					'name'               => esc_html__( 'WordPress Social Login', 'lisner' ),
					'slug'               => 'wordpress-social-login',
					'source'             => 'https://downloads.wordpress.org/plugin/wordpress-social-login.zip',
					'required'           => false,
					'version'            => '2.3.3',
					'force_activation'   => false,
					'force_deactivation' => false,
					'external_url'       => 'https://downloads.wordpress.org/plugin/wordpress-social-login.zip',
				),
			);

			/**
			 * Array of configuration settings. Amend each line as needed.
			 * If you want the default strings to be available under your own theme domain,
			 * leave the strings uncommented.
			 * Some of the strings are added into a sprintf, so see the comments at the
			 * end of each line for what each argument will be.
			 */
			$config = array(
				'id'           => 'lisner',
				'default_path' => '',
				'menu'         => 'lisner-install-plugins',
				'parent_slug'  => 'themes.php',
				'capability'   => 'edit_theme_options',
				'has_notices'  => true,
				'dismissable'  => true,
				'dismiss_msg'  => '',
				'is_automatic' => false,
				'message'      => '',
				'strings'      => array(
					'page_title' => __( 'Install Required Plugins', 'lisner' ),
					'menu_title' => __( 'Install Required Plugins', 'lisner' ),
					// <snip>...</snip>
					'nag_type'   => 'updated',
					// Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
				)
			);

			tgmpa( $plugins, $config );
		}

		public function theme_slug_render_title() {
			?>
			<title><?php wp_title( '|', true, 'right' ); ?></title>
			<?php
		}

		/**
		 * Localize theme options variables so we can use them
		 * call in file includes/pbs_functions
		 * used in assets/scrips/app.js
		 */
		public static function localize_vars() {
			$option    = get_option( 'pbs_option' );
			$direction = isset( $option['site-direction'] ) ? $option['site-direction'] : '';
			wp_localize_script( 'pbs-theme', 'pbs_data', array(
				'url'             => get_template_directory_uri(),
				'ajax_url'        => admin_url( 'admin-ajax.php' ),
				'is_unit'         => ! class_exists( 'Lisner_Core' ) || ( is_home() && is_front_page() ) ? true : false,
				'is_archive'      => is_archive() ? true : false,
				'is_mobile'       => wp_is_mobile() ? true : false,
				'mobile_bg_image' => isset( $option['appearance-menu-mobile-image'] ) && ! empty( $option['appearance-menu-mobile-image']['url'] ) ? esc_url( $option['appearance-menu-mobile-image']['url'] ) : esc_url( get_stylesheet_directory_uri() . '/assets/images/bg-mobile.jpg' ),
				'is_rtl'          => 'rtl' == $direction ? true : false,
				'is_logged_in'    => is_user_logged_in() ? true : false,
				'sticky_header'   => 'yes' === isset( $option['menu-sticky'] ) ? true : false,
			) );
		}

		/**
		 * Format page pagination
		 *
		 * @param $page_links
		 *
		 * @return string
		 */
		public static function format_pagination( $page_links ) {
			global $wp_query;
			$current_page = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
			$total_pages  = $wp_query->max_num_pages;
			$page_links   = paginate_links( array(
				'prev_next' => true,
				'prev_text' => '<i class="material-icons">' . esc_attr( 'keyboard_arrow_left' ) . '</i>',
				'next_text' => '<i class="material-icons">' . esc_attr( 'keyboard_arrow_right' ) . '</i>',
				'end_size'  => 2,
				'mid_size'  => 2,
				'total'     => $total_pages,
				'current'   => $current_page,
				'type'      => 'array'
			) );
			$list         = '';
			if ( ! empty( $page_links ) ) {
				$list .= '<nav class="pagination listing-pagination post-pagination" itemscope itemtype="http://schema.org/Chapter"><ul class="pagination">';
				foreach ( $page_links as $page_link ) {
					$page_link = str_replace( 'page-numbers dots', 'page-link page-link-gap', $page_link );
					$page_link = str_replace( 'page-numbers current', 'page-link active', $page_link );
					$page_link = str_replace( 'page-numbers', 'page-link', $page_link );
					if ( strpos( $page_link, 'page-link active' ) !== false ) {
						$list .= '<li class="page-item active">' . $page_link . '</li>';
					} elseif ( strpos( $page_link, 'prev' ) ) {
						$list .= '<li class="page-item prev">' . $page_link . '</li>';
					} elseif ( strpos( $page_link, 'next' ) ) {
						$list .= '<li class="page-item next">' . $page_link . '</li>';
					} else {
						$list .= '<li itemprop="pagination" class="list-inline-item">' . $page_link . '</li>';
					}
				}
				$list .= '</ul></nav>';
			}

			return $list;
		}

		public static function format_link_pages( $args = array() ) {
			global $page, $numpages, $multipage, $more;

			$defaults = array(
				'before'           => '<nav class="pagination post-pagination listing-pagination" itemscope itemtype="http://schema.org/Chapter"><ul class="list-unstyled list-inline">   ',
				'after'            => '</ul></nav>',
				'link_before'      => '',
				'link_after'       => '',
				'next_or_number'   => 'next',
				'separator'        => ' ',
				'nextpagelink'     => esc_html__( 'Next page', 'lisner' ),
				'previouspagelink' => esc_html__( 'Previous page', 'lisner' ),
				'pagelink'         => '%',
				'echo'             => false
			);

			$params = array_merge( $args, $defaults );

			/**
			 * Filters the arguments used in retrieving page links for paginated posts.
			 *
			 * @since 3.0.0
			 *
			 * @param array $params An array of arguments for page links for paginated posts.
			 */
			$r = apply_filters( 'wp_link_pages_args', $params );

			$output = '';
			if ( $multipage ) {
				if ( 'number' == $r['next_or_number'] ) {
					$output .= $r['before'];
					for ( $i = 1; $i <= $numpages; $i ++ ) {
						$link = $r['link_before'] . str_replace( '%', $i, $r['pagelink'] ) . $r['link_after'];
						if ( $i != $page || ! $more && 1 == $page ) {
							$link = _wp_link_page( $i ) . $link . '</a>';
						}
						/**
						 * Filters the HTML output of individual page number links.
						 *
						 * @since 3.6.0
						 *
						 * @param string $link The page number HTML output.
						 * @param int $i Page number for paginated posts' page links.
						 */
						$link = apply_filters( 'wp_link_pages_link', $link, $i );

						// Use the custom links separator beginning with the second link.
						$output .= ( 1 === $i ) ? ' ' : $r['separator'];
						$output .= $link;
					}
					$output .= $r['after'];
				} elseif ( $more ) {
					$output .= $r['before'];
					$prev   = $page - 1;
					if ( $prev > 0 ) {
						$link = '<li class="list-inline-item prev">' . _wp_link_page( $prev ) . $r['link_before'] . $r['previouspagelink'] . $r['link_after'] . '</a></li>';

						/** This filter is documented in wp-includes/post-template.php */
						$output .= apply_filters( 'wp_link_pages_link', $link, $prev );
					}
					$next = $page + 1;
					if ( $next <= $numpages ) {
						if ( $prev ) {
							$output .= $r['separator'];
						}
						$link = '<li class="list-inline-item next">' . _wp_link_page( $next ) . $r['link_before'] . $r['nextpagelink'] . $r['link_after'] . '</li>';

						/** This filter is documented in wp-includes/post-template.php */
						$output .= apply_filters( 'wp_link_pages_link', $link, $next );
					}
					$output .= $r['after'];
				}
			}

			/**
			 * Filters the HTML output of page links for paginated posts.
			 *
			 * @since 3.6.0
			 *
			 * @param string $output HTML output of paginated posts' page links.
			 * @param array $args An array of arguments.
			 */
			$html = apply_filters( 'wp_link_pages', $output, $args );

			if ( $r ) {
				if ( $r['echo'] ) {
					echo wp_kses_post( $html );
				}
			}

			return $html;
		}

		/**
		 * Format comments pagination
		 *
		 * @return string
		 */
		public static function format_comment_pagination() {
			$current_page = ( get_query_var( 'cpage' ) ) ? get_query_var( 'cpage' ) : 1;
			$total_pages  = get_comment_pages_count();
			$page_links   = paginate_comments_links( array(
				'echo'    => false,
				'total'   => $total_pages,
				'current' => $current_page,
				'type'    => 'array'
			) );
			$list         = '';
			if ( ! empty( $page_links ) ) {
				$list .= '<nav class="pagination post-pagination listing-pagination comment-pagination" itemscope itemtype="http://schema.org/Chapter"><ul class="list-unstyled list-inline">';
				foreach ( $page_links as $page_link ) {
					$page_link = str_replace( '&laquo; Previous', esc_html__( 'Prev', 'lisner' ), $page_link );
					$page_link = str_replace( 'Next &raquo;', esc_html__( 'Next', 'lisner' ), $page_link );
					if ( strpos( $page_link, 'page-numbers current' ) !== false ) {
						$page_link = str_replace( '<span class="page-link active">', '<a class="page-link" href="javascript:;">', $page_link );
						$page_link = str_replace( '</span>', '</a>', $page_link );
						$list      .= '<li class="list-inline-item active">' . $page_link . '</li>';
					} elseif ( strpos( $page_link, 'prev' ) ) {
						$list .= '<li class="list-inline-item prev">' . $page_link . '</li>';
					} elseif ( strpos( $page_link, 'next' ) ) {
						$list .= '<li class="list-inline-item next">' . $page_link . '</li>';
					} else {
						$list .= '<li itemprop="pagination" class="list-inline-item">' . $page_link . '</li>';
					}

				}
				$list .= '</ul></nav>';
			}

			return $list;
		}

		/**
		 * Add custom body classes
		 *
		 * @param $classes
		 *
		 * @return array
		 */
		public function custom_body_class( $classes ) {
			$option = get_option( 'pbs_option' );
			if ( wp_is_mobile() ) {
				$classes[] = 'mobile';
			}
			if ( is_home() || is_archive() || is_404() || is_search() ) {
				$classes[] = 'page';
			}
			if ( pbs_helpers::has_vc_shortcode() ) {
				$classes[] = 'vc-active';
			} else {
				$classes[] = 'vc-inactive';
			}
			if ( is_user_logged_in() && current_user_can( 'administrator' ) && 'true' == get_user_meta( get_current_user_id(), 'show_admin_bar_front', true ) ) {
				$classes[] = 'logged-in-admin';
			}
			if ( ! class_exists( 'Lisner_Core' ) ) {
				$classes[] = 'page-unit';
			}
			if ( isset( $option['menu-sticky'] ) && 'yes' == $option['menu-sticky'] && class_exists( 'Lisner_Core' ) && ! lisner_helper::is_search_page() ) {
				$classes[] = 'body-sticky-menu';
			}

			if ( is_tax( array( 'listing_location', 'listing_amenity', 'listing_tag', 'job_listing_category' ) ) ) {
				$classes[] = 'page-template-page-search';
			}

			return $classes;
		}

		/**
		 * Additional CSS code
		 */
		public function additional_css() {
			$option = get_option( 'pbs_option' );
			$css    = '';
			$css    .= '
            @media screen and (max-width: 600px) {
                #wpadminbar {
                    position: fixed;
                }
            }';
			wp_add_inline_style( 'pbs-theme', $css );
		}

		/**
		 * Get theme colors if change has been made by admin
		 */
		public function enqueue_theme_colors() {
			$option          = get_option( 'pbs_option' );
			$default_options = array(
				// menu colors
				'color-menu-bg'                => '#37003c',
				'color-menu-font'              => '#fff',
				'color-menu-dropdown-hover-bg' => '#37003c',
				'color-menu-sticky-bg'         => '#37003c',
				'color-menu-sticky-font'       => '#fff',

				// default colors
				'color-primary'                => '#fe015b',
				'color-primary-font'           => '#fff',
				'color-secondary'              => '#07f0ff',
				'color-secondary-font'         => '#3d0941',

				// footer colors
				'color-footer-bg'              => '#28002c',
				'color-footer-font'            => '#fff',

				// copyrights colors
				'color-copy-bg'                => '#210024',
				'color-copy-font'              => '#fff',

				// logo options
				'site-logo-padding'            => '',
				'site-logo-size'               => ''
			);
			if ( isset( $option['listings-title-size'] ) && ! empty( $option['listings-title-size'] ) ) {
				$default_options['listings-title-size'] = '26';
			}
			$is_different = false;
			foreach ( $default_options as $label => $value ) {
				if ( isset( $option[ $label ] ) && ! empty( $option[ $label ] ) ) {
					if ( $option[ $label ] != $value ) {
						$is_different = true;
					}
				}
			}
			if ( $is_different ) {
				require_once get_parent_theme_file_path( 'includes/theme-colors/theme-colors.php' );
			}

		}

	}

}

/**
 * Instantiate class
 *
 * @return null|pbs_theme_functions
 */
function pbs_theme_functions() {
	return pbs_theme_functions::instance();
}

pbs_theme_functions();