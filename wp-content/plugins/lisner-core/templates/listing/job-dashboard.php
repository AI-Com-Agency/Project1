<?php
/**
 * Job dashboard shortcode content.
 *
 * This template can be overridden by copying it to yourtheme/job_manager/job-dashboard.php.
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
global $paged;
?>

<?php if ( isset( $_REQUEST['action'] ) && 'statistic' == $_REQUEST['action'] ) : ?>
	<?php $listing_id = isset( $_REQUEST['job_id'] ) ? $_REQUEST['job_id'] : ''; ?>
	<?php $stat_data = array( 'listing_id' => $listing_id ); ?>
	<?php include lisner_helper::get_template_part( 'listing-statistics', 'dashboard', $stat_data ); ?>
<?php elseif ( class_exists( 'Pebas_Listing_Events' ) && isset( $_REQUEST['action'] ) && 'events' == $_REQUEST['action'] ) : ?>
	<?php $listing_id = isset( $_REQUEST['job_id'] ) ? $_REQUEST['job_id'] : ''; ?>
	<?php pebas_events()->listing_events( $listing_id ); ?>
<?php elseif ( class_exists( 'Pebas_Listing_Events' ) && isset( $_REQUEST['action'] ) && 'add_event' == $_REQUEST['action'] ) : ?>
	<?php $listing_id = isset( $_REQUEST['job_id'] ) ? $_REQUEST['job_id'] : ''; ?>
	<?php pebas_events()->event_form( $listing_id ); ?>
<?php elseif ( class_exists( 'Pebas_Listing_Coupons' ) && isset( $_REQUEST['action'] ) && 'coupons' == $_REQUEST['action'] ) : ?>
	<?php $listing_id = isset( $_REQUEST['job_id'] ) ? $_REQUEST['job_id'] : ''; ?>
	<?php pebas_coupons()->listing_coupons( $listing_id ); ?>
<?php elseif ( class_exists( 'Pebas_Listing_Coupons' ) && isset( $_REQUEST['action'] ) && 'add_coupon' == $_REQUEST['action'] ) : ?>
	<?php $listing_id = isset( $_REQUEST['job_id'] ) ? $_REQUEST['job_id'] : ''; ?>
	<?php pebas_coupons()->coupon_form( $listing_id ); ?>
<?php elseif ( class_exists( 'Pebas_Bookings' ) && isset( $_REQUEST['action'] ) && 'edit_booking_product' == $_REQUEST['action'] ) : ?>
	<?php $listing_id = isset( $_REQUEST['job_id'] ) ? $_REQUEST['job_id'] : ''; ?>
	<?php $listing_post = get_post( $listing_id ); ?>
	<?php if ( ! empty( $listing_post ) ) : ?>
		<?php pebas_booking()->booking_product( $listing_id ); ?>
	<?php else: ?>
		<?php esc_html_e( 'Not existing', 'lisner-core' ); // @todo change this to look better  ?>
	<?php endif; ?>
<?php else: ?>
	<div class="lisner-listing-table">
		<div class="lisner-listing-table-header">
			<p><?php esc_html_e( 'Your listings are shown in the table below.', 'lisner-core' ); ?></p>
			<?php $add_listing_link = get_permalink( get_option( 'job_manager_submit_job_form_page_id' ) ); ?>
			<a href="<?php echo esc_url( $add_listing_link ); ?>"><?php esc_html_e( 'Add Listing', 'lisner-core' ); ?><i
						class="material-icons mf"><?php echo esc_html( 'add_circle_outline' ); ?></i></a>
		</div>
		<table class="table table-borderless lisner-table table-responsive">
			<thead>
			<tr>
				<?php foreach ( $job_dashboard_columns as $key => $column ) : ?>
					<th class="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $column ); ?></th>
				<?php endforeach; ?>
				<th class="listing-actions"></th>
			</tr>
			</thead>

			<tbody>
			<?php if ( ! $jobs ) : ?>
				<tr>
					<td colspan="<?php echo intval( count( $job_dashboard_columns ) ); ?>"><?php esc_html_e( 'You do not have any active listings.', 'lisner-core' ); ?></td>
				</tr>
			<?php else : ?>
				<?php foreach ( $jobs as $job ) : ?>
					<tr class="<?php echo 'publish' == $job->post_status ? esc_attr( 'is-published' ) : ( 'expired' == $job->post_status ? esc_attr( 'is-expired' ) : esc_attr( 'is-pending' ) ); ?>">
						<?php foreach ( $job_dashboard_columns as $key => $column ) : ?>
							<td class="<?php echo esc_attr( $key ); ?>">
								<?php if ( 'thumbnail' === $key ) : ?>
									<?php $image_size = 'thumbnail'; ?>
									<?php $banner = rwmb_meta( '_listing_cover', array( 'size' => $image_size ), $job->ID ); ?>
									<?php $gallery = rwmb_meta( '_listing_gallery', array( 'size' => $image_size ), $job->ID ); ?>
									<?php $fallback_image = lisner_get_option( 'fallback-bg-listing', null ); ?>
									<?php $fallback_image = wp_get_attachment_image_src( $fallback_image, $image_size ); ?>
									<?php $image = isset( $banner ) && ! empty( $banner ) ? array_shift( $banner ) : ( isset( $gallery ) && ! empty( $gallery ) ? array_shift( $gallery ) : ( isset( $fallback_image ) && ! empty( $fallback_image ) ? array_shift( $fallback_image ) : '' ) ); ?>
									<figure class="lisner-listing-table-image"
									        data-figure="<?php echo esc_url( isset( $image['url'] ) ? $image['url'] : $image ); ?>">
										<a href="<?php echo esc_url( get_the_permalink( $job->ID ) ); ?>">
											<img src="<?php echo esc_url( isset( $image['url'] ) ? $image['url'] : $image ); ?>"
											     alt="listing-box-image">
										</a>
									</figure>
								<?php elseif ( 'listing_title' === $key ) : ?>
									<?php if ( $job->post_status == 'publish' ) : ?>
										<a href="<?php echo esc_url( get_permalink( $job->ID ) ); ?>"><?php wpjm_the_job_title( $job ); ?></a>
									<?php else : ?>
										<?php wpjm_the_job_title( $job ); ?>
										<small>(<?php the_job_status( $job ); ?>)</small>
									<?php endif; ?>
								<?php elseif ( 'date' === $key ) : ?>
									<?php echo esc_html( date_i18n( 'M j Y', strtotime( $job->post_date ) ) ); ?>
								<?php elseif ( 'expires' === $key ) : ?>
									<?php echo esc_html( $job->_job_expires ? date_i18n( 'M j Y', strtotime( $job->_job_expires ) ) : '&ndash;' ); ?>
								<?php else : ?>
									<?php do_action( 'job_manager_job_dashboard_column_' . $key, $job ); ?>
								<?php endif; ?>
							</td>
						<?php endforeach; ?>
						<td class="listing-actions-content">
							<ul class="job-dashboard-actions">
								<?php
								$booking_product = get_post_meta( $job->ID, '_listing_products', true );
								$actions         = array();
								// see events
								if ( class_exists( 'Pebas_Listing_Events' ) ) {
									$actions['events'] = array(
										'label' => __( 'Manage Events', 'lisner-core' ),
										'nonce' => true,
										'icon'  => esc_html( 'fas fa-calendar-check' )
									);
								}
								// see coupons
								if ( class_exists( 'Pebas_Listing_Coupons' ) ) {
									$actions['coupons'] = array(
										'label' => __( 'Manage Coupons', 'lisner-core' ),
										'nonce' => true,
										'icon'  => esc_html( 'fas fa-cut' )
									);
								}
								// add booking product
								if ( class_exists( 'Pebas_Bookings' ) ) {
									if ( isset( $booking_product ) && ! empty( $booking_product ) ) {
										$actions['edit_booking_product'] = array(
											'label' => __( 'Edit Booking', 'lisner-core' ),
											'nonce' => false,
											'icon'  => esc_html( 'date_range' ),
										);
									} else {
										$actions['create_booking_product'] = array(
											'label' => __( 'Create Booking', 'lisner-core' ),
											'nonce' => true,
											'icon'  => esc_html( 'calendar_today' ),
										);
									}
								}
								// see stats
								$actions['statistic'] = array(
									'label' => __( 'Listing Statistics', 'lisner-core' ),
									'nonce' => true,
									'icon'  => esc_html( 'insert_chart_outlined' )
								);
								$actions['view']      = array(
									'label' => __( 'View', 'lisner-core' ),
									'nonce' => false
								);

								switch ( $job->post_status ) {
									case 'publish' :
										if ( wpjm_user_can_edit_published_submissions() ) {
											$actions['edit'] = array(
												'label' => __( 'Edit', 'lisner-core' ),
												'nonce' => false,
												'icon'  => esc_html( 'create' )
											);
										}
										break;
									case 'expired' :
										if ( job_manager_get_permalink( 'submit_job_form' ) ) {
											$actions['relist'] = array(
												'label' => __( 'Relist', 'lisner-core' ),
												'nonce' => true,
												'icon'  => esc_html( 'redo' )
											);
										}
										break;
									case 'pending_payment' :
									case 'pending' :
										if ( job_manager_user_can_edit_pending_submissions() ) {
											$actions['edit'] = array(
												'label' => __( 'Edit', 'lisner-core' ),
												'nonce' => false,
												'icon'  => esc_html( 'create' )
											);
										} else {
											$actions['edit_disabled'] = array(
												'label' => __( 'Edit', 'lisner-core' ),
												'nonce' => false,
												'icon'  => esc_html( 'create' ),
											);
										}
										break;
								}
								if ( ! pbs_is_demo() ) {
									$actions['delete'] = array(
										'label' => __( 'Delete', 'lisner-core' ),
										'nonce' => true,
										'icon'  => esc_html( 'delete_outline' )
									);
								} else {
									$actions['example'] = array(
										'label' => __( 'example', 'lisner-core' ),
										'nonce' => false,
										'icon'  => esc_html( 'delete_outline' )
									);
								}
								$actions = apply_filters( 'job_manager_my_job_actions', $actions, $job );

								foreach ( $actions as $action => $value ) {
									if ( 'events' === $action ) {
										$action_url         = add_query_arg( array(
											'action' => $action,
											'job_id' => $job->ID
										) );
										$event_count        = pbs_count_events( $job->ID );
										$event_active_count = pbs_count_active_events( $job->ID ) ? count( pbs_count_active_events( $job->ID ) ) : 0;
										echo '<li><a href="' . esc_url( $action_url ) . '" class="job-dashboard-action-' . esc_attr( $action ) . '" title="' . esc_attr( $value['label'] ) . '"><span class="coupon-count-active">' . esc_html( $event_active_count ) . '</span><span class="coupon-count">' . esc_html( $event_count ) . '</span><i class="' . esc_html( $value['icon'] ) . '"></i></a></li>';

									} else if ( 'coupons' === $action ) {
										$action_url          = add_query_arg( array(
											'action' => $action,
											'job_id' => $job->ID
										) );
										$coupon_count        = pbs_count_coupons( $job->ID );
										$coupon_active_count = pbs_count_active_coupons( $job->ID ) ? count( pbs_count_active_coupons( $job->ID ) ) : 0;
										echo '<li><a href="' . esc_url( $action_url ) . '" class="job-dashboard-action-' . esc_attr( $action ) . '" title="' . esc_attr( $value['label'] ) . '"><span class="coupon-count-active">' . esc_html( $coupon_active_count ) . '</span><span class="coupon-count">' . esc_html( $coupon_count ) . '</span><i class="' . esc_html( $value['icon'] ) . '"></i></a></li>';
									} else if ( 'create_booking_product' == $action ) {
										$url = add_query_arg( array(
											'action' => 'edit_booking_product',
											'job_id' => $job->ID
										) );
										echo '<li><a href="javascript:" data-url="' . esc_url( $url ) . '" data-listing-id="' . esc_attr( $job->ID ) . '" data-action="create_booking_product" data-nonce="' . wp_create_nonce( 'create_booking_product' ) . '" class="job-dashboard-action-' . esc_attr( $action ) . '" title="' . esc_attr( $value['label'] ) . '"><i class="material-icons mf">' . esc_html( $value['icon'] ) . '</i></a></li>';
									} else {
										$action_url = add_query_arg( array(
											'action' => $action,
											'job_id' => $job->ID
										) );
										if ( $value['nonce'] ) {
											$action_url = wp_nonce_url( $action_url, 'job_manager_my_job_actions' );
										}
										if ( 'view' == $action ) {
											$action_url    = get_permalink( $job->ID );
											$value['icon'] = esc_html( 'remove_red_eye' );
										}
										if ( 'edit_disabled' == $action ) {
											$action_url = '';
										}
										echo '<li><a href="' . esc_url( $action_url ) . '" class="job-dashboard-action-' . esc_attr( $action ) . '" title="' . esc_attr( $value['label'] ) . '"><i class="material-icons mf">' . esc_html( $value['icon'] ) . '</i></a></li>';
									}
								}
								?>
							</ul>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
			</tbody>
		</table>
		<div class="lisner-listing-pagination">
			<?php get_job_manager_template( 'pagination.php', array( 'max_num_pages' => $max_num_pages ) ); ?>
		</div>
	</div>
<?php endif; ?>
