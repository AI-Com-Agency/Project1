<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin
 */
class pebas_pl_admin {

	/** @var object Class Instance */
	private static $instance;

	/**
	 * Get the class instance
	 *
	 * @return static
	 */
	private $add_action;

	public static function get_instance() {
		return null === self::$instance ? ( self::$instance = new self ) : self::$instance;
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		add_filter( 'woocommerce_screen_ids', array( $this, 'add_screen_ids' ) );
		$this->add_action = add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		$this->add_action;
		add_filter( 'job_manager_admin_screen_ids', array( $this, 'add_screen_ids' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ), 11 );
		add_filter( 'woocommerce_subscription_product_types', array(
			$this,
			'woocommerce_subscription_product_types'
		) );
		add_filter( 'product_type_selector', array( $this, 'product_type_selector' ) );
		add_action( 'woocommerce_process_product_meta_job_package', array( $this, 'save_job_package_data' ) );
		add_action( 'woocommerce_process_product_meta_job_package_subscription', array(
			$this,
			'save_job_package_data'
		) );
		add_action( 'woocommerce_process_product_meta_resume_package', array( $this, 'save_resume_package_data' ) );
		add_action( 'woocommerce_process_product_meta_resume_package_subscription', array(
			$this,
			'save_resume_package_data'
		) );
		add_action( 'woocommerce_product_options_general_product_data', array( $this, 'product_data' ) );
		add_filter( 'parse_query', array( $this, 'parse_query' ) );
	}

	/**
	 * Enqueue CSS for admin.
	 */
	public function admin_enqueue_scripts() {
		$screen = get_current_screen();
		if ( in_array( $screen->id, array( 'product' ) ) ) {
			wp_enqueue_style( 'wc-paid-listings-admin', PEBAS_PL_URL . '/assets/styles/admin.css', array(), PEBAS_PL_VERSION );
		}
		if ( in_array( $screen->id, array( 'job_listing_page_pebas_listing_packages' ) ) ) {
			wp_enqueue_script( 'pebas-pl-select2', PEBAS_PL_URL . 'assets/scripts/select2.min.js', array( 'jquery' ), '', true );
			//wp_enqueue_script( 'pebas-pl-theme', PEBAS_PL_URL . 'assets/scripts/theme-admin.js', array( 'jquery' ), '', true );
		}
	}

	/**
	 * Screen IDS
	 *
	 * @param  array $ids
	 *
	 * @return array
	 */
	public function add_screen_ids( $ids ) {
		$wc_screen_id = sanitize_title( __( 'WooCommerce', 'woocommerce' ) );

		return array_merge( $ids, array(
			'job_listing_page_pebas_listing_packages'
		) );
	}

	/**
	 * Add menu items
	 */
	public function admin_menu() {
		add_submenu_page( 'edit.php?post_type=job_listing', __( 'Listing Packages', 'pebas-paid-listings' ), __( 'Listing Packages', 'pebas-paid-listings' ), 'manage_options', 'pebas_listing_packages', array(
			$this,
			'packages_page'
		) );
	}

	/**
	 * Manage Packages
	 */
	public function packages_page() {
		global $wpdb;
		$table = pebas_paid_listings_install::$pebas_paid_listings_table;

		$action = isset( $_REQUEST['action'] ) ? sanitize_text_field( $_REQUEST['action'] ) : '';

		if ( 'delete' === $action && ! empty( $_GET['delete_nonce'] ) && wp_verify_nonce( $_GET['delete_nonce'], 'delete' ) ) {
			$package_id = absint( $_REQUEST['package_id'] );
			$wpdb->delete( "{$wpdb->prefix}{$table}", array(
				'id' => $package_id,
			) );
			$wpdb->delete( $wpdb->postmeta, array(
				'meta_key'   => '_user_package_id',
				'meta_value' => $package_id,
			) );
			echo sprintf( '<div class="updated"><p>%s</p></div>', __( 'Package successfully deleted', 'pebas-paid-listings' ) );
		}

		if ( 'add' === $action || 'edit' === $action ) {
			$this->add_package_page();
		} else {
			include_once( dirname( __FILE__ ) . '/pebas_pl_admin_packages.php' );
			$table = new pebas_pl_admin_packages();
			$table->prepare_items();
			?>
            <div class="woocommerce wrap">
                <h2><?php _e( 'Listing Packages', 'pebas-paid-listings' ); ?> <a
                            href="<?php echo esc_url( add_query_arg( 'action', 'add', admin_url( 'edit.php?post_type=job_listing&page=pebas_listing_packages' ) ) ); ?>"
                            class="add-new-h2"><?php _e( 'Add User Package', 'pebas-paid-listings' ); ?></a></h2>
                <form id="package-management" method="post">
                    <input type="hidden" name="page" value="pebas_listing_packages"/>
					<?php $table->display() ?>
					<?php wp_nonce_field( 'save', 'pebas_paid_listings_packages_nonce' ); ?>
                </form>
            </div>
			<?php
		}
	}

