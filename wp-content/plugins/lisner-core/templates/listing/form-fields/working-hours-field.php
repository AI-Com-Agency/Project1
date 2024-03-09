<?php
/**
 * Shows the `working-hours` form field on listing forms.
 *
 * @author      pebas
 * @package     Lisner
 * @category    Template
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$classes    = array( 'input-text' );
$field_name = isset( $field['name'] ) ? $field['name'] : $key;

$days    = lisner_days_of_week();
$days_eng = lisner_days_of_week_normalize();
$post_id = isset( $_REQUEST['job_id'] ) ? $_REQUEST['job_id'] : '';
?>
<div class="working-hours">
    <div class="working-hours-days">
        <input type="hidden" name="<?php echo esc_attr( $field_name ); ?>" value="true">
		<?php if ( ! empty( $days ) ) : ?>
            <div class="working-hours-days-wrapper">
				<?php $day_count = 0; ?>
				<?php foreach ( $days as $day => $day_title ) : ?>
                    <!-- Working Days Labels -->
                    <div class="working-hours-day working-hours-day-<?php echo esc_attr( $day ); ?>">
                        <a href="javascript:"
                           class="<?php echo $day == $day_count ? esc_attr( 'active' ) : ''; ?>"
                           data-working-day="<?php echo esc_attr( $days_eng[$day_count] ); ?>"><?php echo esc_html( $day_title ); ?></a>
                    </div>
					<?php $day_count ++; ?>
				<?php endforeach; ?>
            </div>
            <div class="working-hours-form-wrapper">
                <?php $day_count = 0; ?>
				<?php foreach ( $days as $day => $day_title ) : ?>
					<?php $working_radio_hours_open = get_post_meta( $post_id, "_listing_{$days_eng[$day_count]}_hours_open", false ); ?>
					<?php $working_radio_hours_closed = get_post_meta( $post_id, "_listing_{$days_eng[$day_count]}_hours_close",
						false ); ?>
					<?php $working_hours = ! empty( $working_radio_hours_open ) && ! empty( $working_radio_hours_closed ) ? array_combine( array_shift( $working_radio_hours_open ),
						array_shift( $working_radio_hours_closed ) ) : ''; ?>

					<?php $count_count = rand(); ?>

					<?php $working_radio_value = get_post_meta( $post_id, "_listing_{$days_eng[$day_count]}_hours_radio", true ); ?>
					<?php $working_radio_value = isset( $working_radio_value ) ? $working_radio_value : 'custom'; ?>
                    <!-- Working Days Form -->
                    <div class="working-days-form working-days-form-<?php echo esc_attr( $days_eng[$day_count] ); ?> <?php echo $days_eng[$day_count] != 'monday' ? esc_attr( 'hidden' ) : ''; ?>">
                        <div class="working-hours-day-options working-hours-day-options-<?php echo esc_attr( $days_eng[$day_count] ); ?>">
                            <div class="custom-control custom-radio">
                                <input type="radio"
                                       id="working-hours-radio-custom-<?php echo esc_attr( $days_eng[$day_count] ); ?>"
                                       class="custom-control-input working-hours-radio <?php echo esc_attr( 'radio-' . $days_eng[$day_count] ); ?>"
                                       name="<?php echo esc_attr( "_listing_{$days_eng[$day_count]}_hours_radio" ); ?>"
                                       data-working-radio="<?php echo esc_attr( $days_eng[$day_count] ); ?>"
                                       value="custom" <?php echo $working_radio_value == 'custom' ? esc_attr( 'checked' ) : ( ! in_array( $working_radio_value,
									array(
										'open',
										'closed',
										'appointment'
									) ) ? esc_attr( 'checked' ) : '' ); ?> />
                                <label for="working-hours-radio-custom-<?php echo esc_attr( $days_eng[$day_count] ); ?>"
                                       class="custom-control-label"><?php esc_html_e( 'Enter Times',
										'lisner-core' ); ?></label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input type="radio"
                                       id="working-hours-radio-open-<?php echo esc_attr( $days_eng[$day_count] ); ?>"
                                       data-working-radio="<?php echo esc_attr( $days_eng[$day_count] ); ?>"
                                       class="custom-control-input working-hours-radio <?php echo esc_attr( 'radio-' . $days_eng[$day_count] ); ?>"
                                       name="<?php echo esc_attr( "_listing_{$days_eng[$day_count]}_hours_radio" ); ?>"
                                       value="open" <?php echo $working_radio_value == 'open' ? esc_attr( 'checked' ) : ''; ?> />
                                <label for="working-hours-radio-open-<?php echo esc_attr( $days_eng[$day_count] ); ?>"
                                       class="custom-control-label"><?php esc_html_e( 'Open All Day',
										'lisner-core' ); ?></label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input type="radio"
                                       id="working-hours-radio-closed-<?php echo esc_attr( $days_eng[$day_count] ); ?>"
                                       data-working-radio="<?php echo esc_attr( $days_eng[$day_count] ); ?>"
                                       class="custom-control-input working-hours-radio <?php echo esc_attr( 'radio-' . $days_eng[$day_count] ); ?>"
                                       name="<?php echo esc_attr( "_listing_{$days_eng[$day_count]}_hours_radio" ); ?>"
                                       value="closed" <?php echo $working_radio_value == 'closed' ? esc_attr( 'checked' ) : ''; ?> />
                                <label for="working-hours-radio-closed-<?php echo esc_attr( $days_eng[$day_count] ); ?>"
                                       class="custom-control-label"><?php esc_html_e( 'Closed All Day',
										'lisner-core' ); ?></label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input type="radio"
                                       id="working-hours-radio-appointment-<?php echo esc_attr( $days_eng[$day_count] ); ?>"
                                       data-working-radio="<?php echo esc_attr( $days_eng[$day_count] ); ?>"
                                       class="custom-control-input working-hours-radio <?php echo esc_attr( 'radio-' . $days_eng[$day_count] ); ?>"
                                       name="<?php echo esc_attr( "_listing_{$days_eng[$day_count]}_hours_radio" ); ?>"
                                       value="appointment" <?php echo $working_radio_value == 'appointment' ? esc_attr( 'checked' ) : ''; ?> />
                                <label for="working-hours-radio-appointment-<?php echo esc_attr( $days_eng[$day_count] ); ?>"
                                       class="custom-control-label"><?php esc_html_e( 'By Appointment Only',
										'lisner-core' ); ?></label>
                            </div>
                        </div>

                        <div class="working-hours-day-time working-hours-day-time-<?php echo esc_attr( $days_eng[$day_count] ); ?>">
                            <div class="working-hours-labels">
								<span class="working-hours-label"><?php esc_html_e( 'Time From',
										'lisner-core' ); ?></span>
                                <span class="working-hours-label"><?php esc_html_e( 'Time To',
										'lisner-core' ); ?></span>
                            </div>
							<?php if ( empty( $working_hours ) ) : ?>
                                <!-- Working hours template / Open -->
                                <div class="working-hours-template working-hours-template-<?php echo esc_attr( $days_eng[$day_count] ); ?> working-hours-template-active">
                                    <div class="working-hours-fields">
                                <span class="working-hours-input working-hours-input-open input-group">
                                    <input type="text"
                                           class="timepicker form-control"
                                           autocomplete="off"
                                           readonly
                                           placeholder="<?php echo esc_attr( '00:00' ); ?>"
                                           name="<?php echo esc_attr( "_listing_{$days_eng[$day_count]}_hours_open[0]" ); ?>">
                                </span>
                                        <span class="working-hours-input working-hours-input-close input-group">
                                    <input type="text"
                                           class="timepicker form-control"
                                           autocomplete="off"
                                           readonly
                                           placeholder="<?php echo esc_attr( '00:00' ); ?>"
                                           name="<?php echo esc_attr( "_listing_{$days_eng[$day_count]}_hours_close[0]" ); ?>">
                                </span>

                                        <!-- Add working hour -->
                                        <a href="javascript:" data-hours-add="<?php echo esc_attr( $days_eng[$day_count] ); ?>"
                                           class="working-hours-add"><i
                                                    class="material-icons"><?php echo esc_attr( 'add_circle_outline' ); ?></i><?php esc_html_e( 'Add Hours',
												'lisner-core' ); ?>
                                        </a>
                                    </div>
                                </div>
                                <!-- !Working hours template / Open -->
							<?php endif; ?>

                            <!-- Working hours template / Hidden -->
                            <div class="working-hours-template working-hours-template-<?php echo esc_attr( $days_eng[$day_count] ); ?> hidden">
                                <div class="working-hours-fields">
                                <span class="working-hours-input working-hours-input-open input-group">
                                    <input type="text"
                                           class="timepicker form-control"
                                           autocomplete="off"
                                           readonly
                                           placeholder="<?php echo esc_attr( '00:00' ); ?>"
                                           name="<?php echo esc_attr( "_listing_{$days_eng[$day_count]}_hours_open[{#}]" ); ?>" disabled>
                                </span>
                                    <span class="working-hours-input working-hours-input-close input-group">
                                    <input type="text"
                                           class="timepicker form-control"
                                           autocomplete="off"
                                           readonly
                                           placeholder="<?php echo esc_attr( '00:00' ); ?>"
                                           name="<?php echo esc_attr( "_listing_{$days_eng[$day_count]}_hours_close[{#}]" ); ?>"
                                           disabled>
                                </span>

                                    <!-- Remove Working Hour-->
                                    <a href="javascript:" class="working-hours-remove"><i
                                                class="material-icons"><?php echo esc_html( 'delete' ); ?></i></a>
                                </div>
                            </div>
                            <!-- !Working hours template -->

							<?php $count_count ++; ?>

							<?php $count = 0; ?>
							<?php if ( ! empty( $working_hours ) ) : ?>
								<?php foreach ( $working_hours as $open => $close ): ?>
									<?php if ( ! empty( $open ) && ! empty( $close ) ) : ?>
                                        <!-- Working hours default -->
                                        <div class="working-hours-template working-hours-template-<?php echo esc_attr( $days_eng[$count] ); ?>">
                                            <div class="working-hours-fields">
                                            <span class="working-hours-input working-hours-input-open input-group">
                                                <label for="working-hours-time-<?php echo esc_attr( $days_eng[$count] . $count ); ?>"></label>
                                                <input type="text"
                                                       class="timepicker form-control"
                                                       name="<?php echo esc_attr( "_listing_{$days_eng[$count]}_hours_open[{$count}]" ); ?>"
                                                       autocomplete="off"
                                                       readonly
                                                       value="<?php echo ! empty( $open ) ? esc_attr( $open ) : ''; ?>"/>
                                            </span>
                                                <span class="working-hours-input working-hours-input-close input-group">
                                                <label for="working-hours-time-<?php echo esc_attr( $days_eng[$count] . $count ); ?>"></label>
                                                <input type="text"
                                                       class="timepicker form-control"
                                                       name="<?php echo esc_attr( "_listing_{$days_eng[$count]}_hours_close[{$count}]" ); ?>"
                                                       autocomplete="off"
                                                       readonly
                                                       value="<?php echo ! empty( $close ) ? esc_attr( $close ) : ''; ?>"/>
                                            </span>

												<?php if ( ! empty( $working_hours ) && 0 == $count ) : ?>
                                                    <!-- Add working hour -->
                                                    <a href="javascript:"
                                                       data-hours-add="<?php echo esc_attr( $days_eng[$count] ); ?>"
                                                       class="working-hours-add"><i
                                                                class="material-icons"><?php echo esc_attr( 'add_circle_outline' ); ?></i><?php esc_html_e( 'Add Hours',
															'lisner-core' ); ?>
                                                    </a>
												<?php else: ?>
                                                    <a href="javascript:" class="working-hours-remove"><i
                                                                class="material-icons"><?php echo esc_html( 'delete' ); ?></i></a>
												<?php endif; ?>
                                            </div>
                                        </div>
										<?php $count ++; ?>
									<?php endif; ?>
								<?php endforeach; ?>
							<?php endif; ?>
                            <!-- !Working hours default -->
                        </div>
                    </div>
                <?php $day_count ++; ?>
				<?php endforeach; ?>
            </div>
		<?php endif; ?>
    </div>
</div>