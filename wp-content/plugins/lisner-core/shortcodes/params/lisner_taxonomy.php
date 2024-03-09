<?php
/**
 * Lisner shortcode functions / taxonomy shortcode
 *
 * @author pebas
 * @version 1.0.0
 */

/**
 * Map shortcode parameters
 *
 * @return array
 */
if ( ! function_exists( 'lisner_taxonomy_settings' ) ) {
	function lisner_taxonomy_settings() {
		$templates = array(
			array(
				'type'        => 'lisner_image_radio',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Lisner Taxonomy Templates', 'lisner-core' ),
				'param_name'  => 'lisner_taxonomy_template',
				'group'       => esc_html__( 'Templates', 'lisner-core' ),
				'admin_label' => true,
				'value'       => array(
					esc_html__( 'Lisner Taxonomy Template 1', 'lisner-core' ) => 1,
					esc_html__( 'Lisner Taxonomy Template 2', 'lisner-core' ) => 2,
					esc_html__( 'Lisner Taxonomy Template 3', 'lisner-core' ) => 3,
					esc_html__( 'Lisner Taxonomy Template 4', 'lisner-core' ) => 4,
					esc_html__( 'Lisner Taxonomy Template 5', 'lisner-core' ) => 5,
					esc_html__( 'Lisner Taxonomy Template 6', 'lisner-core' ) => 6,
					esc_html__( 'Lisner Taxonomy Template 7', 'lisner-core' ) => 7,
					esc_html__( 'Lisner Taxonomy Template 8', 'lisner-core' ) => 8,
				),
				'description' => esc_html__( 'Choose taxonomy template', 'lisner-core' )
			),
		);
		$filter    = array(
			array(
				'type'        => 'dropdown',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Choose Taxonomy', 'lisner-core' ),
				'param_name'  => 'taxonomy',
				'group'       => esc_html__( 'Taxonomy Filter', 'lisner-core' ),
				'admin_label' => true,
				'value'       => lisner_shortcodes::get_taxonomies( false ),
				'description' => esc_html__( 'Choose taxonomy that you wish to display', 'lisner-core' )
			),
			array(
				'type'        => 'dropdown',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Taxonomy Hierarchy', 'lisner-core' ),
				'param_name'  => 'taxonomy_hierarchy',
				'group'       => esc_html__( 'Taxonomy Filter', 'lisner-core' ),
				'admin_label' => true,
				'value'       => array(
					esc_html__( 'All hierarchy levels', 'lisner-core' ) => 'all',
					esc_html__( 'Parent only levels', 'lisner-core' )   => 'parent',
					esc_html__( 'Children only levels', 'lisner-core' ) => 'children',
				),
				'description' => esc_html__( 'Choose what level of hierarchy you wish to display', 'lisner-core' )
			),
			array(
				'type'        => 'dropdown',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Taxonomy Number', 'lisner-core' ),
				'param_name'  => 'taxonomy_count',
				'group'       => esc_html__( 'Taxonomy Filter', 'lisner-core' ),
				'admin_label' => true,
				'value'       => lisner_shortcodes::get_posts_per_page( 1, 1, 60, false, esc_html__( 'Taxonomies', 'lisner-core' ) ),
				'description' => esc_html__( 'Select number of taxonomies that will be displayed', 'lisner-core' )
			),
			array(
				'type'        => 'dropdown',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Hide Empty Taxonomies?', 'lisner-core' ),
				'param_name'  => 'taxonomy_hide_empty',
				'group'       => esc_html__( 'Taxonomy Filter', 'lisner-core' ),
				'admin_label' => true,
				'value'       => array(
					esc_html__( 'No', 'lisner-core' )  => false,
					esc_html__( 'Yes', 'lisner-core' ) => true,
				),
				'description' => esc_html__( 'Choose whether taxonomies without connected posts should be hidden from user.', 'lisner-core' )
			),
			array(
				'type'        => 'select2',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Specific Categories', 'lisner-core' ),
				'param_name'  => 'post__in_categories',
				'group'       => esc_html__( 'Taxonomy Filter', 'lisner-core' ),
				'admin_label' => true,
				'value'       => lisner_shortcodes::get_taxonomy_terms( 'job_listing_category', true, false ),
				'dependency'  => array(
					'element' => 'taxonomy',
					'value'   => 'job_listing_category'
				),
				'description' => esc_html__( 'Manually set category terms that will be displayed or leave empty to automatize process.', 'lisner-core' )
			),
			array(
				'type'        => 'select2',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Specific Tags', 'lisner-core' ),
				'param_name'  => 'post__in__tags',
				'group'       => esc_html__( 'Taxonomy Filter', 'lisner-core' ),
				'admin_label' => true,
				'value'       => lisner_shortcodes::get_taxonomy_terms( 'listing_tag', true, false ),
				'dependency'  => array(
					'element' => 'taxonomy',
					'value'   => 'listing_tag'
				),
				'description' => esc_html__( 'Manually set category terms that will be displayed or leave empty to automatize process.', 'lisner-core' )
			),
			array(
				'type'        => 'select2',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Specific Amenities', 'lisner-core' ),
				'param_name'  => 'post__in_amenities',
				'group'       => esc_html__( 'Taxonomy Filter', 'lisner-core' ),
				'admin_label' => true,
				'value'       => lisner_shortcodes::get_taxonomy_terms( 'listing_amenity', true, false ),
				'dependency'  => array(
					'element' => 'taxonomy',
					'value'   => 'listing_amenity'
				),
				'description' => esc_html__( 'Manually set category terms that will be displayed or leave empty to automatize process.', 'lisner-core' )
			),
			array(
				'type'        => 'select2',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Specific Locations', 'lisner-core' ),
				'param_name'  => 'post__in_locations',
				'group'       => esc_html__( 'Taxonomy Filter', 'lisner-core' ),
				'admin_label' => true,
				'value'       => lisner_shortcodes::get_taxonomy_terms( 'listing_location', true, false ),
				'dependency'  => array(
					'element' => 'taxonomy',
					'value'   => 'listing_location'
				),
				'description' => esc_html__( 'Manually set category terms that will be displayed or leave empty to automatize process.', 'lisner-core' )
			),
		);
		$sorting   = array(
			array(
				'type'        => 'dropdown',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Taxonomies Order', 'lisner-core' ),
				'param_name'  => 'order_by',
				'group'       => esc_html__( 'Sorting', 'lisner-core' ),
				'admin_label' => true,
				'value'       => array(
					''                                            => '',
					esc_html__( 'Name', 'lisner-core' )           => 'name',
					esc_html__( 'Taxonomy Count', 'lisner-core' ) => 'count',
					esc_html__( 'Randomized', 'lisner-core' )     => 'randomized',
				),
				'description' => esc_html__( 'Choose the way taxonomies should be ordered by.', 'lisner-core' )
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
			/*array(
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
			),*/
			/*array(
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
			),*/
			/*array(
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
			),*/
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
 * Render shortcode html templates
 *
 * @param $atts
 * @param $content
 *
 * @return string
 */
if ( ! function_exists( 'lisner_taxonomy_render' ) ) {
	function lisner_taxonomy_render( $atts, $content ) {
		shortcode_atts( array(
			// template
			'lisner_taxonomy_template' => '',

			// args
			'taxonomy'                 => '',
			'taxonomy_hierarchy'       => '',
			'taxonomy_count'           => '',
			'taxonomy_hide_empty'      => '',
			'post__in_categories'      => '',
			'post__in_tags'            => '',
			'post__in_amenities'       => '',
			'post__in_locations'       => '',
			'order_by'                 => '',

			// title section
			'display_title'            => '',
			'title'                    => '',
			'subtitle'                 => '',

			// button
			'display_button'           => true,
			'button_link'              => '',

			// button icon
			'display_button_icon'      => '',
			'button_icon_set'          => '',
			'button_icon_material'     => '',
			'button_icon_fontawesome'  => '',
			'button_icon_openiconic'   => '',
			'button_icon_typicons'     => '',
			'button_icon_entypo'       => '',

			// show taxonomy count
			'display_count'            => '',
		), $atts );
		static $unique_id = 1;
		$atts['unique_id'] = $unique_id ++;

		// get taxonomy args
		$taxonomy_selected = '';
		$atts['taxonomy']  = isset( $atts['taxonomy'] ) ? $atts['taxonomy'] : 'job_listing_category';
		switch ( $atts['taxonomy'] ) :
			case 'listing_location':
				$taxonomy_selected = 'locations';
				break;
			case 'job_listing_category':
				$taxonomy_selected = 'categories';
				break;
			case 'listing_tags':
				$taxonomy_selected = 'tags';
				break;
			case 'listing_amenities':
				$taxonomy_selected = 'amenities';
				break;
		endswitch;
		$terms_args                   = array();
		$terms_args['taxonomy']       = isset( $atts['taxonomy'] ) ? $atts['taxonomy'] : 'job_listing_category';
		$terms_args['hide_empty']     = $atts['taxonomy_hide_empty'] ? true : false;
		$terms_args['specific_terms'] = isset( $atts["post__in_{$taxonomy_selected}"] ) ? $atts["post__in_{$taxonomy_selected}"] : '';
		$terms_args['include']        = isset( $terms_args['specific_terms'] ) ? $terms_args['specific_terms'] : array();

		$atts['order_by'] = isset( $atts['order_by'] ) ? $atts['order_by'] : '';
		if ( 'count' == $atts['order_by'] ) {
			$terms_args['orderby'] = $atts['order_by'];
			$terms_args['order']   = 'DESC';
		} else if ( 'randomized' == $atts['order_by'] ) {
			$terms_args['number'] = 0;
		}

		// get taxonomy terms
		$atts['taxonomy_hierarchy'] = isset( $atts['taxonomy_hierarchy'] ) ? $atts['taxonomy_hierarchy'] : '';
		$terms                      = get_terms( $terms_args );
		$term_ids                   = array();
		if ( $terms ) {
			if ( 'parent' == $atts['taxonomy_hierarchy'] ) {
				$terms_args['parent'] = 0;
				foreach ( $terms as $term ) {
					if ( 0 == $term->parent ) {
						$term_ids[] = $term->term_id;
					}
				}
			} else if ( 'children' == $atts['taxonomy_hierarchy'] ) {
				foreach ( $terms as $term ) {
					if ( 0 != $term->parent ) {
						$term_ids[] = $term->term_id;
					}
				}
			} else {
				foreach ( $terms as $term ) {
					$term_ids[] = $term->term_id;
				}
			}
			if ( 'randomized' == $atts['order_by'] ) {
				shuffle( $term_ids );
			}
			$acceptable_terms       = array_slice( $term_ids, 0, $atts['taxonomy_count'] );
			$terms_args['include']  = implode( ',', $acceptable_terms );
			$terms                  = get_terms( $terms_args );
			$atts['taxonomy_terms'] = array();
			// if only parents are allowed
			foreach ( $terms as $term ) {
				$atts['taxonomy_terms'][ $term->term_id ] = $term->name;
			}
		}

		// get template from folder: templates/shortcodes/taxonomy
		ob_start();
		$template = isset ( $atts['lisner_taxonomy_template'] ) ? $atts['lisner_taxonomy_template'] : 1;

		include lisner_helper::get_template_part( "taxonomy-{$template}", 'shortcodes/taxonomy', $atts );

		return ob_get_clean();

	}
}
