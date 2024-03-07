<?php
//CUSTOM SHOWCASE
function forest_customize_register_featured_areas($wp_customize ) {
    //Featured Posts Area Panel
    $wp_customize->add_panel('forest_featured_post_areas', array(
        'title' => __('Featured Posts Area', 'forest'),
        'priority' => 30,
    ));

    //Featured Products Areas Panel
    $wp_customize->add_panel('forest_featured_product_areas', array(
        'title' => __('Featured Products Area', 'forest'),
        'priority' => 35,
    ));
}
add_action( 'customize_register', 'forest_customize_register_featured_areas' );