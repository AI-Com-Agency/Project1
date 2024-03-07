<?php

/**
 * Template Name: Coming Soon Template
 *
 * @package Templates
 * @version 1.0.0
 */

/**
 * Main Content rendering
 */
wp_head();
wp_enqueue_script( 'lisner-theme-countdown' );
$option  = get_option( 'pbs_option' );
$image   = lisner_get_var( $option['maintenance-bg'], null );
$image   = wp_get_attachment_image_src( $image, 'full' );
$overlay = isset( $option['maintenance-bg-overlay'] ) ? 'style=background-color:' . $option['maintenance-bg-overlay'] : '';
?>
<div class="maintenance"
     style="background-image: url(<?php echo esc_url( $image[0] ) ?>);">
	<?php if ( ! empty( $overlay ) ) : ?>
		<span class="maintenance-overlay" <?php echo esc_attr( $overlay ); ?>></span>
	<?php endif; ?>
	<div class="container container-wrapped">
		<div class="row justify-content-center">
			<div class="col-sm-12 text-center">

				<!-- Logo -->
				<div class="logo">
					<?php $logo = isset( $option['site-logo'] ) ? $option['site-logo'] : ''; ?>
					<?php if ( ! empty( $logo ) ): ?>
						<img src="<?php echo esc_url( wp_get_attachment_image_url( $logo[0], 'full' ) ); ?>"
						     alt="<?php echo esc_attr( 'Logo' ); ?>">
					<?php endif; ?>
				</div>

				<?php $title = lisner_get_var( $option['maintenance-title'], null ); ?>
				<?php $subtitle = lisner_get_var( $option['maintenance-subtitle'], null ); ?>
				<?php if ( $title || $subtitle ) : ?>
					<?php $title = str_replace( array( '[', ']' ), array(
						'<strong>',
						'</strong>'
					), $title ); ?>
					<!-- Title -->
					<div class="title">
						<h1><?php echo wp_kses_post( $title ); ?></h1>
						<h3><?php echo esc_html( $subtitle ); ?></h3>
					</div>
				<?php endif; ?>

				<?php $time = lisner_get_var( $option['maintenance-date'], null ); ?>
				<?php if ( $time ) : ?>
					<div id="clock"
					     data-date="<?php echo esc_attr( str_replace( '-', '/', $time ) ); ?>"></div>
				<?php endif; ?>

				<?php $chimp = lisner_get_var( $option['maintenance-mailchimp'], null ); ?>
				<?php $chimp_list_id = lisner_get_var( $option['maintenance-mailchimp-list-id'], null ); ?>
				<?php if ( isset( $chimp ) ) : ?>
					<div class="maintenance-chimp">
						<p class="mailchimp-notice"><?php esc_html_e( 'Would you like to stay involved?', 'lisner' ) ?></p>
						<form class="newsletter-form" method="post">
							<div class="input-group">
								<label for="mailchimp_email">
									<i class="material-icons mf"><?php echo esc_html( 'email' ); ?></i>
									<?php esc_html_e( 'Email:', 'lisner' ); ?>
								</label>
								<input id="mailchimp_email" type="email" name="email"
								       placeholder="<?php esc_attr_e( 'Your valid email address', 'lisner' ); ?>">
							</div>
							<button type="submit" class="btn btn-secondary newsletter-submit"><i
										class="material-icons mf"><?php echo esc_html( 'send' ); ?></i>
							</button>
							<input type="hidden" name="action" value="newsletter_ajax">
							<input type="hidden" name="mailchimp_api"
							       value="<?php echo esc_attr( $chimp ); ?>">
							<input type="hidden" name="mailchimp_list_id"
							       value="<?php echo esc_attr( $chimp_list_id ); ?>">
						</form>
					</div>
				<?php endif; ?>

				<?php
				$facebook = $option['maintenance-facebook'];
				if ( isset( $facebook ) && ! empty( $facebook ) ) {
					$social['facebook-f'] = $facebook;
				}
				$google = $option['maintenance-google'];
				if ( isset( $google ) && ! empty( $google ) ) {
					$social['google-plus-g'] = $google;
				}
				$twitter = $option['maintenance-twitter'];
				if ( isset( $twitter ) && ! empty( $twitter ) ) {
					$social['twitter'] = $twitter;
				}
				?>
				<?php if ( isset( $social ) && ! empty( $social ) ) : ?>
					<div class="maintenance-social">
						<?php foreach ( $social as $name => $link ) : ?>
							<a class="<?php echo esc_attr( $name ); ?>"
							   href="<?php echo esc_url( $link ) ?>"><i
										class="fab fa-<?php echo esc_attr( $name ); ?> fa-fw"></i></a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<?php $copyrights = lisner_get_var( $option['copyrights-text'] ); ?>
				<?php if ( $copyrights ) : ?>
					<div class="copyrights">
						<?php echo wp_kses_post( $copyrights ); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
<?php wp_footer(); ?>
