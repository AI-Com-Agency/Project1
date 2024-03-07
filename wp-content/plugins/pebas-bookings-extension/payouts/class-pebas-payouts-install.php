<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * pebas_payouts_install
 */
class pebas_payouts_install {

	protected static $_instance = null;

	/**
	 * Default paid listings term name
	 *
	 * @var string
	 */
	public static $type_name = 'pebas_payout';

	/**
	 * @return null|pebas_payouts_install
	 */
	public static function instance() {
		return null === self::$_instance ? ( self::$_instance = new self ) : self::$_instance;
	}

	/**
	 * pebas_payouts_install constructor.
	 */
	public function __construct() {
		// add actions
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'woocommerce_edit_account_form', array( $this, 'paypal_email_field' ) );
		add_action( 'woocommerce_save_account_details', array( $this, 'paypal_email_save' ), 12, 1 );

		// add filters
	}

	/**
	 * Add PayPal email address field
	 * -------------------------------
	 *
	 */
	public function paypal_email_field() {
		$user_id = get_current_user_id();
		$user = get_userdata( $user_id );
		?>
		<fieldset>
			<legend><?php esc_html_e( 'PayPal Payouts', 'lisner' ); ?></legend>
			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="_payout_paypal"><?php esc_html_e( 'PayPal Email Address', 'lisner' ); ?></label>
				<input type="email" class="woocommerce-Input woocommerce-Input--paypal-email input-text" name="_payout_paypal"
				       id="_payout_paypal" autocomplete="email"
				       value="<?php echo esc_attr( get_user_meta( $user->ID, '_payout_paypal', true ) ); ?>" />
			</p>
		</fieldset>
		<?php
	}

	/*
	 * PayPal Email Address saving
	 * ----------------------------
	 *
	 *
	 */
	public function paypal_email_save( $user_id ) {
		// For Billing email (added related to your comment)
		if ( isset( $_POST['_payout_paypal'] ) ) {
			update_user_meta( $user_id, '_payout_paypal', sanitize_text_field( $_POST['_payout_paypal'] ) );
		}
	}

	/**
	 * Register report post type
	 */
	public function register_post_type() {
		$post_type = self::$type_name;
		if ( post_type_exists( $post_type ) ) {
			return;
		}

		$singular = __( 'Send Payout', 'pebas-bookings-extension' );
		$plural   = __( 'Send Payouts', 'pebas-bookings-extension' );

		$args = array(
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => false,
			'query_var'          => true,
			'rewrite'            => false,
			'capability_type'    => 'job_listing',
			'map_meta_cap'       => true,
			'has_archive'        => false,
			'hierarchical'       => false,
			'supports'           => array( 'title' ),
			'labels'             => array(
				'name'               => sprintf( __( '%s', 'pebas-bookings-extension' ), $plural ),
				'singular_name'      => sprintf( __( '%s', 'pebas-bookings-extension' ), $singular ),
				'menu_name'          => sprintf( __( '%s', 'pebas-bookings-extension' ), $plural ),
				'name_admin_bar'     => sprintf( __( '%s', 'pebas-bookings-extension' ), $plural ),
				'add_new'            => __( 'Add New Payout', 'pebas-bookings-extension' ),
				'add_new_item'       => sprintf( __( 'Add New %s', 'pebas-bookings-extension' ), $singular ),
				'new_item'           => sprintf( __( 'New %s', 'pebas-bookings-extension' ), $singular ),
				'edit_item'          => sprintf( __( 'Edit %s', 'pebas-bookings-extension' ), $singular ),
				'view_item'          => sprintf( __( 'View %s', 'pebas-bookings-extension' ), $singular ),
				'all_items'          => sprintf( __( 'All %s', 'pebas-bookings-extension' ), $plural ),
				'search_items'       => sprintf( __( 'Search %s', 'pebas-bookings-extension' ), $plural ),
				'parent_item_colon'  => sprintf( __( 'Parent %s', 'pebas-bookings-extension' ), $plural ),
				'not_found'          => sprintf( __( 'No %s found', 'pebas-bookings-extension' ), $plural ),
				'not_found_in_trash' => sprintf( __( 'No %s found in Trash', 'pebas-bookings-extension' ), $plural ),
			),
		);

		register_post_type( $post_type, $args );
	}


}

/**
 * Instantiate the class
 *
 * @return null|pebas_payouts_install
 */
function pebas_payouts_install() {
	return pebas_payouts_install::instance();
}
