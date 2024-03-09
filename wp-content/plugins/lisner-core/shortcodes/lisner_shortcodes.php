<?php

/**
 * Class lisner_shortcodes
 */
class lisner_shortcodes {

	protected static $_instance = null;

	/**
	 * @return null|lisner_shortcodes
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * lisner_shortcodes constructor.
	 */
	function __construct() {
		if ( is_admin() ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		}

		// Get all shortcodes - class called lisner_shortcodes
		if ( lisner_helper::is_plugin_active( 'js_composer' ) ) {
			add_action( 'vc_before_init', array( $this, 'require_shortcode_params' ) );
			add_action( 'vc_before_init', array( $this, 'add_shortcodes' ) );

			// Call actions if visual composer is installed
			add_action( 'vc_before_init', array( $this, 'vc_map_shortcodes' ) );
			// add new vc fields
			vc_add_shortcode_param( 'lisner_image_radio', array( $this, 'image_radio_field' ) );
			vc_add_shortcode_param( 'select2', array( $this, 'select2_field' ) );
			vc_add_shortcode_param( 'select1', array( $this, 'select1_field' ) );
		}

	}

	/**
	 * Enqueue admin scripts and styles
	 */
	public function enqueue_scripts() {
		// admin styles
		wp_enqueue_style( 'lisner-admin-style-select2', LISNER_URL . 'assets/styles/select2.min.css', '', '', '' );
		wp_enqueue_style( 'lisner-admin-style', LISNER_URL . 'assets/styles/admin.css', '', LISNER_VERSION, '' );

		// admin scripts
		wp_enqueue_script( 'lisner-admin-script-select2', LISNER_URL . 'assets/scripts/select2.min.js', array( 'jquery' ), '', true );
		wp_enqueue_script( 'lisner-admin-script', LISNER_URL . 'assets/scripts/admin.js', array( 'jquery' ), LISNER_VERSION, true );
	}

	/**
	 * Get all shortcodes in array
	 *
	 * @param string $folder
	 *
	 * @return array
	 */
	public static function get_shortcodes( $folder = '' ) {
		if ( empty( $folder ) ) {
			$shortcodes = glob( LISNER_DIR . "shortcodes/params/*.php" );
		} else {
			$shortcodes = glob( LISNER_DIR . "{$folder}/*.php" );
		}

		return $shortcodes;
	}

	/**
	 * Get template for the shortcode
	 *
	 * @param $view
	 * @param array $atts
	 *
	 * @return string
	 */
	public static function get_shortcode_view( $view, $atts = array() ) {
		return LISNER_DIR . "shortcodes/views/{$view}.php";
	}

	/**
	 * Get news template part
	 *
	 * @param $view
	 * @param string $folder
	 * @param array $args
	 *
	 * @return array
	 */
	public static function get_view_part( $view, $folder = '', $args = array() ) {
		if ( ! empty( $args ) && is_array( $args ) ) {
			extract( $args );
		}

		if ( empty( $folder ) ) {
			$dir = LISNER_DIR . "shortcodes/views/partials/{$view}.php";
		} else {
			$dir = LISNER_DIR . "shortcodes/views/{$folder}/{$view}.php";
		}
		include $dir;

		return $args;
	}

	/**
	 * Get all shortcode blocks params
	 * @return mixed
	 */
	public static function require_shortcode_params() {
		$shortcodes = self::get_shortcodes();
		foreach ( $shortcodes as $shortcode ) {
			require_once( $shortcode );
		}
	}

	/**
	 * Register shortcodes
	 */
	public static function add_shortcodes() {
		$shortcodes = self::get_shortcodes();
		foreach ( $shortcodes as $shortcode ) {
			$shortcode = str_replace( array( '-', '.php' ), array( '_', '' ), wp_basename( $shortcode ) );
			add_shortcode( "{$shortcode}", "{$shortcode}_render" );
		}
	}

