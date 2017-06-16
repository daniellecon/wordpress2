<?php

/**
 * Load section template
 *
 * @since 1.2.1
 *
 * @param $template_names
 * @return string
 */
function sovenco_customizer_load_template( $template_names ){
    $located = '';

    $is_child =  get_stylesheet_directory() != get_template_directory() ;
    foreach ( (array) $template_names as $template_name ) {
        if (  !$template_name )
            continue;

        if ( $is_child && file_exists( get_stylesheet_directory() . '/' . $template_name ) ) {  // Child them
            $located = get_stylesheet_directory() . '/' . $template_name;
            break;

        } elseif ( defined( 'sovenco_PLUS_PATH' ) && file_exists( sovenco_PLUS_PATH  . $template_name ) ) { // Check part in the plugin
            $located = sovenco_PLUS_PATH . $template_name;
            break;
        } elseif ( file_exists( get_template_directory() . '/' . $template_name) ) { // current_theme
            $located =  get_template_directory() . '/' . $template_name;
            break;
        }
    }
    
    return $located;
}

/**
 * Render customizer section
 * @since 1.2.1
 *
 * @param $section_tpl
 * @param array $section
 * @return string
 */
function sovenco_get_customizer_section_content( $section_tpl, $section = array() ){
    ob_start();
    $GLOBALS['sovenco_is_selective_refresh'] = true;
    $file = sovenco_customizer_load_template( $section_tpl );
    if ( $file ) {
        include $file;
    }
    $content = ob_get_clean();
    return trim( $content );
}


/**
 * Add customizer selective refresh
 *
 * @since 1.2.1
 *
 * @param $wp_customize
 */
