<?php

global $wp_query;

class lisner_widget_taxonomies extends WP_Widget {

	function __construct() {
		parent::__construct( 'lisner_widget_taxonomies', esc_html__( 'Lisner Listing Categories', 'lisner-core' ), array(
			'description'                 => esc_html__( 'Display list of various taxonomies to your preference', 'lisner-core' ),
			'customize_selective_refresh' => true,
		) );
		add_action( 'admin_enqueue_scripts', array( $this, 'add_scripts' ) );
	}

	public function add_scripts() {
	}

	function form( $instance ) {
		$title             = isset( $instance['title'] ) ? $instance['title'] : '';
		$number            = isset( $instance['number'] ) ? $instance['number'] : '';
		$taxonomy          = isset( $instance['taxonomy'] ) ? $instance['taxonomy'] : '';
		$cat_specific      = isset( $instance['cat_specific'] ) ? $instance['cat_specific'] : '';
		$location_specific = isset( $instance['location_specific'] ) ? $instance['location_specific'] : '';
		$amenity_specific  = isset( $instance['amenity_specific'] ) ? $instance['amenity_specific'] : '';
		?>
		<p>
			<label
					for="<?php echo esc_attr( $this->get_field_id( 'title' ), 'lisner-core' ); ?>"><?php esc_html_e( 'Title:', 'thebigmagazine-core' ) ?></label>
			<input type="text" class="widefat"
			       id="<?php echo esc_attr( $this->get_field_id( 'title' ), 'lisner-core' ); ?>"
			       name="<?php echo esc_attr( $this->get_field_name( 'title' ), 'lisner-core' ); ?>"
			       value="<?php echo esc_attr( $title ); ?>" />
			<small class="widget-desc"><?php esc_html_e( 'Enter widget title or leave empty to disable it.', 'lisner-core' ); ?></small>
		</p>
		<p>
			<label
					for="<?php echo esc_attr( $this->get_field_id( 'number' ), 'lisner-core' ); ?>"><?php esc_html_e( 'Choose Number of Terms:', 'thebigmagazine-core' ) ?></label>
			<select type="text" class="widefat"
			        id="<?php echo esc_attr( $this->get_field_id( 'number' ), 'lisner-core' ); ?>"
			        name="<?php echo esc_attr( $this->get_field_name( 'number' ), 'lisner-core' ); ?>">
				<option value=""><?php echo esc_html( 'All' ); ?></option>
				<?php for ( $i = 1; $i < 20; $i ++ ) : ?>
					<option value="<?php echo esc_attr( $i ); ?>" <?php echo $number == $i ? esc_attr( 'selected="selected"' ) : ''; ?>><?php printf( esc_html__( '%d Terms' ), $i ); ?></option>
				<?php endfor; ?>
			</select>
			<small class="widget-desc"><?php esc_html_e( 'Choose number of terms that will be displayed in the widget', 'lisner-core' ); ?></small>
		</p>
		<p>
			<label
					for="<?php echo esc_attr( $this->get_field_id( 'taxonomy' ), 'lisner-core' ); ?>"><?php esc_html_e( 'Choose Taxonomy:', 'thebigmagazine-core' ) ?></label>
			<select type="text" class="widefat category-switcher"
			        id="<?php echo esc_attr( $this->get_field_id( 'taxonomy' ), 'lisner-core' ); ?>"
			        name="<?php echo esc_attr( $this->get_field_name( 'taxonomy' ), 'lisner-core' ); ?>">
				<option value="<?php echo esc_attr( 'job_listing_category' ); ?>" <?php echo $taxonomy == 'job_listing_category' ? esc_attr( 'selected="selected"' ) : ''; ?>><?php echo esc_html( 'Listing Category' ); ?></option>
				<option value="<?php echo esc_attr( 'listing_location' ); ?>" <?php echo $taxonomy == 'listing_location' ? esc_attr( 'selected="selected"' ) : ''; ?>><?php echo esc_html( 'Listing Location' ); ?></option>
				<option value="<?php echo esc_attr( 'listing_amenity' ); ?>" <?php echo $taxonomy == 'listing_amenity' ? esc_attr( 'selected="selected"' ) : ''; ?>><?php echo esc_html( 'Listing Amenity' ); ?></option>
			</select>
			<small class="widget-desc"><?php esc_html_e( 'Enter widget title or leave empty to disable it.', 'lisner-core' ); ?></small>
		</p>
		<div class="job_listing_category category-switch-item">
			<label
					for="<?php echo esc_attr( $this->get_field_id( 'cat_specific' ) ); ?>"><?php esc_html_e( 'Choose Specific Categories:', 'lisner-core' ) ?></label>
			<select class="widefat select2-admin"
			        multiple
			        name="<?php echo esc_attr( $this->get_field_name( 'cat_specific' ) ) ?>[]"
			        id="<?php echo esc_attr( $this->get_field_id( 'cat_specific' ) ) ?>">
				<?php $cats = lisner_shortcodes::get_taxonomy_terms( 'job_listing_category', false ); ?>
				<?php if ( $cats ) : ?>
					<?php foreach ( $cats as $title => $id ) : ?>
						<option <?php echo is_array( $cat_specific ) && in_array( $id, $cat_specific ) ? esc_attr( 'selected="selected"' ) : ''; ?>
								value="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $title ); ?></option>
					<?php endforeach; ?>
				<?php endif; ?>
			</select>
			<small class="widget-desc"><?php esc_html_e( 'Choose specific terms that will be displayed', 'lisner-core' ); ?></small>
		</div>
		<div class="listing_location category-switch-item">
			<label
					for="<?php echo esc_attr( $this->get_field_id( 'location_specific' ) ); ?>"><?php esc_html_e( 'Choose Specific Locations:', 'lisner-core' ) ?></label>
			<select class="widefat select2-admin"
			        multiple
			        name="<?php echo esc_attr( $this->get_field_name( 'location_specific' ) ) ?>[]"
			        id="<?php echo esc_attr( $this->get_field_id( 'location_specific' ) ) ?>">
				<?php $cats = lisner_shortcodes::get_taxonomy_terms( 'listing_location', false ); ?>
				<?php if ( $cats ) : ?>
					<?php foreach ( $cats as $title => $id ) : ?>
						<option <?php echo is_array( $location_specific ) && in_array( $id, $location_specific ) ? esc_attr( 'selected="selected"' ) : ''; ?>
								value="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $title ); ?></option>
					<?php endforeach; ?>
				<?php endif; ?>
			</select>
			<small class="widget-desc"><?php esc_html_e( 'Choose specific terms that will be displayed', 'lisner-core' ); ?></small>
		</div>
		<div class="listing_amenity category-switch-item">
			<label
					for="<?php echo esc_attr( $this->get_field_id( 'amenity_specific' ) ); ?>"><?php esc_html_e( 'Choose Specific Amenity:', 'lisner-core' ) ?></label>
			<select class="widefat select2-admin"
			        multiple
			        name="<?php echo esc_attr( $this->get_field_name( 'amenity_specific' ) ) ?>[]"
			        id="<?php echo esc_attr( $this->get_field_id( 'amenity_specific' ) ) ?>">
				<?php $cats = lisner_shortcodes::get_taxonomy_terms( 'listing_amenity', false ); ?>
				<?php if ( $cats ) : ?>
					<?php foreach ( $cats as $title => $id ) : ?>
						<option <?php echo is_array( $amenity_specific ) && in_array( $id, $amenity_specific ) ? esc_attr( 'selected="selected"' ) : ''; ?>
								value="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $title ); ?></option>
					<?php endforeach; ?>
				<?php endif; ?>
			</select>
			<small class="widget-desc"><?php esc_html_e( 'Choose specific terms that will be displayed', 'lisner-core' ); ?></small>
		</div>
		<?php
	}

