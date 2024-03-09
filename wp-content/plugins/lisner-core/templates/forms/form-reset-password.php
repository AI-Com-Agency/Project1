<?php
/**
 * Form / Reset password form template
 *
 * @author pebas
 * @package templates/forms
 * @version 1.0.0
 *
 * @param $args
 */
?>
<!-- Form / Lost password form -->
<form class="ajax-auth" method="post">
    <!-- Field / Password 1 -->
    <div class="form-group">
        <label for="reg_password_1"><?php esc_html_e( 'New Password', 'lisner-core' ); ?><span
                    class="required"><?php echo esc_html( '*' ); ?></span></label>
        <input type="password" name="password_1" id="reg_password_1" class="form-control" value="" size="20" required/>
    </div>
    <!-- Field / Password 2 -->
    <div class="form-group">
        <label for="reg_password_2"><?php esc_html_e( 'Re-enter New Password', 'lisner-core' ); ?><span
                    class="required"><?php echo esc_html( '*' ); ?></span></label>
        <input type="password" name="password_2" id="reg_password_2" class="form-control" value="" size="20" required/>
    </div>
    <input type="hidden" name="reset_key" value="<?php echo esc_attr( $args['key'] ); ?>"/>
    <input type="hidden" name="reset_login" value="<?php echo esc_attr( $args['login'] ); ?>"/>
    <!-- Field / Submit-->
    <div class="btn-submit-group">
		<?php wp_nonce_field( 'reset_password', 'lisner-reset-password-nonce' ); ?>
        <button type="submit" name="reset_password" id="reset_password" class="btn btn-primary btn-lg"
                value="<?php esc_attr_e( 'Reset Password', 'lisner-core' ); ?>"><?php esc_html_e( 'Save', 'lisner-core' ); ?></button>
        <input type="hidden" name="lisner_reset_password" value="true"/>
	    <?php $redirect = isset( $args['redirect'] ) && is_numeric( $args['redirect'] ) ? get_permalink( $args['redirect'] ) : $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>
        <input type="hidden" name="redirect_to"
               value="<?php echo esc_url( $redirect ); ?>" />
    </div>
</form>
