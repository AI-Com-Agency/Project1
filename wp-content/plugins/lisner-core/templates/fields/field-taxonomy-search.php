<?php
/**
 * Home / Hero taxonomy search field template
 *
 * @author pebas
 * @package field/field-taxonomy-search
 * @version 1.0.0
 *
 * @param $args
 */
$option       = get_option( 'pbs_option' );
$search_base  = isset( $option['general-category-search'] ) && ! empty( $option['general-category-search'] ) ? $option['general-category-search'] : 'keyword';
$rand_id      = lisner_get_var( $atts['rand_id'], '' );
$category_var = get_query_var( 'job_listing_category' );
$categories   = lisner_get_var( $_REQUEST['search_categories'] );
$keywords     = lisner_get_var( $_REQUEST['search_keywords'] );
$placeholder  = get_post_meta( get_the_ID(), 'home_search_taxonomies_placeholder', true );
$placeholder  = isset( $placeholder ) && ! empty( $placeholder ) ? $placeholder : get_post_meta( get_option( 'page_on_front' ), 'home_search_taxonomies_placeholder', true );
?>
<?php if ( 'keyword' == $search_base ) : ?>
	<div class="hero-search-field">
		<div class="form-group">
			<div class="input-group-wrapper">
            <span class="input-group-label d-flex align-items-center">
	            <?php esc_html_e( 'Find:', 'lisner-core' ); ?>
            </span>
				<div class="taxonomy-search-wrapper">
					<input type="hidden" name="search_keywords" id="s_keywords"
					       value="<?php echo esc_attr( $keywords ); ?>" />
					<?php if ( lisner_get_var( $category_var ) ) : ?>
						<?php $category = get_term_by( 'slug', $category_var, 'job_listing_category' ); ?>
						<input type="hidden" name="search_categories[]" class="search-categories"
						       value="<?php echo ! empty( $category ) ? esc_attr( $category->term_id ) : ''; ?>">
					<?php elseif ( isset( $_REQUEST['search_categories'] ) && ! empty( $_REQUEST['search_categories'] ) ) : ?>
						<input type="hidden" name="search_categories[]" class="search-categories"
						       value="<?php echo ! empty( $categories ) ? esc_attr( array_shift( $categories ) ) : ''; ?>">
					<?php endif; ?>
					<?php if ( lisner_get_var( $category_var ) || isset( $_REQUEST['search_categories'] ) && ! empty( $_REQUEST['search_categories'] ) ) : ?>
						<?php if ( ! lisner_get_var( $category_var ) ) : ?>
							<?php $category = get_term_by( 'term_id', array_shift( $_REQUEST['search_categories'] ), 'job_listing_category' ); ?>
						<?php else: ?>
							<?php $category = get_term_by( 'slug', $category_var, 'job_listing_category' ); ?>
						<?php endif; ?>
						<input class="form-control tax-search"
						       data-space="<?php esc_attr_e( 'in', 'lisner-core' ); ?>"
						       placeholder="<?php echo isset( $placeholder ) && ! empty( $placeholder ) ? esc_attr( $placeholder ) : ''; ?>"
						       value="<?php echo esc_attr( $keywords ) . ' ' . esc_attr__( 'in', 'lisner-core' ) . ' ' . esc_attr( $category->name ); ?>" />
					<?php else: ?>
						<input class="form-control tax-search"
						       data-space="<?php esc_attr_e( 'in', 'lisner-core' ); ?>"
						       placeholder="<?php echo isset( $placeholder ) && ! empty( $placeholder ) ? esc_attr( $placeholder ) : ''; ?>"
						       value="<?php echo esc_attr( $keywords ); ?>" />
					<?php endif; ?>
					<?php if ( lisner_get_var( $category_var ) || lisner_get_var( $_GET['search_categories'] ) || lisner_get_var( $_POST['search_categories'] ) || lisner_get_var( $_GET['search_keywords'] ) || lisner_get_var( $_POST['search_keywords'] ) ) : ?>
						<a href="javascript:" class="taxonomy-clear active"><i
									class="material-icons mf"><?php echo esc_html( 'close' ); ?></i></a>
					<?php else: ?>
						<a href="javascript:" class="taxonomy-clear"><i
									class="material-icons mf"><?php echo esc_html( 'close' ); ?></i></a>
					<?php endif; ?>
					<div class="taxonomy-select"></div>
				</div>
			</div>
		</div>
		<div class="main-search-results"></div>
	</div>