function sovenco_customizer_partials( $wp_customize ) {

    // Abort if selective refresh is not available.
    if ( ! isset( $wp_customize->selective_refresh ) ) {
        return;
    }

    $selective_refresh_keys = array(
        // section features
        array(
            'id' => 'features',
            'selector' => '.section-features',
            'settings' => array(
                'sovenco_features_boxes',
                'sovenco_features_title',
                'sovenco_features_subtitle',
                'sovenco_features_desc',
                'sovenco_features_layout',
            ),
        ),

        // section services
        array(
            'id' => 'services',
            'selector' => '.section-services',
            'settings' => array(
                'sovenco_services',
                'sovenco_services_title',
                'sovenco_services_subtitle',
                'sovenco_services_desc',
                'sovenco_service_layout',
            ),
        ),

        // section gallery
        'gallery' => array(
            'id' => 'gallery',
            'selector' => '.section-gallery',
            'settings' => array(
                'sovenco_gallery_source',

                'sovenco_gallery_title',
                'sovenco_gallery_subtitle',
                'sovenco_gallery_desc',
                'sovenco_gallery_source_page',
                'sovenco_gallery_layout',
                'sovenco_gallery_display',
                'sovenco_g_number',
                'sovenco_g_row_height',
                'sovenco_g_col',
                'sovenco_g_readmore_link',
                'sovenco_g_readmore_text',
            ),
        ),

        // section news
        array(
            'id' => 'news',
            'selector' => '.section-news',
            'settings' => array(
                'sovenco_news_title',
                'sovenco_news_subtitle',
                'sovenco_news_desc',
                'sovenco_news_number',
                'sovenco_news_more_link',
                'sovenco_news_more_text',
            ),
        ),

        // section contact
        array(
            'id' => 'contact',
            'selector' => '.section-contact',
            'settings' => array(
                'sovenco_contact_title',
                'sovenco_contact_subtitle',
                'sovenco_contact_desc',
                'sovenco_contact_cf7',
                'sovenco_contact_cf7_disable',
                'sovenco_contact_text',
                'sovenco_contact_address_title',
                'sovenco_contact_address',
                'sovenco_contact_phone',
                'sovenco_contact_email',
                'sovenco_contact_fax',
            ),
        ),

        // section counter
        array(
            'id' => 'counter',
            'selector' => '.section-counter',
            'settings' => array(
                'sovenco_counter_boxes',
                'sovenco_counter_title',
                'sovenco_counter_subtitle',
                'sovenco_counter_desc',
            ),
        ),
        // section videolightbox
        array(
            'id' => 'videolightbox',
            'selector' => '.section-videolightbox',
            'settings' => array(
                'sovenco_videolightbox_title',
                'sovenco_videolightbox_url',
            ),
        ),

        // Section about
        array(
            'id' => 'about',
            'selector' => '.section-about',
            'settings' => array(
                'sovenco_about_boxes',
                'sovenco_about_title',
                'sovenco_about_subtitle',
                'sovenco_about_desc',
                'sovenco_about_content_source',
            ),
        ),

        // Section team
        array(
            'id' => 'team',
            'selector' => '.section-team',
            'settings' => array(
                'sovenco_team_members',
                'sovenco_team_title',
                'sovenco_team_subtitle',
                'sovenco_team_desc',
                'sovenco_team_layout',
            ),
        ),
    );

    $selective_refresh_keys = apply_filters( 'sovenco_customizer_partials_selective_refresh_keys', $selective_refresh_keys );

    foreach ( $selective_refresh_keys as $section ) {
        foreach ( $section['settings'] as $key ) {
            if ( $wp_customize->get_setting( $key ) ) {
                $wp_customize->get_setting( $key )->transport = 'postMessage';
            }
        }

        $wp_customize->selective_refresh->add_partial( 'section-'.$section['id'] , array(
            'selector' => $section['selector'],
            'settings' => $section['settings'],
            'render_callback' => 'sovenco_selective_refresh_render_section_content',
        ));
    }

    $wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
    $wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
    $wp_customize->get_setting( 'sovenco_hide_sitetitle' )->transport = 'postMessage';
    $wp_customize->get_setting( 'sovenco_hide_tagline' )->transport = 'postMessage';
    $wp_customize->selective_refresh->add_partial( 'header_brand', array(
        'selector' => '.site-header .site-branding',
        'settings' => array( 'blogname', 'blogdescription', 'sovenco_hide_sitetitle', 'sovenco_hide_tagline' ),
        'render_callback' => 'sovenco_site_logo',
    ) );

    // Footer social heading
    $wp_customize->selective_refresh->add_partial( 'sovenco_social_footer_title', array(
        'selector' => '.footer-social .follow-heading',
        'settings' => array( 'sovenco_social_footer_title' ),
        'render_callback' => 'sovenco_selective_refresh_social_footer_title',
    ) );
    // Footer social icons
    $wp_customize->selective_refresh->add_partial( 'sovenco_social_profiles', array(
        'selector' => '.footer-social .footer-social-icons',
        'settings' => array( 'sovenco_social_profiles' ),
        'render_callback' =>  'sovenco_get_social_profiles',
    ) );

    // Footer New letter heading
    $wp_customize->selective_refresh->add_partial( 'sovenco_newsletter_title', array(
        'selector' => '.footer-subscribe .follow-heading',
        'settings' => array( 'sovenco_newsletter_title' ),
        'render_callback' => 'sovenco_selective_refresh_newsletter_title',
    ) );

}
add_action( 'customize_register', 'sovenco_customizer_partials', 50 );



/**
 * Selective render content
 *
 * @param $partial
 * @param array $container_context
 */
function sovenco_selective_refresh_render_section_content( $partial, $container_context = array() ) {
    $tpl = 'section-parts/'.$partial->id.'.php';
    $GLOBALS['sovenco_is_selective_refresh'] = true;
    $file = sovenco_customizer_load_template( $tpl );
    if ( $file ) {
        include $file;
    }
}

function sovenco_selective_refresh_social_footer_title(){
    return get_theme_mod( 'sovenco_social_footer_title' );
}

function sovenco_selective_refresh_newsletter_title(){
    return get_theme_mod( 'sovenco_newsletter_title' );
}
