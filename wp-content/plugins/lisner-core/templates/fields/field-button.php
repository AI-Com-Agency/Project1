<?php
/**
 * Home / Hero search button template
 *
 * @author pebas
 * @package field/field-button
 * @version 1.0.0
 *
 * @param $args
 */
?>
<?php $btn_text = isset( $args['use_icon'] ) ? '<i class="material-icons">' . esc_html( 'search' ) . '</i>' : esc_html__( 'Search', 'lisner-core' ); ?>
<div class="hero-search-field hero-search-field-button">
    <button type="submit" form="search-form" value="<?php esc_attr_e( 'Search', 'lisner-core' ); ?>"
            class="btn btn-secondary btn-search <?php echo isset( $args['use_icon'] ) ? esc_attr( 'btn-small' ) : ''; ?>">
		<?php echo wp_kses_post( $btn_text ); ?>
    </button>
</div>
