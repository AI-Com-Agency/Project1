<?php
/**
 * Lisner shortcode functions / title shortcode
 *
 * @author pebas
 * @version 1.0.0
 */

/**
 * Map shortcode parameters
 *
 * @return array
 */
if ( ! function_exists( 'lisner_title_settings' ) ) {
	function lisner_title_settings() {
		$title = array(
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
		);

		return array_merge( $title );
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
if ( ! function_exists( 'lisner_title_render' ) ) {
	function lisner_title_render( $atts, $content ) {
		shortcode_atts( array(
			// title section
			'display_title' => '',
			'title'         => '',
			'subtitle'      => '',
		), $atts );
		static $unique_id = 1;
		$atts['unique_id'] = $unique_id ++;

		// get template from folder: templates/shortcodes/listing
		ob_start();
		include lisner_helper::get_template_part( 'title', 'shortcodes/partials', $atts );

		return ob_get_clean();

	}
}
