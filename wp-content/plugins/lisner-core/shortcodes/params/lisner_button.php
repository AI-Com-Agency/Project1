<?php
/**
 * Lisner shortcode functions / button shortcode
 *
 * @author pebas
 * @version 1.0.0
 */

/**
 * Map shortcode parameters
 *
 * @return array
 */
if ( ! function_exists( 'lisner_button_settings' ) ) {
	function lisner_button_settings() {
		$button = array(
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
				'type'        => 'dropdown',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Button Size', 'lisner-core' ),
				'param_name'  => 'button_size',
				'group'       => esc_html__( 'Title Section', 'lisner-core' ),
				'admin_label' => true,
				'value'       => array(
					esc_html__( 'Default', 'lisner-core' ) => 'default',
					esc_html__( 'Big', 'lisner-core' )     => 'big',
					esc_html__( 'Full', 'lisner-core' )    => 'full',
				),
				'dependency'  => array(
					'element' => 'display_button',
					'value'   => '1',
				),
				'description' => esc_html__( 'Choose whether you wish to display button icon', 'lisner-core' )
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
			/*			array(
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
						), */
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
				'group'       => esc_html__( 'Title Section', 'lisner-core' ),
				'admin_label' => true,
				'value'       => 'vc-material vc-material-add',
				'description' => esc_html__( 'Choose icon for the button', 'lisner-core' )
			),
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

		$style = array(
			array(
				'type'        => 'colorpicker',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Button Background Color', 'lisner-core' ),
				'param_name'  => 'button_bg',
				'group'       => esc_html__( 'Button Style', 'lisner-core' ),
				'admin_label' => true,
				'value'       => '#ff015b',
				'description' => esc_html__( 'Choose button background color', 'lisner-core' )
			),
			array(
				'type'        => 'colorpicker',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Button Background Color On Hover', 'lisner-core' ),
				'param_name'  => 'button_bg_hover',
				'group'       => esc_html__( 'Button Style', 'lisner-core' ),
				'admin_label' => true,
				'value'       => '#fc0043',
				'description' => esc_html__( 'Choose button background color on hover', 'lisner-core' )
			),
			array(
				'type'        => 'colorpicker',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Button Font Color', 'lisner-core' ),
				'param_name'  => 'button_font',
				'group'       => esc_html__( 'Button Style', 'lisner-core' ),
				'admin_label' => true,
				'value'       => '#fff',
				'description' => esc_html__( 'Choose button font color', 'lisner-core' )
			),
			array(
				'type'        => 'colorpicker',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Button Font Color On Hover', 'lisner-core' ),
				'param_name'  => 'button_font_hover',
				'group'       => esc_html__( 'Button Style', 'lisner-core' ),
				'admin_label' => true,
				'value'       => '#fff',
				'description' => esc_html__( 'Choose button font color on hover', 'lisner-core' )
			),
		);

		return array_merge( $button, $style );
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
if ( ! function_exists( 'lisner_button_render' ) ) {
	function lisner_button_render( $atts, $content ) {
		shortcode_atts( array(
			// button
			'display_button'          => '',
			'button_link'             => '',
			'button_size'             => 'default',

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

		// get template from folder: templates/shortcodes/listing
		ob_start();
		include lisner_helper::get_template_part( 'button', 'shortcodes/partials', $atts );

		return ob_get_clean();

	}
}
