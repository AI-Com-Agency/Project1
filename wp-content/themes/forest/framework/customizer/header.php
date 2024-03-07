<?php
function forest_customize_register_header_settings( $wp_customize ) {
    //Header Settings
    $wp_customize->add_panel('forest_header_panel', array(
        'title' => __('Header Settings', 'forest'),
        'priority' => 11,
    ));

    //Logo Settings
    $wp_customize->add_section( 'title_tagline' , array(
        'title'      => __( 'Title, Tagline & Logo', 'forest' ),
        'priority'   => 30,
        'panel'     => 'forest_header_panel'
    ) );

    //Settings For Logo Area
    $wp_customize->add_setting(
        'forest_hide_title_tagline',
        array( 'sanitize_callback' => 'forest_sanitize_checkbox' )
    );

    $wp_customize->add_control(
        'forest_hide_title_tagline', array(
            'settings' => 'forest_hide_title_tagline',
            'label'    => __( 'Hide Title and Tagline.', 'forest' ),
            'section'  => 'title_tagline',
            'type'     => 'checkbox',
        )
    );

    function forest_title_visible( $control ) {
        $option = $control->manager->get_setting('forest_hide_title_tagline');
        return $option->value() == false ;
    }
}
add_action( 'customize_register', 'forest_customize_register_header_settings' );