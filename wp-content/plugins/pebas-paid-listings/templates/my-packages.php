<?php
/**
 * My Packages
 *
 * Shows packages on the account page
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="lisner-listing-table">
	<div class="lisner-listing-table-header">
		<p><?php esc_html_e( 'Your packages are shown in the table below.', 'pebas-paid-listings' ); ?></p>
	</div>
	<table class="table table-borderless lisner-table table-responsive-sm">
		<thead>
		<tr>
			<th scope="col"><?php _e( 'Package Name', 'pebas-paid-listings' ); ?></th>
			<th scope="col"><?php _e( 'Remaining', 'pebas-paid-listings' ); ?></th>
			<th scope="col"><?php _e( 'Listing Duration', 'pebas-paid-listings' ); ?></th>
			<th scope="col"><?php _e( 'Featured?', 'pebas-paid-listings' ); ?></th>
		</tr>
		</thead>
		<tbody>
		<?php if ( ! $packages ) : ?>
			<tr>
				<td colspan="4"><?php esc_html_e( 'You do not have any active listing packages.', 'pebas-paid-listings' ); ?></td>
			</tr>
		<?php else : ?>
			<?php foreach ( $packages as $package ) :
				$package = pebas_pl_get_package( $package );
				?>
				<tr>
					<td><?php echo $package->get_title(); ?></td>
					<td><?php echo $package->get_limit() ? absint( $package->get_limit() - $package->get_count() ) : __( 'Unlimited', 'pebas-paid-listings' ); ?></td>
					<?php if ( 'job_listing' === $type ) : ?>
						<td><?php echo $package->get_duration() ? sprintf( _n( '%d day', '%d days', $package->get_duration(), 'pebas-paid-listings' ), $package->get_duration() ) : '-'; ?></td>
					<?php endif; ?>
					<td><?php echo $package->is_featured() ? __( 'Yes', 'pebas-paid-listings' ) : __( 'No', 'pebas-paid-listings' ); ?></td>
				</tr>
			<?php endforeach; ?>
		<?php endif; ?>
		</tbody>
	</table>
</div>
