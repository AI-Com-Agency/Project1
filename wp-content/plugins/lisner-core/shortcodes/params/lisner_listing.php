<?php
/**
 * Lisner shortcode functions / listing shortcode
 *
 * @author pebas
 * @version 1.0.0
 */

/**
 * Map shortcode parameters
 *
 * @return array
 */
if ( ! function_exists( 'lisner_listing_settings' ) ) {
	function lisner_listing_settings() {
		$templates = array(
			array(
				'type'        => 'lisner_image_radio',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Listing Templates', 'lisner-core' ),
				'param_name'  => 'listing_template',
				'group'       => esc_html__( 'Templates', 'lisner-core' ),
				'admin_label' => true,
				'value'       => array(
					esc_html__( 'Listing Template 1', 'lisner-core' ) => 1,
					esc_html__( 'Listing Template 2', 'lisner-core' ) => 2,
					esc_html__( 'Listing Template 3', 'lisner-core' ) => 3,
					esc_html__( 'Listing Template 4', 'lisner-core' ) => 4,
					esc_html__( 'Listing Template 5', 'lisner-core' ) => 5,
					esc_html__( 'Listing Template 6', 'lisner-core' ) => 6,
					esc_html__( 'Listing Template 7', 'lisner-core' ) => 7,
					esc_html__( 'Listing Template 8', 'lisner-core' ) => 8,
					esc_html__( 'Listing Template 9', 'lisner-core' ) => 9,
				),
				'description' => esc_html__( 'Choose listing template for this block of listings', 'lisner-core' )
			),
		);
		$filter    = array(
			array(
				'type'        => 'dropdown',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Listing Number Of Posts', 'lisner-core' ),
				'param_name'  => 'posts_per_page',
				'group'       => esc_html__( 'Listing Filter', 'lisner-core' ),
				'admin_label' => true,
				'value'       => lisner_shortcodes::get_posts_per_page( 1, 1 ),
				'description' => esc_html__( 'Select number of listings that will be displayed', 'lisner-core' )
			),
			array(
				'type'        => 'select2',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Listing Author', 'lisner-core' ),
				'param_name'  => 'author',
				'group'       => esc_html__( 'Listing Filter', 'lisner-core' ),
				'admin_label' => true,
				'value'       => lisner_shortcodes::get_authors(),
				'description' => esc_html__( 'Manually set authors of listings that will be displayed, leave empty to disable.',
					'lisner-core' )
			),
			array(
				'type'        => 'dropdown',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Display Listings By Taxonomy', 'lisner-core' ),
				'param_name'  => 'listing_taxonomy',
				'group'       => esc_html__( 'Listing Filter', 'lisner-core' ),
				'admin_label' => true,
				'value'       => array(
					esc_html__( 'Any Taxonomy', 'lisner-core' ) => '',
					esc_html__( 'By Category', 'lisner-core' )  => 'job_listing_category',
					esc_html__( 'By Location', 'lisner-core' )  => 'listing_location',
					esc_html__( 'By Amenity', 'lisner-core' )   => 'listing_amenity',
					esc_html__( 'By Tag', 'lisner-core' )       => 'listing_tag',
				),
				'description' => esc_html__( 'Choose taxonomy by which you wish to display listings. Select field will be displayed below to choose specific terms.',
					'lisner-core' )
			),
			array(
				'type'        => 'select2',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Listings Category', 'lisner-core' ),
				'param_name'  => 'cat_category',
				'group'       => esc_html__( 'Listing Filter', 'lisner-core' ),
				'admin_label' => true,
				'value'       => lisner_shortcodes::get_taxonomy_terms( 'job_listing_category', true, false ),
				'dependency'  => array(
					'element' => 'listing_taxonomy',
					'value'   => 'job_listing_category'
				),
				'description' => esc_html__( 'Manually set categories of posts that will be displayed.', 'lisner-core' )
			),
			array(
				'type'        => 'select2',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Listings Location', 'lisner-core' ),
				'param_name'  => 'cat_location',
				'group'       => esc_html__( 'Listing Filter', 'lisner-core' ),
				'admin_label' => true,
				'value'       => lisner_shortcodes::get_taxonomy_terms( 'listing_location', true, false ),
				'dependency'  => array(
					'element' => 'listing_taxonomy',
					'value'   => 'listing_location'
				),
				'description' => esc_html__( 'Manually set locations of posts that will be displayed.', 'lisner-core' )
			),
			array(
				'type'        => 'select2',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Listings Amenity', 'lisner-core' ),
				'param_name'  => 'cat_amenity',
				'group'       => esc_html__( 'Listing Filter', 'lisner-core' ),
				'admin_label' => true,
				'value'       => lisner_shortcodes::get_taxonomy_terms( 'listing_amenity', true, false ),
				'dependency'  => array(
					'element' => 'listing_taxonomy',
					'value'   => 'listing_amenity'
				),
				'description' => esc_html__( 'Manually set amenities of posts that will be displayed.', 'lisner-core' )
			),
			array(
				'type'        => 'select2',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Listings Tag', 'lisner-core' ),
				'param_name'  => 'cat_tag',
				'group'       => esc_html__( 'Listing Filter', 'lisner-core' ),
				'admin_label' => true,
				'value'       => lisner_shortcodes::get_taxonomy_terms( 'listing_tag', true, false ),
				'dependency'  => array(
					'element' => 'listing_taxonomy',
					'value'   => 'listing_tag'
				),
				'description' => esc_html__( 'Manually set tags of posts that will be displayed.', 'lisner-core' )
			),
			array(
				'type'        => 'select2',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Display Specific Listings', 'lisner-core' ),
				'param_name'  => 'post__in',
				'group'       => esc_html__( 'Listing Filter', 'lisner-core' ),
				'admin_label' => true,
				'value'       => lisner_shortcodes::get_posts( array( 'post_type' => 'job_listing' ) ),
				'description' => esc_html__( 'Manually set listing posts that will be displayed, leave empty to disable.',
					'lisner-core' )
			),
			array(
				'type'        => 'dropdown',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Display Only Open Listings', 'lisner-core' ),
				'param_name'  => 'only_open',
				'group'       => esc_html__( 'Listing Filter', 'lisner-core' ),
				'admin_label' => true,
				'value'       => array(
					esc_html__( 'No', 'lisner-core' )  => false,
					esc_html__( 'Yes', 'lisner-core' ) => true,
				),
				'description' => esc_html__( 'Display only listings that are open at the time', 'lisner-core' )
			),
		);
		if ( class_exists( 'Pebas_Paid_Listings' ) && class_exists( 'WooCommerce' ) ) {
			$filter[] = array(
				'type'        => 'select2',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Display Listings With Package', 'lisner-core' ),
				'param_name'  => 'job_package',
				'group'       => esc_html__( 'Listing Filter', 'lisner-core' ),
				'admin_label' => true,
				'value'       => lisner_shortcodes::get_posts( array(
					'post_type' => 'product',
					'tax_query' => WC()->query->get_tax_query( array(
						'relation' => 'AND',
						array(
							'taxonomy' => 'product_type',
							'field'    => 'slug',
							'terms'    => array( pebas_paid_listings_install()->pebas_paid_term_name ),
							'operator' => 'IN',
						),
					) ),
				) ),
				'description' => esc_html__( 'Display listings of chosen packages, leave empty to disable.',
					'lisner-core' )
			);
		}
		$sorting = array(
			array(
				'type'        => 'dropdown',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Listings Order', 'lisner-core' ),
				'param_name'  => 'order_by',
				'group'       => esc_html__( 'Listing Sorting', 'lisner-core' ),
				'admin_label' => true,
				'value'       => array(
					esc_html__( 'Published Date', 'lisner-core' ) => 'date',
					esc_html__( 'Views Count', 'lisner-core' )    => 'views_count',
					esc_html__( 'Likes Count', 'lisner-core' )    => 'likes_count',
					esc_html__( 'Price Range', 'lisner-core' )    => 'price_range',
					esc_html__( 'Rating', 'lisner-core' )         => 'rating',
					esc_html__( 'Comment Count', 'lisner-core' )  => 'comment_count',
					esc_html__( 'Title', 'lisner-core' )          => 'post_title',
					esc_html__( 'Author', 'lisner-core' )         => 'post_author',
					esc_html__( 'Featured', 'lisner-core' )       => '_featured',
				),
				'description' => esc_html__( 'Choose the way news should be ordered by. Default is date.',
					'lisner-core' )
			),
			array(
				'type'        => 'dropdown',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Listing Order', 'lisner-core' ),
				'param_name'  => 'order',
				'group'       => esc_html__( 'Listing Sorting', 'lisner-core' ),
				'admin_label' => true,
				'value'       => array(
					esc_html__( 'Descending', 'lisner-core' ) => 'DESC',
					esc_html__( 'Ascending', 'lisner-core' )  => 'ASC',
				),
				'description' => esc_html__( 'Choose whether order should be Ascending or Descending.', 'lisner-core' )
			),
		);
		$title   = array(
			array(
				'type'        => 'dropdown',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Display Title Section?', 'lisner-core' ),
				'param_name'  => 'display_title',
				'group'       => esc_html__( 'Title Section', 'lisner-core' ),
				'admin_label' => true,
				'value'       => array(
					esc_html__( 'Yes', 'lisner-core' ) => true,
					esc_html__( 'No', 'lisner-core' )  => false,
				),
				'description' => esc_html__( 'Choose whether you wish to display title section', 'lisner-core' )
			),
			array(
				'type'        => 'textfield',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Title', 'lisner-core' ),
				'param_name'  => 'title',
				'group'       => esc_html__( 'Title Section', 'lisner-core' ),
				'dependency'  => array(
					'element' => 'display_title',
					'value'   => '1',
				),
				'admin_label' => true,
				'value'       => '',
				'description' => esc_html__( 'Enter text for the title of the section', 'lisner-core' )
			),
			array(
				'type'        => 'textarea',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Subtitle', 'lisner-core' ),
				'param_name'  => 'subtitle',
				'group'       => esc_html__( 'Title Section', 'lisner-core' ),
				'dependency'  => array(
					'element' => 'display_title',
					'value'   => '1',
				),
				'admin_label' => true,
				'value'       => '',
				'description' => esc_html__( 'Enter text for the subtitle of the section', 'lisner-core' )
			),
			array(
				'type'        => 'dropdown',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Display Button?', 'lisner-core' ),
				'param_name'  => 'display_button',
				'group'       => esc_html__( 'Title Section', 'lisner-core' ),
				'admin_label' => true,
				'value'       => array(
					esc_html__( 'Yes', 'lisner-core' ) => true,
					esc_html__( 'No', 'lisner-core' )  => false,
				),
				'dependency'  => array(
					'element' => 'display_title',
					'value'   => '1',
				),
				'description' => esc_html__( 'Choose whether you wish to display button', 'lisner-core' )
			),
			array(
				'type'        => 'vc_link',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Button Link', 'lisner-core' ),
				'param_name'  => 'button_link',
				'group'       => esc_html__( 'Title Section', 'lisner-core' ),
				'dependency'  => array(
					'element' => 'display_button',
					'value'   => '1',
				),
				'admin_label' => true,
				'value'       => '',
				'description' => esc_html__( 'Enter link for the button', 'lisner-core' )
			),
			array(
				'type'        => 'dropdown',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Button Color', 'lisner-core' ),
				'param_name'  => 'button_color',
				'group'       => esc_html__( 'Title Section', 'lisner-core' ),
				'admin_label' => true,
				'value'       => array(
					esc_html__( 'Default', 'lisner-core' ) => false,
					esc_html__( 'Custom', 'lisner-core' )  => true,
				),
				'dependency'  => array(
					'element' => 'display_button',
					'value'   => '1',
				),
				'description' => esc_html__( 'Choose whether you wish to display button', 'lisner-core' )
			),
			array(
				'type'        => 'colorpicker',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Button Background Color', 'lisner-core' ),
				'param_name'  => 'button_bg',
				'group'       => esc_html__( 'Title Section', 'lisner-core' ),
				'admin_label' => true,
				'value'       => '#ff015b',
				'dependency'  => array(
					'element' => 'button_color',
					'value'   => '1',
				),
				'description' => esc_html__( 'Choose button background color', 'lisner-core' )
			),
			array(
				'type'        => 'colorpicker',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Button Background Color On Hover', 'lisner-core' ),
				'param_name'  => 'button_bg_hover',
				'group'       => esc_html__( 'Title Section', 'lisner-core' ),
				'admin_label' => true,
				'value'       => '#fc0043',
				'dependency'  => array(
					'element' => 'button_color',
					'value'   => '1',
				),
				'description' => esc_html__( 'Choose button background color on hover', 'lisner-core' )
			),
			array(
				'type'        => 'colorpicker',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Button Font Color', 'lisner-core' ),
				'param_name'  => 'button_font',
				'group'       => esc_html__( 'Title Section', 'lisner-core' ),
				'admin_label' => true,
				'value'       => '#fff',
				'dependency'  => array(
					'element' => 'button_color',
					'value'   => '1',
				),
				'description' => esc_html__( 'Choose button font color', 'lisner-core' )
			),
			array(
				'type'        => 'colorpicker',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Button Font Color On Hover', 'lisner-core' ),
				'param_name'  => 'button_font_hover',
				'group'       => esc_html__( 'Title Section', 'lisner-core' ),
				'admin_label' => true,
				'value'       => '#fff',
				'dependency'  => array(
					'element' => 'button_color',
					'value'   => '1',
				),
				'description' => esc_html__( 'Choose button font color on hover', 'lisner-core' )
			),
			array(
				'type'        => 'dropdown',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Display Button Icon?', 'lisner-core' ),
				'param_name'  => 'display_button_icon',
				'group'       => esc_html__( 'Title Section', 'lisner-core' ),
				'admin_label' => true,
				'value'       => array(
					esc_html__( 'Yes', 'lisner-core' ) => true,
					esc_html__( 'No', 'lisner-core' )  => false,
				),
				'dependency'  => array(
					'element' => 'display_button',
					'value'   => '1',
				),
				'description' => esc_html__( 'Choose whether you wish to display button icon', 'lisner-core' )
			),
			array(
				'type'        => 'dropdown',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Button Icon Set', 'lisner-core' ),
				'param_name'  => 'button_icon_set',
				'group'       => esc_html__( 'Title Section', 'lisner-core' ),
				'dependency'  => array(
					'element' => 'display_button_icon',
					'value'   => '1',
				),
				'admin_label' => true,
				'value'       => array(
					esc_html__( 'Material Icons', 'lisner-core' ) => 'material',
					esc_html__( 'Font Awesome', 'lisner-core' )   => 'fontawesome',
					esc_html__( 'Open Iconic', 'lisner-core' )    => 'openiconic',
					esc_html__( 'Typicons', 'lisner-core' )       => 'typicons',
					esc_html__( 'Entypo', 'lisner-core' )         => 'entypo',
				),
				'description' => esc_html__( 'Choose button icon set', 'lisner-core' )
			),
			array(
				'type'        => 'iconpicker',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Button Icon', 'lisner-core' ),
				'param_name'  => 'button_icon_material',
				'settings'    => array(
					'emptyIcon'    => false,
					'type'         => 'material',
					'iconsPerPage' => 200
				),
				'dependency'  => array(
					'element' => 'button_icon_set',
					'value'   => 'material',
				),
				'group'       => esc_html__( 'Title Section', 'lisner-core' ),
				'admin_label' => true,
				'value'       => 'vc-material vc-material-add',
				'description' => esc_html__( 'Choose icon for the button', 'lisner-core' )
			),
			array(
				'type'        => 'iconpicker',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Button Icon', 'lisner-core' ),
				'param_name'  => 'button_icon_fontawesome',
				'settings'    => array(
					'emptyIcon'    => false,
					'type'         => 'fontawesome',
					'iconsPerPage' => 200
				),
				'dependency'  => array(
					'element' => 'button_icon_set',
					'value'   => 'fontawesome',
				),
				'group'       => esc_html__( 'Title Section', 'lisner-core' ),
				'admin_label' => true,
				'value'       => 'fa fa-plus',
				'description' => esc_html__( 'Choose icon for the button', 'lisner-core' )
			),
			array(
				'type'        => 'iconpicker',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Button Icon', 'lisner-core' ),
				'param_name'  => 'button_icon_openiconic',
				'settings'    => array(
					'emptyIcon'    => false,
					'type'         => 'openiconic',
					'iconsPerPage' => 200
				),
				'dependency'  => array(
					'element' => 'button_icon_set',
					'value'   => 'openiconic',
				),
				'group'       => esc_html__( 'Title Section', 'lisner-core' ),
				'admin_label' => true,
				'value'       => 'vc-oi vc-oi-plus',
				'description' => esc_html__( 'Choose icon for the button', 'lisner-core' )
			),
			array(
				'type'        => 'iconpicker',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Button Icon', 'lisner-core' ),
				'param_name'  => 'button_icon_typicons',
				'settings'    => array(
					'emptyIcon'    => false,
					'type'         => 'typicons',
					'iconsPerPage' => 200
				),
				'dependency'  => array(
					'element' => 'button_icon_set',
					'value'   => 'typicons',
				),
				'group'       => esc_html__( 'Title Section', 'lisner-core' ),
				'admin_label' => true,
				'value'       => 'typcn typcn-plus',
				'description' => esc_html__( 'Choose icon for the button', 'lisner-core' )
			),
			array(
				'type'        => 'iconpicker',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Button Icon', 'lisner-core' ),
				'param_name'  => 'button_icon_entypo',
				'settings'    => array(
					'emptyIcon'    => false,
					'type'         => 'entypo',
					'iconsPerPage' => 200
				),
				'dependency'  => array(
					'element' => 'button_icon_set',
					'value'   => 'entypo',
				),
				'group'       => esc_html__( 'Title Section', 'lisner-core' ),
				'admin_label' => true,
				'value'       => 'entypo-icon entypo-icon-plus',
				'description' => esc_html__( 'Choose icon for the button', 'lisner-core' )
			),
		);

		$layout = array(
			array(
				'type'        => 'dropdown',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Show Taxonomy Count?', 'lisner-core' ),
				'param_name'  => 'display_count',
				'group'       => esc_html__( 'Box Layout', 'lisner-core' ),
				'admin_label' => true,
				'value'       => array(
					esc_html__( 'Yes', 'lisner-core' ) => true,
					esc_html__( 'No', 'lisner-core' )  => false,
				),
				'description' => esc_html__( 'Choose whether you wish to display taxonomy count number', 'lisner-core' )
			),
		);

		return array_merge( $templates, $title, $filter, $sorting, $layout );
	}
}

