<?php
/**
 * Template Name: Search Page Template / Template 2
 * Description: Search page template for the second layout
 *
 * @author pebas
 * @version 1.0.0
 * @package pages/search-templates
 */
?>
<?php $listing_box_style = lisner_listing_box_style( get_the_ID(), 'search_box_template' ); ?>
<?php do_action( 'lisner_before_search' ); ?>
<div class="container-fluid container-search search-template-2 listing-box-style-<?php echo esc_attr( $listing_box_style ); ?>"
     data-page-id="<?php echo esc_attr( get_the_ID() ); ?>">
	<div class="row">
		<div class="col-lg-5 listing-wrapper">
			<div class="filters">
				<?php include lisner_helper::get_template_part( 'filters', 'pages/search-elements', $args ); ?>
			</div>
			<?php include lisner_helper::get_template_part( 'listings', 'pages/search-elements', $args ); ?>
		</div>
		<div class="col col-cst-padding">
			<div class="map-wrapper map-active">
				<div class="map-preloader">
					<!-- Loader -->
					<div class="loader ajax-loader">
						<svg class="circular">
							<circle class="path" cx="50" cy="50" r="10" fill="none" stroke-width="2"
							        stroke-miterlimit="10" />
						</svg>
					</div>
				</div>
				<?php include lisner_helper::get_template_part( 'map', 'pages/search-elements', $args ); ?>
			</div>
		</div>
	</div>
</div>
<?php do_action( 'lisner_after_search' ); ?>
