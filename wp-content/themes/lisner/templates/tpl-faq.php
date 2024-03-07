<?php

/**
 * Template Name: FAQ Page Template
 *
 * @package Templates
 * @version 1.0.0
 */

if ( ! class_exists( 'pbs_page_template_faq' ) ) {
	class pbs_page_template_faq extends pbs_page_template {

		/**
		 * Additional classes for main wrapper
		 *
		 * @var array
		 */
		public $classes = array( 'page-default' );

		/**
		 * Additional classes for main container
		 *
		 * @var array
		 */
		public $container_classes = array( 'container-wrapped' );

		public function header() {
			if ( class_exists( 'Lisner_Core' ) ) {
				include lisner_helper::get_template_part( 'header-media', 'pages/header' );
			} else {
				$this->classes = array( 'page-default page-default-unit' );
			}
		}

		/**
		 * Main Content rendering
		 */
		public function main_content() {
			global $post;
			$has_sidebar = is_active_sidebar( 'sidebar-page' );
			the_post();
			?>
			<div class="col-sm-<?php echo esc_html( $has_sidebar ) && ! class_exists( 'Vc_Manager' ) ? esc_attr( '9' ) : esc_attr( '12' ); ?>">

				<!-- Page / Main Content -->
				<section class="main-content main-content-page-default">

					<?php if ( ! class_exists( 'Vc_Manager' ) ) : ?>
						<div class="row page-row">
							<div class="col-sm-12">
								<!-- Page / Title -->
								<h1 class="title-page"><?php the_title(); ?></h1>
							</div>
						</div>
					<?php endif; ?>

					<!-- Page / Content -->
					<?php the_content(); ?>

					<?php $faqs = rwmb_meta( 'faq_group' ); ?>
					<?php if ( isset( $faqs ) && ! empty ( $faqs ) ): ?>
						<!-- FAQ / Accordion -->
						<div class="faq-content">
							<div class="accordion" id="faqAccordion">

								<?php $faq_count = 0; ?>
								<?php foreach ( $faqs as $faq ) : ?>
									<!-- FAQ / Item -->
									<div class="card">
										<!-- FAQ / Header -->
										<div class="card-header" id="heading-<?php echo esc_attr( $faq_count ); ?>">
											<button class="btn btn-link" type="button" data-toggle="collapse"
											        data-target="#faq-<?php echo esc_attr( $faq_count ); ?>"
											        aria-expanded="true"
											        aria-controls="<?php echo esc_attr( $faq['faq_heading'] . '-' . $faq_count ); ?>">
												<?php echo esc_html( $faq['faq_heading'] ); ?>
											</button>
											<i class="material-icons mf"><?php echo 0 == $faq_count ? esc_attr( 'remove_circle_outline' ) : esc_attr( 'add_circle_outline' ); ?></i>
										</div>
										<!-- FAQ / Content -->
										<div id="faq-<?php echo esc_attr( $faq_count ) ?>"
										     class="collapse <?php echo 0 == $faq_count ? esc_attr( 'show' ) : ''; ?>"
										     aria-labelledby="heading-<?php echo esc_attr( $faq_count ); ?>"
										     data-parent="#faqAccordion">
											<div class="card-body">
												<?php echo esc_html( $faq['faq_content'] ); ?>
											</div>
										</div>
									</div>
									<?php $faq_count ++; ?>
								<?php endforeach; ?>

							</div>
						</div>
					<?php endif; ?>

					<?php $faq_button = get_post_meta( get_the_ID(), 'faq_contact', true ); ?>
					<?php if ( isset( $faq_button ) && 'yes' == $faq_button ) : ?>
						<?php $contact_link = pbs_helpers::get_permalink_by_tpl( 'templates/tpl-contact' ); ?>
						<div class="faq-button d-flex justify-content-center flex-column">
							<p class="faq-button-text"><?php esc_html_e( 'Still have a question?', 'lisner' ); ?></p>
							<a href="<?php echo esc_url( $contact_link ); ?>"
							   class="btn btn-primary"><?php esc_html_e( 'Contact Us', 'lisner' ); ?></a>
						</div>
					<?php endif ?>

				</section>

			</div>

			<?php if ( ( $has_sidebar ) && ! class_exists( 'Vc_Manager' ) ) : ?>
				<!-- Sidebar -->
				<div class="col-sm-3">
					<div class="sidebar">
						<?php get_sidebar(); ?>
					</div>
				</div>
			<?php endif; ?>
			<?php
		}
	}
}

new pbs_page_template_faq();
