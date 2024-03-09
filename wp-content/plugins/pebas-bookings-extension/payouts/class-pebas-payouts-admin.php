<?php
/**
 * Class pebas_payouts_admin
 *
 * @author pebas
 * @ver 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * pebas_payouts_admin
 */
class pebas_payouts_admin {

	protected static $_instance = null;


	/**
	 * @return null|pebas_payouts_admin
	 */
	public static function instance() {
		return null === self::$_instance ? ( self::$_instance = new self ) : self::$_instance;
	}

	/**
	 * pebas_payouts_admin constructor.
	 */
	public function __construct() {
		$type_name = pebas_payouts_install::$type_name;

		// add actions
		add_action( 'admin_menu', array( $this, 'report_admin_menu' ) );
		add_action( 'woocommerce_booking_paid', array( $this, 'insert_payout' ), 10, 2 );

		add_filter( 'parent_file', array( $this, 'admin_menu_parent_file' ) );
		add_filter( 'submenu_file', array( $this, 'admin_menu_submenu_file' ) );
		// admin columns
		add_filter( "manage_edit-{$type_name}_columns", array( $this, 'manage_columns' ) );
		add_action( "manage_{$type_name}_posts_custom_column", array( $this, 'manage_custom_column' ), 10, 2 );
		add_filter( "views_edit-{$type_name}", array( $this, 'add_process_payments_button_to_views' ) );

		// report update messages
		add_filter( 'post_updated_messages', array( $this, 'post_updated_messages' ) );

		// ajax process update
		add_action( 'wp_ajax_process_payment', array( $this, 'process_payment' ) );
		add_action( 'wp_ajax_process_mass_payment', array( $this, 'process_mass_payment' ) );
	}

	/**
	 * Create and insert payout post when booking status
	 * has been changed to 'paid'
	 * -----------------------------
	 *
	 * @param $booking_id
	 * @param $booking_object
	 *
	 */
	public function insert_payout( $booking_id, $booking_object ) {
		$option     = get_option( 'pbs_option' );
		$booking    = get_wc_booking( $booking_id );
		$id         = wp_insert_post( array(
			'post_type'   => pebas_payouts_install::$type_name,
			'post_status' => 'publish',
			'post_title'  => sprintf( esc_html__( 'Payout for: %s', 'pebas-bookings-extension' ), get_the_title( $booking_id ) ),
		) );
		$percentage = $this->calculate_payment_tax( $option['booking-percentage'], '' );
		$amount     = $booking_object->get_cost();
		$amount     = ! empty( $percentage ) ? $amount - ( ( $amount * $percentage ) / 100 ) : $amount;
		update_post_meta( $id, '_payout_booking', $booking->get_id() );
		update_post_meta( $id, '_payout_customer', $booking->get_customer_id() );
		update_post_meta( $id, '_payout_amount', number_format( $amount, 2 ) );
		update_post_meta( $id, '_payout_tax', $percentage );
		update_post_meta( $id, '_payout_status', 'unpaid' );
	}

	/**
	 * Add Bookmark Listings admin menu
	 */
	public function report_admin_menu() {
		$report     = pebas_payouts_install::$type_name;
		$report_obj = get_post_type_object( $report );

		// add submenu page under listings
		add_submenu_page(
			$parent_slug = 'edit.php?post_type=wc_booking',
			$page_title = $report_obj->labels->name,
			$menu_title = $report_obj->labels->menu_name,
			$capability = 'manage_job_listings',
			$menu_slug = "edit.php?post_type={$report}"
		);
	}

	/**
	 * Parent menu of the submenu we're creating
	 *
	 * @param $parent_file
	 *
	 * @return string
	 */
	function admin_menu_parent_file( $parent_file ) {
		global $current_screen;
		if ( in_array( $current_screen->base, array(
				'post',
				'edit'
			) ) && pebas_payouts_install::$type_name == $current_screen->post_type ) {
			$parent_file = 'edit.php?post_type=wc_booking';
		}

		return $parent_file;
	}

	/**
	 * Creating submenu page for wp-admin
	 *
	 * @param $submenu_file
	 *
	 * @return string
	 */
	function admin_menu_submenu_file( $submenu_file ) {
		global $current_screen;
		$post_type = pebas_payouts_install::$type_name;
		if ( in_array( $current_screen->base, array(
				'post',
				'edit'
			) ) && pebas_payouts_install::$type_name == $current_screen->post_type ) {
			$submenu_file = "edit.php?post_type={$post_type}";
		}

		return $submenu_file;
	}

