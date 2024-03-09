<?php
/**
 * Element Name: Search Filters
 *
 * @author pebas
 * @version 1.0.0
 * @package pages/search-elements
 */
?>
<?php $amenity_var = get_query_var( 'listing_amenity' ); ?>
<?php $tag_var = get_query_var( 'listing_tag' ); ?>
<?php $amenities_selected = lisner_get_var( $_REQUEST['search_amenities'], array() ); ?>
<?php $tags_selected = lisner_get_var( $_REQUEST['search_tags'], array() ); ?>
<?php if ( ! empty( $amenity_var ) ) : ?>
	<?php $amenity_var = get_term_by( 'slug', $amenity_var, 'listing_amenity' ) ?>
	<?php $amenities_selected[] = $amenity_var->term_id; ?>
<?php endif; ?>
<?php if ( ! empty( $tag_var ) ) : ?>
	<?php $tag_var = get_term_by( 'slug', $tag_var, 'listing_tag' ) ?>
	<?php $tags_selected[] = $tag_var->term_id; ?>
<?php endif; ?>
<?php $amenities_count = isset( $amenities_selected ) ? count( $amenities_selected ) : array(); ?>
<?php $tags_count = isset( $tags_selected ) ? count( $tags_selected ) : array(); ?>
<?php $more_filters_count = $amenities_count + $tags_count; ?>
<form class="form-ajax explore">
	<div class="form-wrapper">
		<div class="form-group form-group-search-call">
			<div class="btn-group-toggle search-filters-call">
				<label class="btn btn-explore-parent">
					<i class="material-icons mf"><?php echo esc_attr( 'add_circle_outline' ); ?></i>
					<span><?php esc_html_e( 'Advanced Search', 'lisner-core' ); ?></span>
				</label>
			</div>
		</div>
		<div class="form-wrapper-inner">
			<div class="form-group select-wrapper select-wrapper-price">
				<label for="price-range" class="hidden"></label>
				<select name="price_range" id="price-range" class="select2-select chosen-bg"
				        data-placeholder="<?php esc_attr_e( 'Price Range', 'lisner-core' ); ?>"
				        data-disable-search="yes"
				        data-clear="yes">
					<option value=""><?php esc_html_e( 'Price Range', 'lisner-core' ); ?></option>
					<option value="cheap"
					        data-icon="attach_money"><?php esc_html_e( 'Cheap', 'lisner-core' ); ?></option>
					<option value="moderate" data-icon="attach_money"
					        data-icon-2="attach_money"><?php esc_html_e( 'Moderate', 'lisner-core' ); ?></option>
					<option value="expensive" data-icon="attach_money" data-icon-2="attach_money"
					        data-icon-3="attach_money"><?php esc_html_e( 'Expensive', 'lisner-core' ); ?></option>
					<option value="ultra" data-icon="attach_money" data-icon-2="attach_money" data-icon-3="attach_money"
					        data-icon-4="attach_money"><?php esc_html_e( 'Ultra', 'lisner-core' ); ?></option>
					<option value="none" data-icon-5="refresh"><?php esc_html_e( 'Reset', 'lisner-core' ); ?></option>
				</select>
			</div>
			<div class="form-group">
				<div class="btn-group-toggle" data-toggle="buttons">
					<label class="btn btn-explore-parent">
						<input id="open-now" name="open_now" type="checkbox">
						<i class="material-icons mf"><?php echo esc_attr( 'query_builder' ); ?></i>
						<span><?php esc_html_e( 'Open Now', 'lisner-core' ); ?></span>
					</label>
				</div>
			</div>
