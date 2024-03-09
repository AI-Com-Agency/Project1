<?php
/**
 * Template Name: Search Page Template / Template 1
 * Description: Search page template for the first layout
 *
 * @author pebas
 * @version 1.0.0
 * @package pages/search-templates
 */
?>
<?php $listing_box_style = lisner_listing_box_style( get_the_ID(), 'search_box_template' ); ?>
<?php $map_active = get_post_meta( get_the_ID(), 'search_map_active', true ); ?>
<?php $map_active = lisner_get_var( $map_active, false ); ?>
<?php do_action( 'lisner_before_search' ); ?>
<div class="container-fluid container-search-full container-form">
	<div class="row pl-2 pr-2">
		<div class="col-sm-12">
			<?php include lisner_helper::get_template_part( 'filters', 'pages/search-elements', $args ); ?>
		</div>
	</div>
</div>
<div class="container-fluid container-search container-search-full search-template-1 listing-box-style-<?php echo esc_attr( $listing_box_style ); ?>"
     data-page-id="<?php echo esc_attr( get_the_ID() ); ?>">
	<div class="row">
		<div class="listing-wrapper <?php echo $map_active ? esc_attr( 'map-active' ) : ''; ?>">
			<?php include lisner_helper::get_template_part( 'listings', 'pages/search-elements', $args ); ?>
		</div>
		<div class="map-wrapper <?php echo $map_active ? esc_attr( 'map-active' ) : esc_attr( 'hidden' ); ?>">
			<div class="map-preloader">
				<!-- Loader -->
				<div class="loader ajax-loader">
					<svg class="circular">
						<circle class="path" cx="50" cy="50" r="4" fill="none" stroke-width="2"
						        stroke-miterlimit="10" />
					</svg>
				</div>
			</div>
			<?php include lisner_helper::get_template_part( 'map', 'pages/search-elements', $args ); ?>
		</div>
	</div>
</div>
<?php do_action( 'lisner_after_search' ); ?>