	/*
	 * Add Bookings column statuses
	 *
	 * @param $columns
	 *
	 * @return array
	 */
	public function manage_columns( $columns ) {

		$old_columns = $columns;
		$columns     = array(
			'cb'       => $old_columns['cb'],
			'title'    => __( 'Booking Order', 'pebas-bookings-extension' ),
			'booking'  => __( 'Booking ID', 'pebas-bookings-extension' ),
			'order'    => __( 'Order', 'pebas-bookings-extension' ),
			'customer' => __( 'Customer PayPal', 'pebas-bookings-extension' ),
			'amount'   => __( 'Amount Due', 'pebas-bookings-extension' ),
			'actions'  => __( 'Actions', 'pebas-bookings-extension' ),
		);

		return $columns;
	}

	/**
	 * Manage Custom Bookings columns
	 *
	 * @param $column
	 * @param $post_id
	 *
	 * @return mixed
	 */
	public function manage_custom_column( $column, $post_id ) {
		$option        = get_option( 'pbs_option' );
		$booking_id    = get_post_meta( $post_id, '_payout_booking', true );
		$payout_status = get_post_meta( $post_id, '_payout_status', true );
		$booking       = get_wc_booking( $booking_id );
		$percentage    = $this->calculate_payment_tax( $option['booking-percentage'], $post_id );
		$order         = $booking->get_order();
		$amount        = $booking->get_cost();
		$percentage    = ! empty( $percentage ) ? ( $amount * $percentage ) / 100 : '';
		$full_amount   = ! empty( $percentage ) ? $amount - $percentage : $amount;
		$user_id       = get_post_meta( $post_id, '_payout_customer', true );
		$user          = get_userdata( $user_id );
		$paypal        = get_user_meta( $user_id, '_payout_paypal', true );
		switch ( $column ) {
			case 'customer':
				?>
				<?php if ( isset( $paypal ) && ! empty( $paypal ) ) : ?>
				<strong><?php echo esc_html( $paypal ); ?></strong>
			<?php else: ?>
				<span class="no-paypal"><?php esc_html_e( 'User does not have PayPal email address set, click on below email to send them a message about it.', 'pebas-bookings-extension' ); ?></span>
				<br>
				<strong><a href="mailto:<?php echo esc_attr( $user->user_email ); ?>"><?php echo esc_html( $user->user_email ); ?></a></strong>
			<?php endif; ?>
				<?php
				break;
			case 'booking':
				$booking_permalink = admin_url( '/post.php?post=' . $booking->get_id() . '&action=edit' );
				?>
				<div class="booking-order"><a
							href="<?php echo esc_url( $booking_permalink ); ?>"><strong><?php echo sprintf( esc_html__( 'Booking: #%s', 'pebas-bookings-extension' ), $booking->get_id() ); ?></strong></a>
				</div>
				<?php
				break;
			case 'order':
				$order_permalink = admin_url( '/post.php?post=' . $order->get_id() . '&action=edit' );
				?>
				<div class="booking-order"><a
							href="<?php echo esc_url( $order_permalink ); ?>"><strong><?php echo sprintf( esc_html__( 'Order: #%s', 'pebas-bookings-extension' ), $order->get_id() ); ?></strong></a>
				</div>
				<?php
				break;
			case 'amount':
				?>
				<div class="booking-amount">
					<div class="booking-amount--base"><?php printf( __( '<strong>Base: </strong> %s', 'pebas-bookings-extension' ), wc_price( $amount ) ); ?></div>
					<div class="booking-amount--plus"><?php echo esc_html( '-' ); ?></div>
					<div class="booking-amount--percentage"><?php printf( __( '<strong>Site Tax: </strong> %s', 'pebas-bookings-extension' ), wc_price( $percentage ) ); ?></div>
				</div>
				<div class="booking-amount--total"><?php printf( __( '<strong>Total Due: </strong> %s', 'pebas-bookings-extension' ), wc_price( $full_amount ) ); ?></div>
				<?php
				break;
			case 'actions':
				?>
				<?php if ( 'paid' != $payout_status ) : ?>
				<a href="javascript:" class="button button-primary button-large process-payment"
				   data-id="<?php echo esc_attr( $post_id ) ?>"
				   data-amount="<?php echo esc_attr( number_format( $full_amount, 2 ) ); ?>"
				   data-confirm="<?php echo esc_attr__( 'Are you sure you wish to send the payment to this user? This action is irreversible.', 'pebas-bookings-extension' ); ?>"
				   data-email="<?php echo esc_attr( $paypal ) ?>"><?php echo esc_html__( 'Send Payment', '' ); ?></a>
			<?php else: ?>
				<div class="booking-payment-action">
					<strong><?php esc_html_e( 'Payment sent', 'pebas-bookings-extension' ); ?></strong></div>
			<?php endif; ?>
				<?php
				break;
			default :
				break;
		}

		return $column;
	}

