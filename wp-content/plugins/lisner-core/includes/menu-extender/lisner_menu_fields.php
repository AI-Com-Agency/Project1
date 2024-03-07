<?php

/**
 * Class lisner_menu_fields
 */

class lisner_menu_fields {

	protected static $_instance = null;

	/**
	 * @return null|lisner_menu_fields
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * lisner_menu_fields constructor.
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'init' ) );
		add_action( 'wp_loaded', array( $this, 'load' ), 9 );
	}

	/**
	 * Holds our custom fields
	 *
	 * @var    array
	 * @access protected
	 * @since  Menu_Item_Custom_Fields_Example 0.2.0
	 */
	protected static $fields = array();

	/**
	 * Initialize plugin
	 */
	public static function init() {
		add_action( 'wp_nav_menu_item_custom_fields', array( __CLASS__, '_fields' ), 10, 4 );
		add_action( 'wp_update_nav_menu_item', array( __CLASS__, '_save' ), 10, 3 );
		add_filter( 'manage_nav-menus_columns', array( __CLASS__, '_columns' ), 99 );
		self::$fields = array(
			'menu-label' => __( 'Menu Item Label', 'lisner-core' ),
		);
	}

	/**
	 * Add filter
	 *
	 * @wp_hook action wp_loaded
	 */
	public static function load() {
		add_filter( 'wp_edit_nav_menu_walker', array( __CLASS__, '_filter_walker' ), 99 );
	}

	/**
	 * Replace default menu editor walker with ours
	 *
	 * We don't actually replace the default walker. We're still using it and
	 * only injecting some HTMLs.
	 *
	 * @since   0.1.0
	 * @access  private
	 * @wp_hook filter wp_edit_nav_menu_walker
	 *
	 * @param   string $walker Walker class name
	 *
	 * @return  string Walker class name
	 */
	public static function _filter_walker( $walker ) {
		$walker = 'lisner_menu_walker';
		if ( ! class_exists( $walker ) ) {
			require_once dirname( __FILE__ ) . '/lisner_menu_walker.php';
		}

		return $walker;
	}

	/**
	 * Save custom field value
	 *
	 * @wp_hook action wp_update_nav_menu_item
	 *
	 * @param int $menu_id Nav menu ID
	 * @param int $menu_item_db_id Menu item ID
	 * @param array $menu_item_args Menu item data
	 */
	public static function _save( $menu_id, $menu_item_db_id, $menu_item_args ) {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}
		check_admin_referer( 'update-nav_menu', 'update-nav-menu-nonce' );
		foreach ( self::$fields as $_key => $label ) {
			$key = sprintf( 'menu-item-%s', $_key );
			// Sanitize
			if ( ! empty( $_POST[ $key ][ $menu_item_db_id ] ) ) {
				// Do some checks here...
				$value = $_POST[ $key ][ $menu_item_db_id ];
			} else {
				$value = null;
			}
			// Update
			if ( ! is_null( $value ) ) {
				update_post_meta( $menu_item_db_id, $key, $value );
			} else {
				delete_post_meta( $menu_item_db_id, $key );
			}
		}
	}

	/**
	 * Print field
	 *
	 * @param object $item Menu item data object.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param array $args Menu item args.
	 * @param int $id Nav menu ID.
	 *
	 * @return string Form fields
	 */
	public static function _fields( $id, $item, $depth, $args ) {
		foreach ( self::$fields as $_key => $label ) :
			$key = sprintf( 'menu-item-%s', $_key );
			$id = sprintf( 'edit-%s-%s', $key, $item->ID );
			$name = sprintf( '%s[%s]', $key, $item->ID );
			$value = get_post_meta( $item->ID, $key, true );
			$class = sprintf( 'field-%s', $_key );
			?>
            <p class="description description-wide <?php echo esc_attr( $class ) ?>">
				<?php printf(
					'<label for="%1$s">%2$s<br /><input type="text" id="%1$s" class="widefat %1$s" name="%3$s" value="%4$s" /></label>',
					esc_attr( $id ),
					esc_html( $label ),
					esc_attr( $name ),
					esc_attr( $value )
				) ?>
            </p>
		<?php
		endforeach;
	}

	/**
	 * Add our fields to the screen options toggle
	 *
	 * @param array $columns Menu item columns
	 *
	 * @return array
	 */
	public static function _columns( $columns ) {
		$columns = array_merge( $columns, self::$fields );

		return $columns;
	}

}

/**
 * Instantiate the class
 *
 * @return lisner_menu_fields|null
 */
function lisner_menu_fields() {
	return lisner_menu_fields::instance();
}
