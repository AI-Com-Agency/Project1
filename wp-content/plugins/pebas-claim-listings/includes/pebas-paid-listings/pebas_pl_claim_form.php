<?php
/**
 * Class pebas_claim_form
 *
 * @author pebas
 * @ver 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * pebas_claim_form
 */
class pebas_claim_form {

	protected static $_instance = null;


	/**
	 * @return null|pebas_claim_form
	 */
	public static function instance() {
		return null === self::$_instance ? ( self::$_instance = new self ) : self::$_instance;
	}

	/**
	 * pebas_claim_form constructor.
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );
		$paid_listing_enabled = get_option( 'paid_claiming' );
		if ( $paid_listing_enabled ) {

			/* Filter Form Steps */
			add_filter( 'pebas_claim_listings_submit_claim_form_steps', array( $this, 'add_form_step' ) );

			/* "Claim Listing" Step Handler */
			add_action( 'pebas_claim_listings_submit_claim_form_login_register_handler_after', array(
				$this,
				'submit_claim_handler_after'
			) );

			/* "Claim Detail" View */
			add_action( 'pebas_claim_listings_submit_claim_form_claim_detail_view_close', array(
				$this,
				'add_claim_detail'
			) );

		}
	}

	/**
	 * Add form styles
	 */
	public static function scripts() {
		if ( is_page( get_option( 'job_manager_claim_listing_page_id' ) ) ) {
			wp_enqueue_script( 'pebas-cl-theme', PEBAS_CL_URL . 'assets/scripts/cl-theme.js', array( 'jquery' ), '1.0.0', true );
		}
	}

	public function add_form_step( $steps ) {

		/* Register on checkout */
		if ( get_option( 'register_on_checkout' ) ) {
			unset( $steps['login_register'] );
		}

		/* Add select package step. */
		$steps['claim_package'] = array(
			'name'     => __( 'Choose a package', 'pebas-claim-listings' ),
			'view'     => array( $this, 'claim_package_view' ),
			'handler'  => array( $this, 'claim_package_handler' ),
			'priority' => 4,
			'submit'   => __( 'Checkout &rarr;', 'pebas-claim-listings' ),
		);

		return $steps;
	}

	public function claim_package_view() {
		$form     = pebas_claim_submit_form();
		$packages = self::get_packages_for_claiming();
		?>
        <form id="<?php echo esc_attr( $form->get_form_name() ); ?>"
              class="job-manager-form pcl-claim-form pcl-form-claim-package" method="post">
			<?php if ( $packages ) { ?>
                <input type="hidden" name="claim_id"
                       value="<?php echo esc_attr( $form->claim_id ); ?>"/>
                <input type="hidden" name="step"
                       value="<?php echo intval( $form->get_step() ); ?>">
			<?php } ?>
            <div class="listing-packages">
				<?php get_job_manager_template( 'package-selection.php', array(
					'packages' => $packages,
				), '', PEBAS_CL_DIR . '/templates/' ); ?>
            </div>

        </form>
		<?php
	}

	public function claim_package_handler() {
		$form       = pebas_claim_submit_form();
		$listing_id = $form->listing_id;
		$claim_id   = ( isset( $_POST['claim_id'] ) && ! empty( $_POST['claim_id'] ) ) ? intval( $_POST['claim_id'] ) : $form->claim_id;

		/* Package ID. */
		$package_id = 0;
		if ( isset( $_POST['job_package'] ) ) {
			$package_id = intval( $_POST['job_package'] );
		}

		/* If order already in place: No need to checkout, Go to next step. */
		if ( false ) {
			$form->next_step();
		} else {
			/* Validate selected package */
			$validation = self::validate_package( $package_id );

			/* If not valid, display message */
			if ( is_wp_error( $validation ) ) {
				$form->add_error( $validation->get_error_message() );
			} // End if().

			else {

				/* Product object */
				$package = wc_get_product( $package_id );

				update_post_meta( $claim_id, '_package_id', $package_id );

				update_post_meta( $listing_id, '_package_id', $package_id );

				/* Add product to cart with all info needed. */
				WC()->cart->add_to_cart(
					$product_id = $package_id,
					$quantity = 1,
					$variation_id = '',
					$variation = array(),
					$cart_item_data = array(
						'job_id'   => $listing_id, // WC Paid Listing Compat.
						'claim_id' => $claim_id,
					)
				);

				/* Redirect to checkout */
				wp_redirect( esc_url_raw( wc_get_checkout_url() ) );
				exit;
			}
		}
	}

	public function submit_claim_handler_after( $claim_id ) {
		$form = pebas_claim_submit_form();
		if ( isset( $claim_id ) && ! empty( $claim_id ) ) {
			$status = get_post_meta( $claim_id, '_status', true );
			if ( in_array( $status, array( 'pending_order', 'order_completed' ) ) ) {
				$form->next_step();
			}
		}
	}

	public function add_claim_detail( $claim_id ) {
		if ( ! isset( $claim_id ) || empty( $claim_id ) ) {
			return false;
		}

		/* Add order details */
		$order_id  = get_post_meta( $claim_id, '_order_id', true );
		$order_obj = wc_get_order( $order_id );
		if ( $order_obj ) {
			$status = $order_obj->get_status();
			?>
            <div class="listing-claim-item">
                <div class="listing-claim-label"><?php _e( 'Order ID', 'pebas-claim-listings' ); ?></div>
                <div class="listing-claim-content">
					<?php echo "<strong>#{$order_id}</strong> ({$status})"; ?>
                </div>
            </div>
			<?php

		}

		/* Package Details */
		$product_id  = get_post_meta( $claim_id, '_package_id', true );
		$product_obj = wc_get_product( $product_id );
		if ( $product_obj ) {
			?>
            <div class="listing-claim-item">
                <div class="listing-claim-label"><?php _e( 'Package', 'pebas-claim-listings' ); ?></div>
                <div class="listing-claim-content">
					<?php echo $product_obj->get_title(); ?>
                </div>
            </div>
			<?php
		}

	}

	private static function validate_package( $package_id ) {

		/* No Package Selected */
		if ( empty( $package_id ) ) {
			return new WP_Error( 'error', __( 'Invalid Package', 'pebas-claim-listings' ) );
		} // End if().

		else {

			/* Get packages */
			$packages = self::get_packages_for_claiming();
			if ( ! $packages ) {
				return new WP_Error( 'error', __( 'No package available to purchase.', 'pebas-claim-listings' ) );
			}

			/* Check if selected package is in the list. */
			$package_ids = array();
			foreach ( $packages as $package ) {
				$package_ids[] = $package->ID;
			}
			if ( ! in_array( $package_id, $package_ids ) ) {
				return new WP_Error( 'error', __( 'Invalid Package', 'pebas-claim-listings' ) );
			}
		}

		return true;
	}

	public static function get_packages_for_claiming( $post__in = array() ) {

		/* Query Args */
		$args = array(
			'post_type'        => 'product',
			'posts_per_page'   => - 1,
			'post__in'         => $post__in,
			'order'            => 'asc',
			'orderby'          => 'menu_order',
			'suppress_filters' => false,
			'tax_query'        => array(
				'relation' => 'AND',
				array(
					'taxonomy' => 'product_visibility',
					'field'    => 'slug',
					'terms'    => array( 'exclude-from-search', 'exclude-from-catalog' ),
					'operator' => 'NOT IN',
				),
				array(
					'taxonomy' => 'product_type',
					'field'    => 'slug',
					'terms'    => array( 'job_package', 'job_package_subscription' ),
				),
			),
			'meta_query'       => array(
				array(
					'key'     => '_use_for_claims',
					'value'   => 'yes',
					'compare' => '=',
				),
			),
		);
		$args = apply_filters( 'pebas_claim_listings_get_packages_for_claiming', $args );

		return get_posts( $args );
	}

}

/**
 * Instantiate the class
 *
 * @return null|pebas_claim_form
 */
function pebas_claim_form() {
	return pebas_claim_form::instance();
}
