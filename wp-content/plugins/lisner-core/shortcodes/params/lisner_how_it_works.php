<?php
/**
 * Lisner shortcode functions / how it works shortcode
 *
 * @author pebas
 * @version 1.0.0
 */

/**
 * Map shortcode parameters
 *
 * @return array
 */
if ( ! function_exists( 'lisner_how_it_works_settings' ) ) {
	function lisner_how_it_works_settings() {
		$templates = array(
			array(
				'type'        => 'lisner_image_radio',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'How It Works Templates', 'lisner-core' ),
				'param_name'  => 'lisner_hiw_template',
				'group'       => esc_html__( 'Templates', 'lisner-core' ),
				'admin_label' => true,
				'value'       => array(
					esc_html__( 'How It Works Template 1', 'lisner-core' ) => '1',
					esc_html__( 'How It Works Template 2', 'lisner-core' ) => '2',
					esc_html__( 'How It Works Template 3', 'lisner-core' ) => '3',
					esc_html__( 'How It Works Template 4', 'lisner-core' ) => '4',
				),
				'description' => esc_html__( 'Choose how it works template style', 'lisner-core' )
			),
			array(
				'type'        => 'dropdown',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Chosen Template Style', 'lisner-core' ),
				'param_name'  => 'template_4_style',
				'group'       => esc_html__( 'Templates', 'lisner-core' ),
				'admin_label' => true,
				'value'       => array(
					esc_html__( 'Style 1', 'lisner-core' ) => '1',
					esc_html__( 'Style 2', 'lisner-core' ) => '2',
				),
				'description' => esc_html__( 'Choose whether you wish to display button', 'lisner-core' )
			),
		);
		$title     = array(
			array(
				'type'        => 'dropdown',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Display Button?', 'lisner-core' ),
				'param_name'  => 'display_button',
				'group'       => esc_html__( 'Button Section', 'lisner-core' ),
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
				'group'       => esc_html__( 'Button Section', 'lisner-core' ),
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
				'group'       => esc_html__( 'Button Section', 'lisner-core' ),
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
				'type'        => 'colorpicker',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Button Background Color', 'lisner-core' ),
				'param_name'  => 'button_bg',
				'group'       => esc_html__( 'Button Section', 'lisner-core' ),
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
				'group'       => esc_html__( 'Button Section', 'lisner-core' ),
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
				'group'       => esc_html__( 'Button Section', 'lisner-core' ),
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
				'group'       => esc_html__( 'Button Section', 'lisner-core' ),
				'admin_label' => true,
				'value'       => '#fff',
				'description' => esc_html__( 'Choose button font color on hover', 'lisner-core' )
			),
		);

		$content = array(
			array(
				'type'        => 'textarea_html',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Text', 'lisner-core' ),
				'param_name'  => 'content',
				'group'       => esc_html__( 'Content', 'lisner-core' ),
				'admin_label' => true,
				'value'       => '',
				'dependency'  => array(
					'element' => 'lisner_hiw_template',
					'value'   => array( '1', '2' ),
				),
				'description' => esc_html__( 'Enter the text for the how it works section', 'lisner-core' )
			),
			// style template 2
			array(
				'type'        => 'dropdown',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Display Video or Image', 'lisner-core' ),
				'param_name'  => 'video_or_image',
				'group'       => esc_html__( 'Content', 'lisner-core' ),
				'admin_label' => true,
				'dependency'  => array(
					'element' => 'lisner_hiw_template',
					'value'   => array( '2' ),
				),
				'value'       => array(
					esc_html__( 'Image', 'lisner-core' ) => 'image',
					esc_html__( 'Video', 'lisner-core' ) => 'video',
				),
				'description' => esc_html__( 'Choose whether you wish to display video or image for this style template number 2', 'lisner-core' )
			),
			array(
				'type'        => 'textfield',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Title', 'lisner-core' ),
				'param_name'  => 'title',
				'group'       => esc_html__( 'Content', 'lisner-core' ),
				'admin_label' => true,
				'value'       => '',
				'dependency'  => array(
					'element' => 'lisner_hiw_template',
					'value'   => array( '2', '3' ),
				),
				'description' => esc_html__( 'Enter the title of the section. To make word bolder use signs like: -_TEXT_-', 'lisner-core' )
			),
			// style template 1
			array(
				'type'        => 'attach_image',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Background Image', 'lisner-core' ),
				'param_name'  => 'hiw_image',
				'group'       => esc_html__( 'Content', 'lisner-core' ),
				'admin_label' => true,
				'dependency'  => array(
					'element' => 'lisner_hiw_template',
					'value'   => array( '1', '2' ),
				),
				'description' => esc_html__( 'Choose background image for the element.', 'lisner-core' )
			),
			array(
				'type'        => 'textfield',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'How It Works Video', 'lisner-core' ),
				'param_name'  => 'hiw_video',
				'group'       => esc_html__( 'Content', 'lisner-core' ),
				'admin_label' => true,
				'dependency'  => array(
					'element' => 'lisner_hiw_template',
					'value'   => array( '1', '2' ),
				),
				'description' => esc_html__( 'Enter link to the video you wish to display. ( YouTube, Vimeo etc )', 'lisner-core' )
			),
			array(
				'type'        => 'attach_image',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Background Video Image', 'lisner-core' ),
				'param_name'  => 'hiw_video_image',
				'group'       => esc_html__( 'Content', 'lisner-core' ),
				'admin_label' => true,
				'dependency'  => array(
					'element' => 'lisner_hiw_template',
					'value'   => array( '1', '2' ),
				),
				'description' => esc_html__( 'Choose background image for the video element that will be displayed in place of video.', 'lisner-core' )
			),
		);

		$tabs = array(
			array(
				'type'        => 'dropdown',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Tabs Navigation Style', 'lisner-core' ),
				'param_name'  => 'tab_nav_style',
				'group'       => esc_html__( 'Tabs', 'lisner-core' ),
				'admin_label' => true,
				'value'       => array(
					esc_html__( 'Default', 'lisner-core' )   => 'default',
					esc_html__( 'Stretched', 'lisner-core' ) => 'stretched'
				),
				'dependency'  => array(
					'element' => 'lisner_hiw_template',
					'value'   => array( '3' ),
				),
				'description' => esc_html__( 'Please enter the title of the tab', 'lisner-core' )
			),
			array(
				'type'        => 'textfield',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Tab Section Title', 'lisner-core' ),
				'param_name'  => 'tab_title',
				'group'       => esc_html__( 'Tabs', 'lisner-core' ),
				'admin_label' => true,
				'value'       => '',
				'dependency'  => array(
					'element' => 'lisner_hiw_template',
					'value'   => array( '3' ),
				),
				'description' => esc_html__( 'Please enter the title of the tab', 'lisner-core' )
			),
			array(
				'type'       => 'param_group',
				'value'      => '',
				'param_name' => 'tab',
				'group'      => esc_html__( 'Tabs', 'lisner-core' ),
				'dependency' => array(
					'element' => 'lisner_hiw_template',
					'value'   => array( '3' ),
				),
				'params'     => array(
					array(
						'type'        => 'attach_image',
						'holder'      => '',
						'class'       => '',
						'heading'     => esc_html__( 'Tab Image', 'lisner-core' ),
						'param_name'  => 'tab_image',
						'group'       => esc_html__( 'Tabs', 'lisner-core' ),
						'admin_label' => true,
						'value'       => '',
						'description' => esc_html__( 'Please upload the image for the tab', 'lisner-core' )
					),
					array(
						'type'        => 'textfield',
						'holder'      => '',
						'class'       => '',
						'heading'     => esc_html__( 'Tab Heading', 'lisner-core' ),
						'param_name'  => 'tab_heading',
						'group'       => esc_html__( 'Tabs', 'lisner-core' ),
						'admin_label' => true,
						'value'       => '',
						'description' => esc_html__( 'Please enter the heading of the tab', 'lisner-core' )
					),
					array(
						'type'        => 'textarea',
						'holder'      => '',
						'class'       => '',
						'heading'     => esc_html__( 'Tab Text', 'lisner-core' ),
						'param_name'  => 'tab_text',
						'group'       => esc_html__( 'Tabs', 'lisner-core' ),
						'admin_label' => true,
						'value'       => '',
						'description' => esc_html__( 'Please enter text for the tab.', 'lisner-core' )
					),
				)
			)
		);

		$tab_image = array(
			array(
				'type'        => 'textfield',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Title', 'lisner-core' ),
				'param_name'  => 'tab_alt_title',
				'group'       => esc_html__( 'Tabs', 'lisner-core' ),
				'admin_label' => true,
				'dependency'  => array(
					'element' => 'lisner_hiw_template',
					'value'   => array( '4' ),
				),
				'description' => esc_html__( 'Enter the subtitle for your tab. To make word bold put it like this: .-WORD-.', 'lisner-core' )
			),
			array(
				'type'        => 'textfield',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Subtitle', 'lisner-core' ),
				'param_name'  => 'tab_alt_subtitle',
				'group'       => esc_html__( 'Tabs', 'lisner-core' ),
				'admin_label' => true,
				'dependency'  => array(
					'element' => 'lisner_hiw_template',
					'value'   => array( '4' ),
				),
				'description' => esc_html__( 'Enter the subtitle for your tab', 'lisner-core' )
			),
			array(
				'type'        => 'textfield',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Link', 'lisner-core' ),
				'param_name'  => 'tab_alt_link',
				'group'       => esc_html__( 'Tabs', 'lisner-core' ),
				'admin_label' => true,
				'dependency'  => array(
					'element' => 'lisner_hiw_template',
					'value'   => array( '4' ),
				),
				'description' => esc_html__( 'Enter the link where the tab should lead a user.', 'lisner-core' )
			),
			array(
				'type'        => 'attach_image',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Background Image', 'lisner-core' ),
				'param_name'  => 'tab_bg_image',
				'group'       => esc_html__( 'Tabs', 'lisner-core' ),
				'admin_label' => true,
				'dependency'  => array(
					'element' => 'lisner_hiw_template',
					'value'   => array( '4' ),
				),
				'description' => esc_html__( 'Choose background image for the video element that will be displayed in place of video.', 'lisner-core' )
			),
		);
		$tabs      = array_merge( $tabs, $tab_image );

		$tabs_2 = array(
			array(
				'type'        => 'textfield',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Tab Section Title', 'lisner-core' ),
				'param_name'  => 'tab_title_2',
				'group'       => esc_html__( 'Tabs 2', 'lisner-core' ),
				'admin_label' => true,
				'value'       => '',
				'dependency'  => array(
					'element' => 'lisner_hiw_template',
					'value'   => array( '3' ),
				),
				'description' => esc_html__( 'Please enter the title of the tab. To make word bold put it like this: .-WORD-.', 'lisner-core' )
			),
			array(
				'type'       => 'param_group',
				'value'      => '',
				'param_name' => 'tab_2',
				'group'      => esc_html__( 'Tabs 2', 'lisner-core' ),
				'dependency' => array(
					'element' => 'lisner_hiw_template',
					'value'   => array( '3' ),
				),
				'params'     => array(
					array(
						'type'        => 'attach_image',
						'holder'      => '',
						'class'       => '',
						'heading'     => esc_html__( 'Tab Image', 'lisner-core' ),
						'param_name'  => 'tab_image_2',
						'group'       => esc_html__( 'Tabs 2', 'lisner-core' ),
						'admin_label' => true,
						'value'       => '',
						'description' => esc_html__( 'Please upload the image for the tab', 'lisner-core' )
					),
					array(
						'type'        => 'textfield',
						'holder'      => '',
						'class'       => '',
						'heading'     => esc_html__( 'Tab Heading', 'lisner-core' ),
						'param_name'  => 'tab_heading_2',
						'group'       => esc_html__( 'Tabs 2', 'lisner-core' ),
						'admin_label' => true,
						'value'       => '',
						'description' => esc_html__( 'Please enter the heading of the tab', 'lisner-core' )
					),
					array(
						'type'        => 'textarea',
						'holder'      => '',
						'class'       => '',
						'heading'     => esc_html__( 'Tab Text', 'lisner-core' ),
						'param_name'  => 'tab_text_2',
						'group'       => esc_html__( 'Tabs 2', 'lisner-core' ),
						'admin_label' => true,
						'value'       => '',
						'description' => esc_html__( 'Please enter text for the tab.', 'lisner-core' )
					),
				)
			)
		);

		$tab_image_2 = array(
			array(
				'type'        => 'textfield',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Title', 'lisner-core' ),
				'param_name'  => 'tab_alt_title_2',
				'group'       => esc_html__( 'Tabs 2', 'lisner-core' ),
				'admin_label' => true,
				'dependency'  => array(
					'element' => 'lisner_hiw_template',
					'value'   => array( '4' ),
				),
				'description' => esc_html__( 'Enter the subtitle for your tab', 'lisner-core' )
			),
			array(
				'type'        => 'textfield',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Subtitle', 'lisner-core' ),
				'param_name'  => 'tab_alt_subtitle_2',
				'group'       => esc_html__( 'Tabs 2', 'lisner-core' ),
				'admin_label' => true,
				'dependency'  => array(
					'element' => 'lisner_hiw_template',
					'value'   => array( '4' ),
				),
				'description' => esc_html__( 'Enter the subtitle for your tab', 'lisner-core' )
			),
			array(
				'type'        => 'textfield',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Link', 'lisner-core' ),
				'param_name'  => 'tab_alt_link_2',
				'group'       => esc_html__( 'Tabs 2', 'lisner-core' ),
				'admin_label' => true,
				'dependency'  => array(
					'element' => 'lisner_hiw_template',
					'value'   => array( '4' ),
				),
				'description' => esc_html__( 'Enter the link where the tab should lead a user.', 'lisner-core' )
			),
			array(
				'type'        => 'attach_image',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Background Image', 'lisner-core' ),
				'param_name'  => 'tab_bg_image_2',
				'group'       => esc_html__( 'Tabs 2', 'lisner-core' ),
				'admin_label' => true,
				'dependency'  => array(
					'element' => 'lisner_hiw_template',
					'value'   => array( '4' ),
				),
				'description' => esc_html__( 'Choose background image for the video element that will be displayed in place of video.', 'lisner-core' )
			),
		);
		$tabs_2      = array_merge( $tabs_2, $tab_image_2 );

		return array_merge( $templates, $title, $content, $tabs, $tabs_2 );
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
if ( ! function_exists( 'lisner_how_it_works_render' ) ) {
	function lisner_how_it_works_render( $atts, $content ) {
		shortcode_atts( array(
			// template
			'lisner_hiw_template'     => '',
			'template_4_style'        => '1',

			// button
			'display_button'          => true,
			'button_link'             => '',

			// button icon
			'display_button_icon'     => '',
			'button_icon_set'         => '',
			'button_icon_material'    => '',
			'button_icon_fontawesome' => '',
			'button_icon_openiconic'  => '',
			'button_icon_typicons'    => '',
			'button_icon_entypo'      => '',

			// content
			'video_or_image'          => 'image',
			'title'                   => '',
			'content'                 => '',
			'hiw_image'               => '',
			'hiw_video'               => '',
			'hiw_video_image'         => '',

			// tabs
			'tab_nav_style'           => 'default',
			'tab_title'               => '',
			'tab_image'               => '',
			'tab_heading'             => '',
			'tab_text'                => '',

			// tabs 2
			'tab_title_2'             => '',
			'tab_image_2'             => '',
			'tab_heading_2'           => '',
			'tab_text_2'              => ''
		), $atts );
		static $unique_id = 1;
		$atts['unique_id'] = $unique_id ++;

		// get template from folder: templates/shortcodes/how-it-works
		ob_start();
		$template = isset ( $atts['lisner_hiw_template'] ) ? $atts['lisner_hiw_template'] : 1;

		include lisner_helper::get_template_part( "how-it-works-{$template}", 'shortcodes/how-it-works', $atts );

		return ob_get_clean();

	}
}
