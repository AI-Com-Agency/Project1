<div class="lisner-listing-table">
	<div class="lisner-listing-table-header">
		<p><?php esc_html_e( 'Your bookmarked listings are shown in the table below.', 'pebas-bookmark-listings' ); ?></p>
	</div>
	<table class="table table-borderless lisner-table table-responsive-sm">
		<thead>
		<tr>
			<th scope="col"><?php _e( 'Listing Name', 'pebas-bookmark-listings' ); ?></th>
			<th></th>
		</tr>
		</thead>
		<tbody>
		<?php if ( 0 == count( $bookmark_posts ) ) : ?>
			<tr>
				<td colspan="4"><?php esc_html_e( 'You do not have any bookmarked listings', 'pebas-bookmark-listings' ); ?></td>
			</tr>
		<?php else : ?>
			<?php foreach ( $bookmark_posts as $bookmark_post ) : ?>
				<?php $post = get_post( $bookmark_post->_bookmark_listing ); ?>
				<?php if ( 'job_listing' == $post->post_type ) : ?>
					<tr>
						<td><?php echo esc_html( $post->post_title ); ?></td>
						<td class="listing-actions-content">
							<ul class="job-dashboard-actions d-flex justify-content-end">
								<li>
									<a class="job-dashboard-action-view"
									   href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>"><i
												class="material-icons mf"><?php echo esc_attr( 'remove_red_eye' ); ?></i>
									</a>
								</li>
								<li>
									<a class="bookmark-delete-call job-dashboard-action-view job-dashboard-action-delete"
									   href="javascript:"
									   data-confirm="<?php esc_attr_e( 'Are you sure you want to delete this?', 'pebas-bookmark-listings' ); ?>"
									   data-nonce="<?php echo wp_create_nonce( 'bookmark_delete' ) ?>"
									   data-listing-id="<?php echo esc_attr( $post->ID ); ?>"><i
												class="material-icons mf"><?php echo esc_attr( 'delete_outline' ); ?></i>
									</a>
								</li>
							</ul>
						</td>
					</tr>
				<?php endif; ?>
			<?php endforeach; ?>
		<?php wp_reset_postdata(); ?>
		<?php endif; ?>
		</tbody>
	</table>
</div>