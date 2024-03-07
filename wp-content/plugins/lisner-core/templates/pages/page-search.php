<?php
/**
 * Template Name: Search Page Template
 * Description: Search page template
 *
 * @author pebas
 * @version 1.0.0
 */
get_header();
wp_enqueue_style( 'lisner-theme-map-cluster-style' );
wp_enqueue_script( 'lisner-theme-map-cluster' );
wp_enqueue_script( 'lisner-theme-search' );
?>
<?php $args = lisner_search()->get_updated_args(); ?>
<?php $template = get_post_meta( get_the_ID(), 'search_template', true ); ?>
<?php $template = lisner_get_var( $template, 1 ); ?>
<?php $args['search_template'] = $template; ?>

<?php include lisner_helper::get_template_part( "search-template-{$template}", 'pages/search-templates', $args ); ?>

<?php get_footer(); ?>