	/**
	 * Frontend display of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	function widget( $args, $instance ) {
		/** This filter is documented in wp-includes/default-widgets.php */
		$instance['taxonomy']          = ! empty( $instance['taxonomy'] ) ? $instance['taxonomy'] : '';
		$instance['number']            = ! empty( $instance['number'] ) ? $instance['number'] : '';
		$instance['cat_specific']      = ! empty( $instance['cat_specific'] ) ? $instance['cat_specific'] : '';
		$instance['location_specific'] = ! empty( $instance['location_specific'] ) ? $instance['location_specific'] : '';
		$instance['amenity_specific']  = ! empty( $instance['amenity_specific'] ) ? $instance['amenity_specific'] : '';

		echo $args['before_widget'];
		?>

		<?php if ( ! empty( $instance['title'] ) ) : ?>
			<h6 class="widget-title"><?php echo esc_html( $instance['title'] ); ?></h6>
		<?php endif; ?>

		<?php require lisner_helper::get_template_part( 'categories', 'widgets' ); ?>


		<?php echo $args['after_widget'];
	}

	/**
	 * Update widget with newly applied settings
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 *
	 * @return array
	 */
	function update( $new_instance, $old_instance ) {
		$instance                      = $old_instance;
		$instance['title']             = strip_tags( stripslashes( $new_instance['title'] ) );
		$instance['taxonomy']          = strip_tags( stripslashes( $new_instance['taxonomy'] ) );
		$instance['number']            = $new_instance['number'];
		$instance['cat_specific']      = isset( $new_instance['cat_specific'] ) ? $new_instance['cat_specific'] : '';
		$instance['location_specific'] = isset( $new_instance['location_specific'] ) ? $new_instance['location_specific'] : '';
		$instance['amenity_specific']  = isset( $new_instance['amenity_specific'] ) ? $new_instance['amenity_specific'] : '';

		return $instance;
	}
}
