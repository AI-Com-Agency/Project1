<?php
/**
 * Lisner shortcode functions / clients shortcode
 *
 * @author pebas
 * @version 1.0.0
 */

/**
 * Map shortcode parameters
 *
 * @return array
 */
if ( ! function_exists( 'lisner_clients_settings' ) ) {
	function lisner_clients_settings() {
		$button = array(
			array(
				'type'        => 'attach_images',
				'holder'      => '',
				'class'       => '',
				'heading'     => esc_html__( 'Add Clients Images', 'lisner-core' ),
				'param_name'  => 'clients_images',
				'group'       => esc_html__( 'Clients Section', 'lisner-core' ),
				'admin_label' => true,
				'description' => esc_html__( 'Add clients logos for the clients section', 'lisner-core' )
			),

		);

		return array_merge( $button );
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
if ( ! function_exists( 'lisner_clients_render' ) ) {
	function lisner_clients_render( $atts, $content ) {
		shortcode_atts( array(
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

		// get template from folder: templates/shortcodes/clients
		ob_start();
		include lisner_helper::get_template_part( 'clients', 'shortcodes', $atts );

		return ob_get_clean();

	}
}