	/**
	 * Register shortcodes with Visual Composer
	 */
	public static function vc_map_shortcodes() {
		if ( function_exists( 'vc_map' ) ) {
			$shortcodes = self::get_shortcodes();
			$count      = 1;
			foreach ( $shortcodes as $shortcode ) {
				$shortcode = str_replace( array( '-', '.php' ), array( '_', '' ), wp_basename( $shortcode ) );
				switch ( $shortcode ):
					case 'lisner_listing':
						$name = esc_html__( 'Listing', 'lisner-core' );
						break;
					case 'lisner_button':
						$name = esc_html__( 'Button', 'lisner-core' );
						break;
					case 'lisner_taxonomy':
						$name = esc_html__( 'Taxonomy', 'lisner-core' );
						break;
					case 'lisner_title':
						$name = esc_html__( 'Title', 'lisner-core' );
						break;
					case 'lisner_post':
						$name = esc_html__( 'Post', 'lisner-core' );
						break;
					case 'lisner_clients':
						$name = esc_html__( 'Clients', 'lisner-core' );
						break;
					case 'lisner_how_it_works':
						$name = esc_html__( 'How It Works', 'lisner-core' );
						break;
					default:
						$name = sprintf( esc_html__( 'Template %s', 'lisner-core' ), $count );
						break;
				endswitch;
				vc_map( array(
					'name'        => sprintf( esc_html__( 'Lisner %s', 'lisner-core' ), $name ),
					'base'        => "{$shortcode}",
					'category'    => esc_html__( 'Lisner Shortcodes', 'lisner-core' ),
					'params'      => call_user_func( "{$shortcode}_settings" ),
					'class'       => 'lisner-shortcode-' . mb_strtolower( str_replace( ' ', '-', $name ) ),
					'weight'      => $count . 0,
					'description' => esc_html__( 'Lisner shortcode template for Lisner theme', 'lisner-core' )
				) );
				$count ++;
			}
		}
	}

	/**
	 * Extend visual composer to include radio image
	 *
	 * @param $settings
	 * @param $value
	 *
	 * @return string
	 */
	public static function image_radio_field( $settings, $value ) {
		$output = '';
		$values = isset( $settings['value'] ) && is_array( $settings['value'] ) ? $settings['value'] : array( __( 'Yes' ) => 'true' );
		$count  = 0;
		$output .= '<input type="hidden" name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value wpb-textinput lisner_radio_image ' . esc_attr( $settings['param_name'] ) . ' ' . esc_attr( $settings['type'] ) . '_field" type="text" value="' . esc_attr( $value ) . '" />';
		if ( ! empty( $values ) ) {
			foreach ( $values as $label => $v ) {
				$checked = $v == $value ? 'checked' : '';
				$output  .= ' <label class="vc_checkbox-label ' . $checked . '" style="background-image: url(' . esc_attr( LISNER_URL . 'assets/images/' . esc_attr( $settings['param_name'] ) . '-images/' . $v . '.png' ) . ');">';
				$output  .= '<input name="' . $settings['param_name'] . '_news_radio" value="' . $v . '" type="radio" ' . $checked . ' >';
				$output  .= '</label>';
				$count ++;
			}
		}

		return $output;
	}

	/**
	 * Extend visual composer to include select2 dropdown
	 *
	 * @param $settings
	 * @param $value
	 * @param $options
	 *
	 * @return string
	 */
	public static function select2_field( $settings, $value, $options = 'multiple' ) {
		$options        = ! empty( $options ) ? 'multiple' : '';
		$default_option = __( 'No Preference', 'lisner-core' );
		$param          = $settings;
		$param_line     = '';
		$param_line     .= "<script>jQuery('.vc-select2').select2({width: '100%'});</script>";
		$param_line     .= '<select data-placeholder="' . $default_option . '" ' . $options . '  name="' . esc_attr( $param['param_name'] ) . '" class="wpb_vc_param_value wpb-input wpb-select vc-select2 ' . esc_attr( $param['param_name'] ) . ' ' . esc_attr( $param['type'] ) . '">';
		$count          = 0;
		foreach ( $param['value'] as $text_val => $val ) {
			if ( is_numeric( $text_val ) && ( is_string( $val ) || is_numeric( $val ) ) ) {
				$text_val = $val;
			}
			$selected = '';

			if ( ! is_array( $value ) ) {
				$param_value_arr = explode( ',', $value );
			} else {
				$param_value_arr = $value;
			}

			if ( '' !== $value && in_array( $val, $param_value_arr ) ) {
				$selected = ' selected="selected"';
			}
			$param_line .= '<option class="' . $val . '" value="' . $val . '"' . $selected . '>' . $text_val . '</option>';
			$count ++;
		}
		$param_line .= '</select>';

		return $param_line;
	}

