<?php
//Select the Default Theme Skin
function forest_customize_register_skin( $wp_customize ) {

$wp_customize->get_section('colors')->title = __('Theme Skins & Colors', 'forest');
$wp_customize->get_section('colors')->panel = 'forest_design_panel';

$wp_customize->add_setting(
    'forest_skin',
    array(
        'default'=> 'default',
        'sanitize_callback' => 'forest_sanitize_skin'
    )
);

$skins = array( 'default' => __('Default(blue)','forest'),
    'orange' =>  __('Orange','forest'),
    'green' => __('Green','forest'),
);

$wp_customize->add_control(
    'forest_skin',array(
        'settings' => 'forest_skin',
        'section'  => 'colors',
        'type' => 'select',
        'choices' => $skins,
    )
);

function forest_sanitize_skin( $input ) {
    if ( in_array($input, array('default','orange','brown','green','grayscale') ) )
        return $input;
    else
        return '';
}


    //Replace Header Text Color with, separate colors for Title and Description
    //Override forest_site_titlecolor
    $wp_customize->remove_control('header_text');
    $wp_customize->remove_setting('header_textcolor');
    $wp_customize->add_setting('forest_site_titlecolor', array(
        'default'     => '#333333',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control(
            $wp_customize,
            'forest_site_titlecolor', array(
            'label' => __('Site Title Color','forest'),
            'section' => 'colors',
            'settings' => 'forest_site_titlecolor',
            'type' => 'color'
        ) )
    );

    $wp_customize->add_setting('forest_header_desccolor', array(
        'default'     => '#777777',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control(
            $wp_customize,
            'forest_header_desccolor', array(
            'label' => __('Site Tagline Color','forest'),
            'section' => 'colors',
            'settings' => 'forest_header_desccolor',
            'type' => 'color'
        ) )
    );

}
add_action( 'customize_register', 'forest_customize_register_skin' );