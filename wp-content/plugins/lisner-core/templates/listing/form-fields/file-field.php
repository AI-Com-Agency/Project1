<?php
/**
 * Shows the `file` form field on job listing forms.
 *
 * This template can be overridden by copying it to yourtheme/job_manager/form-fields/file-field.php.
 *
 * @see         https://wpjobmanager.com/document/template-overrides/
 * @author      Automattic
 * @package     WP Job Manager
 * @category    Template
 * @version     1.27.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$classes            = array( 'input-text lisner-input-file' );
$allowed_mime_types = array_keys( ! empty( $field['allowed_mime_types'] ) ? $field['allowed_mime_types'] : get_allowed_mime_types() );
$field_name         = isset( $field['name'] ) ? $field['name'] : $key;
$field_name         .= ! empty( $field['multiple'] ) ? '[]' : '';

if ( ! empty( $field['ajax'] ) && job_manager_user_can_upload_file_via_ajax() ) {
	wp_enqueue_script( 'wp-job-manager-ajax-file-upload' );
	$classes[] = 'wp-job-manager-file-upload';
}
$job_id = isset( $_REQUEST['job_id'] ) ? $_REQUEST['job_id'] : '';
?>
<?php if ( 'listing_gallery' == $key ) : ?>
	<?php if ( ! is_user_logged_in() ) : ?>
        <div class="file-upload-container">
            <div class="file-upload-info d-flex">
                <span class="file-upload-label"><?php esc_html_e( 'Images:', 'lisner-core' ); ?></span>
                <span class="file-upload-files"
                      data-title="<?php esc_attr_e( 'No images chosen', 'lisner-core' ); ?>"><?php esc_html_e( 'No images chosen', 'lisner-core' ); ?></span>
                <i class="material-icons mf ml-auto"><?php echo esc_html( 'add_a_photo_alternate' ); ?></i>
            </div>
            <input type="file" class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"
                   title=" "
                   data-multiple-caption="<?php printf( esc_html__( '%s files selected', 'lisner-core' ), '{count}' ); ?>"
                   data-file_types="<?php echo esc_attr( implode( '|', $allowed_mime_types ) ); ?>" <?php if ( ! empty( $field['multiple'] ) ) {
				echo 'multiple';
			} ?>
                   name="<?php echo esc_attr( isset( $field['name'] ) ? $field['name'] : $key ); ?><?php if ( ! empty( $field['multiple'] ) ) {
				       echo '[]';
			       } ?>" id="<?php echo esc_attr( $key ); ?>"
                   placeholder="<?php echo empty( $field['placeholder'] ) ? '' : esc_attr( $field['placeholder'] ); ?>" />
        </div>
	<?php else: ?>
        <input type="file" class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"
               title=" "
               data-multiple-caption="<?php printf( esc_html__( '%s files selected', 'lisner-core' ), '{count}' ); ?>"
               data-file_types="<?php echo esc_attr( implode( '|', $allowed_mime_types ) ); ?>" <?php if ( ! empty( $field['multiple'] ) ) {
			echo 'multiple';
		} ?>
               name="<?php echo esc_attr( isset( $field['name'] ) ? $field['name'] : $key ); ?><?php if ( ! empty( $field['multiple'] ) ) {
			       echo '[]';
		       } ?>" id="<?php echo esc_attr( $key ); ?>"
               placeholder="<?php echo empty( $field['placeholder'] ) ? '' : esc_attr( $field['placeholder'] ); ?>" />
	<?php endif; ?>
	<?php if ( is_user_logged_in() ) : ?>
        <div class="file-upload-wrapper">
			<?php esc_html_e( 'Drag images here or click to upload them.', 'lisner-core' ); ?>
        </div>
		<?php $values = rwmb_meta( '_listing_gallery', '', $job_id ); ?>
        <div class="job-manager-uploaded-files d-flex">
			<?php if ( ! empty( $values ) ) : ?>
				<?php if ( is_array( $values ) ) : ?>
					<?php foreach ( $values as $value ) : ?>
						<?php get_job_manager_template( 'form-fields/uploaded-file-html.php', array(
							'key'   => $key,
							'name'  => 'current_' . $field_name,
							'value' => $value['ID'],
							'field' => $field
						) ); ?>
					<?php endforeach; ?>
				<?php endif; ?>
			<?php endif; ?>
        </div>
	<?php endif; ?>
    <small class="description">
		<?php if ( ! empty( $field['description'] ) ) : ?>
			<?php echo $field['description']; ?>
		<?php else : ?>
			<?php printf( __( 'Maximum file size: %s.', 'wp-job-manager' ), size_format( wp_max_upload_size() ) ); ?>
		<?php endif; ?>
    </small>
<?php elseif ( 'listing_cover' == $key || 'listing_logo' == $key ) : ?>
	<?php if ( ! is_user_logged_in() ) : ?>
        <div class="file-upload-container">
            <div class="file-upload-info d-flex">
                <span class="file-upload-label"><?php esc_html_e( 'Image:', 'lisner-core' ); ?></span>
                <span class="file-upload-files"
                      data-title="<?php esc_attr_e( 'No image chosen', 'lisner-core' ); ?>"><?php esc_html_e( 'No image chosen', 'lisner-core' ); ?></span>
                <i class="material-icons mf ml-auto"><?php echo esc_html( 'add_a_photo_alternate' ); ?></i>
            </div>
            <input type="file" class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"
                   title=" "
                   data-file_types="<?php echo esc_attr( implode( '|', $allowed_mime_types ) ); ?>" <?php if ( ! empty( $field['multiple'] ) ) {
				echo 'multiple';
			} ?>
                   name="<?php echo esc_attr( isset( $field['name'] ) ? $field['name'] : $key ); ?><?php if ( ! empty( $field['multiple'] ) ) {
				       echo '[]';
			       } ?>" id="<?php echo esc_attr( $key ); ?>"
                   placeholder="<?php echo empty( $field['placeholder'] ) ? '' : esc_attr( $field['placeholder'] ); ?>" />
        </div>
	<?php else: ?>
        <input type="file" class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"
               title=" "
               data-file_types="<?php echo esc_attr( implode( '|', $allowed_mime_types ) ); ?>" <?php if ( ! empty( $field['multiple'] ) ) {
			echo 'multiple';
		} ?>
               name="<?php echo esc_attr( isset( $field['name'] ) ? $field['name'] : $key ); ?><?php if ( ! empty( $field['multiple'] ) ) {
			       echo '[]';
		       } ?>" id="<?php echo esc_attr( $key ); ?>"
               placeholder="<?php echo empty( $field['placeholder'] ) ? '' : esc_attr( $field['placeholder'] ); ?>" />
	<?php endif; ?>
	<?php if ( is_user_logged_in() ) : ?>
        <div class="file-upload-wrapper file-upload-wrapper-single">
			<?php esc_html_e( 'Drag image here or click to upload it.', 'lisner-core' ); ?>
        </div>
		<?php if ( $key == 'listing_cover' ) : ?>
			<?php $values = rwmb_meta( '_listing_cover', '', $job_id ); ?>
		<?php else: ?>
			<?php $values = rwmb_meta( '_listing_logo', '', $job_id ); ?>
		<?php endif; ?>
        <div class="job-manager-uploaded-files d-flex">
			<?php if ( ! empty( $values ) ) : ?>
				<?php if ( is_array( $values ) ) : ?>
					<?php foreach ( $values as $value ) : ?>
						<?php get_job_manager_template( 'form-fields/uploaded-file-html.php', array(
							'key'   => $key,
							'name'  => 'current_' . $field_name,
							'value' => $value['ID'],
							'field' => $field
						) ); ?>
					<?php endforeach; ?>
				<?php endif; ?>
			<?php endif; ?></div>
	<?php endif; ?>
    <small class="description">
		<?php if ( ! empty( $field['description'] ) ) : ?>
			<?php echo $field['description']; ?>
		<?php else : ?>
			<?php printf( __( 'Maximum file size: %s.', 'wp-job-manager' ), size_format( wp_max_upload_size() ) ); ?>
		<?php endif; ?>
    </small>
<?php else : ?>
    <div class="job-manager-uploaded-files">
		<?php $values = rwmb_meta( '_listing_files', '', $job_id ); ?>
		<?php if ( ! empty( $values ) ) : ?>
			<?php if ( is_array( $values ) ) : ?>
				<?php foreach ( $values as $value ) : ?>
					<?php get_job_manager_template( 'form-fields/uploaded-file-html.php', array(
						'key'   => $key,
						'name'  => 'current_' . $field_name,
						'value' => $value['ID'],
						'field' => $field
					) ); ?>
				<?php endforeach; ?>
			<?php elseif ( $value = $field['value'] ) : ?>
				<?php get_job_manager_template( 'form-fields/uploaded-file-html.php', array(
					'key'   => $key,
					'name'  => 'current_' . $field_name,
					'value' => $value['ID'],
					'field' => $field
				) ); ?>
			<?php endif; ?>
		<?php endif; ?>
    </div>

	<?php if ( is_user_logged_in() ) : ?>
        <input type="file" class="<?php echo esc_attr( implode( ' ', $classes ) ); ?> form-control"
               data-file_types="<?php echo esc_attr( implode( '|', $allowed_mime_types ) ); ?>" <?php if ( ! empty( $field['multiple'] ) ) {
			echo 'multiple';
		} ?>
               name="<?php echo esc_attr( isset( $field['name'] ) ? $field['name'] : $key ); ?><?php if ( ! empty( $field['multiple'] ) ) {
			       echo '[]';
		       } ?>" id="<?php echo esc_attr( $key ); ?>"
               placeholder="<?php echo empty( $field['placeholder'] ) ? '' : esc_attr( $field['placeholder'] ); ?>" />
	<?php endif; ?>
    <div class="comment-respond">
        <div class="input-group input-group-alt">
            <i class="material-icons mf"><?php esc_html( 'cloud_upload' ); ?></i>
            <label class="hidden-xs-up" for="listing_files"><?php esc_html_e( 'Files', 'lisner-core' ) ?></label>
			<?php if ( ! is_user_logged_in() ) : ?>
                <input type="file" class="<?php echo esc_attr( implode( ' ', $classes ) ); ?> form-control"
                       data-file_types="<?php echo esc_attr( implode( '|', $allowed_mime_types ) ); ?>" <?php if ( ! empty( $field['multiple'] ) ) {
					echo 'multiple';
				} ?>
                       name="<?php echo esc_attr( isset( $field['name'] ) ? $field['name'] : $key ); ?><?php if ( ! empty( $field['multiple'] ) ) {
					       echo '[]';
				       } ?>" id="<?php echo esc_attr( $key ); ?>"
                       placeholder="<?php echo empty( $field['placeholder'] ) ? '' : esc_attr( $field['placeholder'] ); ?>" />
			<?php endif; ?>
            <span class="file-upload-label"><?php esc_html_e( 'Browse', 'lisner-core' ); ?></span>
            <span class="file-upload-files"></span>
            <small class="description">
				<?php if ( ! empty( $field['description'] ) ) : ?>
					<?php echo $field['description']; ?>
				<?php else : ?>
					<?php printf( __( 'Maximum file size: %s.', 'wp-job-manager' ), size_format( wp_max_upload_size() ) ); ?>
				<?php endif; ?>
            </small>
        </div>
    </div>
<?php endif; ?>