	/**
	 * Add package
	 */
	public function add_package_page() {
		include_once( dirname( __FILE__ ) . '/pebas_pl_admin_add_package.php' );
		$add_package = new pebas_pl_admin_add_package();
		?>
        <div class="woocommerce wrap">
            <h2><?php _e( 'Add User Package', 'pebas-paid-listings' ); ?></h2>
            <form id="package-add-form" method="post">
                <input type="hidden" name="page" value="pebas_listing_packages"/>
				<?php $add_package->form() ?>
				<?php wp_nonce_field( 'save', 'pebas_paid_listings_packages_nonce' ); ?>
            </form>
        </div>
		<?php
	}

	/**
	 * Types for subscriptions
	 *
	 * @param  array $types
	 *
	 * @return array
	 */
	public function woocommerce_subscription_product_types( $types ) {
		$types[] = 'job_package_subscription';

		return $types;
	}

	/**
	 * Add the product type
	 *
	 * @param array $types
	 *
	 * @return array
	 */
	public function product_type_selector( $types ) {
		$types['job_package'] = __( 'Listing Package', 'pebas-paid-listings' );
		if ( class_exists( 'WC_Subscriptions' ) ) {
			$types['job_package_subscription'] = __( 'Listing Package Subscription', 'pebas-paid-listings' );
		}

		return $types;
	}

	/**
	 * Show the job package product options
	 */
	public function product_data() {
		global $post;
		$post_id = $post->ID;
		include( 'views/html-job-package-data.php' );
	}

	/**
	 * Save Job Package data for the product
	 *
	 * @param  int $post_id
	 */
	public function save_job_package_data( $post_id ) {
		global $wpdb;

		// Save meta
		$meta_to_save = array(
			'_job_listing_duration' => '',
			'_job_listing_limit'    => 'int',
			'_job_listing_featured' => 'yesno',
		);

		foreach ( $meta_to_save as $meta_key => $sanitize ) {
			$value = ! empty( $_POST[ $meta_key ] ) ? $_POST[ $meta_key ] : '';
			switch ( $sanitize ) {
				case 'int' :
					$value = absint( $value );
					break;
				case 'float' :
					$value = floatval( $value );
					break;
				case 'yesno' :
					$value = $value == 'yes' ? 'yes' : 'no';
					break;
				default :
					$value = sanitize_text_field( $value );
			}
			update_post_meta( $post_id, $meta_key, $value );
		}

		$_package_subscription_type = ! empty( $_POST['_job_listing_package_subscription_type'] ) ? $_POST['_job_listing_package_subscription_type'] : 'package';
		update_post_meta( $post_id, '_package_subscription_type', $_package_subscription_type );
	}

	/**
	 * Save Job Package data for the product
	 *
	 * @param  int $post_id
	 */
	public function save_resume_package_data( $post_id ) {
		global $wpdb;

		// Save meta
		$meta_to_save = array(
			'_job_listing_duration' => '',
			'_job_listing_limit'    => 'int',
			'_job_listing_featured' => 'yesno',
			'_resume_duration'      => '',
			'_resume_limit'         => '',
			'_resume_featured'      => 'yesno',
		);

		foreach ( $meta_to_save as $meta_key => $sanitize ) {
			$value = ! empty( $_POST[ $meta_key ] ) ? $_POST[ $meta_key ] : '';
			switch ( $sanitize ) {
				case 'int' :
					$value = absint( $value );
					break;
				case 'float' :
					$value = floatval( $value );
					break;
				case 'yesno' :
					$value = $value == 'yes' ? 'yes' : 'no';
					break;
				default :
					$value = sanitize_text_field( $value );
			}
			update_post_meta( $post_id, $meta_key, $value );
		}

		$_package_subscription_type = 'package';
		update_post_meta( $post_id, '_package_subscription_type', $_package_subscription_type );
	}

	/**
	 * Filters and sorting handler
	 *
	 * @param  WP_Query $query
	 *
	 * @return WP_Query
	 */
	public function parse_query( $query ) {
		global $typenow;

		if ( 'job_listing' === $typenow ) {
			if ( isset( $_GET['package'] ) ) {
				$query->query_vars['meta_key']   = '_user_package_id';
				$query->query_vars['meta_value'] = absint( $_GET['package'] );
			}
		}

		return $query;
	}
}

pebas_pl_admin::get_instance();
