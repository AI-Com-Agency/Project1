<?php
/**
 * Job listing preview when submitting job listings.
 *
 * This template can be overridden by copying it to yourtheme/job_manager/job-preview.php.
 *
 * @see         https://wpjobmanager.com/document/template-overrides/
 * @author      Automattic
 * @package     WP Job Manager
 * @category    Template
 * @version     1.31.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<?php include lisner_helper::get_template_part( 'single-header', 'listing/single' ); ?>
<main class="main main-single-listing">
    <div class="container">
        <div class="row row-wrapper">
			<?php get_job_manager_template_part( 'content-single', 'job_listing' ); ?>
            <form method="post" id="job_preview" action="<?php echo esc_url( $form->get_action() ); ?>">
                <?php do_action( 'preview_job_form_start' ); ?>
                <div class="listing-user-packages">
                    <div class="listing-user-packages-title">
                        <h6><?php esc_html_e( 'Choose Listing Action', 'lisner-core' ); ?></h6>
                        <i class="material-icons mf listing-user-packages-call"><?php echo esc_html( 'keyboard_arrow_down' ); ?></i>
                    </div>
                    <!-- Listing User Package / New Package -->
                    <div class="listing-user-package">
                        <a href="javascript:" class="submit-action-call">
                            <!-- Listing User Package / Title -->
                            <div class="listing-user-package-title">
                                <div class="listing-user-package-title-inner">
                                    <h6><?php esc_html_e( 'Submit Listing', 'lisner-core' ); ?></h6>
                                    <p class="listing-package-title-user"><?php esc_html_e( 'Proceed submitting listing', 'lisner-core' ) ?></p>
                                </div>
                                <div class="listing-user-package-title-icon">
                                    <i class="material-icons mf"><?php echo esc_html( 'keyboard_arrow_right' ); ?></i>
                                </div>
                            </div>
                        </a>
                        <input type="submit" name="continue" id="job_preview_submit_button"
                               class="button job-manager-button-submit-listing"
                               value="<?php echo esc_attr( apply_filters( 'submit_job_step_preview_submit_text', __( 'Submit Listing', 'lisner-core' ) ) ); ?>
                                    "/>
                    </div>
                    <!-- Listing User Package / New Package -->
                    <div class="listing-user-package">
                        <a href="javascript:" class="submit-action-call">
                            <!-- Listing User Package / Title -->
                            <div class="listing-user-package-title">
                                <div class="listing-user-package-title-inner">
                                    <h6><?php esc_html_e( 'Edit Listing', 'lisner-core' ); ?></h6>
                                    <p class="listing-package-title-user"><?php esc_html_e( 'Get back to edit listing', 'lisner-core' ) ?></p>
                                </div>
                                <div class="listing-user-package-title-icon">
                                    <i class="material-icons mf"><?php echo esc_html( 'keyboard_arrow_right' ); ?></i>
                                </div>
                            </div>
                        </a>
                        <input type="submit" name="edit_job" class="button job-manager-button-edit-listing"
                               value="<?php esc_attr_e( 'Edit listing', 'lisner-core' ); ?>"/>
                    </div>


                    <input type="hidden" name="job_id" value="<?php echo esc_attr( $form->get_job_id() ); ?>"/>
                    <input type="hidden" name="step" value="<?php echo esc_attr( $form->get_step() ); ?>"/>
                    <input type="hidden" name="job_manager_form"
                           value="<?php echo esc_attr( $form->get_form_name() ); ?>"/>
                    <?php do_action( 'preview_job_form_end' ); ?>
                </div>
            </form>
        </div>
</main>
