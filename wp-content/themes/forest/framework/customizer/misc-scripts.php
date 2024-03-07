<?php
function forest_customize_register_misc($wp_customize) {
    //Upgrade to Pro
    $wp_customize->add_section(
        'forest_sec_pro',
        array(
            'title'     => __('Important Links','forest'),
            'priority'  => 10,
        )
    );

    $wp_customize->add_setting(
        'forest_pro',
        array( 'sanitize_callback' => 'esc_textarea' )
    );

    $wp_customize->add_control(
        new Forest_WP_Customize_Upgrade_Control(
            $wp_customize,
            'forest_pro',
            array(
                'description'	=> '<a class="forest-important-links" href="https://inkhive.com/contact-us/" target="_blank">'.__('InkHive Support Forum', 'forest').'</a>
                                    <a class="forest-important-links" href="https://inkhive.com/documentation/forest" target="_blank">'.__('Forest Documentation', 'forest').'</a>
                                    <a class="forest-important-links" href="https://demo.inkhive.com/forest-pro/" target="_blank">'.__('Forest Plus Live Demo', 'forest').'</a>
                                    <a class="forest-important-links" href="https://www.facebook.com/inkhivethemes/" target="_blank">'.__('We Love Our Facebook Fans', 'forest').'</a>
                                    <a class="forest-important-links" href="https://wordpress.org/support/theme/forest/reviews" target="_blank">'.__('Review Forest on WordPress', 'forest').'</a>',
                'section' => 'forest_sec_pro',
                'settings' => 'forest_pro',
            )
        )
    );
}
add_action('customize_register', 'forest_customize_register_misc');