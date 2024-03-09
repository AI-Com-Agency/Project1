<?php
/**
 * Template for displaying search forms
 *
 * @author pebas
 * @package Lister
 * @version 1.0.0
 */

?>

<?php $unique_id = esc_attr( uniqid( 'search-form-' ) ); ?>

<?php if ( class_exists( 'Lisner_Core' ) ) : ?>
	<?php $search_id = lisner_search()->get_search_page_template(); ?>
	<form role="search" method="get" class="search-form" action="<?php echo esc_url( get_permalink( $search_id ) ); ?>">
		<div class="input-group">
			<div class="input-group-form">
				<label for="<?php echo esc_attr( $unique_id ); ?>">
					<i class="material-icons mf"><?php echo esc_html( 'search' ); ?></i>
					<?php esc_html_e( 'Type Keyword:', 'lisner' ); ?>
					<span class="screen-reader-text"><?php echo _x( 'Search for:', 'label', 'lisner' ); ?></span>
				</label>
				<input type="search" id="<?php echo esc_attr( $unique_id ); ?>" class="search-field form-control"
				       placeholder="<?php echo esc_attr_x( '(food, barber, bakery, hotel...)', 'placeholder', 'lisner' ); ?>"
				       value="<?php echo get_search_query(); ?>" name="search_keywords" />
			</div>
			<button type="submit" class="search-submit btn btn-secondary"><?php esc_html_e( 'Search', 'lisner' ); ?>
				<span
						class="screen-reader-text"><?php echo _x( 'Search', 'submit button', 'lisner' ); ?></span>
			</button>
		</div>
	</form>
<?php else: ?>
	<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<div class="input-group">
			<div class="input-group-form">
				<label for="<?php echo esc_attr( $unique_id ); ?>">
					<i class="material-icons mf"><?php echo esc_html( 'search' ); ?></i>
					<?php esc_html_e( 'Type Keyword:', 'lisner' ); ?>
					<span class="screen-reader-text"><?php echo _x( 'Search for:', 'label', 'lisner' ); ?></span>
				</label>
				<input type="search" id="<?php echo esc_attr( $unique_id ); ?>" class="search-field form-control"
				       placeholder="<?php echo esc_attr_x( 'Type keyword...', 'placeholder', 'lisner' ); ?>"
				       value="<?php echo get_search_query(); ?>" name="s" />
			</div>
			<button type="submit" class="search-submit btn btn-secondary"><?php esc_html_e( 'Search', 'lisner' ); ?>
				<span
						class="screen-reader-text"><?php echo _x( 'Search', 'submit button', 'lisner' ); ?></span>
			</button>
		</div>
	</form>
<?php endif; ?>
