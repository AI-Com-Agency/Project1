<?php

global $wp_query;

class lisner_widget_ads extends WP_Widget {

	function __construct() {
		parent::__construct( 'lisner_widget_ads', esc_html__( 'Lisner Ads', 'lisner-core' ), array(
			'description'                 => esc_html__( 'Display Google AdSense or custom ad to the site', 'lisner-core' ),
			'customize_selective_refresh' => true,
		) );
		add_action( 'admin_enqueue_scripts', array( $this, 'add_scripts' ) );
	}

	public function add_scripts() {
		wp_enqueue_media();
		wp_enqueue_script( 'lisner-ads-script', LISNER_URL . 'assets/scripts/widget-ad-upload.js', array( 'jquery' ), '1.0', true );
	}

	function form( $instance ) {
		$title           = isset( $instance['title'] ) ? $instance['title'] : '';
		$ad_type         = isset( $instance['ad_type'] ) ? $instance['ad_type'] : '';
		$google_code     = isset( $instance['google_code'] ) ? $instance['google_code'] : '';
		$custom_ad_link  = isset( $instance['custom_ad_link'] ) ? $instance['custom_ad_link'] : '';
		$custom_ad_media = isset( $instance['custom_ad_media'] ) ? $instance['custom_ad_media'] : '';
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
                    for="<?php echo esc_attr( $this->get_field_id( 'ad_type' ) ); ?>"><?php esc_html_e( 'Choose Ad Type:', 'lisner-core' ) ?></label>
            <select class="widefat select2-admin tbm-ad-switch"
                    name="<?php echo esc_attr( $this->get_field_name( 'ad_type' ) ) ?>"
                    id="<?php echo esc_attr( $this->get_field_id( 'ad_type' ) ) ?>">
                <option <?php echo strstr( $ad_type, 'google_ad' ) ? esc_attr( 'selected="selected"' ) : ''; ?>
                        value="google_ad"><?php esc_html_e( 'Google AdSense', 'lisner-core' ); ?></option>
                <option <?php echo strstr( $ad_type, 'custom_ad' ) ? esc_attr( 'selected="selected"' ) : ''; ?>
                        value="custom_ad"><?php esc_html_e( 'Custom Ad', 'lisner-core' ); ?></option>
            </select>
            <small class="widget-desc"><?php esc_html_e( 'Choose the type of the ad that you wish to use.', 'lisner-core' ); ?></small>
        </p>
        <p class="google_ad">
            <label
                    for="<?php echo esc_attr( $this->get_field_id( 'google_code' ), 'lisner-core' ); ?>"><?php esc_html_e( 'Google AdSense Code:', 'thebigmagazine-core' ) ?></label>
            <textarea rows="10" type="text" class="widefat"
                      id="<?php echo esc_attr( $this->get_field_id( 'google_code' ), 'lisner-core' ); ?>"
                      name="<?php echo esc_attr( $this->get_field_name( 'google_code' ), 'lisner-core' ); ?>"><?php echo $google_code; ?></textarea>
            <small class="widget-desc"><?php esc_html_e( 'Enter Google AdSense code.', 'lisner-core' ); ?></small>
        </p>
        <p class="custom_ad">
            <label
                    for="<?php echo esc_attr( $this->get_field_id( 'custom_ad_link' ), 'lisner-core' ); ?>"><?php esc_html_e( 'Custom Ad Link:', 'thebigmagazine-core' ) ?></label>
            <input type="text" class="widefat"
                   id="<?php echo esc_attr( $this->get_field_id( 'custom_ad_link' ), 'lisner-core' ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'custom_ad_link' ), 'lisner-core' ); ?>"
                   value="<?php echo esc_attr( $custom_ad_link ); ?>"/>
            <small class="widget-desc"><?php esc_html_e( 'Enter link for the custom ad.', 'lisner-core' ); ?></small>
        </p>
        <p class="custom_ad">
            <label for="<?php echo esc_attr( $this->get_field_id( 'custom_ad_media' ) ); ?>"><?php esc_html_e( 'Upload Ad Image:', 'lisner-core' ); ?></label><br/>

			<?php if ( $custom_ad_media ): ?>
                <img class="<?php echo esc_attr( $this->get_field_id( 'custom_ad_media' ) ); ?>_media_image custom_media_image"
                     src="<?php echo ! empty( $custom_ad_media ) ? esc_url( $custom_ad_media ) : '' ?>"/>
			<?php endif; ?>
            <input type="hidden"
                   class="<?php echo esc_attr( $this->get_field_id( 'custom_ad_media' ) ); ?>_media_id custom_media_id"
                   name="<?php echo esc_attr( $this->get_field_name( 'custom_ad_media' ) ); ?>"
                   id="<?php echo esc_attr( $this->get_field_id( 'custom_ad_media' ) ); ?>"
                   value="<?php echo esc_attr( $custom_ad_media ); ?>"/>
            <input type="button" value="<?php esc_attr_e( 'Upload Image', 'lisner-core' ); ?>"
                   class="button custom_media_upload"
                   id="<?php echo esc_attr( $this->get_field_id( 'custom_ad_media' ) ); ?>"/>
            <small class="widget-desc"><?php esc_html_e( 'Upload image for your custom ad.', 'lisner-core' ); ?></small>
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
		$instance['title']           = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$instance['ad_type']         = ! empty( $instance['ad_type'] ) ? $instance['ad_type'] : '';
		$instance['google_code']     = ! empty( $instance['google_code'] ) ? $instance['google_code'] : '';
		$instance['custom_ad_link']  = ! empty( $instance['custom_ad_link'] ) ? $instance['custom_ad_link'] : '';
		$instance['custom_ad_media'] = ! empty( $instance['custom_ad_media'] ) ? $instance['custom_ad_media'] : '';

		echo $args['before_widget'];
		?>

		<?php if ( ! empty( $instance['title'] ) ) : ?>
            <h6 class="widget-title"><?php echo esc_html( $instance['title'] ); ?></h6>
		<?php endif; ?>

		<?php require lisner_helper::get_template_part( 'ads', 'widgets' ); ?>


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
		$instance                    = $old_instance;
		$instance['title']           = strip_tags( stripslashes( $new_instance['title'] ) );
		$instance['custom_ad_link']  = strip_tags( stripslashes( $new_instance['custom_ad_link'] ) );
		$instance['custom_ad_media'] = strip_tags( stripslashes( $new_instance['custom_ad_media'] ) );
		$instance['ad_type']         = esc_sql( $new_instance['ad_type'] );
		$instance['google_code']     = $new_instance['google_code'];

		return $instance;
	}
}
