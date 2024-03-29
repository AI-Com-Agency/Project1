<?php
/**
 * Shows the `text` form field on job listing forms.
 *
 * This template can be overridden by copying it to yourtheme/job_manager/form-fields/text-field.php.
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
?>
<div class="input-group">
	<?php if ( isset( $key ) && strstr( $key, 'listing_social' ) ) : ?>
		<?php $social = explode( '__', $key ); ?>
        <span class="input-group-prepend <?php echo esc_attr( $social[1] ) ?>">
            <i class="fab fa-<?php echo esc_html( $social[1] ); ?>"></i>
        </span>
	<?php endif; ?>
    <input type="text" class="form-control"
           name="<?php echo esc_attr( isset( $field['name'] ) ? $field['name'] : $key ); ?>"<?php if ( isset( $field['autocomplete'] ) && false === $field['autocomplete'] ) {
		echo ' autocomplete="off"';
	} ?> id="<?php echo esc_attr( $key ); ?>"
           placeholder="<?php echo empty( $field['placeholder'] ) ? '' : esc_attr( $field['placeholder'] ); ?>"
           value="<?php echo isset( $field['value'] ) ? esc_attr( $field['value'] ) : ''; ?>"
           maxlength="<?php echo esc_attr( ! empty( $field['maxlength'] ) ? $field['maxlength'] : '' ); ?>" <?php if ( ! empty( $field['required'] ) ) {
		echo 'required';
	} ?> />
	<?php if ( ! empty( $field['description'] ) ) : ?>
        <small class="description"><?php echo wp_kses_post( $field['description'] ); ?></small><?php endif; ?>
</div>