/**
 * Set WP_Query arguments
 *
 * @param $atts
 *
 * @return array
 */
if ( ! function_exists( 'lisner_adjust_query' ) ) {
	function lisner_adjust_query( $atts ) {
		$shortcode_args = array(
			// query args
			'posts_per_page'   => '',
			'author'           => '',
			'post__in'         => '',
			'order_by'         => '',
			'order'            => '',

			// taxonomy args
			'listing_taxonomy' => '',
			'cat_category'     => '',
			'cat_location'     => '',
			'cat_amenity'      => '',
			'cat_tag'          => '',
			'only_open'        => '',
		);
		if ( lisner_helper::is_plugin_active( 'pebas-paid-listings' ) && lisner_helper::is_plugin_active( 'woocommerce' ) ) {
			$shortcode_args['job_package'] = '';
		}

		// get shortcode attributes
		shortcode_atts( $shortcode_args, $atts );

		if ( get_query_var( 'paged' ) ) {
			$cur_page = get_query_var( 'paged' );
		} elseif ( get_query_var( 'page' ) ) {
			$cur_page = get_query_var( 'page' );
		} else {
			$cur_page = 1;
		}

		if ( ! empty( $args['post_status'] ) ) {
			$post_status = $args['post_status'];
		} elseif ( false == get_option( 'job_manager_hide_expired',
				get_option( 'job_manager_hide_expired_content', 1 ) ) ) {
			$post_status = array( 'publish', 'expired' );
		} else {
			$post_status = 'publish';
		}

		$args                        = array();
		$args['post_type']           = 'job_listing';
		$args['post_status']         = $post_status;
		$args['paged']               = isset( $_GET['cur_page'] ) ? $_GET['cur_page'] : $cur_page; // display only published posts
		$args['ignore_sticky_posts'] = 1; // make sure sticky posts are ignored

		// set query post per page
		if ( isset( $atts['posts_per_page'] ) && ! empty( $atts['posts_per_page'] ) ) :
			$args['posts_per_page'] = $atts['posts_per_page'];
		endif;

		// set query authors
		if ( isset( $atts['author'] ) && ! empty( $atts['author'] ) ) :
			$args['author'] = $atts['author'];
		endif;

		// set query categories
		if ( isset( $atts['listing_taxonomy'] ) && 'any' != $atts['listing_taxonomy'] ) :
			if ( 'job_listing_category' == $atts['listing_taxonomy'] ) :
				$selected_taxonomy = explode( 'listing_', $atts['listing_taxonomy'] );
			else:
				$selected_taxonomy = explode( '_', $atts['listing_taxonomy'] );
			endif;
			$selected_taxonomy = $selected_taxonomy[1];
			$tax               = '';
			$chosen_taxonomy   = "cat_{$selected_taxonomy}";
			if ( isset( $atts['listing_taxonomy'] ) ) :
				$args['tax_query'] = array(
					array(
						'taxonomy' => $atts['listing_taxonomy'],
						'field'    => 'term_id',
						'terms'    => explode( ',', $atts[ $chosen_taxonomy ] )
					)
				);
			endif;
		endif;

		// set query post ids
		if ( isset( $atts['post__in'] ) && ! empty( $atts['post__in'] ) ) :
			$args['post__in'] = explode( ',', $atts['post__in'] );
		endif;

		// set query order by
		if ( isset( $atts['order_by'] ) && ! empty( $atts['order_by'] ) ) :
			if ( '_featured' == $atts['order_by'] ) :
				$args['meta_key'] = '_featured';
				$args['orderby']  = 'meta_value';
			elseif ( 'views_count' == $atts['order_by'] ) :
				$args['meta_key'] = 'listing_views';
				$args['orderby']  = 'meta_value';
			elseif ( 'likes_count' == $atts['order_by'] ) :
				$args['meta_key'] = 'listing_likes';
				$args['orderby']  = 'meta_value';
			elseif ( 'price_range' == $atts['order_by'] ) :
				$args['meta_key'] = 'price_range';
				$args['orderby']  = 'meta_value';
			else:
				$args['orderby'] = $atts['order_by'];
			endif;
		endif;

		// set query order
		if ( isset( $atts['order'] ) && ! empty( $atts['order'] ) ) :
			$args['order'] = $atts['order'];
		endif;

		// display only open listings
		if ( isset( $atts['only_open'] ) && $atts['only_open'] ) :
			$open_ids         = lisner_search()->display_open_listings();
			$args['post__in'] = $open_ids;
		endif;

		// display only open listings
		if ( isset( $atts['job_package'] ) && ! empty( $atts['job_package'] ) ) :
			$post_ids = pebas_get_listings_by_package( $atts['job_package'] );
			if ( isset( $open_ids ) && ! empty( $open_ids ) ):
				$post_ids         = array_intersect( $open_ids, $post_ids );
				$args['post__in'] = $post_ids;
			else:
				$args['post__in'] = $post_ids;
			endif;
		endif;

		// return WP_Query arguments
		return $args;
	}

}

/**
 * Render shortcode html templates
 *
 * @param $atts
 * @param $content
 *
 * @return string
 */
if ( ! function_exists( 'lisner_listing_render' ) ) {
	function lisner_listing_render( $atts, $content ) {
		shortcode_atts( array(
			// template
			'listing_template'        => '',

			// title section
			'display_title'           => '',
			'title'                   => '',
			'subtitle'                => '',

			// button
			'display_button'          => true,
			'button_link'             => '',

			// button icons
			'display_button_icon'     => '',
			'button_icon_set'         => '',
			'button_icon_material'    => '',
			'button_icon_fontawesome' => '',
			'button_icon_openiconic'  => '',
			'button_icon_typicons'    => '',
			'button_icon_entypo'      => '',
		), $atts );
		static $unique_id = 1;
		$atts['unique_id']        = $unique_id ++;
		$atts['listing_template'] = lisner_get_var( $atts['listing_template'], 1 );

		// get template from folder: templates/shortcodes/listing
		ob_start();
		$template = isset ( $atts['listing_template'] ) ? $atts['listing_template'] : 1;
		include lisner_helper::get_template_part( "listing-{$template}", 'shortcodes/listing', $atts );

		return ob_get_clean();

	}
}
