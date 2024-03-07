<?php

global $wp_query;

class lisner_widget_promo extends WP_Widget {

	function __construct() {
		parent::__construct( 'lisner_widget_promo', esc_html__( 'Lisner Promo', 'lisner-core' ), array(
			'description'                 => esc_html__( 'Display promoted listings according to your preference', 'lisner-core' ),
			'customize_selective_refresh' => true,
		) );
	}

	function form( $instance ) {
		$title           = isset( $instance['title'] ) ? $instance['title'] : '';
		$style           = isset( $instance['style'] ) ? $instance['style'] : '';
		$post_in         = isset( $instance['post_in'] ) ? $instance['post_in'] : '';
		$ppp             = isset( $instance['posts_per_page'] ) ? $instance['posts_per_page'] : '';
		$order           = isset( $instance['order'] ) ? $instance['order'] : '';
		$listing_package = isset( $instance['job_package'] ) ? $instance['job_package'] : '';
		$only_open       = isset( $instance['only_open'] ) ? $instance['only_open'] : '';
		?>
        <p>
            <label
                    for="<?php echo esc_attr( $this->get_field_id( 'title' ), 'lisner-core' ); ?>"><?php esc_html_e( 'Title:', 'thebigmagazine-core' ) ?></label>
            <input type="text" class="widefat"
                   id="<?php echo esc_attr( $this->get_field_id( 'title' ), 'lisner-core' ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'title' ), 'lisner-core' ); ?>"
                   value="<?php echo esc_attr( $title, 'lisner-core' ); ?>"/>
            <small class="widget-desc"><?php esc_html_e( 'Enter widget title or leave empty to disable it.', 'lisner-core' ); ?></small>
        </p>
        <p>
            <label
                    for="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>"><?php esc_html_e( 'Choose Promo Listing Style:', 'lisner-core' ) ?></label>
            <select class="widefat"
                    name="<?php echo esc_attr( $this->get_field_name( 'style' ) ) ?>"
                    id="<?php echo esc_attr( $this->get_field_id( 'style' ) ) ?>">
                <option <?php echo 1 == $style ? esc_attr( 'selected="selected"' ) : ''; ?>
                        value="1"><?php esc_html_e( 'Style 1', 'lisner-core' ); ?></option>
            </select>
            <small class="widget-desc"><?php esc_html_e( 'Choose widget style, see the theme documentation or demo to find out more about them.', 'lisner-core' ); ?></small>
        </p>
        <p>
            <label
                    for="<?php echo esc_attr( $this->get_field_id( 'posts_per_page' ) ); ?>"><?php esc_html_e( 'Choose Number of News:', 'lisner-core' ) ?></label>
            <select class="widefat select2-admin"
                    name="<?php echo esc_attr( $this->get_field_name( 'posts_per_page' ) ) ?>"
                    id="<?php echo esc_attr( $this->get_field_id( 'posts_per_page' ) ) ?>">
                <option value="1"><?php esc_html_e( '1 Listing', 'lisner-core' ); ?></option>
				<?php for ( $i = 2; $i <= 10; $i ++ ) : ?>
                    <option <?php echo ! empty( $ppp ) && $ppp == $i ? esc_attr( 'selected="selected"' ) : ( empty( $ppp ) && 1 == $i ? esc_attr( 'selected="selected"' ) : '' ); ?>
                            value="<?php echo esc_attr( $i ); ?>"><?php echo sprintf( esc_html__( '%d Listings', 'lisner-core' ), $i ); ?></option>
				<?php endfor; ?>
            </select>
            <small class="widget-desc"><?php esc_html_e( 'Choose maximum number of listings that will be displayed.', 'lisner-core' ); ?></small>
        </p>
        <p>
            <label
                    for="<?php echo esc_attr( $this->get_field_id( 'post_in' ) ); ?>"><?php esc_html_e( 'Choose Specific Listings:', 'lisner-core' ) ?></label>
            <select class="widefat select2-admin"
                    multiple
                    name="<?php echo esc_attr( $this->get_field_name( 'post_in' ) ) ?>[]"
                    id="<?php echo esc_attr( $this->get_field_id( 'post_in' ) ) ?>">
				<?php $listings = lisner_shortcodes::get_posts( array(
					'post_type'      => 'job_listing',
					'posts_per_page' => - 1
				), false ); ?>
				<?php if ( $listings ) : ?>
					<?php foreach ( $listings as $title => $id ) : ?>
                        <option <?php echo is_array( $post_in ) && in_array( $id, $post_in ) ? esc_attr( 'selected="selected"' ) : ''; ?>
                                value="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $title ); ?></option>
					<?php endforeach; ?>
				<?php endif; ?>
            </select>
            <small class="widget-desc"><?php esc_html_e( 'Choose specific listings that will be displayed. Can be overridden by Listing Package.', 'lisner-core' ); ?></small>
        </p>
        <p>
            <label
                    for="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>"><?php esc_html_e( 'Choose Listings Order:', 'lisner-core' ) ?></label>
            <select class="widefat select2-admin"
                    name="<?php echo esc_attr( $this->get_field_name( 'order' ) ) ?>"
                    id="<?php echo esc_attr( $this->get_field_id( 'order' ) ) ?>">
                <option <?php echo strstr( $order, 'random' ) ? esc_attr( 'selected="selected"' ) : ''; ?>
                        value="rand"><?php esc_html_e( 'Random', 'lisner-core' ); ?></option>
                <option <?php echo strstr( $order, 'date' ) ? esc_attr( 'selected="selected"' ) : ''; ?>
                        value="date"><?php esc_html_e( 'By Date', 'lisner-core' ); ?></option>
                <option <?php echo strstr( $order, 'views' ) ? esc_attr( 'selected="selected"' ) : ''; ?>
                        value="views"><?php esc_html_e( 'By Views', 'lisner-core' ); ?></option>
                <option <?php echo strstr( $order, 'views' ) ? esc_attr( 'selected="selected"' ) : ''; ?>
                        value="comment"><?php esc_html_e( 'By Comments', 'lisner-core' ); ?></option>
            </select>
            <small class="widget-desc"><?php esc_html_e( 'Choose arguments by which the listing will be displayed.', 'lisner-core' ); ?></small>
        </p>
		<?php if ( lisner_helper::is_plugin_active( 'pebas-paid-listings' ) && lisner_helper::is_plugin_active( 'woocommerce' ) ) : ?>
            <p>
				<?php $packages_args = array(
					'post_type' => 'product',
					'tax_query' => WC()->query->get_tax_query( array(
							'relation' => 'AND',
							array(
								'taxonomy' => 'product_type',
								'field'    => 'slug',
								'terms'    => array( pebas_paid_listings_install()->pebas_paid_term_name ),
								'operator' => 'IN',
							),
						)
					),
				);
				?>
				<?php $packages = lisner_shortcodes::get_posts( $packages_args, false ); ?>
                <label
                        for="<?php echo esc_attr( $this->get_field_id( 'job_package' ) ); ?>"><?php esc_html_e( 'Choose Promoted Package:', 'lisner-core' ) ?></label>
                <select class="widefat select2-admin"
                        name="<?php echo esc_attr( $this->get_field_name( 'job_package' ) ) ?>"
                        id="<?php echo esc_attr( $this->get_field_id( 'job_package' ) ) ?>">
					<?php if ( $packages ) : ?>
                        <option value=""><?php esc_html_e( 'No Package', 'lisner-core' ); ?></option>
						<?php foreach ( $packages as $title => $id ) : ?>
                            <option <?php echo $listing_package == $id ? esc_attr( 'selected="selected"' ) : ''; ?>
                                    value="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $title ); ?></option>
						<?php endforeach; ?>
					<?php endif; ?>
                </select>
                <small class="widget-desc"><?php esc_html_e( 'Choose listing package by which the listings will be displayed.', 'lisner-core' ); ?></small>
            </p>
            <p>
                <label
                        for="<?php echo esc_attr( $this->get_field_id( 'only_open' ) ); ?>"><?php esc_html_e( 'Display Only Open Listings:', 'lisner-core' ) ?></label>
                <select class="widefat select2-admin"
                        name="<?php echo esc_attr( $this->get_field_name( 'only_open' ) ) ?>"
                        id="<?php echo esc_attr( $this->get_field_id( 'only_open' ) ) ?>">
                    <option <?php echo strstr( $only_open, '0' ) ? esc_attr( 'selected="selected"' ) : ''; ?>
                            value="0"><?php esc_html_e( 'No', 'lisner-core' ); ?></option>
                    <option <?php echo strstr( $only_open, '1' ) ? esc_attr( 'selected="selected"' ) : ''; ?>
                            value="1"><?php esc_html_e( 'Yes', 'lisner-core' ); ?></option>
                </select>
                <small class="widget-desc"><?php esc_html_e( 'Only display listings that are open at the time of query. Do not show closed ( outside of working hours ) listings.', 'lisner-core' ); ?></small>
            </p>
		<?php endif; ?>
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
		$instance['style']     = ! empty( $instance['style'] ) ? $instance['style'] : '';
		$instance['post_in']   = ! empty( $instance['post_in'] ) ? $instance['post_in'] : '';
		$instance['order']     = ! empty( $instance['order'] ) ? $instance['order'] : '';
		$instance['only_open'] = ! empty( $instance['only_open'] ) ? $instance['only_open'] : '';

		// set post status by user preference
		if ( ! empty( $args['post_status'] ) ) {
			$post_status = $args['post_status'];
		} elseif ( false == get_option( 'job_manager_hide_expired', get_option( 'job_manager_hide_expired_content', 1 ) ) ) {
			$post_status = array( 'publish', 'expired' );
		} else {
			$post_status = 'publish';
		}
		$query                        = array(
			'post_type'      => 'job_listing',
			'post_status'    => $post_status,
			'posts_per_page' => $instance['posts_per_page'],
			'paged'          => get_query_var( 'paged' ),
		);
		$query['ignore_sticky_posts'] = 1; // make sure sticky posts are ignored

		// display specific listings
		if ( ! empty( $instance['post_in'] ) ) {
			$query['post__in'] = $instance['post_in'];
		}

		// sort listings by preferred order
		if ( isset( $instance['order'] ) && $instance['order'] ) :
			if ( 'rand' == $instance['order'] ) :
				$query['orderby'] = 'rand';
			else:
				$query['order'] = $instance['order'];
			endif;
		endif;

		// display only opened listings
		if ( isset( $instance['only_open'] ) && $instance['only_open'] ) :
			$open_ids         = lisner_search()->display_open_listings();
			$args['post__in'] = $open_ids;
		endif;

		// display listings of certain package
		if ( lisner_helper::is_plugin_active( 'pebas-paid-listings' ) && lisner_helper::is_plugin_active( 'woocommerce' ) ) {
			if ( ! empty( $instance['job_package'] ) ) {
				$post_ids = pebas_get_listings_by_package( $instance['job_package'] );
				if ( isset( $open_ids ) && ! empty( $open_ids ) ):
					$post_ids          = array_intersect( $open_ids, $post_ids );
					$query['post__in'] = $post_ids;
				else:
					$query['post__in'] = $post_ids;
				endif;
			}
		}

		echo $args['before_widget'];
		?>

		<?php if ( ! empty( $instance['title'] ) ) : ?>
            <h4 class="single-listing-section-title"><?php echo esc_html( $instance['title'] ); ?></h4>
		<?php endif; ?>

		<?php require lisner_helper::get_template_part( 'promo', 'widgets', $query ); ?>


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
		$instance                   = $old_instance;
		$instance['title']          = strip_tags( stripslashes( $new_instance['title'] ) );
		$instance['style']          = esc_sql( $new_instance['style'] );
		$instance['posts_per_page'] = esc_sql( $new_instance['posts_per_page'] );
		$instance['order']          = esc_sql( $new_instance['order'] );
		$instance['post_in']        = isset( $new_instance['post_in'] ) ? esc_sql( $new_instance['post_in'] ) : '';
		$instance['only_open']      = esc_sql( $new_instance['only_open'] );
		if ( lisner_helper::is_plugin_active( 'pebas-paid-listings' ) && lisner_helper::is_plugin_active( 'woocommerce' ) ) {
			$instance['job_package'] = esc_sql( $new_instance['job_package'] );
		}

		return $instance;
	}
}
