<?php
/**
 * Template: Listing author statistics
 *
 * @author pebas
 * @version 1.0.0
 * @since 1.2.3
 */

$option = get_option( 'pbs_option' );
?>

<div class="profile-statistics">

	<?php $focus_enabled = isset( $option['listing-statistics-focus-enable'] ) && $option['listing-statistics-focus-enable'] ? true : false; ?>
	<?php if ( $focus_enabled ) : ?>
	<!-- Profile Statistic / Focus -->
	<a href="javascript:" class="profile-statistic focus" data-stat="focus">
		<span class="profile-statistic--icon material-icons mf"><?php echo esc_html( 'personal_video' ); ?></span>
		<div class="profile-statistic--title">
			<h6><?php esc_html_e( 'Listing Focus', 'lisner-core' ); ?></h6>
			<p><?php esc_html_e( 'Calculates how many times all listings were in browser focus.', 'lisner-core' ); ?></p>
		</div>
		<div class="profile-statistic--count">
			<span><?php echo lisner_statistics::get_author_stat( 'focus' ); ?></span>
		</div>
	</a>
    <?php endif; ?>

	<?php $ctr_enabled = isset( $option['listing-statistics-ctr-enable'] ) && $option['listing-statistics-ctr-enable'] ? true : false; ?>
	<?php if ( $ctr_enabled ) : ?>
	<!-- Profile Statistic / CTR -->
	<a href="javascript:" class="profile-statistic ctr" data-stat="ctr">
		<span class="profile-statistic--icon material-icons mf"><?php echo esc_html( 'trending_up' ); ?></span>
		<div class="profile-statistic--title">
			<h6><?php esc_html_e( 'Listing CTR', 'lisner-core' ); ?></h6>
			<p><?php esc_html_e( 'Calculates click through rate for all listings combined.', 'lisner-core' ); ?></p>
		</div>
		<div class="profile-statistic--count">
			<span><?php echo esc_html( lisner_statistics::calculate_author_ctr() ); ?></span>
		</div>
	</a>
    <?php endif; ?>

	<!-- Profile Statistic / View -->
	<a href="javascript:" class="profile-statistic view" data-stat="view">
		<span class="profile-statistic--icon material-icons mf"><?php echo esc_html( 'visibility' ); ?></span>
		<div class="profile-statistic--title">
			<h6><?php esc_html_e( 'Listing Views', 'lisner-core' ); ?></h6>
			<p><?php esc_html_e( 'Calculates your all time visits of listings single pages.', 'lisner-core' ); ?></p>
		</div>
		<div class="profile-statistic--count">
			<span><?php echo lisner_statistics::get_author_stat( 'view' ); ?></span>
		</div>
	</a>

	<!-- Profile Statistic / Lead -->
	<a href="javascript:" class="profile-statistic lead" data-stat="lead">
		<span class="profile-statistic--icon material-icons mf"><?php echo esc_html( 'show_chart' ); ?></span>
		<div class="profile-statistic--title">
			<h6><?php esc_html_e( 'Listing Leads', 'lisner-core' ); ?></h6>
			<p><?php esc_html_e( 'Calculates your all time listing leads generation.', 'lisner-core' ); ?></p>
		</div>
		<div class="profile-statistic--count">
			<span><?php echo lisner_statistics::get_author_stat( 'lead' ); ?></span>
		</div>
	</a>

	<!-- Profile Statistic / Review -->
	<a href="javascript:" class="profile-statistic review" data-stat="review">
		<span class="profile-statistic--icon material-icons mf"><?php echo esc_html( 'star' ); ?></span>
		<div class="profile-statistic--title">
			<h6><?php esc_html_e( 'Listing Reviews', 'lisner-core' ); ?></h6>
			<p><?php esc_html_e( 'Calculates all reviews for every listing you own.', 'lisner-core' ); ?></p>
		</div>
		<div class="profile-statistic--count">
			<span><?php echo lisner_statistics::get_author_stat( 'review' ); ?></span>
		</div>
	</a>
</div>

<!-- Profile Charts -->
<div class="profile-charts-wrapper">
	<div class="profile-charts-heading">
		<h5 class="stat-title"></h5>
		<div class="stat-switch">
			<a href="javascript:" class="stat-switch-call active"
			   data-stat="weekly"><?php esc_html_e( 'Weekly', 'lisner-core' ); ?></a>
			<a href="javascript:" class="stat-switch-call"
			   data-stat="monthly"><?php esc_html_e( 'Monthly', 'lisner-core' ); ?></a>
			<a href="javascript:" class="stat-switch-call"
			   data-stat="yearly"><?php esc_html_e( 'Yearly', 'lisner-core' ); ?></a>
		</div>
	</div>
	<div id="user-chart" class="profile-charts"></div>
</div>