	/**
	 * Extend visual composer to include single select2 dropdown
	 *
	 * @param $settings
	 * @param $value
	 *
	 * @return string
	 */
	public static function select1_field( $settings, $value ) {
		return self::select2_field( $settings, $value );
	}

	/**
	 * Get formatted list of authors for shortcode param
	 *
	 * @param $first_empty boolean
	 * @param $roles array
	 *
	 * @return array
	 */
	public static function get_authors( $first_empty = true, $roles = array() ) {
		$list_authors = array();
		$first_empty  = $first_empty ? $list_authors = array( '' => '' ) : '';
		$roles        = ! empty( $roles ) ? $roles : array( 'administrator', 'editor', 'subscriber' );
		$authors      = get_users( array(
			'role__in' => $roles
		) );
		foreach ( $authors as $author ) {
			$list_authors[ $author->display_name ] = $author->ID;
		}

		return $list_authors;
	}

	/**
	 * Get formatted array of categories for shortcode param
	 *
	 * @param bool $first_empty
	 *
	 * @return array
	 *
	 */
	public static function get_taxonomies( $first_empty = true ) {
		$list_taxonomies = array();
		if ( $first_empty ) {
			$list_taxonomies = array( '' => '' );
		}
		$taxonomies = array(
			'job_listing_category' => esc_html( 'Category' ),
			'listing_location'     => esc_html( 'Location' ),
			'listing_amenity'      => esc_html( 'Amenity' ),
			'listing_tag'          => esc_html( 'Tag' ),
		);
		foreach ( $taxonomies as $slug => $name ) {
			$list_taxonomies[ $name ] = $slug;
		}

		return $list_taxonomies;
	}

	/**
	 * Get formatted array of categories for shortcode param
	 *
	 * @return array
	 */
	public static function get_categories() {
		$list_categories = array( '' => '' );
		$categories      = get_categories();
		foreach ( $categories as $category ) {
			$list_categories[ $category->name ] = $category->term_id;
		}

		return $list_categories;
	}

	/**
	 * Get formatted array of posts for shortcode param
	 *
	 * @param $first_empty boolean
	 * @param array $args
	 *
	 * @return array
	 */
	public static function get_posts( $args = array(), $first_empty = true ) {
		$post_args   = array(
			'post_type'              => 'post',
			'post_status'            => 'publish',
			'posts_per_page'         => - 1,
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		);
		$args        = ! empty( $args ) ? array_merge( $post_args, $args ) : $post_args;
		$cache_key   = md5( serialize( $args ) );
		$posts_array = wp_cache_get( $cache_key, 'pebas-get-posts-field' );
		// Get from cache to prevent same queries.
		if ( false !== $posts_array ) {
			return $posts_array;
		}

		$query       = new WP_Query( $args );
		$posts_array = array();
		$first_empty = $first_empty ? $posts_array = array( '' => '' ) : '';
		foreach ( $query->posts as $post ) {
			$posts_array[ $post->post_title ] = $post->ID;
		}

		// Cache the query.
		wp_cache_set( $cache_key, $posts_array, 'pebas-get-posts-field' );

		return $posts_array;

	}

