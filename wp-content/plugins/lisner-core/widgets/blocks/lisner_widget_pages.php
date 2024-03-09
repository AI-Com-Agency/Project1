<?php

global $wp_query;

class lisner_widget_pages extends WP_Widget {

	function __construct() {
		parent::__construct( 'lisner_widget_pages', esc_html__( 'Lisner Pages', 'lisner-core' ), array(
			'description'                 => esc_html__( 'Display list of pages according to your preference', 'lisner-core' ),
			'customize_selective_refresh' => true,
		) );
		add_action( 'admin_enqueue_scripts', array( $this, 'add_scripts' ) );
	}

	public function add_scripts() {
	}

	function form( $instance ) {
		$title          = isset( $instance['title'] ) ? $instance['title'] : '';
		$number         = isset( $instance['number'] ) ? $instance['number'] : '';
		$specific_pages = isset( $instance['specific_pages'] ) ? $instance['specific_pages'] : '';
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
				<?php for ( $i = 1; $i <= 20; $i ++ ) : ?>
					<option value="<?php echo esc_attr( $i ); ?>" <?php echo $number == $i ? esc_attr( 'selected="selected"' ) : ''; ?>><?php printf( esc_html__( '%d Pages' ), $i ); ?></option>
				<?php endfor; ?>
			</select>
			<small class="widget-desc"><?php esc_html_e( 'Choose number of terms that will be displayed in the widget', 'lisner-core' ); ?></small>
		</p>
		<p>
			<label
					for="<?php echo esc_attr( $this->get_field_id( 'specific_pages' ) ); ?>"><?php esc_html_e( 'Choose Specific Pages:', 'lisner-core' ) ?></label>
			<select class="widefat select2-admin"
			        multiple
			        name="<?php echo esc_attr( $this->get_field_name( 'specific_pages' ) ) ?>[]"
			        id="<?php echo esc_attr( $this->get_field_id( 'specific_pages' ) ) ?>">
				<?php $pages = get_pages(); ?>
				<?php if ( $pages ) : ?>
					<?php foreach ( $pages as $page ) : ?>
						<option <?php echo is_array( $specific_pages ) && in_array( $page->ID, $specific_pages ) ? esc_attr( 'selected="selected"' ) : ''; ?>
								value="<?php echo esc_attr( $page->ID ); ?>"><?php echo esc_html( $page->post_title ); ?></option>
					<?php endforeach; ?>
				<?php endif; ?>
			</select>
			<small class="widget-desc"><?php esc_html_e( 'Choose specific terms that will be displayed', 'lisner-core' ); ?></small>
		</p>
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
		$instance['number']         = ! empty( $instance['number'] ) ? $instance['number'] : '';
		$instance['specific_pages'] = ! empty( $instance['specific_pages'] ) ? $instance['specific_pages'] : '';

		echo $args['before_widget'];
		?>

		<?php if ( ! empty( $instance['title'] ) ) : ?>
			<h6 class="widget-title"><?php echo esc_html( $instance['title'] ); ?></h6>
		<?php endif; ?>

		<?php require lisner_helper::get_template_part( 'pages', 'widgets' ); ?>


		<?php
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
		$instance                   = $old_instance;
		$instance['title']          = strip_tags( stripslashes( $new_instance['title'] ) );
		$instance['number']         = $new_instance['number'];
		$instance['specific_pages'] = isset( $new_instance['specific_pages'] ) ? $new_instance['specific_pages'] : '';

		return $instance;
	}
}
