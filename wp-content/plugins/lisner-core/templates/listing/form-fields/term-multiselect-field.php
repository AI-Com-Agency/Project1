<?php
/**
 * Shows term `select` (multiple) form field on job listing forms.
 *
 * This template can be overridden by copying it to yourtheme/job_manager/form-fields/term-multiselect-field.php.
 *
 * @see         https://wpjobmanager.com/document/template-overrides/
 * @author      Automattic
 * @package     WP Job Manager
 * @category    Template
 * @version     1.31.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Get selected value.
$post_id = isset( $_REQUEST['job_id'] ) ? $_REQUEST['job_id'] : '';
if ( isset( $field['value'] ) && ! empty( $field['value'] ) ) {
	$selected = $field['value'];
} elseif ( ! empty( $post_id ) ) {
	$selection = get_the_terms( $post_id, $field['taxonomy'] );
	$selected  = array();
	if ( is_array( $selection ) ) {
		foreach ( $selection as $term ) {
			$selected[] = $term->term_id;
		}
	}
} elseif ( ! empty( $field['default'] ) && is_int( $field['default'] ) ) {
	$selected = $field['default'];
} elseif ( ! empty( $field['default'] ) && ( $term = get_term_by( 'slug', $field['default'], $field['taxonomy'] ) ) ) {
	$selected = $term->term_id;
} else {
	$selected = '';
}

wp_enqueue_script( 'wp-job-manager-term-multiselect' );

$args = array(
	'taxonomy'     => $field['taxonomy'],
	'hierarchical' => 1,
	'name'         => isset( $field['name'] ) ? $field['name'] : $key,
	'orderby'      => 'name',
	'selected'     => $selected,
	'hide_empty'   => false
);

if ( isset( $field['placeholder'] ) && ! empty( $field['placeholder'] ) ) {
	$args['placeholder'] = $field['placeholder'];
}
?>
<div class="lisner-taxonomy-field">
	<?php $label_name = isset( $field['label_name'] ) ? '<span class="lisner-taxonomy-label">' . esc_html( $field['label_name'] ) . '</span>' : ''; ?>
	<i class="lisner-taxonomy-icon material-icons mf"><?php echo esc_attr( 'keyboard_arrow_down' ); ?></i>
	<?php echo wp_kses_post( $label_name ); ?>
	<?php
	job_manager_dropdown_categories( apply_filters( 'job_manager_term_multiselect_field_args', $args ) );
	?>
</div>

<?php
if ( ! empty( $field['description'] ) ) : ?>
	<small class="description"><?php echo wp_kses_post( $field['description'] ); ?></small><?php endif; ?>