<!--			<div class="form-group">
				<div class="btn-group-toggle btn-nearby" data-toggle="buttons">
					<label class="btn btn-explore-parent">
						<input id="nearby" name="nearby" type="checkbox" class="nearby">
						<input name="nearby_coords" type="hidden" value="" class="nearby-coords">
						<i class="material-icons mf"><?php /*echo esc_html( 'place' ); */?></i>
						<span><?php /*esc_html_e( 'Near Me', 'lisner-core' ); */?></span>
					</label>
				</div>
			</div>-->
			<!-- Filter Group / Order By -->
			<div class="btn-group-toggle order-filters-call" data-toggle="order-filters">
				<label class="btn btn-explore order-filters-explore">
					<i class="material-icons mf"
					   data-icon="<?php echo esc_attr( 'filter_list' ); ?>"><?php echo esc_html( 'filter_list' ); ?></i>
					<span class="order-label"
					      data-name="<?php esc_attr_e( 'Sort By', 'lisner-core' ); ?>"><?php esc_html_e( 'Sort By', 'lisner-core' ); ?></span>
				</label>
				<div class="form-group orderby-group">
					<!-- OrderBy / Date -->
					<div class="orderby-group-item-wrapper">
						<i class="icon material-icons mf" data-toggle="tooltip"
						   data-title="<?php esc_attr_e( 'Sort by date', 'lisner-core' ); ?>"><?php echo esc_html( 'date_range' ); ?></i>
						<div class="orderby-group-item">
							<div class="orderby-item">
								<input type="radio" id="orderby_date_desc" name="search_orderby" value="date_desc">
								<label for="orderby_date_desc"><?php esc_html_e( 'Newer', 'lisner-core' ) ?></label>
							</div>
						</div>
						<span class="divider"><?php esc_html_e( 'or', 'lisner-core' ); ?></span>
						<div class="orderby-group-item">
							<div class="orderby-item">
								<input type="radio" id="orderby_date_asc" name="search_orderby" value="date_asc">
								<label for="orderby_date_asc"><?php esc_html_e( 'Older', 'lisner-core' ) ?></label>
							</div>
						</div>
					</div>
					<!-- OrderBy / Price -->
					<div class="orderby-group-item-wrapper">
						<i class="icon material-icons mf" data-toggle="tooltip"
						   data-title="<?php esc_attr_e( 'Sort by price', 'lisner-core' ); ?>"><?php echo esc_html( 'monetization_on' ); ?></i>
						<div class="orderby-group-item">
							<div class="orderby-item">
								<input type="radio" id="orderby_price_asc" name="search_orderby" value="price_asc">
								<label for="orderby_price_asc"><?php esc_html_e( 'Lower', 'lisner-core' ) ?></label>
							</div>
						</div>
						<span class="divider"><?php esc_html_e( 'or', 'lisner-core' ); ?></span>
						<div class="orderby-group-item">
							<div class="orderby-item">
								<input type="radio" id="orderby_price_desc" name="search_orderby" value="price_desc">
								<label for="orderby_price_desc"><?php esc_html_e( 'Higher', 'lisner-core' ) ?></label>
							</div>
						</div>
					</div>
					<!-- OrderBy / Distance -->
					<div class="orderby-group-item-wrapper">
						<i class="icon material-icons mf" data-toggle="tooltip"
						   data-title="<?php esc_attr_e( 'Sort by distance', 'lisner-core' ); ?>"><?php echo esc_html( 'near_me' ); ?></i>
						<div class="orderby-group-item">
							<div class="orderby-item">
								<input type="radio" id="orderby_distance_desc" name="search_orderby"
								       value="distance_desc">
								<label for="orderby_distance_desc"><?php esc_html_e( 'Nearer', 'lisner-core' ) ?></label>
							</div>
						</div>
						<span class="divider"><?php esc_html_e( 'or', 'lisner-core' ); ?></span>
						<div class="orderby-group-item">
							<div class="orderby-item">
								<input type="radio" id="orderby_distance_asc" name="search_orderby"
								       value="distance_asc">
								<label for="orderby_distance_asc"><?php esc_html_e( 'Further', 'lisner-core' ) ?></label>
							</div>
						</div>
					</div>
					<!-- Orderby Filter Clear -->
					<div class="filter-clear-wrapper">
						<a href="javascript:" class="filter-clear hidden"><i
									class="material-icons mf"><?php echo esc_html( 'refresh' ); ?></i><span><?php esc_html_e( 'Reset', 'lisner-core' ); ?></span></a>
					</div>
				</div>
			</div>

			<!-- Filter Group / More Filters -->
			<div class="form-group">
				<div class="btn-group-toggle more-filters-call" data-toggle="more-filters">
					<label class="btn btn-explore more-filters-explore <?php echo ! empty( $amenities_selected ) || ! empty( $tags_selected ) ? esc_attr( 'active' ) : ''; ?>">
						<span class="more-filters-notification <?php echo 0 != $more_filters_count ? esc_attr( 'active' ) : ''; ?>"><?php echo esc_html( $more_filters_count ); ?></span>
						<span><?php esc_html_e( 'More Filters', 'lisner-core' ); ?></span>
						<i class="material-icons mf"><?php echo esc_attr( 'add_circle_outline' ); ?></i>
					</label>
				</div>
			</div>
			<!-- More Filters -->
			<div class="more-filters">
				<button class="more-filters-close"><i class="material-icons mf"><?php echo esc_html( 'close' ); ?></i>
				</button>
				<!-- Amenities -->
				<?php $amenities = get_terms( array( 'taxonomy' => 'listing_amenity', 'hide_empty' => false ) ); ?>
				<?php if ( $amenities ) : ?>
					<div class="more-filters-item">
						<div class="more-filters-title-wrapper">
							<h6 class="more-filters-title"><?php esc_html_e( 'Amenities', 'lisner-core' ); ?></h6>
							<a href="javascript:"
							   class="auth-link reset-taxonomies-call <?php echo 0 == $amenities_count ? esc_attr( 'hidden' ) : ''; ?>"><i
										class="material-icons mf"><?php echo esc_html( 'refresh' ); ?></i><?php esc_html_e( 'Reset', 'lisner-core' ); ?>
							</a>
						</div>
						<div class="more-filters-item-wrapper">
							<?php foreach ( $amenities as $amenity ) : ?>
								<div class="custom-control custom-checkbox more-filters-item-inner">
									<input name="search_amenities[]" type="checkbox" class="custom-control-input"
									       id="<?php echo esc_attr( $amenity->term_id ); ?>"
									       value="<?php echo esc_attr( $amenity->term_id ); ?>"
										<?php echo in_array( $amenity->term_id, $amenities_selected ) ? esc_attr( 'checked' ) : ''; ?>>
									<label class="custom-control-label"
									       for="<?php echo esc_attr( $amenity->term_id ); ?>"><?php echo esc_html( $amenity->name ); ?></label>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>

				<!-- Tags -->
				<?php $tags = get_terms( array( 'taxonomy' => 'listing_tag', 'hide_empty' => false ) ); ?>
				<?php if ( $tags ) : ?>
					<div class="more-filters-item">
						<div class="more-filters-title-wrapper">
							<h6 class="more-filters-title"><?php esc_html_e( 'Tags', 'lisner-core' ); ?></h6>
							<a href="javascript:"
							   class="auth-link reset-taxonomies-call <?php echo 0 == $tags_count ? esc_attr( 'hidden' ) : ''; ?>"><i
										class="material-icons mf"><?php echo esc_html( 'refresh' ); ?></i><?php esc_html_e( 'Reset', 'lisner-core' ); ?>
							</a>
						</div>
						<div class="more-filters-item-wrapper">
							<?php foreach ( $tags as $tag ) : ?>
								<div class="custom-control custom-checkbox more-filters-item-inner">
									<input name="search_tags[]" type="checkbox" class="custom-control-input"
									       id="<?php echo esc_attr( $tag->term_id ); ?>"
									       value="<?php echo esc_attr( $tag->term_id ); ?>"
										<?php echo in_array( $tag->term_id, $tags_selected ) ? esc_attr( 'checked' ) : ''; ?>>
									<label class="custom-control-label"
									       for="<?php echo esc_attr( $tag->term_id ); ?>"><?php echo esc_html( $tag->name ); ?></label>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>
			</div>

			<!-- Filter Group / Reset Filters -->
			<div class="form-group">
				<div class="btn-group-toggle reset-filters-call">
					<label class="btn btn-explore btn-explore-reset">
						<i class="material-icons mf"><?php echo esc_attr( 'refresh' ); ?></i>
					</label>
				</div>
			</div>
		</div>
		<?php $map_active = get_post_meta( get_the_ID(), 'search_map_active', true ); ?>
		<?php $map_active = lisner_get_var( $map_active, false ); ?>
		<div class="form-group ml-lg-auto form-group-map">
			<div class="btn-group-toggle show-map-call" data-toggle="show-map">
				<label class="btn btn-explore <?php echo $map_active ? esc_attr( 'active' ) : ''; ?>">
					<span><?php esc_html_e( 'Show Map', 'lisner-core' ); ?></span>
					<i class="material-icons mf"><?php echo esc_attr( 'map' ); ?></i>
				</label>
			</div>
		</div>
	</div>
</form>
