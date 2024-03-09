<?php
/**
 * Home / Hero taxonomy search field template
 *
 * @author pebas
 * @package field/field-taxonomy-search
 * @version 1.0.1
 *
 * @param $args
 */
$option        = get_option( 'pbs_option' );
$location_var  = get_query_var( 'listing_location' );
$location_term = isset( $location_var ) ? get_term_by( 'slug', $location_var, 'listing_location' ) : null;
$location_base = isset( $option['general-location-search'] ) && ! empty( $option['general-location-search'] ) ? $option['general-location-search'] : 'google';
$location      = $location_term ? $location_term->name : lisner_get_var( $_REQUEST['search_location'] );
$placeholder   = get_post_meta( get_the_ID(), 'home_search_location_placeholder', true );
$placeholder   = isset( $placeholder ) && ! empty( $placeholder ) ? $placeholder : get_post_meta( get_option( 'page_on_front' ), 'home_search_location_placeholder', true );
?>
<?php if ( 'google' == $location_base ) : ?>
	<div class="hero-search-field">
		<div class="form-group">
			<div class="input-group-wrapper">
            <span class="input-group-label d-flex align-items-center">
	            <?php esc_html_e( 'Near:', 'lisner-core' ); ?>
	            <i class="input-group-icon material-icons geolocate"><?php echo esc_attr( 'location_searching' ); ?></i>
            </span>
				<input class="form-control location-search"
				       autocomplete="off"
				       name="search_location"
				       placeholder="<?php echo isset( $placeholder ) && ! empty( $placeholder ) ? esc_attr( $placeholder ) : ''; ?>"
				       value="<?php echo esc_attr( $location ); ?>" />
				<div class="input-group-results">
					<ul class="list-unstyled location-results"></ul>
				</div>
				<a href="javascript:"
				   class="location-clear <?php echo ! empty( $location ) ? esc_attr( 'active' ) : ''; ?>"><i
							class="material-icons mf"><?php echo esc_html( 'close' ); ?></i></a>
			</div>
		</div>
	</div>
<?php else: ?>
	<?php
	$predefined          = isset( $option['general-location-search-taxonomies'] ) ? $option['general-location-search-taxonomies'] : '';
	$hide_empty          = isset( $option['general-location-search-hide-empty'] ) ? $option['general-location-search-hide-empty'] : false;
	$site_locations_args = array(
		'taxonomy' => 'listing_location',
	);
	if ( ! empty( $predefined ) ) {
		$site_locations_args['include'] = $predefined;
	}
	$site_locations_args['hide_empty'] = $hide_empty;
	$site_locations                    = get_terms( $site_locations_args );
	?>
	<div class="hero-search-field">
		<div class="form-group">
			<div class="input-group-wrapper">
            <span class="input-group-label d-flex align-items-center">
	            <?php esc_html_e( 'Near:', 'lisner-core' ); ?>
	            <i class="input-group-icon material-icons geolocate"><?php echo esc_attr( 'location_searching' ); ?></i>
            </span>
				<input class="form-control custom-location-search"
				       name="search_location"
				       placeholder="<?php echo isset( $placeholder ) && ! empty( $placeholder ) ? esc_attr( $placeholder ) : ''; ?>"
				       autocomplete="off"
				       value="<?php echo esc_attr( $location ); ?>" />
				<div class="input-group-results hidden">
					<ul class="list-unstyled custom-location-results">
						<?php if ( isset( $site_locations ) ) : ?>
							<?php foreach ( $site_locations as $site_location ) : ?>
								<li data-place="<?php echo esc_attr( $site_location->name ); ?>"
								    class="custom-location-result"><i
											class="material-icons mf"><?php echo esc_attr( 'place' ); ?></i>
									<?php echo esc_html( $site_location->name ); ?>
								</li>
							<?php endforeach; ?>
						<?php endif; ?>
					</ul>
				</div>
				<a href="javascript:"
				   class="location-clear <?php echo ! empty( $location ) ? esc_attr( 'active' ) : ''; ?>"><i
							class="material-icons mf"><?php echo esc_html( 'close' ); ?></i></a>
			</div>
		</div>
	</div>
<?php endif; ?>
