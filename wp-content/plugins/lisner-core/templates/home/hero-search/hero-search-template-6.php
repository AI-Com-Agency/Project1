<?php
/**
 * Home / Hero search template 6
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
<div class="hero-search hero-search-template-2 hero-search-template-6" <?php echo esc_attr( $style ); ?>>
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-10">
				<div class="row align-items-end">
					<!-- Hero / Form -->
					<div class="col-lg-5">
						<?php include lisner_helper::get_template_part( 'field-heading', 'fields/', $args ); // taxonomy search ?>
						<?php $search_page = get_option( 'job_manager_jobs_page_id' ); ?>
						<form id="search-form" action="<?php echo esc_url( get_permalink( $search_page ) ) ?>"
						      method="get">
							<?php include lisner_helper::get_template_part( 'field-taxonomy-search', 'fields/', $args ); // taxonomy search ?>
							<?php include lisner_helper::get_template_part( 'field-location-search', 'fields/', $args ); // taxonomy search ?>
							<?php include lisner_helper::get_template_part( 'field-button', 'fields/', $args ); // taxonomy search ?>
						</form>
					</div>

					<!-- Hero / Categories -->
					<div class="col-lg-5 offset-lg-2 hero-category-style-5">
						<div class="hero-category-heading">
							<?php if ( isset( $args['category_heading'] ) ) : ?>
								<p><?php echo wp_kses_post( $args['category_heading'] ); ?></p>
							<?php endif; ?>
							<?php include lisner_helper::get_template_part( 'field-featured-categories', 'fields/', $args ); // taxonomy search ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
