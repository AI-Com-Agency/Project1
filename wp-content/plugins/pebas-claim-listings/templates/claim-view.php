<?php
/**
 * Claim / claim detailed view
 *
 * @author pebas
 * @package templates/claims
 * @version 1.0.0
 *
 * @var $claim_id
 * @var $claim_obj
 * @var $claim_status
 * @var $claim_data
 * @var $claim_id
 * @var $claimer
 */
?>
<div class="alert alert-info">
	<?php printf( __( 'Listing has already been claimed. Status is %s.', 'pebas-claim-listings' ), '<strong>' . $claim_status . '</strong>' ); ?>
</div>

<?php do_action( 'pebas_claim_listings_submit_claim_form_claim_detail_view_before', $claim_id ); ?>

<div class="listing-claim-wrapper">
    <div class="listing-claim-header"><h5><?php esc_html_e( 'Claim Information', 'pebas-claim-listings' ); ?></h5></div>
    <div class="listing-claim-item">
        <div class="listing-claim-label"><?php _e( 'Listing to claim', 'pebas-claim-listings' ); ?></div>
        <div class="listing-claim-content">
            <a href="<?php echo esc_url( get_permalink( pebas_claim_submit_form()->listing_id ) ); ?>"><?php echo get_the_title( pebas_claim_submit_form()->listing_id ); ?></a>
            <input type="hidden" value="<?php echo intval( pebas_claim_submit_form()->listing_id ); ?>"
                   name="listing_id">
        </div>
    </div>

	<?php do_action( 'pebas_claim_listings_submit_claim_form_claim_detail_view_open', $claim_id ); ?>

    <div class="listing-claim-item">
        <div class="listing-claim-label"><?php _e( 'Claimed by', 'pebas-claim-listings' ); ?></div>
        <div class="listing-claim-content">
			<?php echo $claimer->data->display_name; ?>
        </div>
    </div>

    <div class="listing-claim-item">
        <div class="listing-claim-label"><?php _e( 'Submitted on', 'pebas-claim-listings' ); ?></div>
        <div class="listing-claim-content">
			<?php echo get_the_date( get_option( 'date_format' ), $claim_id ); ?>
        </div>
    </div>

	<?php if ( $claim_data ) { ?>
        <div class="listing-claim-item">
            <div class="listing-claim-label"><?php _e( 'Claim data', 'pebas-claim-listings' ); ?></div>
            <div class="listing-claim-content">
				<?php echo wp_kses_post( $claim_data ); ?>
            </div>
        </div>
	<?php } ?>

	<?php do_action( 'pebas_claim_listings_submit_claim_form_claim_detail_view_close', $claim_id ); ?>

</div>
<?php do_action( 'pebas_claim_listings_submit_claim_form_claim_detail_view_after', $claim_id ); ?>

