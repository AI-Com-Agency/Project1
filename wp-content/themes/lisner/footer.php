<?php $is_search_page = class_exists( 'Lisner_Core' ) && lisner_helper::is_search_page() ? true : false; ?>
<?php $is_profile_page = get_option( 'woocommerce_myaccount_page_id' ) == get_the_ID() ? true : false; ?>
<?php $has_sidebar = is_active_sidebar( 'sidebar-footer' ) || is_active_sidebar( 'sidebar-footer-2' ) || is_active_sidebar( 'sidebar-footer-3' ) || is_active_sidebar( 'sidebar-footer-4' ); ?>
<?php if ( ! $is_search_page && ! $is_profile_page ) : ?>
	<?php if ( $has_sidebar ) : ?>
		<footer class="footer">
			<div class="container">
				<div class="row">
					<div class="col-xl-3 col-md-6">
						<?php dynamic_sidebar( 'sidebar-footer' ); ?>
					</div>
					<div class="col-xl-3 col-md-6">
						<?php dynamic_sidebar( 'sidebar-footer-2' ); ?>
					</div>
					<div class="col-xl-3 col-md-6">
						<?php dynamic_sidebar( 'sidebar-footer-3' ); ?>
					</div>
					<div class="col-xl-3 col-md-6">
						<?php dynamic_sidebar( 'sidebar-footer-4' ); ?>
					</div>
				</div>
			</div>

		</footer>
	<?php endif; ?>
	<?php do_action( 'pbs_footer_after' ); ?>
<?php endif; ?>
<?php wp_footer(); ?>
</body>
</html>