	/**
	 * Process Payment
	 */
	public function process_payment() {
		$post_id   = $_POST['post_id'];
		$amount    = $_POST['amount'];
		$email     = $_POST['email'];
		$email     = $_POST['email'];
		$response  = array();
		$option    = get_option( 'woocommerce_paypal_settings' );
		$testmode  = 'yes' == $option['testmode'] ? 'sandbox_' : '';
		$signature = $option["{$testmode}api_signature"];
		$username  = $option["{$testmode}api_username"];
		$password  = $option["{$testmode}api_password"];

		$paypal = new PayPal( array(
			'username'  => $username,
			'password'  => $password,
			'signature' => $signature,
			'cancelUrl' => '',
			'returnUrl' => '',
		) );

		$pdata             = array(
			'RECEIVERTYPE' => 'EmailAddress',
			'CURRENCYCODE' => get_option( 'woocommerce_currency' ),
		);
		$pdata['L_EMAIL0'] = $email;
		$pdata['L_AMT0']   = number_format( $amount, 2 );

		$details = $paypal->MassPay( $pdata );

		$response['details'] = $details;

		if ( ! empty( $details['error'] ) ) {
			$response['error'] = $details['error'];
		} else {
			$response['success'] = esc_html__( 'Payment Sent', 'pebas-bookings-extension' );
			update_post_meta( $post_id, '_payout_status', 'paid' );
		}

		wp_send_json( $response );
	}

	/**
	 * Process Mass Payout
	 */
	public function process_mass_payment() {
		$response  = array();
		$option    = get_option( 'woocommerce_paypal_settings' );
		$testmode  = 'yes' == $option['testmode'] ? 'sandbox_' : '';
		$signature = $option["{$testmode}api_signature"];
		$username  = $option["{$testmode}api_username"];
		$password  = $option["{$testmode}api_password"];

		$paypal = new PayPal( array(
			'username'  => $username,
			'password'  => $password,
			'signature' => $signature,
			'cancelUrl' => '',
			'returnUrl' => '',
		) );

		$pdata = array(
			'RECEIVERTYPE' => 'EmailAddress',
			'CURRENCYCODE' => get_option( 'woocommerce_currency' ),
		);

		$payout_ids = $this->get_payout_ids();
		$pay_count  = 0;
		foreach ( $payout_ids as $id ) {
			$payout  = get_post( $id );
			$user_id = $payout->_payout_customer;
			$user    = get_userdata( $user_id );
			$amount  = $payout->_payout_amount;
			$email   = get_user_meta( $user_id, '_payout_paypal', true );
			if ( isset( $email ) && ! empty( $email ) ) {
				$pdata["L_EMAIL{$pay_count}"]                    = $email;
				$pdata["L_AMT{$pay_count}"]                      = number_format( $amount, 2 );
				$response['success_user'][ $user->display_name ] = sprintf( esc_html__( 'Payment to user %s has been sent.', 'lisner-core' ), $user->display_name );
			} else {
				$response['error_user'][ $user->display_name ] = sprintf( esc_html__( 'User %s has not set their paypal email address.', 'lisner-core' ), $user->display_name );
			}

			$pay_count ++;
		}

		$details = $paypal->MassPay( $pdata );

		$response['details'] = $details;

		if ( ! empty( $details['error'] ) ) {
			$response          = array();
			$response['error'] = $details['error'];
		} else {
			$response['success'] = esc_html__( 'Payments Sent', 'pebas-bookings-extension' );
			foreach ( $payout_ids as $id ) {
				update_post_meta( $id, '_payout_status', 'paid' );
			}
		}

		wp_send_json( $response );
	}

