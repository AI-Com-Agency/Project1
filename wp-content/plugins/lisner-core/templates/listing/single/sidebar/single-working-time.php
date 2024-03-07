<?php
/**
 * Template Name: Listing Single Working Time
 * Description: Partial content for single listing sidebar
 *
 * @author pebas
 * @version 1.0.0
 * @package listing/single/sidebar
 *
 * @var $wt_args
 */
?>
<?php if ( $wt_args['has_title'] ) : ?>
	<?php $title = $wt_args['title']; ?>
	<?php include lisner_helper::get_template_part( 'single-section-title', 'listing/single/content' ); ?>
<?php endif; ?>

<?php $time_format = lisner_get_option( 'units-clock' ); ?>
<div class="working-time-wrapper">
	<?php $open_now = lisner_helper()->is_open_now_render( get_the_ID() ); ?>
    <!-- Single Widget / Open atm -->
    <div class="working-time-open">
        <i class="material-icons mf"><?php echo esc_html( 'query_builder' ); ?></i>
		<?php if ( '1' == lisner_get_var( $args['page_template'], 1 ) ): ?>
            <span class="working-time-text theme-color"><?php esc_html_e( 'Work Time', 'lisner-core' ); ?></span>
		<?php else: ?>
			<?php if ( $open_now ) : ?>
                <span class="working-time-text"><?php esc_html_e( 'Open', 'lisner-core' ); ?></span>
			<?php else: ?>
                <span class="working-time-text closed"><?php esc_html_e( 'Closed', 'lisner-core' ); ?></span>
			<?php endif; ?>
		<?php endif; ?>
    </div>
	<?php if ( '1' == lisner_get_var( $args['page_template'], 1 ) ): ?>
        <div class="working-time-open">
			<?php if ( $open_now ) : ?>
                <span class="working-time-text"><?php esc_html_e( 'Open', 'lisner-core' ); ?></span>
			<?php else: ?>
                <span class="working-time-text closed"><?php esc_html_e( 'Closed', 'lisner-core' ); ?></span>
			<?php endif; ?>
        </div>
	<?php endif; ?>
	<?php if ( isset( $open_settings ) ) : ?>
        <!-- Single Widget / Working Time -->
        <div class="working-time-hours">
			<?php if ( 'custom' == $open_settings ) : ?>
                <div class="working-time-current">
					<?php foreach ( $wt_args['hours'] as $open => $close ) : ?>
						<?php $open = 'am_pm' != $time_format ? $open : date( 'g:ia', strtotime( $open ) ); ?>
						<?php $close = 'am_pm' != $time_format ? $close : date( 'g:ia', strtotime( $close ) ); ?>
                        <div class="working-time-group">
                            <span class="working-time-open"><?php echo esc_html( $open ); ?></span>
                            <span class="working-time-divider"><?php echo esc_html( '-' ); ?></span>
                            <span class="working-time-close"><?php echo esc_html( $close ); ?></span>
                        </div>
					<?php endforeach; ?>
                </div>
			<?php else: ?>
                <div class="working-time-group">
					<?php switch ( $open_settings ) :
						case 'open':
							$open_text = '<span class="working-time-open">' . esc_html__( 'Open All Day', 'lisner-core' ) . '</span>';
							break;
						case 'closed':
							$open_text = '<span class="working-time-open">' . esc_html__( 'Closed All Day', 'lisner-core' ) . '</span>';
							break;
						case 'appointment':
							$open_text = '<span class="working-time-open">' . esc_html__( 'By Appointment', 'lisner-core' ) . '</span>';
							break;
					endswitch; ?>
					<?php echo wp_kses_post( $open_text ); ?>
                </div>
			<?php endif; ?>

        </div>
	<?php endif; ?>
	<?php $days = lisner_days_of_week(); ?>
	<?php $days_eng = lisner_days_of_week_normalize(); ?>
	<?php $days_count = 0; ?>
	<?php if ( isset( $days ) ) : ?>
        <a href="javascript:"
           data-icon-open="<?php echo esc_attr( 'add_circle_outline' ); ?>"
           data-icon-close="<?php echo esc_attr( 'remove_circle_outline' ); ?>"
           class="working-hours-call material-icons mf"><?php echo esc_html( 'add_circle_outline' ); ?></a>
        <!-- Single Widgets / Working Time All Days -->
        <div class="working-time-all hidden">
            <div class="working-time-all-wrapper">
				<?php foreach ( $days as $day => $label ) : ?>
					<?php $open_settings = get_post_meta( get_the_ID(), "_listing_{$days_eng[$days_count]}_hours_radio", true ); ?>
					<?php $open_hours = get_post_meta( get_the_ID(), "_listing_{$days_eng[$days_count]}_hours_open" ); ?>
					<?php $close_hours = get_post_meta( get_the_ID(), "_listing_{$days_eng[$days_count]}_hours_close" ); ?>
					<?php $open_hours = array_shift( $open_hours ); ?>
					<?php $close_hours = array_shift( $close_hours ); ?>
					<?php $hours = is_array( $open_hours ) && is_array( $close_hours ) ? array_combine( $open_hours, $close_hours ) : ''; ?>
					<?php $count = 0; ?>
                    <div class="working-time-all-group">
						<?php if ( 0 == $count ) : ?>
                            <div class="working-time-all-day"><?php echo esc_html( ucwords( $day ) ); ?></div>
						<?php endif; ?>
                        <div class="working-time-group-wrapper">
							<?php if ( 'custom' == $open_settings ) : ?>
								<?php foreach ( $hours as $open => $close ) : ?>
									<?php $open = 'am_pm' != $time_format ? $open : date( 'g:ia', strtotime( $open ) ); ?>
									<?php $close = 'am_pm' != $time_format ? $close : date( 'g:ia', strtotime( $close ) ); ?>
                                    <div class="working-time-group">
                                        <span class="working-time-open"><?php echo esc_html( $open ); ?></span>
                                        <span class="working-time-divider"><?php echo esc_html( '-' ); ?></span>
                                        <span class="working-time-close"><?php echo esc_html( $close ); ?></span>
                                    </div>
								<?php endforeach; ?>
							<?php else: ?>
                                <div class="working-time-group">
									<?php switch ( $open_settings ) :
										case 'open':
											$open_text = '<span class="working-time-open">' . esc_html__( 'Open All Day', 'lisner-core' ) . '</span>';
											break;
										case 'closed':
											$open_text = '<span class="working-time-open">' . esc_html__( 'Closed All Day', 'lisner-core' ) . '</span>';
											break;
										case 'appointment':
											$open_text = '<span class="working-time-open">' . esc_html__( 'By Appointment', 'lisner-core' ) . '</span>';
											break;
									endswitch; ?>
									<?php echo wp_kses_post( $open_text ); ?>
                                </div>
							<?php endif; ?>
                        </div>
                    </div>
					<?php $count ++; ?>
					<?php $days_count ++; ?>
				<?php endforeach; ?>
            </div>
        </div>
	<?php endif; ?>

</div>
