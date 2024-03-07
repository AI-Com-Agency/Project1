<?php
/**
 * Lisner shortcode functions / post shortcode
 *
 * @author pebas
 * @version 1.0.0
 */

/**
 * Map shortcode parameters
 *
 * @return array
 */
if ( ! function_exists( 'lisner_post_settings' ) ) {
	function lisner_post_settings() {
		$templates = array(
			array(
				'type'        => 'lisner_image_radio',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Post Templates', 'lisner-core' ),
				'param_name'  => 'post_template',
				'group'       => esc_html__( 'Templates', 'lisner-core' ),
				'admin_label' => true,
				'value'       => array(
					esc_html__( 'Listing Template 1', 'lisner-core' ) => 1,
					esc_html__( 'Listing Template 2', 'lisner-core' ) => 2,
					esc_html__( 'Listing Template 3', 'lisner-core' ) => 3,
					esc_html__( 'Listing Template 4', 'lisner-core' ) => 4,
					esc_html__( 'Listing Template 5', 'lisner-core' ) => 5,
				),
				'description' => esc_html__( 'Choose listing template for this block of listings', 'lisner-core' )
			),
		);
		$filter    = array(
			array(
				'type'        => 'dropdown',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Number Of Posts', 'lisner-core' ),
				'param_name'  => 'posts_per_page',
				'group'       => esc_html__( 'Post Filter', 'lisner-core' ),
				'admin_label' => true,
				'value'       => lisner_shortcodes::get_posts_per_page( 1, 1 ),
				'description' => esc_html__( 'Select number of listings that will be displayed', 'lisner-core' )
			),
			array(
				'type'        => 'select2',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Post Author', 'lisner-core' ),
				'param_name'  => 'author',
				'group'       => esc_html__( 'Post Filter', 'lisner-core' ),
				'admin_label' => true,
				'value'       => lisner_shortcodes::get_authors(),
				'description' => esc_html__( 'Manually set authors of listings that will be displayed, leave empty to disable.', 'lisner-core' )
			),
			array(
				'type'        => 'select2',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Posts Category', 'lisner-core' ),
				'param_name'  => 'category',
				'group'       => esc_html__( 'Post Filter', 'lisner-core' ),
				'admin_label' => true,
				'value'       => lisner_shortcodes::get_taxonomy_terms( 'category', true, false ),
				'description' => esc_html__( 'Manually set categories of posts that will be displayed.', 'lisner-core' )
			),
			array(
				'type'        => 'select2',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Display Specific Posts', 'lisner-core' ),
				'param_name'  => 'post__in',
				'group'       => esc_html__( 'Post Filter', 'lisner-core' ),
				'admin_label' => true,
				'value'       => lisner_shortcodes::get_posts( array( 'post_type' => 'post' ) ),
				'description' => esc_html__( 'Manually set posts that will be displayed, leave empty to disable.', 'lisner-core' )
			),
		);
		$sorting   = array(
			array(
				'type'        => 'dropdown',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Post Order', 'lisner-core' ),
				'param_name'  => 'order_by',
				'group'       => esc_html__( 'Post Sorting', 'lisner-core' ),
				'admin_label' => true,
				'value'       => array(
					esc_html__( 'Published Date', 'lisner-core' ) => 'date',
					esc_html__( 'Views Count', 'lisner-core' )    => 'views_count',
					esc_html__( 'Comment Count', 'lisner-core' )  => 'comment_count',
					esc_html__( 'Title', 'lisner-core' )          => 'post_title',
					esc_html__( 'Author', 'lisner-core' )         => 'post_author',
				),
				'description' => esc_html__( 'Choose the way news should be ordered by. Default is date.', 'lisner-core' )
			),
			array(
				'type'        => 'dropdown',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Post Order', 'lisner-core' ),
				'param_name'  => 'order',
				'group'       => esc_html__( 'Post Sorting', 'lisner-core' ),
				'admin_label' => true,
				'value'       => array(
					esc_html__( 'Descending', 'lisner-core' ) => 'DESC',
					esc_html__( 'Ascending', 'lisner-core' )  => 'ASC',
				),
				'description' => esc_html__( 'Choose whether order should be Ascending or Descending.', 'lisner-core' )
			),
		);
		$title     = array(
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

		return array_merge( $templates, $title, $filter, $sorting );
	}
}

/**
 * Set WP_Query arguments
 *
 * @param $atts
 *
 * @return array
 */
if ( ! function_exists( 'lisner_post_query' ) ) {
	function lisner_post_query( $atts ) {
		$shortcode_args = array(
			// query args
			'posts_per_page' => '',
			'author'         => '',
			'post__in'       => '',
			'order_by'       => '',
			'order'          => '',

			// taxonomy args
			'category'       => '',
		);

		// get shortcode attributes
		shortcode_atts( $shortcode_args, $atts );

		if ( get_query_var( 'paged' ) ) {
			$cur_page = get_query_var( 'paged' );
		} elseif ( get_query_var( 'page' ) ) {
			$cur_page = get_query_var( 'page' );
		} else {
			$cur_page = 1;
		}

		$args                        = array();
		$args['post_type']           = 'post';
		$args['post_status']         = 'publish';
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
		if ( isset( $atts['category'] ) && ! empty( $atts['category'] ) ) {
			$args['cat'] = implode( ',', $atts['category'] );
		}

		// set query post ids
		if ( isset( $atts['post__in'] ) && ! empty( $atts['post__in'] ) ) :
			$args['post__in'] = explode( ',', $atts['post__in'] );
		endif;

		// set query order by
		if ( isset( $atts['order_by'] ) && ! empty( $atts['order_by'] ) ) :
			if ( 'views_count' == $atts['order_by'] ) :
				$args['meta_key'] = 'listing_views';
				$args['orderby']  = 'meta_value';
			else:
				$args['orderby'] = $atts['order_by'];
			endif;
		endif;

		// set query order
		if ( isset( $atts['order'] ) && ! empty( $atts['order'] ) ) :
			$args['order'] = $atts['order'];
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
if ( ! function_exists( 'lisner_post_render' ) ) {
	function lisner_post_render( $atts, $content ) {
		shortcode_atts( array(
			// template
			'post_template'           => '',

			// title section
			'display_title'           => '',
			'title'                   => '',
			'subtitle'                => '',

			// button
			'display_button'          => '',
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
		$atts['unique_id'] = $unique_id ++;

		// get template from folder: templates/shortcodes/post
		ob_start();
		switch ( $atts['post_template'] ) :
			case 1:
				include lisner_helper::get_template_part( 'post-1', 'shortcodes/post', $atts );
				break;
			default:
				include lisner_helper::get_template_part( 'post-1', 'shortcodes/post', $atts );
				break;
		endswitch;

		return ob_get_clean();

	}
}