<?php else: ?>
	<?php
	$predefined           = isset( $option['general-category-search-terms'] ) ? $option['general-category-search-terms'] : '';
	$hide_empty           = isset( $option['general-category-search-hide-empty'] ) ? $option['general-category-search-hide-empty'] : false;
	$site_categories_args = array(
		'taxonomy' => 'job_listing_category',
	);
	if ( ! empty( $predefined ) ) {
		$site_categories_args['include'] = $predefined;
	}
	$site_categories_args['hide_empty'] = $hide_empty;
	$site_categories                    = get_terms( $site_categories_args );
	?>
	<div class="hero-search-field">
		<div class="form-group">
			<div class="input-group-wrapper">
            <span class="input-group-label d-flex align-items-center">
	            <?php esc_html_e( 'Find:', 'lisner-core' ); ?>
            </span>
				<?php if ( lisner_get_var( $category_var ) || isset( $_REQUEST['search_categories'] ) && ! empty( $_REQUEST['search_categories'] ) ) : ?>
					<?php if ( lisner_get_var( $category_var ) ) : ?>
						<?php $category = get_term_by( 'slug', $category_var, 'job_listing_category' ); ?>
					<?php elseif ( isset( $_REQUEST['search_categories'] ) && ! empty( $_REQUEST['search_categories'] ) ) : ?>
						<?php $category = get_term_by( 'term_id', array_shift( $_REQUEST['search_categories'] ), 'job_listing_category' ); ?>
					<?php endif; ?>
					<input type="hidden" name="search_categories[]" class="search-categories"
					       value="<?php echo ! empty( $category ) ? esc_attr( $category->term_id ) : ''; ?>">
				<?php endif; ?>
				<input class="form-control custom-category-search"
				       placeholder="<?php echo isset( $placeholder ) && ! empty( $placeholder ) ? esc_attr( $placeholder ) : ''; ?>"
				       autocomplete="off"
				       value="<?php echo ! empty( $category ) ? esc_attr( $category->name ) : ''; ?>">
				<div class="main-search-results hidden">
					<ul class="list-unstyled custom-categories-results">
						<?php if ( isset( $site_categories ) ) : ?>
							<?php foreach ( $site_categories as $site_category ) : ?>
								<?php $icon = get_term_meta( $site_category->term_id, 'term_icon', true ); ?>
								<li data-id="<?php echo esc_attr( $site_category->term_id ); ?>"
								    data-place="<?php echo esc_attr( $site_category->name ); ?>"
								    class="custom-category-result"><i
											class="material-icons mf"><?php echo esc_attr( $icon ); ?></i>
									<?php echo esc_html( $site_category->name ); ?>
								</li>
							<?php endforeach; ?>
						<?php endif; ?>
					</ul>
				</div>
				<?php if ( lisner_get_var( $_GET['search_categories'] ) || lisner_get_var( $_POST['search_categories'] ) || lisner_get_var( $_GET['search_keywords'] ) || lisner_get_var( $_POST['search_keywords'] ) ) : ?>
					<a href="javascript:" class="taxonomy-clear active"><i
								class="material-icons mf"><?php echo esc_html( 'close' ); ?></i></a>
				<?php else: ?>
					<a href="javascript:" class="taxonomy-clear"><i
								class="material-icons mf"><?php echo esc_html( 'close' ); ?></i></a>
				<?php endif; ?>
			</div>
		</div>
	</div>
<?php endif; ?>