	/**
	 * Ger list of taxonomy terms
	 *
	 * @param string $taxonomy
	 * @param bool $first_empty
	 * @param bool $only_parent
	 *
	 * @return array
	 */
	public static function get_taxonomy_terms( $taxonomy, $first_empty = true, $only_parent = false ) {
		$terms_array = array();
		if ( $first_empty ) {
			$terms_array = array( '' );
		}
		$terms = get_terms( array(
			'taxonomy'   => $taxonomy,
			'hide_empty' => false
		) );
		foreach ( $terms as $term ) {
			if ( $term ) {
				if ( ! $only_parent ) {
					if ( strpos( $taxonomy, 'location' ) ) {
						if ( $term->parent != 0 ) {
							$terms_array[ sprintf( esc_html__( '%s City', $term->name ), $term->name ) ] = $term->term_id;
						} else {
							$terms_array[ $term->name ] = $term->term_id;
						}
					} else {
						if ( isset( $term->term_id ) ) {
							$terms_array[ $term->name ] = $term->term_id;
						}
					}

				} else {
					if ( 0 == $term->parent ) {
						$terms_array[ $term->name ] = $term->term_id;
					}
				}
			}
		}

		return $terms_array;
	}

	/**
	 * Check whether user has chosen one or more categories
	 *
	 * @param $filter
	 *
	 * @return bool
	 */
	public static function is_category_single( $filter ) {
		$filter_cat   = isset( $filter['cat'] ) ? $filter['cat'] : '';
		$has_category = ! empty( $filter_cat ) && ! strstr( $filter_cat, ',' ) ? true : false;

		return $has_category;
	}

	/**
	 * Decide what category filter to display per user selection
	 *
	 * @param $filter
	 */
	public static function category_filter( $filter ) {
		if ( self::is_category_single( $filter ) ) {
			self::category_filter_single( $filter );
		} else {
			self::category_filter_multiple( $filter );
		}
	}

	/**
	 * Create single category filter
	 *
	 * @param $filter
	 * @param bool $has_category
	 */
	public static function category_filter_single( $filter, $has_category = true ) {
		$filter_cat = isset( $filter['cat'] ) ? $filter['cat'] : '';
		?>
		<?php if ( $has_category && isset( $filter['category_filter'] ) && ! empty( $filter['category_filter'] ) && 1 == $filter['category_filter'] ): ?>
            <label for="lisner-category-filter-single" class="hidden"></label>
			<?php $categories = get_terms( array( 'taxonomy' => 'category', 'parent' => $filter_cat ) ); ?>
            <select name="lisner_category_filter" id="lisner-category-filter-single" class="news-filter">
				<?php if ( $has_category && empty( $filter_cat ) ) : ?>
                    <option value="<?php echo esc_attr( $filter_cat ); ?>"><?php esc_html_e( 'All', 'lisner-core' ); ?></option>
				<?php else: ?>
                    <option value="<?php echo esc_attr( $filter_cat ); ?>"><?php echo esc_html( get_term( $filter_cat )->name ); ?></option>
				<?php endif; ?>
				<?php foreach ( $categories as $category ) : ?>
                    <option value="<?php echo esc_attr( $category->term_id ); ?>"><?php echo esc_html( $category->name ); ?></option>
				<?php endforeach; ?>
            </select>
            <span class="news-query-args hidden"
                  data-posts_per_page="<?php echo isset( $filter['posts_per_page'] ) ? esc_attr( $filter['posts_per_page'] ) : ''; ?>"
                  data-author="<?php echo isset( $filter['author'] ) ? esc_attr( $filter['author'] ) : ''; ?>"
                  data-cat="<?php echo isset( $filter['cat'] ) ? esc_attr( $filter['cat'] ) : ''; ?>"
                  data-post__in="<?php echo isset( $filter['post__in'] ) ? esc_attr( $filter['post__in'] ) : ''; ?>"
                  data-order_by="<?php echo isset( $filter['order_by'] ) ? esc_attr( $filter['order_by'] ) : ''; ?>"
                  data-order="<?php echo isset( $filter['order'] ) ? esc_attr( $filter['order'] ) : ''; ?>">
                </span>
		<?php endif;
	}

