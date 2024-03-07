<?php
/**
 * Template Name: Listing Single Contact
 * Description: Partial content for single listing sidebar
 *
 * @author pebas
 * @version 1.0.0
 * @package listing/single/sidebar
 *
 * @var $con_args
 */
global $post;
?>
<?php if ( $con_args['has_title'] ) : ?>
	<?php $title = $con_args['title']; ?>
	<?php include lisner_helper::get_template_part( 'single-section-title', 'listing/single/content' ); ?>
<?php endif; ?>

<!-- Single Listing Widget / Contact Listing Form -->
<form class="form-contact-listing container" method="post">
    <!-- Form Field / Name -->
    <div class="form-group row" data-required="name">
        <label class="d-flex align-items-center col-sm-2 col-form-label" for="listing_name"><i
                    class="material-icons mf"><?php echo esc_attr( 'person_outline' ); ?></i><?php esc_html_e( 'Name', 'lisner-core' ); ?>
        </label>
        <div class="col-sm-10">
            <input id="listing_name" name="listing_name" type="text" class="form-control"
                   placeholder="<?php esc_attr_e( 'Your name here...', 'lisner-core' ); ?>" required>
        </div>
    </div>
    <!-- Form Field / Email -->
    <div class="form-group row" data-required="email">
        <label class="d-flex align-items-center col-sm-2 col-form-label" for="listing_email"><i
                    class="material-icons mf"><?php echo esc_attr( 'mail' ); ?></i><?php esc_html_e( 'Email', 'lisner-core' ); ?>
        </label>
        <div class="col-sm-10">
            <input id="listing_email" name="listing_email" type="text" class="form-control"
                   placeholder="<?php esc_attr_e( 'Your email address...', 'lisner-core' ); ?>" required>
        </div>
    </div>
    <!-- Form Field / Email -->
    <div class="form-group row align-items-start" data-required="message">
        <label class="d-flex align-items-center col-sm-2 col-form-label" for="listing_message"><i
                    class="material-icons mf"><?php echo esc_attr( 'message' ); ?></i><?php esc_html_e( 'Message', 'lisner-core' ); ?>
        </label>
        <div class="col-sm-10">
            <textarea name="listing_message" class="form-control" id="listing_message" cols="30" rows="5"
                      placeholder="<?php esc_attr_e( 'Your message...', 'lisner-core' ); ?>" required></textarea>
        </div>
    </div>
    <div class="row form-group form-group-submit">
		<?php if ( '2' == lisner_get_var( $args['page_template'], 1 ) ) : ?>
            <button class="btn btn-primary"><?php esc_html_e( 'Send Message', 'lisner-core' ); ?></button>
		<?php else: ?>
            <button class="btn btn-primary btn-grey"><?php esc_html_e( 'Send Message', 'lisner-core' ); ?></button>
		<?php endif; ?>
        <input type="hidden" name="action" value="<?php echo esc_attr( 'contact_listing' ); ?>">
	    <input type="hidden" name="author_id" value="<?php echo esc_attr( $author ); ?>">
        <input type="hidden" name="listing_id" value="<?php echo esc_attr( get_the_ID() ); ?>">
        <input type="hidden" name="_contact_listing"
               value="<?php echo esc_attr( wp_create_nonce( 'contact_listing' ) ); ?>">
    </div>
</form>