	/**
	 * Add mass payment button above custom
	 * post type table
	 * ----------------------------------
	 *
	 * @param $views
	 *
	 * @return mixed
	 */
	public function add_process_payments_button_to_views( $views ) {
		$views['mass-payment'] = '<button id="pebas-mass-payment" data-nonce="' . wp_create_nonce( 'process_mass_payment' ) . '" class="button button-primary button-large process-mass-payment" data-confirm="' . esc_html__( 'Are you sure you wish to proceed with mass payment? This action is irreversible.', 'pebas-bookings-extension' ) . '" type="button">' . esc_html__( 'Send Payment To All', 'pebas-bookings-extension' ) . '</button>';

		return $views;
	}

	/**
	 * Get payout ids
	 *
	 * @return array
	 */
	public function get_payout_ids() {
		$payout_ids = array();
		$payouts    = get_posts( array(
			'post_type'      => pebas_payouts_install::$type_name,
			'posts_per_page' => - 1,
			'meta_query'     => array(
				array(
					'key'   => '_payout_status',
					'value' => 'unpaid'
				)
			)
		) );

		foreach ( $payouts as $payout ) {
			$payout_ids[] = $payout->ID;
		}

		return $payout_ids;
	}


	/**
	 * Booking Payout updated messages
	 *
	 * @param $messages
	 *
	 * @return mixed
	 */
	public function post_updated_messages( $messages ) {
		$post      = get_post();
		$post_type = pebas_payouts_install::$type_name;

		$messages[ $post_type ] = array(
			0  => '', // Unused. Messages start at index 1.
			1  => __( 'Booking Payout updated.', 'pebas-bookings-extension' ),
			2  => __( 'Custom field updated.', 'pebas-bookings-extension' ),
			3  => __( 'Custom field deleted.', 'pebas-bookings-extension' ),
			4  => __( 'Booking Payout updated.', 'pebas-bookings-extension' ),
			5  => isset( $_GET['revision'] ) ? sprintf( __( 'Booking Payout restored to revision from %s', 'pebas-bookings-extension' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6  => __( 'Booking Payout published.', 'pebas-bookings-extension' ),
			7  => __( 'Booking Payout saved.', 'pebas-bookings-extension' ),
			8  => __( 'Booking Payout submitted.', 'pebas-bookings-extension' ),
			9  => sprintf(
				__( 'Booking Payout scheduled for: <strong>%1$s</strong>.', 'pebas-bookings-extension' ),
				date_i18n( __( 'M j, Y @ G:i', 'pebas-bookings-extension' ), strtotime( $post->post_date ) )
			),
			10 => __( 'Booking Payout draft updated.', 'pebas-bookings-extension' ),
		);

		$return_to_reports_link = sprintf( ' <a href="%s">%s</a>', esc_url( admin_url( "edit.php?post_type={$post_type}" ) ), __( 'Return to payments', 'pebas-bookings-extension' ) );

		$messages[ $post_type ][1]  .= $return_to_reports_link;
		$messages[ $post_type ][6]  .= $return_to_reports_link;
		$messages[ $post_type ][9]  .= $return_to_reports_link;
		$messages[ $post_type ][8]  .= $return_to_reports_link;
		$messages[ $post_type ][10] .= $return_to_reports_link;

		return $messages;
	}

	/**
	 * Get the right percentage for the payout post
	 * ---------------------------------------
	 *
	 * @param $theme_option_percentage
	 * @param $payout_id
	 *
	 * @return string
	 */
	public function calculate_payment_tax( $theme_option_percentage = '', $payout_id = '' ) {
		$payout_id       = ! empty( $payout_id ) ? $payout_id : get_the_id();
		$meta_percentage = get_post_meta( $payout_id, '_payout_tax', true );
		$percentage      = isset( $meta_percentage ) && ! empty( $meta_percentage ) && 0 != $meta_percentage ? $meta_percentage : ( isset( $theme_option_percentage ) && ! empty( $theme_option_percentage ) && 0 != $theme_option_percentage ? $theme_option_percentage : '' );

		return $percentage;
	}

}

/**
 * Instantiate the class
 *
 * @return null|pebas_payouts_admin
 */
function pebas_payouts_admin() {
	return pebas_payouts_admin::instance();
}
