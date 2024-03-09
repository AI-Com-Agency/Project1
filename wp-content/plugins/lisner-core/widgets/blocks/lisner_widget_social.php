<?php

global $wp_query;

class lisner_widget_social extends WP_Widget {

	function __construct() {
		parent::__construct( 'lisner_widget_social', esc_html__( 'Lisner Social', 'lisner-core' ), array(
			'description'                 => esc_html__( 'Display list of your various social profiles with icons', 'lisner-core' ),
			'customize_selective_refresh' => true,
		) );
		add_action( 'admin_enqueue_scripts', array( $this, 'add_scripts' ) );
	}

	public function add_scripts() {
	}

	function form( $instance ) {
		$title     = isset( $instance['title'] ) ? $instance['title'] : '';
		$facebook  = isset( $instance['facebook'] ) ? $instance['facebook'] : '';
		$twitter   = isset( $instance['twitter'] ) ? $instance['twitter'] : '';
		$google    = isset( $instance['google'] ) ? $instance['google'] : '';
		$youtube   = isset( $instance['youtube'] ) ? $instance['youtube'] : '';
		$instagram = isset( $instance['instagram'] ) ? $instance['instagram'] : '';
		$pinterest = isset( $instance['pinterest'] ) ? $instance['pinterest'] : '';
		$email     = isset( $instance['email'] ) ? $instance['email'] : '';
		?>
        <p>
            <label
                    for="<?php echo esc_attr( $this->get_field_id( 'title' ), 'lisner-core' ); ?>"><?php esc_html_e( 'Title:', 'thebigmagazine-core' ) ?></label>
            <input type="text" class="widefat"
                   id="<?php echo esc_attr( $this->get_field_id( 'title' ), 'lisner-core' ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'title' ), 'lisner-core' ); ?>"
                   value="<?php echo esc_attr( $title ); ?>"/>
            <small class="widget-desc"><?php esc_html_e( 'Enter widget title or leave empty to disable it.', 'lisner-core' ); ?></small>
        </p>
        <p>
            <label
                    for="<?php echo esc_attr( $this->get_field_id( 'facebook' ), 'lisner-core' ); ?>"><?php esc_html_e( 'Facebook Profile:', 'thebigmagazine-core' ) ?></label>
            <input type="text" class="widefat"
                   id="<?php echo esc_attr( $this->get_field_id( 'facebook' ), 'lisner-core' ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'facebook' ), 'lisner-core' ); ?>"
                   value="<?php echo esc_attr( $facebook ); ?>"/>
            <small class="widget-desc"><?php esc_html_e( 'Enter link to your facebook profile', 'lisner-core' ); ?></small>
        </p>
        <p>
            <label
                    for="<?php echo esc_attr( $this->get_field_id( 'twitter' ), 'lisner-core' ); ?>"><?php esc_html_e( 'Twitter Profile:', 'thebigmagazine-core' ) ?></label>
            <input type="text" class="widefat"
                   id="<?php echo esc_attr( $this->get_field_id( 'twitter' ), 'lisner-core' ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'twitter' ), 'lisner-core' ); ?>"
                   value="<?php echo esc_attr( $twitter ); ?>"/>
            <small class="widget-desc"><?php esc_html_e( 'Enter link to your twitter profile', 'lisner-core' ); ?></small>
        </p>
        <p>
            <label
                    for="<?php echo esc_attr( $this->get_field_id( 'google' ), 'lisner-core' ); ?>"><?php esc_html_e( 'Google+ Profile:', 'thebigmagazine-core' ) ?></label>
            <input type="text" class="widefat"
                   id="<?php echo esc_attr( $this->get_field_id( 'google' ), 'lisner-core' ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'google' ), 'lisner-core' ); ?>"
                   value="<?php echo esc_attr( $google ); ?>"/>
            <small class="widget-desc"><?php esc_html_e( 'Enter link to your google+ profile', 'lisner-core' ); ?></small>
        </p>
        <p>
            <label
                    for="<?php echo esc_attr( $this->get_field_id( 'youtube' ), 'lisner-core' ); ?>"><?php esc_html_e( 'YouTube Profile:', 'thebigmagazine-core' ) ?></label>
            <input type="text" class="widefat"
                   id="<?php echo esc_attr( $this->get_field_id( 'youtube' ), 'lisner-core' ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'youtube' ), 'lisner-core' ); ?>"
                   value="<?php echo esc_attr( $youtube ); ?>"/>
            <small class="widget-desc"><?php esc_html_e( 'Enter link to your youtube profile', 'lisner-core' ); ?></small>
        </p>
        <p>
            <label
                    for="<?php echo esc_attr( $this->get_field_id( 'instagram' ), 'lisner-core' ); ?>"><?php esc_html_e( 'Instagram Profile:', 'thebigmagazine-core' ) ?></label>
            <input type="text" class="widefat"
                   id="<?php echo esc_attr( $this->get_field_id( 'instagram' ), 'lisner-core' ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'instagram' ), 'lisner-core' ); ?>"
                   value="<?php echo esc_attr( $instagram ); ?>"/>
            <small class="widget-desc"><?php esc_html_e( 'Enter link to your instagram profile', 'lisner-core' ); ?></small>
        </p>
        <p>
            <label
                    for="<?php echo esc_attr( $this->get_field_id( 'pinterest' ), 'lisner-core' ); ?>"><?php esc_html_e( 'Pinterest Profile:', 'thebigmagazine-core' ) ?></label>
            <input type="text" class="widefat"
                   id="<?php echo esc_attr( $this->get_field_id( 'pinterest' ), 'lisner-core' ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'pinterest' ), 'lisner-core' ); ?>"
                   value="<?php echo esc_attr( $pinterest ); ?>"/>
            <small class="widget-desc"><?php esc_html_e( 'Enter link to your pinterest profile', 'lisner-core' ); ?></small>
        </p>
        <p>
            <label
                    for="<?php echo esc_attr( $this->get_field_id( 'email' ), 'lisner-core' ); ?>"><?php esc_html_e( 'Your Email:', 'thebigmagazine-core' ) ?></label>
            <input type="text" class="widefat"
                   id="<?php echo esc_attr( $this->get_field_id( 'email' ), 'lisner-core' ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'email' ), 'lisner-core' ); ?>"
                   value="<?php echo esc_attr( $email ); ?>"/>
            <small class="widget-desc"><?php esc_html_e( 'Enter link to your email', 'lisner-core' ); ?></small>
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
		$instance['title']     = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$instance['facebook']  = ! empty( $instance['facebook'] ) ? $instance['facebook'] : '';
		$instance['twitter']   = ! empty( $instance['twitter'] ) ? $instance['twitter'] : '';
		$instance['google']    = ! empty( $instance['google'] ) ? $instance['google'] : '';
		$instance['youtube']   = ! empty( $instance['youtube'] ) ? $instance['youtube'] : '';
		$instance['instagram'] = ! empty( $instance['instagram'] ) ? $instance['instagram'] : '';
		$instance['pinterest'] = ! empty( $instance['pinterest'] ) ? $instance['pinterest'] : '';
		$instance['email']     = ! empty( $instance['email'] ) ? $instance['email'] : '';

		echo $args['before_widget'];
		?>

		<?php if ( ! empty( $instance['title'] ) ) : ?>
            <h6 class="widget-title"><?php echo esc_html( $instance['title'] ); ?></h6>
		<?php endif; ?>

		<?php require lisner_helper::get_template_part( 'social', 'widgets' ); ?>


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
		$instance              = $old_instance;
		$instance['title']     = strip_tags( stripslashes( $new_instance['title'] ) );
		$instance['facebook']  = strip_tags( stripslashes( $new_instance['facebook'] ) );
		$instance['twitter']   = strip_tags( stripslashes( $new_instance['twitter'] ) );
		$instance['google']    = strip_tags( stripslashes( $new_instance['google'] ) );
		$instance['youtube']   = strip_tags( stripslashes( $new_instance['youtube'] ) );
		$instance['instagram'] = strip_tags( stripslashes( $new_instance['instagram'] ) );
		$instance['pinterest'] = strip_tags( stripslashes( $new_instance['pinterest'] ) );
		$instance['email']     = strip_tags( stripslashes( $new_instance['email'] ) );

		return $instance;
	}
}
