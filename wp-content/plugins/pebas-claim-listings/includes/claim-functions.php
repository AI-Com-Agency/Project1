<?php
/**
 * Claim listings functions
 *
 * @author pebas
 * @version 1.0.0
 */

if ( ! function_exists( 'pebas_claim_listings_get_template_part' ) ) {
	/**
	 * Get template part
	 *
	 * @param $template
	 * @param string $folder
	 * @param array $args
	 *
	 * @return string
	 */
	function pebas_claim_listings_get_template_part( $template, $folder = '', $args = array() ) {
		if ( ! empty( $args ) && is_array( $args ) ) {
			extract( $args );
		}

		if ( empty( $folder ) ) {
			$dir = PEBAS_CL_DIR . "templates/{$template}.php";
		} else {
			$dir = PEBAS_CL_DIR . "templates/{$folder}/{$template}.php";
		}

		return $dir;
	}
}