	/**
	 * Create multiple category filter
	 *
	 * @param $filter
	 * @param bool $has_category
	 */
	public static function category_filter_multiple( $filter, $has_category = false ) {
		$filter_cat = isset( $filter['cat'] ) ? $filter['cat'] : '';
		$filter_cat = explode( ',', $filter_cat );
		?>
		<?php if ( ! $has_category && isset( $filter['category_filter'] ) && ! empty( $filter['category_filter'] ) && 1 == $filter['category_filter'] ): ?>
            <label for="lisner-category-filter-multiple" class="hidden"></label>
            <select name="lisner_category_filter" id="lisner-category-filter-multiple" class="news-filter">
				<?php foreach ( $filter_cat as $category ) : ?>
					<?php $category = get_term( $category ); ?>
                    <option value="<?php echo esc_attr( $category->term_id ); ?>"><?php echo esc_html( $category->name ); ?></option>
				<?php endforeach; ?>
            </select>
            <span class="news-query-args hidden"
                  data-posts_per_page="<?php echo isset( $filter['posts_per_page'] ) ? esc_attr( $filter['posts_per_page'] ) : ''; ?>"
                  data-author="<?php echo isset( $filter['author'] ) ? esc_attr( $filter['author'] ) : ''; ?>"
                  data-cat="<?php echo isset( $filter['cat'] ) ? esc_attr( $filter['cat'] ) : ''; ?>"
                  data-post__in="<?php echo isset( $filter['post__in'] ) ? esc_attr( $filter['post__in'] ) : ''; ?>"
                  data-order_by="<?php echo isset( $filter['order_by'] ) ? esc_attr( $filter['order_by'] ) : ''; ?>"
                  data-order="<?php echo isset( $filter['order'] ) ? esc_attr( $filter['order'] ) : ''; ?>">
                </span>
		<?php endif;
	}

	/**
	 * Load more new button for ajax news loading
	 *
	 * @param $filter
	 */
	public static function load_more_news_button( $filter ) {
		?>
		<?php if ( 1 == $filter['load_more'] ) : ?>
            <div class="load-more-wrapper d-flex justify-content-center mt-3">
                <a href="javascript:"
                   class="load-more-news btn btn-default"><?php esc_html_e( 'Load More', 'lisner-core' ); ?>
                    <span class="news-query-args hidden"
                          data-posts_per_page="<?php echo isset( $filter['posts_per_page'] ) ? esc_attr( $filter['posts_per_page'] ) : ''; ?>"
                          data-author="<?php echo isset( $filter['author'] ) ? esc_attr( $filter['author'] ) : ''; ?>"
                          data-cat="<?php echo isset( $filter['cat'] ) ? esc_attr( $filter['cat'] ) : ''; ?>"
                          data-post__in="<?php echo isset( $filter['post__in'] ) ? esc_attr( $filter['post__in'] ) : ''; ?>"
                          data-order_by="<?php echo isset( $filter['order_by'] ) ? esc_attr( $filter['order_by'] ) : ''; ?>"
                          data-order="<?php echo isset( $filter['order'] ) ? esc_attr( $filter['order'] ) : ''; ?>">
                </span>
                </a>
            </div>
		<?php endif; ?>
		<?php
	}

	/**
	 * Get posts per page array for shortcode param
	 *
	 * @param $count_from
	 * @param int $count_by
	 * @param int $count_max
	 * @param bool $first_empty
	 * @param string $name
	 *
	 * @return array
	 */
	public static function get_posts_per_page( $count_from, $count_by = 1, $count_max = 20, $first_empty = true, $name = '' ) {
		$posts_array = array();
		if ( $first_empty ) {
			$posts_array = array( esc_html__( 'All', 'lisner-core' ) => - 1 );
		}
		for ( $i = $count_from; $i <= $count_max; $i ++ ) {
			if ( $i % $count_by == 0 ) {
				$posts_array[ sprintf( esc_html__( '%d %s', 'lisner-core' ), $i, $name ) ] = $i;
			}
		}

		return $posts_array;
	}

}

/** Instantiate class
 *
 * @return null|lisner_shortcodes
 */
function lisner_shortcodes() {
	return lisner_shortcodes::instance();
}
