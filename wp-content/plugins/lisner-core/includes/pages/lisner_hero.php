<?php

/**
 * Class lisner_hero
 */

class lisner_hero {

	protected static $_instance = null;

	/**
	 * @return null|lisner_hero
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * lisner_pages constructor.
	 */
	public function __construct() {
		add_action( 'pbs_home_header', array( $this, 'home_hero_section' ) );
		add_image_size( 'lisner_hero', 1920, 350, true );
	}

	/**
	 * homepage hero section
	 */
	public function home_hero_section() {
		$page_id       = get_the_ID();
		$bg_type       = get_post_meta( $page_id, 'home_bg_type', true );
		$bg_type       = lisner_get_var( $bg_type, 'image' );
		$hero_template = get_post_meta( $page_id, 'home_hero_template', true );
		$images        = $this->get_home_hero_images( $page_id );
		$args          = array(
			'page_id' => $page_id
		);
		if ( $images ) {
			$args['images'] = $images;
		}

		// image overlay style
		$style    = '';
		$bg_color = $this->get_home_hero_images_overlay_style( $page_id, 'overlay', 'background-color' );
		if ( 'video' == $bg_type ) {
			$bg_color = $this->get_home_hero_images_overlay_style( $page_id, 'video_color', 'background-color' );
		}
		if ( lisner_get_var( $bg_color ) ) {
			$style = 'style=' . $bg_color;
		}
		?>
		<!-- Hero Section -->
		<section
				class="hero <?php echo esc_attr( 'hero-template-style-' . $hero_template ); ?>" <?php echo esc_attr( $style ); ?>>
			<?php if ( 'image' == $bg_type ) : ?>
				<?php include lisner_helper::get_template_part( 'hero-images', 'home/', $args ); // get homepage hero media ?>
			<?php else: ?>
				<?php include lisner_helper::get_template_part( 'hero-video', 'home/', $args ); // get homepage hero media ?>
			<?php endif; ?>
			<?php $this->get_main_search( $page_id ); // get search fields ?>
		</section>
		<?php
	}

	/**
	 * Get homepage hero section images
	 *
	 * @param $page_id
	 *
	 * @return mixed
	 */
	public function get_home_hero_images( $page_id ) {
		//todo add availability to use image from given listing package etc
		$images = rwmb_meta( 'home_bg_image', '', $page_id );

		return $images;
	}

	/**
	 * Get homepage hero section background images overlay styles
	 *
	 * @param $page_id
	 * @param $meta_key
	 * @param $option
	 *
	 * @return string
	 */
	public function get_home_hero_images_overlay_style( $page_id, $meta_key, $option ) {
		$bg_overlay = get_post_meta( $page_id, 'home_bg_overlay_show', true ) ? true : false;
		$meta_value = get_post_meta( $page_id, "home_bg_{$meta_key}", true ) ? : '';
		if ( is_numeric( $meta_value ) ) {
			$meta_value = 1 - $meta_value;
		}
		$overlay_style = '';
		if ( $bg_overlay && ! empty( $meta_value ) ) {
			$overlay_style = $option . ':' . $meta_value . ';';
		}

		return $overlay_style;

	}

	public function get_main_search( $page_id ) {
		$args                  = array(
			'page_id' => $page_id
		);
		$template              = $this->get_hero_template( $page_id );
		$multi_category_search = $this->allow_multi_category_search( $page_id );
		$default_taxonomies    = $this->get_default_taxonomies( $page_id );
		$featured_taxonomies   = $this->get_featured_taxonomies( $page_id );
		$heading               = $this->get_hero_heading( $page_id );
		$category_heading      = $this->get_hero_category_text( $page_id, $template );

		if ( $template ) { // get hero template
			$args['hero_template'] = $template;
		}
		if ( $multi_category_search ) { // get multi category option
			$args['is_multi_category'] = $multi_category_search;
		}
		if ( $heading ) { // get hero heading
			$args['heading'] = $heading;
		}
		if ( $category_heading ) { // get hero category heading
			$args['category_heading'] = $category_heading;
		}
		if ( $default_taxonomies ) {
			$args['default_taxonomies'] = $default_taxonomies;
		}
		if ( $featured_taxonomies ) {
			$args['featured_taxonomies'] = $featured_taxonomies;
		}

		include lisner_helper::get_template_part( "hero-search-template-{$template}", 'home/hero-search', $args ); // get homepage hero search
	}

	/**
	 * Get taxonomies that user selected as available
	 * to search from
	 *
	 * @param $page_id
	 *
	 * @return array|mixed
	 */
	public function get_searchable_taxonomies( $page_id ) {
		$taxonomies = array( 'job_listing_category' );

		return $taxonomies;
	}

	/**
	 * Get default taxonomies
	 *
	 * @param $page_id
	 *
	 * @return mixed
	 */
	public function get_default_taxonomies( $page_id ) {
		$taxonomies = rwmb_meta( "home_search_taxonomies_job_listing_category", '', $page_id );

		return $taxonomies;
	}

	/**
	 * Get featured taxonomies
	 *
	 * @param $page_id
	 *
	 * @return mixed
	 */
	public function get_featured_taxonomies( $page_id ) {
		$taxonomies = rwmb_meta( "home_search_featured_taxonomies", '', $page_id );

		return $taxonomies;
	}

	/**
	 * Get hero template
	 *
	 * @param $page_id
	 *
	 * @return int|mixed
	 */
	public function get_hero_template( $page_id ) {
		$template = rwmb_meta( 'home_hero_template', '', $page_id );

		if ( ! isset( $template ) || empty( $template ) ) {
			$template = 1;

			return $template;
		}

		return $template;
	}

	/**
	 * Get Homepage hero section heading
	 *
	 * @param $page_id
	 *
	 * @return mixed
	 */
	public function get_hero_heading( $page_id ) {
		$heading = rwmb_meta( 'home_hero_heading', '', $page_id );
		if ( ! isset( $heading ) || empty( $heading ) ) {
			return '';
		}

		return $heading;
	}

	/**
	 * Is multi category search allowed
	 *
	 * @param $page_id
	 *
	 * @return int|mixed
	 */
	public function allow_multi_category_search( $page_id ) {
		$option = get_post_meta( $page_id, 'home_search_multi_category', true );

		if ( isset( $option ) && ! empty( $option ) ) {
			return $option;
		}

		return true;
	}

	/**
	 * Return category text if it is existing and proper hero template is set
	 *
	 * @param $page_id
	 * @param $template
	 *
	 * @return mixed|string
	 */
	public function get_hero_category_text( $page_id, $template ) {
		$text = get_post_meta( $page_id, 'home_hero_category_heading', true );

		if ( 1 != $template && isset( $text ) && ! empty( $text ) ) {
			$text = str_replace( array( '[', ']' ), array( '<strong>', '</strong>' ), $text );

			return $text;
		}

		return false;

	}

	/**
	 * Get the id of YouTube video
	 *
	 * @param $url
	 *
	 * @return mixed|null
	 */
	public static function get_youtube_id( $url ) {
		preg_match( "#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $url, $matches );

		if ( $matches ) {
			return array_shift( $matches );
		}

		return null;
	}

	/**
	 * Get the thumbnail of the youtube video
	 *
	 *
	 * @param $url
	 *
	 * @return string
	 */
	public static function get_youtube_thumbnail( $url ) {
		$id = self::get_youtube_id( $url );

		$thumb = "https://ytimg.googleusercontent.com/vi/{$id}/maxresdefault.jpg";

		return $thumb;
	}

}

function lisner_hero() {
	return lisner_hero::instance();
}
