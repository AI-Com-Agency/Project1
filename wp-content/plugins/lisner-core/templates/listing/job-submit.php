<?php
/**
 * Content for job submission (`[submit_job_form]`) shortcode.
 *
 * This template can be overridden by copying it to yourtheme/job_manager/job-submit.php.
 *
 * @see         https://wpjobmanager.com/document/template-overrides/
 * @author      Automattic
 * @package     WP Job Manager
 * @category    Template
 * @version     1.32.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( is_admin() ) {
	return;
}

//todo there's an issue with wp job manager causing this template to be called twice.
//todo $pbs_edit_job_count is used to prevent that.
global $job_manager, $pbs_edit_job_count;
$pbs_edit_job_count ++;
if ( 1 < $pbs_edit_job_count ) {
	return;
}
$option       = get_option( 'pbs_option' );
$is_dashboard = get_the_ID() == lisner_dashboard()->get_dashboard_page_template() ? true : false;
if ( ! $is_dashboard ) {
	include lisner_helper::get_template_part( 'header-media', 'pages/header' );
}
?>
<main class="main <?php echo ! $is_dashboard ? esc_attr( 'page-default' ) : esc_attr( 'page-dashboard' ) ?> main-submit-listing">
    <div class="container <?php echo 'edit-job' != $form ? esc_attr( 'container-wrapped' ) : ''; ?>">
        <div class="row row-wrapper">
            <div class="col-sm-12">
                <!-- Page / Main Content -->
                <section class="main-content">
                    <div class="row">
                        <form action="<?php echo esc_url( $action ); ?>" method="post" id="submit-job-form"
                              class="job-manager-form"
                              enctype="multipart/form-data">

							<?php if ( ! $is_dashboard ) : ?>
								<?php
								if ( isset( $resume_edit ) && $resume_edit ) {
									printf( '<div class="alert alert-info">' . __( "You are editing an existing listing. %s",
											'lisner-core' ) . '</div>',
										'<a class="link-auth" href="?new=1&key=' . $resume_edit . '"><strong>' . __( 'Create A New Listing',
											'listing-core' ) . '</strong></a>' );
								}
								?>


								<?php if ( apply_filters( 'submit_job_form_show_signin', true ) ) : ?>

									<?php get_job_manager_template( 'account-signin.php' ); ?>

								<?php endif; ?>

							<?php endif; ?>
							<?php do_action( 'submit_job_form_start' ); ?>

							<?php if ( job_manager_user_can_post_job() || job_manager_user_can_edit_job( $job_id ) ) : ?>

                                <!-- Job Information Fields -->
                                <div class="listing-submit-group">
                                    <h4 class="listing-submit-title"><?php _e( 'Listing Details',
											'lisner-core' ); ?></h4>
									<?php do_action( 'submit_job_form_job_fields_start' ); ?>

									<?php foreach ( $job_fields as $key => $field ) : ?>
										<?php $desc = lisner_get_var( $field['tooltip'], null ); ?>
										<?php $desc = $desc ? '<i class="material-icons mf" data-toggle="tooltip" data-placement="top" title="' . esc_attr( $desc ) . '">' . esc_html( 'help_outline' ) . '</i>' : ''; ?>
                                        <fieldset class="fieldset-<?php echo esc_attr( $key ); ?>">
                                            <div class="field <?php echo $field['required'] ? 'required-field' : ''; ?>">
                                                <div class="field-information">
                                                    <label for="<?php echo esc_attr( $key ); ?>"><?php echo $field['label'] . apply_filters( 'submit_job_form_required_label',
																$field['required'] ? '' : '', $field ); ?></label>
													<?php echo wp_kses( $desc, array(
														'i' => array(
															'class'          => array(),
															'data-toggle'    => array(),
															'data-placement' => array(),
															'title'          => array(),
															'data-title'     => array()
														)
													) ); ?>
                                                </div>
												<?php get_job_manager_template( 'form-fields/' . $field['type'] . '-field.php',
													array(
														'key'   => $key,
														'field' => $field
													) ); ?>
                                            </div>
                                        </fieldset>
									<?php endforeach; ?>

									<?php do_action( 'submit_job_form_job_fields_end' ); ?>

                                    <!-- Listing Information Fields -->
									<?php if ( isset( $_REQUEST['action'] ) && ! empty( $_REQUEST['action'] ) && 'edit' == $_REQUEST['action'] ): ?>
										<?php $listing_form = WP_Job_Manager_Form_Edit_Job::instance(); ?>
									<?php else: ?>
										<?php $listing_form = WP_Job_Manager_Form_Submit_Job::instance(); ?>
									<?php endif; ?>
									<?php foreach ( $listing_form->get_fields( 'listing' ) as $key => $field ) : ?>
										<?php $desc = lisner_get_var( $field['tooltip'], null ); ?>
										<?php $desc = $desc ? '<i class="material-icons mf" data-toggle="tooltip" data-placement="top" title="' . esc_attr( $desc ) . '">' . esc_html( 'help_outline' ) . '</i>' : ''; ?>
                                        <fieldset class="fieldset-<?php echo esc_attr( $key ); ?>">
                                            <div class="field <?php echo $field['required'] ? 'required-field' : ''; ?>">
                                                <div class="field-information">
                                                    <label for="<?php echo esc_attr( $key ); ?>"><?php echo $field['label'] . apply_filters( 'submit_job_form_required_label',
																$field['required'] ? '' : '', $field ); ?></label>
													<?php echo wp_kses( $desc, array(
														'i' => array(
															'class'          => array(),
															'data-toggle'    => array(),
															'data-placement' => array(),
															'title'          => array(),
															'data-title'     => array()
														)
													) ); ?>
                                                </div>
												<?php get_job_manager_template( 'form-fields/' . $field['type'] . '-field.php',
													array(
														'key'   => $key,
														'field' => $field
													) ); ?>
                                            </div>
                                        </fieldset>
									<?php endforeach; ?>
                                </div>

								<?php if ( ( isset( $option['listing-fields-amenities'] ) && $option['listing-fields-amenities'] ) || ( isset( $option['listing-fields-tags'] ) && $option['listing-fields-tags'] ) || ( isset( $option['listing-fields-pricing'] ) && $option['listing-fields-pricing'] ) || ( isset( $option['listing-fields-working-hours'] ) && $option['listing-fields-working-hours'] ) ) : ?>
                                    <!-- Listing Specific Fields -->
                                    <div class="listing-submit-group">
                                        <h4 class="listing-submit-title"><?php _e( 'Listing Specific',
												'lisner-core' ); ?></h4>
										<?php foreach ( $listing_form->get_fields( 'listing_specific' ) as $key => $field ) : ?>
											<?php $desc = lisner_get_var( $field['tooltip'], null ); ?>
											<?php $desc = $desc ? '<i class="material-icons mf" data-toggle="tooltip" data-placement="top" title="' . esc_attr( $desc ) . '">' . esc_html( 'help_outline' ) . '</i>' : ''; ?>
											<?php echo isset( $field['before'] ) ? wp_kses_post( $field['before'] ) : ''; ?>
                                            <fieldset class="fieldset-<?php echo esc_attr( $key ); ?>">
                                                <div class="field <?php echo $field['required'] ? 'required-field' : ''; ?>">
                                                    <div class="field-information">
                                                        <label for="<?php echo esc_attr( $key ); ?>"><?php echo $field['label'] . apply_filters( 'submit_job_form_required_label',
																	$field['required'] ? '' : '', $field ); ?></label>
														<?php echo wp_kses( $desc, array(
															'i' => array(
																'class'          => array(),
																'data-toggle'    => array(),
																'data-placement' => array(),
																'title'          => array(),
																'data-title'     => array()
															)
														) ); ?>
                                                    </div>
													<?php get_job_manager_template( 'form-fields/' . $field['type'] . '-field.php',
														array(
															'key'   => $key,
															'field' => $field
														) ); ?>
                                                </div>
                                            </fieldset>
											<?php echo isset( $field['after'] ) ? wp_kses_post( $field['after'] ) : ''; ?>
										<?php endforeach; ?>
                                    </div>
								<?php endif; ?>

								<?php if ( ( isset( $option['listing-fields-logo'] ) && $option['listing-fields-logo'] ) || ( isset( $option['listing-fields-cover'] ) && $option['listing-fields-cover'] ) || ( isset( $option['listing-fields-gallery'] ) && $option['listing-fields-gallery'] ) || ( isset( $option['listing-fields-video'] ) && $option['listing-fields-video'] ) || ( isset( $option['listing-fields-files'] ) && $option['listing-fields-files'] ) ) : ?>
                                    <!-- Listing Media Fields -->
                                    <div class="listing-submit-group">
                                        <h4 class="listing-submit-title"><?php _e( 'Listing Media',
												'lisner-core' ); ?></h4>
										<?php foreach ( $listing_form->get_fields( 'listing_media' ) as $key => $field ) : ?>
											<?php $desc = lisner_get_var( $field['tooltip'], null ); ?>
											<?php $desc = $desc ? '<i class="material-icons mf" data-toggle="tooltip" data-placement="top" title="' . esc_attr( $desc ) . '">' . esc_html( 'help_outline' ) . '</i>' : ''; ?>
                                            <fieldset class="fieldset-<?php echo esc_attr( $key ); ?>">
                                                <div class="field <?php echo $field['required'] ? 'required-field' : ''; ?>">
                                                    <div class="field-information">
                                                        <label for="<?php echo esc_attr( $key ); ?>"><?php echo $field['label'] . apply_filters( 'submit_job_form_required_label',
																	$field['required'] ? '' : '', $field ); ?></label>
														<?php echo wp_kses( $desc, array(
															'i' => array(
																'class'          => array(),
																'data-toggle'    => array(),
																'data-placement' => array(),
																'title'          => array(),
																'data-title'     => array()
															)
														) ); ?>
                                                    </div>
													<?php get_job_manager_template( 'form-fields/' . $field['type'] . '-field.php',
														array(
															'key'   => $key,
															'field' => $field
														) ); ?>
                                                </div>
                                            </fieldset>
										<?php endforeach; ?>
                                    </div>
								<?php endif; ?>

								<?php if ( isset( $option['listing-fields-social'] ) && $option['listing-fields-social'] ) : ?>
                                    <!-- Listing Social Fields -->
                                    <div class="listing-submit-group">
                                        <h4 class="listing-submit-title"><?php _e( 'Listing Social',
												'lisner-core' ); ?></h4>
                                        <div class="field-label">
											<?php esc_html_e( 'Listing Social Networks', 'lisner-core' ) ?>
                                            <i class="material-icons mf" data-toggle="tooltip" data-placement="top"
                                               title="<?php esc_html_e( 'Please enter link to your social profile pages if you have any.',
												   'lisner-core' ); ?>"><?php echo esc_html( 'help_outline' ); ?></i>
                                        </div>
										<?php foreach ( $listing_form->get_fields( 'listing_social' ) as $key => $field ) : ?>
											<?php $desc = lisner_get_var( $field['tooltip'], null ); ?>
											<?php $desc = $desc ? '<i class="material-icons mf hidden" data-toggle="tooltip" data-placement="top" title="' . esc_attr( $desc ) . '">' . esc_html( 'help_outline' ) . '</i>' : ''; ?>
                                            <fieldset class="fieldset-<?php echo esc_attr( $key ); ?>">
                                                <div class="field <?php echo $field['required'] ? 'required-field' : ''; ?>">
                                                    <div class="field-information">
                                                        <label class="hidden"
                                                               for="<?php echo esc_attr( $key ); ?>"><?php echo $field['label'] . apply_filters( 'submit_job_form_required_label',
																	$field['required'] ? '' : '', $field ); ?></label>
														<?php echo wp_kses( $desc, array(
															'i' => array(
																'class'          => array(),
																'data-toggle'    => array(),
																'data-placement' => array(),
																'title'          => array(),
																'data-title'     => array()
															)
														) ); ?>
                                                    </div>
													<?php get_job_manager_template( 'form-fields/' . $field['type'] . '-field.php',
														array(
															'key'   => $key,
															'field' => $field
														) ); ?>
                                                </div>
                                            </fieldset>
										<?php endforeach; ?>
                                    </div>
								<?php endif; ?>

                                <!-- Company Information Fields -->
                                <div class="listing-submit-group">
									<?php if ( $company_fields ) : ?>
                                        <h4 class="listing-submit-title"><?php _e( 'Company Details',
												'lisner-core' ); ?></h4>

										<?php do_action( 'submit_job_form_company_fields_start' ); ?>

										<?php foreach ( $company_fields as $key => $field ) : ?>
                                            <fieldset class="fieldset-<?php echo esc_attr( $key ); ?>">
                                                <label for="<?php echo esc_attr( $key ); ?>"><?php echo $field['label'] . apply_filters( 'submit_job_form_required_label',
															$field['required'] ? '' : '', $field ); ?></label>
                                                <div class="field <?php echo $field['required'] ? 'required-field' : ''; ?>">
													<?php get_job_manager_template( 'form-fields/' . $field['type'] . '-field.php',
														array(
															'key'   => $key,
															'field' => $field
														) ); ?>
                                                </div>
                                            </fieldset>
										<?php endforeach; ?>

										<?php do_action( 'submit_job_form_company_fields_end' ); ?>
									<?php endif; ?>

									<?php do_action( 'submit_job_form_end' ); ?>
                                </div>

                                <div class="submit-listing">
                                    <input type="hidden" name="job_manager_form" value="<?php echo $form; ?>"/>
                                    <input type="hidden" name="job_id" value="<?php echo esc_attr( $job_id ); ?>"/>
                                    <input type="hidden" name="step" value="<?php echo esc_attr( $step ); ?>"/>
                                    <input type="hidden" name="step" value="<?php echo esc_attr( $step ); ?>"/>
									<?php if ( pbs_is_demo() && isset( $_REQUEST['action'] ) && 'edit' == $_REQUEST['action'] ) : ?>
                                        <button type="submit" name="" class="btn btn-primary"
                                                value="<?php echo esc_attr( $submit_button_text ); ?>"
                                                disabled="disabled"><?php echo esc_html( $submit_button_text ); ?></button>
                                        <div class="d-flex mt-2"><?php esc_html_e( 'Disabled for demo purposes',
												'lisner-core' ); ?></div>
									<?php else: ?>
                                        <input type="submit" name="submit_job" class="btn btn-primary"
                                               value="<?php echo esc_attr( $submit_button_text ); ?>"/>
									<?php endif; ?>
                                    <div class="loader ajax-loader">
                                        <svg class="circular">
                                            <circle class="path" cx="50" cy="50" r="11" fill="none" stroke-width="4"
                                                    stroke-miterlimit="10"/>
                                        </svg>
                                    </div>
                                </div>

							<?php else : ?>

								<?php do_action( 'submit_job_form_disabled' ); ?>

							<?php endif; ?>
                        </form>
                    </div>
                </section>
            </div>
        </div>
    </div>
</main>
