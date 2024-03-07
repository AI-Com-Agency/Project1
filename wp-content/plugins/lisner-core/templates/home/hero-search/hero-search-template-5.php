<?php
/**
 * Home / Hero search template 5
 *
 * @author pebas
 * @package home/hero-search
 * @version 1.0.0
 *
 * @param $args
 */
$position_margin = get_post_meta( $args['page_id'], 'home_hero_position_side', true );
$position_margin = lisner_get_var( $position_margin, 'top' );
$position_margin = 'top' != $position_margin ? '-' : '';
$position        = get_post_meta( $args['page_id'], 'home_hero_position', true );
$style           = isset( $position ) && ! empty( $position ) && 0 != $position ? 'style=top:' . $position_margin . $position . 'px;' : '';
?>
<div class="hero-search hero-search-template-3 hero-search-template-5 w-100" <?php echo esc_attr( $style ); ?>>
	<div class="container">

		<div class="row align-items-end justify-content-center">
			<div class="col-9">

				<div class="row">
					<!-- Hero / Form -->
					<div class="col-md-12">
						<?php include lisner_helper::get_template_part( 'field-heading', 'fields/', $args ); // taxonomy search ?>
						<?php $search_page = get_option( 'job_manager_jobs_page_id' ); ?>
						<form id="search-form" action="<?php echo esc_url( get_permalink( $search_page ) ) ?>"
						      method="get">
							<div class="row row-cst-margin justify-content-center">
								<div class="col-sm-4 col-cst-padding">
									<?php include lisner_helper::get_template_part( 'field-taxonomy-search', 'fields/', $args ); // taxonomy search ?>
								</div>
								<div class="col-sm-4 col-cst-padding">
									<?php include lisner_helper::get_template_part( 'field-location-search', 'fields/', $args ); // taxonomy search ?>
								</div>
								<div class="col-sm-2 col-cst-padding">
									<?php include lisner_helper::get_template_part( 'field-button', 'fields/', $args ); // taxonomy search ?>
								</div>
							</div>
						</form>
					</div>
				</div>

				<!-- Hero / Categories -->
				<div class="row">
					<div class="col-lg-12">
						<div class="hero-category-heading hero-category-style-4">
							<?php include lisner_helper::get_template_part( 'field-featured-categories', 'fields/', $args ); // taxonomy search ?>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>
