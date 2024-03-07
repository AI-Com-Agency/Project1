<?php

/**
 * Template Name: Contact Page Template
 *
 * @package Templates
 * @version 1.0.0
 */

if ( ! class_exists( 'pbs_page_template_contact' ) ) {
	class pbs_page_template_contact extends pbs_page_template {

		/**
		 * Additional classes for main wrapper
		 *
		 * @var array
		 */
		public $classes = array( 'main main-contact page-default' );

		/**
		 * Additional classes for main container
		 *
		 * @var array
		 */
		public $container_classes = array( 'container-wrapped' );

		public function header() {
			if ( class_exists( 'Lisner_Core' ) ) {
				include lisner_helper::get_template_part( 'header-media', 'pages/header' );
			}
		}

		/**
		 * Main Content rendering
		 */
		public function main_content() {
			the_post();
			?>
			<div class="col-sm-12">
				<!-- Page / Main Content -->
				<section class="main-content main-content-page-default">

					<div class="contact-heading d-flex justify-content-between align-items-center">
						<div class="contact-heading-title">
							<h1><?php echo esc_html( get_the_title() ); ?></h1>
						</div>
						<div class="contact-heading-action ml-auto">
							<div class="single-listing-main-meta-action">
								<a class="animate"
								   href="#send_comment"><?php esc_html_e( 'Send Message', 'lisner' ); ?><i
											class="material-icons rotate--90"><?php echo esc_attr( 'subdirectory_arrow_left' ); ?></i></a>
							</div>
						</div>
					</div>

					<?php $map = rwmb_get_value( 'contact_address_map' ); ?>
					<?php $zoom = rwmb_get_value( 'contact_zoom' ); ?>
					<?php $marker = rwmb_get_value( 'contact_marker' ); ?>
					<?php if ( isset( $map ) && ! empty( $map ) ) : ?>
						<div id="map-preview" class="map-preview" data-lat="<?php echo esc_attr( $map['latitude'] ); ?>"
						     data-long="<?php echo esc_attr( $map['longitude'] ); ?>"
						     data-zoom="<?php echo isset( $zoom ) && ! empty( $zoom ) ? $zoom : '';?>"
							 data-marker="<?php echo isset( $marker ) && !empty( $marker ) ? $marker['url'] : ''; ?>">
						</div>
					<?php endif; ?>

					<div class="contact-info row">
						<?php $form = get_post_meta( get_the_ID(), 'contact_form', true ); ?>
						<?php if ( isset( $form ) && ! empty( $form ) ): ?>
							<div class="col-sm-6">
								<h4><?php esc_html_e( 'Send Message', 'lisner' ); ?></h4>
								<div id="send_comment" class="contact-form">
									<?php echo do_shortcode( $form ); ?>
								</div>
							</div>
						<?php endif ?>
						<div class="col-sm-6">
							<div class="info">
								<h4><?php esc_html_e( 'Address & Info', 'lisner' ); ?></h4>
								<?php
								if ( get_the_content() ) :
									the_content();
								endif;
								?>
								<?php
								$facebook = get_post_meta( get_the_ID(), 'contact_social_facebook', true );
								if ( isset( $facebook ) && ! empty( $facebook ) ) {
									$social['facebook-f'] = $facebook;
								}
								$twitter = get_post_meta( get_the_ID(), 'contact_social_twitter', true );
								if ( isset( $twitter ) && ! empty( $twitter ) ) {
									$social['twitter'] = $twitter;
								}
								$google = get_post_meta( get_the_ID(), 'contact_social_google', true );
								if ( isset( $google ) && ! empty( $google ) ) {
									$social['google-plus-g'] = $google;
								}
								$linkedin = get_post_meta( get_the_ID(), 'contact_social_linkedin', true );
								if ( isset( $linkedin ) && ! empty( $linkedin ) ) {
									$social['linkedin'] = $linkedin;
								}
								?>
								<?php if ( isset( $social ) && ! empty( $social ) ) : ?>
									<div class="contact-info-social">
										<p><?php esc_html_e( 'Or follow us:', 'lisner' ) ?></p>
										<?php foreach ( $social as $name => $link ) : ?>
											<a class="<?php echo esc_attr( $name ); ?>"
											   href="<?php echo esc_url( $link ) ?>"
											   target="_blank"><i
														class="fab fa-<?php echo esc_attr( $name ); ?> fa-fw"></i></a>
										<?php endforeach; ?>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>

				</section>
			</div>

			<?php
		}

	}
}

new pbs_page_template_contact();
