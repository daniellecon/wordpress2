<?php
/**
 * sovenco Theme Customizer.
 *
 * @package sovenco
 */


/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function sovenco_customize_register( $wp_customize ) {


	// Load custom controls.
	require get_template_directory() . '/inc/customizer-controls.php';


	// Custom WP default control & settings.
	$wp_customize->get_section( 'background_image' );
	$wp_customize->get_section( 'colors' );
	$wp_customize->get_section( 'title_tagline' )->title = esc_html__('Site Title, Tagline & Logo', 'sovenco');
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	/**
	 * Hook to add other customize
	 */
	do_action( 'sovenco_customize_before_register', $wp_customize );


	$pages  =  get_pages();
	$option_pages = array();
	$option_pages[0] = esc_html__( 'Select page', 'sovenco' );
	foreach( $pages as $p ){
		$option_pages[ $p->ID ] = $p->post_title;
	}

	$users = get_users( array(
		'orderby'      => 'display_name',
		'order'        => 'ASC',
		'number'       => '',
	) );

	$option_users[0] = esc_html__( 'Select member', 'sovenco' );
	foreach( $users as $user ){
		$option_users[ $user->ID ] = $user->display_name;
	}

	/*------------------------------------------------------------------------*/
    /*  Site Identity.
    /*------------------------------------------------------------------------*/

        $is_old_logo = get_theme_mod( 'sovenco_site_image_logo' );

        $wp_customize->add_setting( 'sovenco_hide_sitetitle',
            array(
                'sanitize_callback' => 'sovenco_sanitize_checkbox',
                'default'           => $is_old_logo ? 1: 0,
            )
        );
        $wp_customize->add_control(
            'sovenco_hide_sitetitle',
            array(
                'label' 		=> esc_html__('Hide site title', 'sovenco'),
                'section' 		=> 'title_tagline',
                'type'          => 'checkbox',
            )
        );

        $wp_customize->add_setting( 'sovenco_hide_tagline',
            array(
                'sanitize_callback' => 'sovenco_sanitize_checkbox',
                'default'           => $is_old_logo ? 1: 0,
            )
        );
        $wp_customize->add_control(
            'sovenco_hide_tagline',
            array(
                'label' 		=> esc_html__('Hide site tagline', 'sovenco'),
                'section' 		=> 'title_tagline',
                'type'          => 'checkbox',

            )
        );

	/*------------------------------------------------------------------------*/
    /*  Site Options
    /*------------------------------------------------------------------------*/
		$wp_customize->add_panel( 'sovenco_options',
			array(
				'priority'       => 22,
			    'capability'     => 'edit_theme_options',
			    'theme_supports' => '',
			    'title'          => esc_html__( 'Theme Options', 'sovenco' ),
			    'description'    => '',
			)
		);

		/* Global Settings
		----------------------------------------------------------------------*/
		$wp_customize->add_section( 'sovenco_global_settings' ,
			array(
				'priority'    => 3,
				'title'       => esc_html__( 'Global', 'sovenco' ),
				'description' => '',
				'panel'       => 'sovenco_options',
			)
		);

            // Sidebar settings
            $wp_customize->add_setting( 'sovenco_layout',
                array(
                    'sanitize_callback' => 'sanitize_text_field',
                    'default'           => 'right-sidebar',
                    //'transport'			=> 'postMessage'
                )
            );
            $wp_customize->add_control( 'sovenco_layout',
                array(
                    'type'        => 'select',
                    'label'       => esc_html__('Site Layout', 'sovenco'),
                    'description'       => esc_html__('Site Layout, apply for all pages, exclude home page and custom page templates.', 'sovenco'),
                    'section'     => 'sovenco_global_settings',
                    'choices' => array(
                        'right-sidebar' => esc_html__('Right sidebar', 'sovenco'),
                        'left-sidebar' => esc_html__('Left sidebar', 'sovenco'),
                        'no-sidebar' => esc_html__('No sidebar', 'sovenco'),
                    )
                )
            );

			// Disable Sticky Header
			$wp_customize->add_setting( 'sovenco_sticky_header_disable',
				array(
					'sanitize_callback' => 'sovenco_sanitize_checkbox',
					'default'           => '',
				)
			);
			$wp_customize->add_control( 'sovenco_sticky_header_disable',
				array(
					'type'        => 'checkbox',
					'label'       => esc_html__('Disable Sticky Header?', 'sovenco'),
					'section'     => 'sovenco_global_settings',
					'description' => esc_html__('Check this box to disable sticky header when scroll.', 'sovenco')
				)
			);

			// Disable Animation
			$wp_customize->add_setting( 'sovenco_animation_disable',
				array(
					'sanitize_callback' => 'sovenco_sanitize_checkbox',
					'default'           => '',
				)
			);
			$wp_customize->add_control( 'sovenco_animation_disable',
				array(
					'type'        => 'checkbox',
					'label'       => esc_html__('Disable animation effect?', 'sovenco'),
					'section'     => 'sovenco_global_settings',
					'description' => esc_html__('Check this box to disable all element animation when scroll.', 'sovenco')
				)
			);

			// Disable Animation
			$wp_customize->add_setting( 'sovenco_btt_disable',
				array(
					'sanitize_callback' => 'sovenco_sanitize_checkbox',
					'default'           => '',
					'transport'			=> 'postMessage'
				)
			);
			$wp_customize->add_control( 'sovenco_btt_disable',
				array(
					'type'        => 'checkbox',
					'label'       => esc_html__('Hide footer back to top?', 'sovenco'),
					'section'     => 'sovenco_global_settings',
					'description' => esc_html__('Check this box to hide footer back to top button.', 'sovenco')
				)
			);


		/* Colors
		----------------------------------------------------------------------*/
		$wp_customize->add_section( 'sovenco_colors_settings' ,
			array(
				'priority'    => 4,
				'title'       => esc_html__( 'Site Colors', 'sovenco' ),
				'description' => '',
				'panel'       => 'sovenco_options',
			)
		);
			// Primary Color
			$wp_customize->add_setting( 'sovenco_primary_color', array('sanitize_callback' => 'sanitize_hex_color_no_hash', 'sanitize_js_callback' => 'maybe_hash_hex_color', 'default' => '#03c4eb' ) );
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sovenco_primary_color',
				array(
					'label'       => esc_html__( 'Primary Color', 'sovenco' ),
					'section'     => 'sovenco_colors_settings',
					'description' => '',
					'priority'    => 1
				)
			));

            // Footer BG Color
            $wp_customize->add_setting( 'sovenco_footer_bg', array(
                'sanitize_callback' => 'sanitize_hex_color_no_hash',
                'sanitize_js_callback' => 'maybe_hash_hex_color',
                'default' => '',
                'transport' => 'postMessage'
            ) );
            $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sovenco_footer_bg',
                array(
                    'label'       => esc_html__( 'Footer Background', 'sovenco' ),
                    'section'     => 'sovenco_colors_settings',
                    'description' => '',
                )
            ));

            // Footer Widgets Color
            $wp_customize->add_setting( 'sovenco_footer_info_bg', array(
                'sanitize_callback' => 'sanitize_hex_color_no_hash',
                'sanitize_js_callback' => 'maybe_hash_hex_color',
                'default' => '',
                'transport' => 'postMessage'
            ) );
            $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sovenco_footer_info_bg',
                array(
                    'label'       => esc_html__( 'Footer Info Background', 'sovenco' ),
                    'section'     => 'sovenco_colors_settings',
                    'description' => '',
                )
            ));
    

		/* Header
		----------------------------------------------------------------------*/
		$wp_customize->add_section( 'sovenco_header_settings' ,
			array(
				'priority'    => 5,
				'title'       => esc_html__( 'Header', 'sovenco' ),
				'description' => '',
				'panel'       => 'sovenco_options',
			)
		);

		// Header BG Color
		$wp_customize->add_setting( 'sovenco_header_bg_color',
			array(
				'sanitize_callback' => 'sanitize_hex_color_no_hash',
				'sanitize_js_callback' => 'maybe_hash_hex_color',
				'default' => ''
			) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sovenco_header_bg_color',
			array(
				'label'       => esc_html__( 'Background Color', 'sovenco' ),
				'section'     => 'sovenco_header_settings',
				'description' => '',
			)
		));


		// Site Title Color
		$wp_customize->add_setting( 'sovenco_logo_text_color',
			array(
				'sanitize_callback' => 'sanitize_hex_color_no_hash',
				'sanitize_js_callback' => 'maybe_hash_hex_color',
				'default' => ''
			) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sovenco_logo_text_color',
			array(
				'label'       => esc_html__( 'Site Title Color', 'sovenco' ),
				'section'     => 'sovenco_header_settings',
				'description' => esc_html__( 'Only set if you don\'t use an image logo.', 'sovenco' ),
			)
		));

		// Header Menu Color
		$wp_customize->add_setting( 'sovenco_menu_color',
			array(
				'sanitize_callback' => 'sanitize_hex_color_no_hash',
				'sanitize_js_callback' => 'maybe_hash_hex_color',
				'default' => ''
			) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sovenco_menu_color',
			array(
				'label'       => esc_html__( 'Menu Link Color', 'sovenco' ),
				'section'     => 'sovenco_header_settings',
				'description' => '',
			)
		));

		// Header Menu Hover Color
		$wp_customize->add_setting( 'sovenco_menu_hover_color',
			array(
				'sanitize_callback' => 'sanitize_hex_color_no_hash',
				'sanitize_js_callback' => 'maybe_hash_hex_color',
				'default' => ''
			) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sovenco_menu_hover_color',
			array(
				'label'       => esc_html__( 'Menu Link Hover/Active Color', 'sovenco' ),
				'section'     => 'sovenco_header_settings',
				'description' => '',

			)
		));

		// Header Menu Hover BG Color
		$wp_customize->add_setting( 'sovenco_menu_hover_bg_color',
			array(
				'sanitize_callback' => 'sanitize_hex_color_no_hash',
				'sanitize_js_callback' => 'maybe_hash_hex_color',
				'default' => ''
			) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sovenco_menu_hover_bg_color',
			array(
				'label'       => esc_html__( 'Menu Link Hover/Active BG Color', 'sovenco' ),
				'section'     => 'sovenco_header_settings',
				'description' => '',
			)
		));

		// Reponsive Mobie button color
		$wp_customize->add_setting( 'sovenco_menu_toggle_button_color',
			array(
				'sanitize_callback' => 'sanitize_hex_color_no_hash',
				'sanitize_js_callback' => 'maybe_hash_hex_color',
				'default' => ''
			) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sovenco_menu_toggle_button_color',
			array(
				'label'       => esc_html__( 'Responsive Menu Button Color', 'sovenco' ),
				'section'     => 'sovenco_header_settings',
				'description' => '',
			)
		));

		// Vertical align menu
		$wp_customize->add_setting( 'sovenco_vertical_align_menu',
			array(
				'sanitize_callback' => 'sovenco_sanitize_checkbox',
				'default'           => '',
			)
		);
		$wp_customize->add_control( 'sovenco_vertical_align_menu',
			array(
				'type'        => 'checkbox',
				'label'       => esc_html__('Center vertical align for menu', 'sovenco'),
				'section'     => 'sovenco_header_settings',
				'description' => esc_html__('If you use logo and your logo is too tall, check this box to auto vertical align menu.', 'sovenco')
			)
		);

		// Header Transparent
        $wp_customize->add_setting( 'sovenco_header_transparent',
            array(
                'sanitize_callback' => 'sovenco_sanitize_checkbox',
                'default'           => '',
                'active_callback'   => 'sovenco_showon_frontpage'
            )
        );
        $wp_customize->add_control( 'sovenco_header_transparent',
            array(
                'type'        => 'checkbox',
                'label'       => esc_html__('Header Transparent', 'sovenco'),
                'section'     => 'sovenco_header_settings',
                'description' => esc_html__('Apply for front page template only.', 'sovenco')
            )
        );

        $wp_customize->add_setting( 'sovenco_header_scroll_logo',
            array(
                'sanitize_callback' => 'sovenco_sanitize_checkbox',
                'default'           => 0,
                'active_callback'   => ''
            )
        );
        $wp_customize->add_control( 'sovenco_header_scroll_logo',
            array(
                'type'        => 'checkbox',
                'label'       => esc_html__('Scroll to top when click to the site logo or site title, only apply on front page.', 'sovenco'),
                'section'     => 'sovenco_header_settings',
            )
        );

		/* Social Settings
		----------------------------------------------------------------------*/
		$wp_customize->add_section( 'sovenco_social' ,
			array(
				'priority'    => 6,
				'title'       => esc_html__( 'Social Profiles', 'sovenco' ),
				'description' => '',
				'panel'       => 'sovenco_options',
			)
		);

			// Disable Social
			$wp_customize->add_setting( 'sovenco_social_disable',
				array(
					'sanitize_callback' => 'sovenco_sanitize_checkbox',
					'default'           => '1',
				)
			);
			$wp_customize->add_control( 'sovenco_social_disable',
				array(
					'type'        => 'checkbox',
					'label'       => esc_html__('Hide Footer Social?', 'sovenco'),
					'section'     => 'sovenco_social',
					'description' => esc_html__('Check this box to hide footer social section.', 'sovenco')
				)
			);

			$wp_customize->add_setting( 'sovenco_social_footer_guide',
				array(
					'sanitize_callback' => 'sovenco_sanitize_text'
				)
			);
			$wp_customize->add_control( new sovenco_Misc_Control( $wp_customize, 'sovenco_social_footer_guide',
				array(
					'section'     => 'sovenco_social',
					'type'        => 'custom_message',
					'description' => esc_html__( 'These social profiles setting below will display at the footer of your site.', 'sovenco' )
				)
			));

			// Footer Social Title
			$wp_customize->add_setting( 'sovenco_social_footer_title',
				array(
					'sanitize_callback' => 'sanitize_text_field',
					'default'           => esc_html__( 'Keep Updated', 'sovenco' ),
					'transport'			=> 'postMessage',
				)
			);
			$wp_customize->add_control( 'sovenco_social_footer_title',
				array(
					'label'       => esc_html__('Social Footer Title', 'sovenco'),
					'section'     => 'sovenco_social',
					'description' => ''
				)
			);

           // Socials
            $wp_customize->add_setting(
                'sovenco_social_profiles',
                array(
                    //'default' => '',
                    'sanitize_callback' => 'sovenco_sanitize_repeatable_data_field',
                    'transport' => 'postMessage', // refresh or postMessage
            ) );

            $wp_customize->add_control(
                new sovenco_Customize_Repeatable_Control(
                    $wp_customize,
                    'sovenco_social_profiles',
                    array(
                        'label' 		=> esc_html__('Socials', 'sovenco'),
                        'description'   => '',
                        'section'       => 'sovenco_social',
                        'live_title_id' => 'network', // apply for unput text and textarea only
                        'title_format'  => esc_html__('[live_title]', 'sovenco'), // [live_title]
                        'max_item'      => 5, // Maximum item can add
                        'limited_msg' 	=> wp_kses_post( 'Upgrade to <a target="_blank" href="https://www.sovenco.com">sovenco Plus</a> to be able to add more items and unlock other premium features!', 'sovenco' ),
                        'fields'    => array(
                            'network'  => array(
                                'title' => esc_html__('Social network', 'sovenco'),
                                'type'  =>'text',
                            ),
                            'icon'  => array(
                                'title' => esc_html__('Icon', 'sovenco'),
                                'type'  =>'icon',
                            ),
                            'link'  => array(
                                'title' => esc_html__('URL', 'sovenco'),
                                'type'  =>'text',
                            ),
                        ),

                    )
                )
            );

		/* Newsletter Settings
		----------------------------------------------------------------------*/
		$wp_customize->add_section( 'sovenco_newsletter' ,
			array(
				'priority'    => 9,
				'title'       => esc_html__( 'Newsletter', 'sovenco' ),
				'description' => '',
				'panel'       => 'sovenco_options',
			)
		);
			// Disable Newsletter
			$wp_customize->add_setting( 'sovenco_newsletter_disable',
				array(
					'sanitize_callback' => 'sovenco_sanitize_checkbox',
					'default'           => '1',
				)
			);
			$wp_customize->add_control( 'sovenco_newsletter_disable',
				array(
					'type'        => 'checkbox',
					'label'       => esc_html__('Hide Footer Newsletter?', 'sovenco'),
					'section'     => 'sovenco_newsletter',
					'description' => esc_html__('Check this box to hide footer newsletter form.', 'sovenco')
				)
			);

			// Mailchimp Form Title
			$wp_customize->add_setting( 'sovenco_newsletter_title',
				array(
					'sanitize_callback' => 'sanitize_text_field',
					'default'           => esc_html__( 'Join our Newsletter', 'sovenco' ),
                    'transport'         => 'postMessage', // refresh or postMessage
				)
			);
			$wp_customize->add_control( 'sovenco_newsletter_title',
				array(
					'label'       => esc_html__('Newsletter Form Title', 'sovenco'),
					'section'     => 'sovenco_newsletter',
					'description' => ''
				)
			);

			// Mailchimp action url
			$wp_customize->add_setting( 'sovenco_newsletter_mailchimp',
				array(
					'sanitize_callback' => 'esc_url',
					'default'           => '',
                    'transport'         => 'postMessage', // refresh or postMessage
				)
			);
			$wp_customize->add_control( 'sovenco_newsletter_mailchimp',
				array(
					'label'       => esc_html__('MailChimp Action URL', 'sovenco'),
					'section'     => 'sovenco_newsletter',
					'description' => __( 'The newsletter form use MailChimp, please follow <a target="_blank" href="http://goo.gl/uRVIst">this guide</a> to know how to get MailChimp Action URL. Example <i>//mailchimp.com', 'sovenco' )
				)
			);



            if ( ! function_exists( 'wp_get_custom_css' ) ) {  // Back-compat for WordPress < 4.7.

                /* Custom CSS Settings
                ----------------------------------------------------------------------*/
                $wp_customize->add_section(
                    'sovenco_custom_code',
                    array(
                        'title' => __('Custom CSS', 'sovenco'),
                        'panel' => 'sovenco_options',
                    )
                );


                $wp_customize->add_setting(
                    'sovenco_custom_css',
                    array(
                        'default' => '',
                        'sanitize_callback' => 'sovenco_sanitize_css',
                        'type' => 'option',
                    )
                );

                $wp_customize->add_control(
                    'sovenco_custom_css',
                    array(
                        'label' => __('Custom CSS', 'sovenco'),
                        'section' => 'sovenco_custom_code',
                        'type' => 'textarea'
                    )
                );
            } else {
                $wp_customize->get_section( 'custom_css' )->priority = 994;
            }

	/*------------------------------------------------------------------------*/
    /*  Section: Order & Styling
    /*------------------------------------------------------------------------*/
	$wp_customize->add_section( 'sovenco_order_styling' ,
		array(
			'priority'        => 129,
			'title'           => esc_html__( 'Section Order & Styling', 'sovenco' ),
			'description'     => '',
			'active_callback' => 'sovenco_showon_frontpage'
		)
	);
		// Plus message
		$wp_customize->add_setting( 'sovenco_order_styling_message',
			array(
				'sanitize_callback' => 'sovenco_sanitize_text',
			)
		);
		$wp_customize->add_control( new sovenco_Misc_Control( $wp_customize, 'sovenco_order_styling_message',
			array(
				'section'     => 'sovenco_news_settings',
				'type'        => 'custom_message',
				'section'     => 'sovenco_order_styling',
				'description' => wp_kses_post( 'Check out <a target="_blank" href="https://www.sovenco.com">sovenco Plus version</a> for full control over <strong>section order</strong> and <strong>section styling</strong>! ', 'sovenco' )
			)
		));


	/*------------------------------------------------------------------------*/
    /*  Section: Hero
    /*------------------------------------------------------------------------*/

	$wp_customize->add_panel( 'sovenco_hero_panel' ,
		array(
			'priority'        => 130,
			'title'           => esc_html__( 'Section: Hero', 'sovenco' ),
			'description'     => '',
			'active_callback' => 'sovenco_showon_frontpage'
		)
	);

		// Hero settings
		$wp_customize->add_section( 'sovenco_hero_settings' ,
			array(
				'priority'    => 3,
				'title'       => esc_html__( 'Hero Settings', 'sovenco' ),
				'description' => '',
				'panel'       => 'sovenco_hero_panel',
			)
		);

			// Show section
			$wp_customize->add_setting( 'sovenco_hero_disable',
				array(
					'sanitize_callback' => 'sovenco_sanitize_checkbox',
					'default'           => '',
				)
			);
			$wp_customize->add_control( 'sovenco_hero_disable',
				array(
					'type'        => 'checkbox',
					'label'       => esc_html__('Hide this section?', 'sovenco'),
					'section'     => 'sovenco_hero_settings',
					'description' => esc_html__('Check this box to hide this section.', 'sovenco'),
				)
			);
			// Section ID
			$wp_customize->add_setting( 'sovenco_hero_id',
				array(
					'sanitize_callback' => 'sovenco_sanitize_text',
					'default'           => esc_html__('hero', 'sovenco'),
				)
			);
			$wp_customize->add_control( 'sovenco_hero_id',
				array(
					'label' 		=> esc_html__('Section ID:', 'sovenco'),
					'section' 		=> 'sovenco_hero_settings',
					'description'   => esc_html__( 'The section id, we will use this for link anchor.', 'sovenco' )
				)
			);

			// Show hero full screen
			$wp_customize->add_setting( 'sovenco_hero_fullscreen',
				array(
					'sanitize_callback' => 'sovenco_sanitize_checkbox',
					'default'           => '',
				)
			);
			$wp_customize->add_control( 'sovenco_hero_fullscreen',
				array(
					'type'        => 'checkbox',
					'label'       => esc_html__('Make hero section full screen', 'sovenco'),
					'section'     => 'sovenco_hero_settings',
					'description' => esc_html__('Check this box to make hero section full screen.', 'sovenco'),
				)
			);

			// Hero content padding top
			$wp_customize->add_setting( 'sovenco_hero_pdtop',
				array(
					'sanitize_callback' => 'sovenco_sanitize_text',
					'default'           => esc_html__('10', 'sovenco'),
				)
			);
			$wp_customize->add_control( 'sovenco_hero_pdtop',
				array(
					'label'           => esc_html__('Padding Top:', 'sovenco'),
					'section'         => 'sovenco_hero_settings',
					'description'     => esc_html__( 'The hero content padding top in percent (%).', 'sovenco' ),
					'active_callback' => 'sovenco_hero_fullscreen_callback'
				)
			);

			// Hero content padding bottom
			$wp_customize->add_setting( 'sovenco_hero_pdbotom',
				array(
					'sanitize_callback' => 'sovenco_sanitize_text',
					'default'           => esc_html__('10', 'sovenco'),
				)
			);
			$wp_customize->add_control( 'sovenco_hero_pdbotom',
				array(
					'label'           => esc_html__('Padding Bottom:', 'sovenco'),
					'section'         => 'sovenco_hero_settings',
					'description'     => esc_html__( 'The hero content padding bottom in percent (%).', 'sovenco' ),
					'active_callback' => 'sovenco_hero_fullscreen_callback'
				)
			);


            /* Hero options
            ----------------------------------------------------------------------*/

            $wp_customize->add_setting(
                'sovenco_hero_option_animation',
                array(
                    'default'              => 'flipInX',
                    'sanitize_callback'    => 'sanitize_text_field',
                )
            );

            /**
             * @see https://github.com/daneden/animate.css
             */

            $animations_css = 'bounce flash pulse rubberBand shake headShake swing tada wobble jello bounceIn bounceInDown bounceInLeft bounceInRight bounceInUp bounceOut bounceOutDown bounceOutLeft bounceOutRight bounceOutUp fadeIn fadeInDown fadeInDownBig fadeInLeft fadeInLeftBig fadeInRight fadeInRightBig fadeInUp fadeInUpBig fadeOut fadeOutDown fadeOutDownBig fadeOutLeft fadeOutLeftBig fadeOutRight fadeOutRightBig fadeOutUp fadeOutUpBig flipInX flipInY flipOutX flipOutY lightSpeedIn lightSpeedOut rotateIn rotateInDownLeft rotateInDownRight rotateInUpLeft rotateInUpRight rotateOut rotateOutDownLeft rotateOutDownRight rotateOutUpLeft rotateOutUpRight hinge rollIn rollOut zoomIn zoomInDown zoomInLeft zoomInRight zoomInUp zoomOut zoomOutDown zoomOutLeft zoomOutRight zoomOutUp slideInDown slideInLeft slideInRight slideInUp slideOutDown slideOutLeft slideOutRight slideOutUp';

            $animations_css = explode( ' ', $animations_css );
            $animations = array();
            foreach ( $animations_css as $v ) {
                $v =  trim( $v );
                if ( $v ){
                    $animations[ $v ]= $v;
                }

            }

            $wp_customize->add_control(
                'sovenco_hero_option_animation',
                array(
                    'label'    => __( 'Text animation', 'sovenco' ),
                    'section'  => 'sovenco_hero_settings',
                    'type'     => 'select',
                    'choices' => $animations,
                )
            );


            $wp_customize->add_setting(
                'sovenco_hero_option_speed',
                array(
                    'default'              => '5000',
                    'sanitize_callback'    => 'sanitize_text_field',
                )
            );

            $wp_customize->add_control(
                'sovenco_hero_option_speed',
                array(
                    'label'    => __( 'Text animation speed', 'sovenco' ),
                    'description' => esc_html__( 'The delay between the changing of each phrase in milliseconds.', 'sovenco' ),
                    'section'  => 'sovenco_hero_settings',
                )
            );


        $wp_customize->add_setting(
            'sovenco_hero_slider_fade',
            array(
                'default'              => '750',
                'sanitize_callback'    => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control(
            'sovenco_hero_slider_fade',
            array(
                'label'    => __( 'Slider animation speed', 'sovenco' ),
                'description' => esc_html__( 'This is the speed at which the image will fade in. Integers in milliseconds are accepted.', 'sovenco' ),
                'section'  => 'sovenco_hero_settings',
            )
        );

        $wp_customize->add_setting(
            'sovenco_hero_slider_duration',
            array(
                'default'              => '5000',
                'sanitize_callback'    => 'sanitize_text_field',
            )
        );

        $wp_customize->add_control(
            'sovenco_hero_slider_duration',
            array(
                'label'    => __( 'Slider duration speed', 'sovenco' ),
                'description' => esc_html__( 'The amount of time in between slides, expressed as the number of milliseconds.', 'sovenco' ),
                'section'  => 'sovenco_hero_settings',
            )
        );



		$wp_customize->add_section( 'sovenco_hero_images' ,
			array(
				'priority'    => 6,
				'title'       => esc_html__( 'Hero Background Media', 'sovenco' ),
				'description' => '',
				'panel'       => 'sovenco_hero_panel',
			)
		);

			$wp_customize->add_setting(
				'sovenco_hero_images',
				array(
					'sanitize_callback' => 'sovenco_sanitize_repeatable_data_field',
					'transport' => 'refresh', // refresh or postMessage
					'default' => json_encode( array(
						array(
							'image'=> array(
								'url' => get_template_directory_uri().'/assets/images/hero5.jpg',
								'id' => ''
							)
						)
					) )
				) );

			$wp_customize->add_control(
				new sovenco_Customize_Repeatable_Control(
					$wp_customize,
					'sovenco_hero_images',
					array(
						'label'     => esc_html__('Background Images', 'sovenco'),
						'description'   => '',
						'priority'     => 40,
						'section'       => 'sovenco_hero_images',
						'title_format'  => esc_html__( 'Background', 'sovenco'), // [live_title]
						'max_item'      => 2, // Maximum item can add

						'fields'    => array(
							'image' => array(
								'title' => esc_html__('Background Image', 'sovenco'),
								'type'  =>'media',
								'default' => array(
									'url' => get_template_directory_uri().'/assets/images/hero5.jpg',
									'id' => ''
								)
							),

						),

					)
				)
			);

			// Overlay color
			$wp_customize->add_setting( 'sovenco_hero_overlay_color',
				array(
					'sanitize_callback' => 'sovenco_sanitize_color_alpha',
					'default'           => 'rgba(0,0,0,.3)',
					'transport' => 'refresh', // refresh or postMessage
				)
			);
			$wp_customize->add_control( new sovenco_Alpha_Color_Control(
					$wp_customize,
					'sovenco_hero_overlay_color',
					array(
						'label' 		=> esc_html__('Background Overlay Color', 'sovenco'),
						'section' 		=> 'sovenco_hero_images',
						'priority'      => 130,
					)
				)
			);


            // Parallax
            $wp_customize->add_setting( 'sovenco_hero_parallax',
                array(
                    'sanitize_callback' => 'sovenco_sanitize_checkbox',
                    'default'           => 0,
                    'transport' => 'refresh', // refresh or postMessage
                )
            );
            $wp_customize->add_control(
                'sovenco_hero_parallax',
                array(
                    'label' 		=> esc_html__('Enable parallax effect (apply for first BG image only)', 'sovenco'),
                    'section' 		=> 'sovenco_hero_images',
                    'type' 		   => 'checkbox',
                    'priority'      => 50,
                    'description' => '',
                )
            );

			// Background Video
			$wp_customize->add_setting( 'sovenco_hero_videobackground_upsell',
				array(
					'sanitize_callback' => 'sovenco_sanitize_text',
				)
			);
			$wp_customize->add_control( new sovenco_Misc_Control( $wp_customize, 'sovenco_hero_videobackground_upsell',
				array(
					'section'     => 'sovenco_hero_images',
					'type'        => 'custom_message',
					'description' => wp_kses_post( 'Want to add <strong>background video</strong> for hero section? Upgrade to <a target="_blank" href="https://www.sovenco.com">sovenco Plus</a> version.', 'sovenco' ),
					'priority'    => 131,
				)
			));



		$wp_customize->add_section( 'sovenco_hero_content_layout1' ,
			array(
				'priority'    => 9,
				'title'       => esc_html__( 'Hero Content Layout', 'sovenco' ),
				'description' => '',
				'panel'       => 'sovenco_hero_panel',

			)
		);

			// Hero Layout
			$wp_customize->add_setting( 'sovenco_hero_layout',
				array(
					'sanitize_callback' => 'sovenco_sanitize_text',
					'default'           => '1',
				)
			);
			$wp_customize->add_control( 'sovenco_hero_layout',
				array(
					'label' 		=> esc_html__('Display Layout', 'sovenco'),
					'section' 		=> 'sovenco_hero_content_layout1',
					'description'   => '',
					'type'          => 'select',
					'choices'       => array(
						'1' => esc_html__('Layout 1', 'sovenco' ),
						'2' => esc_html__('Layout 2', 'sovenco' ),
					),
				)
			);
			// For Hero layout ------------------------

				// Large Text
				$wp_customize->add_setting( 'sovenco_hcl1_largetext',
					array(
						'sanitize_callback' => 'sovenco_sanitize_text',
						'mod' 				=> 'html',
						'default'           => wp_kses_post('We are <span class="js-rotating">sovenco | One Page | Responsive | Perfection</span>', 'sovenco'),
					)
				);
				$wp_customize->add_control( new sovenco_Editor_Custom_Control(
					$wp_customize,
					'sovenco_hcl1_largetext',
					array(
						'label' 		=> esc_html__('Large Text', 'sovenco'),
						'section' 		=> 'sovenco_hero_content_layout1',
						'description'   => esc_html__('Text Rotating Guide: Put your rotate texts separate by "|" into <span class="js-rotating">...</span>, go to Customizer->Site Option->Animate to control rotate animation.', 'sovenco'),
					)
				));

				// Small Text
				$wp_customize->add_setting( 'sovenco_hcl1_smalltext',
					array(
						'sanitize_callback' => 'sovenco_sanitize_text',
						'default'			=> wp_kses_post('Morbi tempus porta nunc <strong>pharetra quisque</strong> ligula imperdiet posuere<br> vitae felis proin sagittis leo ac tellus blandit sollicitudin quisque vitae placerat.', 'sovenco'),
					)
				);
				$wp_customize->add_control( new sovenco_Editor_Custom_Control(
					$wp_customize,
					'sovenco_hcl1_smalltext',
					array(
						'label' 		=> esc_html__('Small Text', 'sovenco'),
						'section' 		=> 'sovenco_hero_content_layout1',
						'mod' 				=> 'html',
						'description'   => esc_html__('You can use text rotate slider in this textarea too.', 'sovenco'),
					)
				));

				// Button #1 Text
				$wp_customize->add_setting( 'sovenco_hcl1_btn1_text',
					array(
						'sanitize_callback' => 'sovenco_sanitize_text',
						'default'           => esc_html__('About Us', 'sovenco'),
					)
				);
				$wp_customize->add_control( 'sovenco_hcl1_btn1_text',
					array(
						'label' 		=> esc_html__('Button #1 Text', 'sovenco'),
						'section' 		=> 'sovenco_hero_content_layout1'
					)
				);

				// Button #1 Link
				$wp_customize->add_setting( 'sovenco_hcl1_btn1_link',
					array(
						'sanitize_callback' => 'esc_url',
						'default'           => esc_url( home_url( '/' )).esc_html__('#about', 'sovenco'),
					)
				);
				$wp_customize->add_control( 'sovenco_hcl1_btn1_link',
					array(
						'label' 		=> esc_html__('Button #1 Link', 'sovenco'),
						'section' 		=> 'sovenco_hero_content_layout1'
					)
				);
                // Button #1 Style
				$wp_customize->add_setting( 'sovenco_hcl1_btn1_style',
					array(
						'sanitize_callback' => 'sovenco_sanitize_text',
						'default'           => 'btn-theme-primary',
					)
				);
				$wp_customize->add_control( 'sovenco_hcl1_btn1_style',
					array(
						'label' 		=> esc_html__('Button #1 style', 'sovenco'),
						'section' 		=> 'sovenco_hero_content_layout1',
                        'type'          => 'select',
                        'choices' => array(
                                'btn-theme-primary' => esc_html__('Button Primary', 'sovenco'),
                                'btn-secondary-outline' => esc_html__('Button Secondary', 'sovenco'),
                                'btn-default' => esc_html__('Button', 'sovenco'),
                                'btn-primary' => esc_html__('Primary', 'sovenco'),
                                'btn-success' => esc_html__('Success', 'sovenco'),
                                'btn-info' => esc_html__('Info', 'sovenco'),
                                'btn-warning' => esc_html__('Warning', 'sovenco'),
                                'btn-danger' => esc_html__('Danger', 'sovenco'),
                        )
					)
				);

				// Button #2 Text
				$wp_customize->add_setting( 'sovenco_hcl1_btn2_text',
					array(
						'sanitize_callback' => 'sovenco_sanitize_text',
						'default'           => esc_html__('Get Started', 'sovenco'),
					)
				);
				$wp_customize->add_control( 'sovenco_hcl1_btn2_text',
					array(
						'label' 		=> esc_html__('Button #2 Text', 'sovenco'),
						'section' 		=> 'sovenco_hero_content_layout1'
					)
				);

				// Button #2 Link
				$wp_customize->add_setting( 'sovenco_hcl1_btn2_link',
					array(
						'sanitize_callback' => 'esc_url',
						'default'           => esc_url( home_url( '/' )).esc_html__('#contact', 'sovenco'),
					)
				);
				$wp_customize->add_control( 'sovenco_hcl1_btn2_link',
					array(
						'label' 		=> esc_html__('Button #2 Link', 'sovenco'),
						'section' 		=> 'sovenco_hero_content_layout1'
					)
				);

                // Button #1 Style
                $wp_customize->add_setting( 'sovenco_hcl1_btn2_style',
                    array(
                        'sanitize_callback' => 'sovenco_sanitize_text',
                        'default'           => 'btn-secondary-outline',
                    )
                );
                $wp_customize->add_control( 'sovenco_hcl1_btn2_style',
                    array(
                        'label' 		=> esc_html__('Button #2 style', 'sovenco'),
                        'section' 		=> 'sovenco_hero_content_layout1',
                        'type'          => 'select',
                        'choices' => array(
                            'btn-theme-primary' => esc_html__('Button Primary', 'sovenco'),
                            'btn-secondary-outline' => esc_html__('Button Secondary', 'sovenco'),
                            'btn-default' => esc_html__('Button', 'sovenco'),
                            'btn-primary' => esc_html__('Primary', 'sovenco'),
                            'btn-success' => esc_html__('Success', 'sovenco'),
                            'btn-info' => esc_html__('Info', 'sovenco'),
                            'btn-warning' => esc_html__('Warning', 'sovenco'),
                            'btn-danger' => esc_html__('Danger', 'sovenco'),
                        )
                    )
                );


				/* Layout 2 ---- */

				// Layout 22 content text
				$wp_customize->add_setting( 'sovenco_hcl2_content',
					array(
						'sanitize_callback' => 'sovenco_sanitize_text',
						'mod' 				=> 'html',
						'default'           =>  wp_kses_post( '<h1>Business Website'."\n".'Made Simple.</h1>'."\n".'We provide creative solutions to clients around the world,'."\n".'creating things that get attention and meaningful.'."\n\n".'<a class="btn btn-secondary-outline btn-lg" href="#">Get Started</a>' ),
					)
				);
				$wp_customize->add_control( new sovenco_Editor_Custom_Control(
					$wp_customize,
					'sovenco_hcl2_content',
					array(
						'label' 		=> esc_html__('Content Text', 'sovenco'),
						'section' 		=> 'sovenco_hero_content_layout1',
						'description'   => '',
					)
				));

				// Layout 2 image
				$wp_customize->add_setting( 'sovenco_hcl2_image',
					array(
						'sanitize_callback' => 'sovenco_sanitize_text',
						'mod' 				=> 'html',
						'default'           =>  get_template_directory_uri().'/assets/images/sovenco_responsive.png',
					)
				);
				$wp_customize->add_control( new WP_Customize_Image_Control(
					$wp_customize,
					'sovenco_hcl2_image',
					array(
						'label' 		=> esc_html__('Image', 'sovenco'),
						'section' 		=> 'sovenco_hero_content_layout1',
						'description'   => '',
					)
				));


			// END For Hero layout ------------------------

	/*------------------------------------------------------------------------*/
	/*  Section: Video Popup
	/*------------------------------------------------------------------------*/
	$wp_customize->add_panel( 'sovenco_videolightbox' ,
		array(
			'priority'        => 180,
			'title'           => esc_html__( 'Section: Video Lightbox', 'sovenco' ),
			'description'     => '',
			'active_callback' => 'sovenco_showon_frontpage'
		)
	);

    $wp_customize->add_section( 'sovenco_videolightbox_settings' ,
        array(
            'priority'    => 3,
            'title'       => esc_html__( 'Section Settings', 'sovenco' ),
            'description' => '',
            'panel'       => 'sovenco_videolightbox',
        )
    );

    // Show Content
    $wp_customize->add_setting( 'sovenco_videolightbox_disable',
        array(
            'sanitize_callback' => 'sovenco_sanitize_checkbox',
            'default'           => '',
        )
    );
    $wp_customize->add_control( 'sovenco_videolightbox_disable',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Hide this section?', 'sovenco'),
            'section'     => 'sovenco_videolightbox_settings',
            'description' => esc_html__('Check this box to hide this section.', 'sovenco'),
        )
    );

    // Section ID
    $wp_customize->add_setting( 'sovenco_videolightbox_id',
        array(
            'sanitize_callback' => 'sovenco_sanitize_text',
            'default'           => 'videolightbox',
        )
    );
    $wp_customize->add_control( 'sovenco_videolightbox_id',
        array(
            'label' 		=> esc_html__('Section ID:', 'sovenco'),
            'section' 		=> 'sovenco_videolightbox_settings',
            'description'   => esc_html__('The section id, we will use this for link anchor.', 'sovenco' )
        )
    );

    // Title
    $wp_customize->add_setting( 'sovenco_videolightbox_title',
        array(
            'sanitize_callback' => 'sovenco_sanitize_text',
            'default'           => '',
        )
    );

    $wp_customize->add_control( new sovenco_Editor_Custom_Control(
        $wp_customize,
        'sovenco_videolightbox_title',
        array(
            'label'     	=>  esc_html__('Section heading', 'sovenco'),
            'section' 		=> 'sovenco_videolightbox_settings',
            'description'   => '',
        )
    ));

    // Video URL
    $wp_customize->add_setting( 'sovenco_videolightbox_url',
        array(
            'sanitize_callback' => 'esc_url_raw',
            'default'           => '',
        )
    );
    $wp_customize->add_control( 'sovenco_videolightbox_url',
        array(
            'label' 		=> esc_html__('Video url', 'sovenco'),
            'section' 		=> 'sovenco_videolightbox_settings',
            'description'   =>  esc_html__('Paste Youtube or Vimeo url here', 'sovenco'),
        )
    );

    // Parallax image
    $wp_customize->add_setting( 'sovenco_videolightbox_image',
        array(
            'sanitize_callback' => 'esc_url_raw',
            'default'           => '',
        )
    );
    $wp_customize->add_control( new WP_Customize_Image_Control(
        $wp_customize,
        'sovenco_videolightbox_image',
        array(
            'label' 		=> esc_html__('Background image', 'sovenco'),
            'section' 		=> 'sovenco_videolightbox_settings',
        )
    ));


	/*------------------------------------------------------------------------*/
	/*  Section: Gallery
    /*------------------------------------------------------------------------*/
	$wp_customize->add_panel( 'sovenco_gallery' ,
		array(
			'priority'        => 190,
			'title'           => esc_html__( 'Section: Gallery', 'sovenco' ),
			'description'     => '',
			'active_callback' => 'sovenco_showon_frontpage'
		)
	);

	$wp_customize->add_section( 'sovenco_gallery_settings' ,
		array(
			'priority'    => 3,
			'title'       => esc_html__( 'Section Settings', 'sovenco' ),
			'description' => '',
			'panel'       => 'sovenco_gallery',
		)
	);

	// Show Content
	$wp_customize->add_setting( 'sovenco_gallery_disable',
		array(
			'sanitize_callback' => 'sovenco_sanitize_checkbox',
			'default'           => 1,
		)
	);
	$wp_customize->add_control( 'sovenco_gallery_disable',
		array(
			'type'        => 'checkbox',
			'label'       => esc_html__('Hide this section?', 'sovenco'),
			'section'     => 'sovenco_gallery_settings',
			'description' => esc_html__('Check this box to hide this section.', 'sovenco'),
		)
	);

	// Section ID
	$wp_customize->add_setting( 'sovenco_gallery_id',
		array(
			'sanitize_callback' => 'sovenco_sanitize_text',
			'default'           => esc_html__('gallery', 'sovenco'),
		)
	);
	$wp_customize->add_control( 'sovenco_gallery_id',
		array(
			'label'     => esc_html__('Section ID:', 'sovenco'),
			'section' 		=> 'sovenco_gallery_settings',
			'description'   => esc_html__( 'The section id, we will use this for link anchor.', 'sovenco' )
		)
	);

	// Title
	$wp_customize->add_setting( 'sovenco_gallery_title',
		array(
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => esc_html__('Gallery', 'sovenco'),
		)
	);
	$wp_customize->add_control( 'sovenco_gallery_title',
		array(
			'label'     => esc_html__('Section Title', 'sovenco'),
			'section' 		=> 'sovenco_gallery_settings',
			'description'   => '',
		)
	);

	// Sub Title
	$wp_customize->add_setting( 'sovenco_gallery_subtitle',
		array(
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => esc_html__('Section subtitle', 'sovenco'),
		)
	);
	$wp_customize->add_control( 'sovenco_gallery_subtitle',
		array(
			'label'     => esc_html__('Section Subtitle', 'sovenco'),
			'section' 		=> 'sovenco_gallery_settings',
			'description'   => '',
		)
	);

	// Description
	$wp_customize->add_setting( 'sovenco_gallery_desc',
		array(
			'sanitize_callback' => 'sovenco_sanitize_text',
			'default'           => '',
		)
	);
	$wp_customize->add_control( new sovenco_Editor_Custom_Control(
		$wp_customize,
		'sovenco_gallery_desc',
		array(
			'label' 		=> esc_html__('Section Description', 'sovenco'),
			'section' 		=> 'sovenco_gallery_settings',
			'description'   => '',
		)
	));

	$wp_customize->add_section( 'sovenco_gallery_content' ,
		array(
			'priority'    => 6,
			'title'       => esc_html__( 'Section Content', 'sovenco' ),
			'description' => '',
			'panel'       => 'sovenco_gallery',
		)
	);
	// Gallery Source
	$wp_customize->add_setting( 'sovenco_gallery_source',
		array(
			'sanitize_callback' => 'sanitize_text_field',
			'validate_callback' => 'sovenco_gallery_source_validate',
			'default'           => 'page',
		)
	);
	$wp_customize->add_control( 'sovenco_gallery_source',
		array(
			'label'     	=> esc_html__('Select Gallery Source', 'sovenco'),
			'section' 		=> 'sovenco_gallery_content',
			'type'          => 'select',
			'priority'      => 5,
			'choices'       => array(
				'page'      => esc_html__('Page', 'sovenco'),
				'facebook'  => 'Facebook',
				'instagram' => 'Instagram',
				'flickr'    => 'Flickr',
			)
		)
	);

	// Source page settings
	$wp_customize->add_setting( 'sovenco_gallery_source_page',
		array(
			'sanitize_callback' => 'sovenco_sanitize_number',
			'default'           => '',
		)
	);
	$wp_customize->add_control( 'sovenco_gallery_source_page',
		array(
			'label'     	=> esc_html__('Select Gallery Page', 'sovenco'),
			'section' 		=> 'sovenco_gallery_content',
			'type'          => 'select',
			'priority'      => 10,
			'choices'       => $option_pages,
			'description'   => esc_html__('Select a page which have content contain [gallery] shortcode.', 'sovenco'),
		)
	);


	// Gallery Layout
	$wp_customize->add_setting( 'sovenco_gallery_layout',
		array(
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => 'default',
		)
	);
	$wp_customize->add_control( 'sovenco_gallery_layout',
		array(
			'label'     	=> esc_html__('Layout', 'sovenco'),
			'section' 		=> 'sovenco_gallery_content',
			'type'          => 'select',
			'priority'      => 40,
			'choices'       => array(
				'default'      => esc_html__('Default, inside container', 'sovenco'),
				'full-width'  => esc_html__('Full Width', 'sovenco'),
			)
		)
	);

	// Gallery Display
	$wp_customize->add_setting( 'sovenco_gallery_display',
		array(
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => 'default',
		)
	);
	$wp_customize->add_control( 'sovenco_gallery_display',
		array(
			'label'     	=> esc_html__('Display', 'sovenco'),
			'section' 		=> 'sovenco_gallery_content',
			'type'          => 'select',
			'priority'      => 50,
			'choices'       => array(
				'grid'      => esc_html__('Grid', 'sovenco'),
				'carousel'    => esc_html__('Carousel', 'sovenco'),
				'slider'      => esc_html__('Slider', 'sovenco'),
				'justified'   => esc_html__('Justified', 'sovenco'),
				'masonry'     => esc_html__('Masonry', 'sovenco'),
			)
		)
	);

	// Gallery grid spacing
	$wp_customize->add_setting( 'sovenco_g_spacing',
		array(
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => 20,
		)
	);
	$wp_customize->add_control( 'sovenco_g_spacing',
		array(
			'label'     	=> esc_html__('Item Spacing', 'sovenco'),
			'section' 		=> 'sovenco_gallery_content',
			'priority'      => 55,

		)
	);

	// Gallery grid spacing
	$wp_customize->add_setting( 'sovenco_g_row_height',
		array(
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => 120,
		)
	);
	$wp_customize->add_control( 'sovenco_g_row_height',
		array(
			'label'     	=> esc_html__('Row Height', 'sovenco'),
			'section' 		=> 'sovenco_gallery_content',
			'priority'      => 57,

		)
	);

	// Gallery grid gird col
	$wp_customize->add_setting( 'sovenco_g_col',
		array(
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => '4',
		)
	);
	$wp_customize->add_control( 'sovenco_g_col',
		array(
			'label'     	=> esc_html__('Layout columns', 'sovenco'),
			'section' 		=> 'sovenco_gallery_content',
			'priority'      => 60,
			'type'          => 'select',
			'choices'       => array(
				'1'      => 1,
				'2'      => 2,
				'3'      => 3,
				'4'      => 4,
				'5'      => 5,
				'6'      => 6,
			)

		)
	);

	// Gallery max number
	$wp_customize->add_setting( 'sovenco_g_number',
		array(
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => 10,
		)
	);
	$wp_customize->add_control( 'sovenco_g_number',
		array(
			'label'     	=> esc_html__('Number items', 'sovenco'),
			'section' 		=> 'sovenco_gallery_content',
			'priority'      => 65,
		)
	);
	// Gallery grid spacing
	$wp_customize->add_setting( 'sovenco_g_lightbox',
		array(
			'sanitize_callback' => 'sovenco_sanitize_checkbox',
			'default'           => 1,
		)
	);
	$wp_customize->add_control( 'sovenco_g_lightbox',
		array(
			'label'     	=> esc_html__('Enable Lightbox', 'sovenco'),
			'section' 		=> 'sovenco_gallery_content',
			'priority'      => 70,
			'type'          => 'checkbox',
		)
	);

    // Gallery readmore link
    $wp_customize->add_setting( 'sovenco_g_readmore_link',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => '',
        )
    );
    $wp_customize->add_control( 'sovenco_g_readmore_link',
        array(
            'label'     	=> esc_html__('Read More Link', 'sovenco'),
            'section' 		=> 'sovenco_gallery_content',
            'priority'      => 90,
            'type'          => 'text',
        )
    );

    $wp_customize->add_setting( 'sovenco_g_readmore_text',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => esc_html__('View More', 'sovenco'),
        )
    );
    $wp_customize->add_control( 'sovenco_g_readmore_text',
        array(
            'label'     	=> esc_html__('Read More Text', 'sovenco'),
            'section' 		=> 'sovenco_gallery_content',
            'priority'      => 100,
            'type'          => 'text',
        )
    );


	/*------------------------------------------------------------------------*/
    /*  Section: About
    /*------------------------------------------------------------------------*/
    $wp_customize->add_panel( 'sovenco_about' ,
		array(
			'priority'        => 160,
			'title'           => esc_html__( 'Section: About', 'sovenco' ),
			'description'     => '',
			'active_callback' => 'sovenco_showon_frontpage'
		)
	);

	$wp_customize->add_section( 'sovenco_about_settings' ,
		array(
			'priority'    => 3,
			'title'       => esc_html__( 'Section Settings', 'sovenco' ),
			'description' => '',
			'panel'       => 'sovenco_about',
		)
	);

		// Show Content
		$wp_customize->add_setting( 'sovenco_about_disable',
			array(
				'sanitize_callback' => 'sovenco_sanitize_checkbox',
				'default'           => '',
			)
		);
		$wp_customize->add_control( 'sovenco_about_disable',
			array(
				'type'        => 'checkbox',
				'label'       => esc_html__('Hide this section?', 'sovenco'),
				'section'     => 'sovenco_about_settings',
				'description' => esc_html__('Check this box to hide this section.', 'sovenco'),
			)
		);

		// Section ID
		$wp_customize->add_setting( 'sovenco_about_id',
			array(
				'sanitize_callback' => 'sovenco_sanitize_text',
				'default'           => esc_html__('about', 'sovenco'),
			)
		);
		$wp_customize->add_control( 'sovenco_about_id',
			array(
				'label' 		=> esc_html__('Section ID:', 'sovenco'),
				'section' 		=> 'sovenco_about_settings',
				'description'   => esc_html__( 'The section id, we will use this for link anchor.', 'sovenco' )
			)
		);

		// Title
		$wp_customize->add_setting( 'sovenco_about_title',
			array(
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => esc_html__('About Us', 'sovenco'),
			)
		);
		$wp_customize->add_control( 'sovenco_about_title',
			array(
				'label' 		=> esc_html__('Section Title', 'sovenco'),
				'section' 		=> 'sovenco_about_settings',
				'description'   => '',
			)
		);

		// Sub Title
		$wp_customize->add_setting( 'sovenco_about_subtitle',
			array(
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => esc_html__('Section subtitle', 'sovenco'),
			)
		);
		$wp_customize->add_control( 'sovenco_about_subtitle',
			array(
				'label' 		=> esc_html__('Section Subtitle', 'sovenco'),
				'section' 		=> 'sovenco_about_settings',
				'description'   => '',
			)
		);

		// Description
		$wp_customize->add_setting( 'sovenco_about_desc',
			array(
				'sanitize_callback' => 'sovenco_sanitize_text',
				'default'           => '',
			)
		);
		$wp_customize->add_control( new sovenco_Editor_Custom_Control(
			$wp_customize,
			'sovenco_about_desc',
			array(
				'label' 		=> esc_html__('Section Description', 'sovenco'),
				'section' 		=> 'sovenco_about_settings',
				'description'   => '',
			)
		));


	$wp_customize->add_section( 'sovenco_about_content' ,
		array(
			'priority'    => 6,
			'title'       => esc_html__( 'Section Content', 'sovenco' ),
			'description' => '',
			'panel'       => 'sovenco_about',
		)
	);

		// Order & Stlying
		$wp_customize->add_setting(
			'sovenco_about_boxes',
			array(
				//'default' => '',
				'sanitize_callback' => 'sovenco_sanitize_repeatable_data_field',
				'transport' => 'refresh', // refresh or postMessage
			) );


			$wp_customize->add_control(
				new sovenco_Customize_Repeatable_Control(
					$wp_customize,
					'sovenco_about_boxes',
					array(
						'label' 		=> esc_html__('About content page', 'sovenco'),
						'description'   => '',
						'section'       => 'sovenco_about_content',
						'live_title_id' => 'content_page', // apply for unput text and textarea only
						'title_format'  => esc_html__('[live_title]', 'sovenco'), // [live_title]
						'max_item'      => 3, // Maximum item can add
                        'limited_msg' 	=> wp_kses_post( 'Upgrade to <a target="_blank" href="https://www.sovenco.com">sovenco Plus</a> to be able to add more items and unlock other premium features!', 'sovenco' ),
						//'allow_unlimited' => false, // Maximum item can add

						'fields'    => array(
							'content_page'  => array(
								'title' => esc_html__('Select a page', 'sovenco'),
								'type'  =>'select',
								'options' => $option_pages
							),
							'hide_title'  => array(
								'title' => esc_html__('Hide item title', 'sovenco'),
								'type'  =>'checkbox',
							),
							'enable_link'  => array(
								'title' => esc_html__('Link to single page', 'sovenco'),
								'type'  =>'checkbox',
							),
						),

					)
				)
			);

            // About content source
            $wp_customize->add_setting( 'sovenco_about_content_source',
                array(
                    'sanitize_callback' => 'sanitize_text_field',
                    'default'           => 'content',
                )
            );

            $wp_customize->add_control( 'sovenco_about_content_source',
                array(
                    'label' 		=> esc_html__('Item content source', 'sovenco'),
                    'section' 		=> 'sovenco_about_content',
                    'description'   => '',
                    'type'          => 'select',
                    'choices'       => array(
                        'content' => esc_html__( 'Full Page Content', 'sovenco' ),
                        'excerpt' => esc_html__( 'Page Excerpt', 'sovenco' ),
                    ),
                )
            );


    /*------------------------------------------------------------------------*/
    /*  Section: Features
    /*------------------------------------------------------------------------*/
    $wp_customize->add_panel( 'sovenco_features' ,
        array(
            'priority'        => 150,
            'title'           => esc_html__( 'Section: Features', 'sovenco' ),
            'description'     => '',
            'active_callback' => 'sovenco_showon_frontpage'
        )
    );

    $wp_customize->add_section( 'sovenco_features_settings' ,
        array(
            'priority'    => 3,
            'title'       => esc_html__( 'Section Settings', 'sovenco' ),
            'description' => '',
            'panel'       => 'sovenco_features',
        )
    );

    // Show Content
    $wp_customize->add_setting( 'sovenco_features_disable',
        array(
            'sanitize_callback' => 'sovenco_sanitize_checkbox',
            'default'           => '',
        )
    );
    $wp_customize->add_control( 'sovenco_features_disable',
        array(
            'type'        => 'checkbox',
            'label'       => esc_html__('Hide this section?', 'sovenco'),
            'section'     => 'sovenco_features_settings',
            'description' => esc_html__('Check this box to hide this section.', 'sovenco'),
        )
    );

    // Section ID
    $wp_customize->add_setting( 'sovenco_features_id',
        array(
            'sanitize_callback' => 'sovenco_sanitize_text',
            'default'           => esc_html__('features', 'sovenco'),
        )
    );
    $wp_customize->add_control( 'sovenco_features_id',
        array(
            'label' 		=> esc_html__('Section ID:', 'sovenco'),
            'section' 		=> 'sovenco_features_settings',
            'description'   => esc_html__( 'The section id, we will use this for link anchor.', 'sovenco' )
        )
    );

    // Title
    $wp_customize->add_setting( 'sovenco_features_title',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => esc_html__('Features', 'sovenco'),
        )
    );
    $wp_customize->add_control( 'sovenco_features_title',
        array(
            'label' 		=> esc_html__('Section Title', 'sovenco'),
            'section' 		=> 'sovenco_features_settings',
            'description'   => '',
        )
    );

    // Sub Title
    $wp_customize->add_setting( 'sovenco_features_subtitle',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => esc_html__('Section subtitle', 'sovenco'),
        )
    );
    $wp_customize->add_control( 'sovenco_features_subtitle',
        array(
            'label' 		=> esc_html__('Section Subtitle', 'sovenco'),
            'section' 		=> 'sovenco_features_settings',
            'description'   => '',
        )
    );

    // Description
    $wp_customize->add_setting( 'sovenco_features_desc',
        array(
            'sanitize_callback' => 'sovenco_sanitize_text',
            'default'           => '',
        )
    );
    $wp_customize->add_control( new sovenco_Editor_Custom_Control(
        $wp_customize,
        'sovenco_features_desc',
        array(
            'label' 		=> esc_html__('Section Description', 'sovenco'),
            'section' 		=> 'sovenco_features_settings',
            'description'   => '',
        )
    ));

    // Features layout
    $wp_customize->add_setting( 'sovenco_features_layout',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => '3',
        )
    );

    $wp_customize->add_control( 'sovenco_features_layout',
        array(
            'label' 		=> esc_html__('Features Layout Setting', 'sovenco'),
            'section' 		=> 'sovenco_features_settings',
            'description'   => '',
            'type'          => 'select',
            'choices'       => array(
                '3' => esc_html__( '4 Columns', 'sovenco' ),
                '4' => esc_html__( '3 Columns', 'sovenco' ),
                '6' => esc_html__( '2 Columns', 'sovenco' ),
            ),
        )
    );


    $wp_customize->add_section( 'sovenco_features_content' ,
        array(
            'priority'    => 6,
            'title'       => esc_html__( 'Section Content', 'sovenco' ),
            'description' => '',
            'panel'       => 'sovenco_features',
        )
    );

    // Order & Styling
    $wp_customize->add_setting(
        'sovenco_features_boxes',
        array(
            //'default' => '',
            'sanitize_callback' => 'sovenco_sanitize_repeatable_data_field',
            'transport' => 'refresh', // refresh or postMessage
        ) );

    $wp_customize->add_control(
        new sovenco_Customize_Repeatable_Control(
            $wp_customize,
            'sovenco_features_boxes',
            array(
                'label' 		=> esc_html__('Features content', 'sovenco'),
                'description'   => '',
                'section'       => 'sovenco_features_content',
                'live_title_id' => 'title', // apply for unput text and textarea only
                'title_format'  => esc_html__('[live_title]', 'sovenco'), // [live_title]
                'max_item'      => 4, // Maximum item can add
                'limited_msg' 	=> wp_kses_post( 'Upgrade to <a target="_blank" href="https://www.sovenco.com">sovenco Plus</a> to be able to add more items and unlock other premium features!', 'sovenco' ),
                'fields'    => array(
                    'title'  => array(
                        'title' => esc_html__('Title', 'sovenco'),
                        'type'  =>'text',
                    ),
					'icon_type'  => array(
						'title' => esc_html__('Custom icon', 'sovenco'),
						'type'  =>'select',
						'options' => array(
							'icon' => esc_html__('Icon', 'sovenco'),
							'image' => esc_html__('image', 'sovenco'),
						),
					),
                    'icon'  => array(
                        'title' => esc_html__('Icon', 'sovenco'),
                        'type'  =>'icon',
						'required' => array( 'icon_type', '=', 'icon' ),
                    ),
					'image'  => array(
						'title' => esc_html__('Image', 'sovenco'),
						'type'  =>'media',
						'required' => array( 'icon_type', '=', 'image' ),
					),
                    'desc'  => array(
                        'title' => esc_html__('Description', 'sovenco'),
                        'type'  =>'editor',
                    ),
                    'link'  => array(
                        'title' => esc_html__('Custom Link', 'sovenco'),
                        'type'  =>'text',
                    ),
                ),

            )
        )
    );

    // About content source
    $wp_customize->add_setting( 'sovenco_about_content_source',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => 'content',
        )
    );

    $wp_customize->add_control( 'sovenco_about_content_source',
        array(
            'label' 		=> esc_html__('Item content source', 'sovenco'),
            'section' 		=> 'sovenco_about_content',
            'description'   => '',
            'type'          => 'select',
            'choices'       => array(
                'content' => esc_html__( 'Full Page Content', 'sovenco' ),
                'excerpt' => esc_html__( 'Page Excerpt', 'sovenco' ),
            ),
        )
    );


    /*------------------------------------------------------------------------*/
    /*  Section: Services
    /*------------------------------------------------------------------------*/
    $wp_customize->add_panel( 'sovenco_services' ,
		array(
			'priority'        => 170,
			'title'           => esc_html__( 'Section: Services', 'sovenco' ),
			'description'     => '',
			'active_callback' => 'sovenco_showon_frontpage'
		)
	);

	$wp_customize->add_section( 'sovenco_service_settings' ,
		array(
			'priority'    => 3,
			'title'       => esc_html__( 'Section Settings', 'sovenco' ),
			'description' => '',
			'panel'       => 'sovenco_services',
		)
	);

		// Show Content
		$wp_customize->add_setting( 'sovenco_services_disable',
			array(
				'sanitize_callback' => 'sovenco_sanitize_checkbox',
				'default'           => '',
			)
		);
		$wp_customize->add_control( 'sovenco_services_disable',
			array(
				'type'        => 'checkbox',
				'label'       => esc_html__('Hide this section?', 'sovenco'),
				'section'     => 'sovenco_service_settings',
				'description' => esc_html__('Check this box to hide this section.', 'sovenco'),
			)
		);

		// Section ID
		$wp_customize->add_setting( 'sovenco_services_id',
			array(
				'sanitize_callback' => 'sovenco_sanitize_text',
				'default'           => esc_html__('services', 'sovenco'),
			)
		);
		$wp_customize->add_control( 'sovenco_services_id',
			array(
				'label'     => esc_html__('Section ID:', 'sovenco'),
				'section' 		=> 'sovenco_service_settings',
				'description'   => 'The section id, we will use this for link anchor.'
			)
		);

		// Title
		$wp_customize->add_setting( 'sovenco_services_title',
			array(
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => esc_html__('Our Services', 'sovenco'),
			)
		);
		$wp_customize->add_control( 'sovenco_services_title',
			array(
				'label'     => esc_html__('Section Title', 'sovenco'),
				'section' 		=> 'sovenco_service_settings',
				'description'   => '',
			)
		);

		// Sub Title
		$wp_customize->add_setting( 'sovenco_services_subtitle',
			array(
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => esc_html__('Section subtitle', 'sovenco'),
			)
		);
		$wp_customize->add_control( 'sovenco_services_subtitle',
			array(
				'label'     => esc_html__('Section Subtitle', 'sovenco'),
				'section' 		=> 'sovenco_service_settings',
				'description'   => '',
			)
		);

        // Description
        $wp_customize->add_setting( 'sovenco_services_desc',
            array(
                'sanitize_callback' => 'sovenco_sanitize_text',
                'default'           => '',
            )
        );
        $wp_customize->add_control( new sovenco_Editor_Custom_Control(
            $wp_customize,
            'sovenco_services_desc',
            array(
                'label' 		=> esc_html__('Section Description', 'sovenco'),
                'section' 		=> 'sovenco_service_settings',
                'description'   => '',
            )
        ));


        // Services layout
        $wp_customize->add_setting( 'sovenco_service_layout',
            array(
                'sanitize_callback' => 'sanitize_text_field',
                'default'           => '6',
            )
        );

        $wp_customize->add_control( 'sovenco_service_layout',
            array(
                'label' 		=> esc_html__('Services Layout Setting', 'sovenco'),
                'section' 		=> 'sovenco_service_settings',
                'description'   => '',
                'type'          => 'select',
                'choices'       => array(
                    '3' => esc_html__( '4 Columns', 'sovenco' ),
                    '4' => esc_html__( '3 Columns', 'sovenco' ),
                    '6' => esc_html__( '2 Columns', 'sovenco' ),
                    '12' => esc_html__( '1 Column', 'sovenco' ),
                ),
            )
        );


	$wp_customize->add_section( 'sovenco_service_content' ,
		array(
			'priority'    => 6,
			'title'       => esc_html__( 'Section Content', 'sovenco' ),
			'description' => '',
			'panel'       => 'sovenco_services',
		)
	);

		// Section service content.
		$wp_customize->add_setting(
			'sovenco_services',
			array(
				'sanitize_callback' => 'sovenco_sanitize_repeatable_data_field',
				'transport' => 'refresh', // refresh or postMessage
			) );


		$wp_customize->add_control(
			new sovenco_Customize_Repeatable_Control(
				$wp_customize,
				'sovenco_services',
				array(
					'label'     	=> esc_html__('Service content', 'sovenco'),
					'description'   => '',
					'section'       => 'sovenco_service_content',
					'live_title_id' => 'content_page', // apply for unput text and textarea only
					'title_format'  => esc_html__('[live_title]', 'sovenco'), // [live_title]
					'max_item'      => 4, // Maximum item can add
                    'limited_msg' 	=> wp_kses_post( 'Upgrade to <a target="_blank" href="https://www.sovenco.com">sovenco Plus</a> to be able to add more items and unlock other premium features!', 'sovenco' ),

					'fields'    => array(
						'icon_type'  => array(
							'title' => esc_html__('Custom icon', 'sovenco'),
							'type'  =>'select',
							'options' => array(
								'icon' => esc_html__('Icon', 'sovenco'),
								'image' => esc_html__('image', 'sovenco'),
							),
						),
						'icon'  => array(
							'title' => esc_html__('Icon', 'sovenco'),
							'type'  =>'icon',
							'required' => array( 'icon_type', '=', 'icon' ),
						),
						'image'  => array(
							'title' => esc_html__('Image', 'sovenco'),
							'type'  =>'media',
							'required' => array( 'icon_type', '=', 'image' ),
						),

						'content_page'  => array(
							'title' => esc_html__('Select a page', 'sovenco'),
							'type'  =>'select',
							'options' => $option_pages
						),
						'enable_link'  => array(
							'title' => esc_html__('Link to single page', 'sovenco'),
							'type'  =>'checkbox',
						),
					),

				)
			)
		);

	/*------------------------------------------------------------------------*/
    /*  Section: Counter
    /*------------------------------------------------------------------------*/
	$wp_customize->add_panel( 'sovenco_counter' ,
		array(
			'priority'        => 210,
			'title'           => esc_html__( 'Section: Counter', 'sovenco' ),
			'description'     => '',
			'active_callback' => 'sovenco_showon_frontpage'
		)
	);

	$wp_customize->add_section( 'sovenco_counter_settings' ,
		array(
			'priority'    => 3,
			'title'       => esc_html__( 'Section Settings', 'sovenco' ),
			'description' => '',
			'panel'       => 'sovenco_counter',
		)
	);
		// Show Content
		$wp_customize->add_setting( 'sovenco_counter_disable',
			array(
				'sanitize_callback' => 'sovenco_sanitize_checkbox',
				'default'           => '',
			)
		);
		$wp_customize->add_control( 'sovenco_counter_disable',
			array(
				'type'        => 'checkbox',
				'label'       => esc_html__('Hide this section?', 'sovenco'),
				'section'     => 'sovenco_counter_settings',
				'description' => esc_html__('Check this box to hide this section.', 'sovenco'),
			)
		);

		// Section ID
		$wp_customize->add_setting( 'sovenco_counter_id',
			array(
				'sanitize_callback' => 'sovenco_sanitize_text',
				'default'           => esc_html__('counter', 'sovenco'),
			)
		);
		$wp_customize->add_control( 'sovenco_counter_id',
			array(
				'label'     	=> esc_html__('Section ID:', 'sovenco'),
				'section' 		=> 'sovenco_counter_settings',
				'description'   => esc_html__( 'The section id, we will use this for link anchor.', 'sovenco' )
			)
		);

		// Title
		$wp_customize->add_setting( 'sovenco_counter_title',
			array(
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => esc_html__('Our Numbers', 'sovenco'),
			)
		);
		$wp_customize->add_control( 'sovenco_counter_title',
			array(
				'label'     	=> esc_html__('Section Title', 'sovenco'),
				'section' 		=> 'sovenco_counter_settings',
				'description'   => '',
			)
		);

		// Sub Title
		$wp_customize->add_setting( 'sovenco_counter_subtitle',
			array(
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => esc_html__('Section subtitle', 'sovenco'),
			)
		);
		$wp_customize->add_control( 'sovenco_counter_subtitle',
			array(
				'label'     	=> esc_html__('Section Subtitle', 'sovenco'),
				'section' 		=> 'sovenco_counter_settings',
				'description'   => '',
			)
		);

        // Description
        $wp_customize->add_setting( 'sovenco_counter_desc',
            array(
                'sanitize_callback' => 'sovenco_sanitize_text',
                'default'           => '',
            )
        );
        $wp_customize->add_control( new sovenco_Editor_Custom_Control(
            $wp_customize,
            'sovenco_counter_desc',
            array(
                'label' 		=> esc_html__('Section Description', 'sovenco'),
                'section' 		=> 'sovenco_counter_settings',
                'description'   => '',
            )
        ));

	$wp_customize->add_section( 'sovenco_counter_content' ,
		array(
			'priority'    => 6,
			'title'       => esc_html__( 'Section Content', 'sovenco' ),
			'description' => '',
			'panel'       => 'sovenco_counter',
		)
	);

	// Order & Styling
	$wp_customize->add_setting(
		'sovenco_counter_boxes',
		array(
			'sanitize_callback' => 'sovenco_sanitize_repeatable_data_field',
			'transport' => 'refresh', // refresh or postMessage
		) );


		$wp_customize->add_control(
			new sovenco_Customize_Repeatable_Control(
				$wp_customize,
				'sovenco_counter_boxes',
				array(
					'label'     	=> esc_html__('Counter content', 'sovenco'),
					'description'   => '',
					'section'       => 'sovenco_counter_content',
					'live_title_id' => 'title', // apply for unput text and textarea only
					'title_format'  => esc_html__('[live_title]', 'sovenco'), // [live_title]
					'max_item'      => 4, // Maximum item can add
                    'limited_msg' 	=> wp_kses_post( 'Upgrade to <a target="_blank" href="https://www.sovenco.com">sovenco Plus</a> to be able to add more items and unlock other premium features!', 'sovenco' ),
                    'fields'    => array(
						'title' => array(
							'title' => esc_html__('Title', 'sovenco'),
							'type'  =>'text',
							'desc'  => '',
							'default' => esc_html__( 'Your counter label', 'sovenco' ),
						),
						'number' => array(
							'title' => esc_html__('Number', 'sovenco'),
							'type'  =>'text',
							'default' => 99,
						),
						'unit_before'  => array(
							'title' => esc_html__('Before number', 'sovenco'),
							'type'  =>'text',
							'default' => '',
						),
						'unit_after'  => array(
							'title' => esc_html__('After number', 'sovenco'),
							'type'  =>'text',
							'default' => '',
						),
					),

				)
			)
		);

	/*------------------------------------------------------------------------*/
    /*  Section: Team
    /*------------------------------------------------------------------------*/
    $wp_customize->add_panel( 'sovenco_team' ,
		array(
			'priority'        => 250,
			'title'           => esc_html__( 'Section: Team', 'sovenco' ),
			'description'     => '',
			'active_callback' => 'sovenco_showon_frontpage'
		)
	);

	$wp_customize->add_section( 'sovenco_team_settings' ,
		array(
			'priority'    => 3,
			'title'       => esc_html__( 'Section Settings', 'sovenco' ),
			'description' => '',
			'panel'       => 'sovenco_team',
		)
	);

		// Show Content
		$wp_customize->add_setting( 'sovenco_team_disable',
			array(
				'sanitize_callback' => 'sovenco_sanitize_checkbox',
				'default'           => '',
			)
		);
		$wp_customize->add_control( 'sovenco_team_disable',
			array(
				'type'        => 'checkbox',
				'label'       => esc_html__('Hide this section?', 'sovenco'),
				'section'     => 'sovenco_team_settings',
				'description' => esc_html__('Check this box to hide this section.', 'sovenco'),
			)
		);
		// Section ID
		$wp_customize->add_setting( 'sovenco_team_id',
			array(
				'sanitize_callback' => 'sovenco_sanitize_text',
				'default'           => esc_html__('team', 'sovenco'),
			)
		);
		$wp_customize->add_control( 'sovenco_team_id',
			array(
				'label'     	=> esc_html__('Section ID:', 'sovenco'),
				'section' 		=> 'sovenco_team_settings',
				'description'   => 'The section id, we will use this for link anchor.'
			)
		);

		// Title
		$wp_customize->add_setting( 'sovenco_team_title',
			array(
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => esc_html__('Our Team', 'sovenco'),
			)
		);
		$wp_customize->add_control( 'sovenco_team_title',
			array(
				'label'    		=> esc_html__('Section Title', 'sovenco'),
				'section' 		=> 'sovenco_team_settings',
				'description'   => '',
			)
		);

		// Sub Title
		$wp_customize->add_setting( 'sovenco_team_subtitle',
			array(
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => esc_html__('Section subtitle', 'sovenco'),
			)
		);
		$wp_customize->add_control( 'sovenco_team_subtitle',
			array(
				'label'     => esc_html__('Section Subtitle', 'sovenco'),
				'section' 		=> 'sovenco_team_settings',
				'description'   => '',
			)
		);

        // Description
        $wp_customize->add_setting( 'sovenco_team_desc',
            array(
                'sanitize_callback' => 'sovenco_sanitize_text',
                'default'           => '',
            )
        );
        $wp_customize->add_control( new sovenco_Editor_Custom_Control(
            $wp_customize,
            'sovenco_team_desc',
            array(
                'label' 		=> esc_html__('Section Description', 'sovenco'),
                'section' 		=> 'sovenco_team_settings',
                'description'   => '',
            )
        ));

        // Team layout
        $wp_customize->add_setting( 'sovenco_team_layout',
            array(
                'sanitize_callback' => 'sanitize_text_field',
                'default'           => '3',
            )
        );

        $wp_customize->add_control( 'sovenco_team_layout',
            array(
                'label' 		=> esc_html__('Team Layout Setting', 'sovenco'),
                'section' 		=> 'sovenco_team_settings',
                'description'   => '',
                'type'          => 'select',
                'choices'       => array(
					'3' => esc_html__( '4 Columns', 'sovenco' ),
					'4' => esc_html__( '3 Columns', 'sovenco' ),
					'6' => esc_html__( '2 Columns', 'sovenco' ),
                ),
            )
        );

	$wp_customize->add_section( 'sovenco_team_content' ,
		array(
			'priority'    => 6,
			'title'       => esc_html__( 'Section Content', 'sovenco' ),
			'description' => '',
			'panel'       => 'sovenco_team',
		)
	);

		// Team member settings
		$wp_customize->add_setting(
			'sovenco_team_members',
			array(
				'sanitize_callback' => 'sovenco_sanitize_repeatable_data_field',
				'transport' => 'refresh', // refresh or postMessage
			) );


		$wp_customize->add_control(
			new sovenco_Customize_Repeatable_Control(
				$wp_customize,
				'sovenco_team_members',
				array(
					'label'     => esc_html__('Team members', 'sovenco'),
					'description'   => '',
					'section'       => 'sovenco_team_content',
					//'live_title_id' => 'user_id', // apply for unput text and textarea only
					'title_format'  => esc_html__( '[live_title]', 'sovenco'), // [live_title]
					'max_item'      => 4, // Maximum item can add
                    'limited_msg' 	=> wp_kses_post( 'Upgrade to <a target="_blank" href="https://www.sovenco.com">sovenco Plus</a> to be able to add more items and unlock other premium features!', 'sovenco' ),
                    'fields'    => array(
						'user_id' => array(
							'title' => esc_html__('User media', 'sovenco'),
							'type'  =>'media',
							'desc'  => '',
						),
                        'link' => array(
                            'title' => esc_html__('Custom Link', 'sovenco'),
                            'type'  =>'text',
                            'desc'  => '',
                        ),
					),

				)
			)
		);



	/*------------------------------------------------------------------------*/
    /*  Section: News
    /*------------------------------------------------------------------------*/
    $wp_customize->add_panel( 'sovenco_news' ,
		array(
			'priority'        => 260,
			'title'           => esc_html__( 'Section: News', 'sovenco' ),
			'description'     => '',
			'active_callback' => 'sovenco_showon_frontpage'
		)
	);

	$wp_customize->add_section( 'sovenco_news_settings' ,
		array(
			'priority'    => 3,
			'title'       => esc_html__( 'Section Settings', 'sovenco' ),
			'description' => '',
			'panel'       => 'sovenco_news',
		)
	);

		// Show Content
		$wp_customize->add_setting( 'sovenco_news_disable',
			array(
				'sanitize_callback' => 'sovenco_sanitize_checkbox',
				'default'           => '',
			)
		);
		$wp_customize->add_control( 'sovenco_news_disable',
			array(
				'type'        => 'checkbox',
				'label'       => esc_html__('Hide this section?', 'sovenco'),
				'section'     => 'sovenco_news_settings',
				'description' => esc_html__('Check this box to hide this section.', 'sovenco'),
			)
		);

		// Section ID
		$wp_customize->add_setting( 'sovenco_news_id',
			array(
				'sanitize_callback' => 'sovenco_sanitize_text',
				'default'           => esc_html__('news', 'sovenco'),
			)
		);
		$wp_customize->add_control( 'sovenco_news_id',
			array(
				'label'     => esc_html__('Section ID:', 'sovenco'),
				'section' 		=> 'sovenco_news_settings',
				'description'   => esc_html__( 'The section id, we will use this for link anchor.', 'sovenco' )
			)
		);

		// Title
		$wp_customize->add_setting( 'sovenco_news_title',
			array(
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => esc_html__('Latest News', 'sovenco'),
			)
		);
		$wp_customize->add_control( 'sovenco_news_title',
			array(
				'label'     => esc_html__('Section Title', 'sovenco'),
				'section' 		=> 'sovenco_news_settings',
				'description'   => '',
			)
		);

		// Sub Title
		$wp_customize->add_setting( 'sovenco_news_subtitle',
			array(
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => esc_html__('Section subtitle', 'sovenco'),
			)
		);
		$wp_customize->add_control( 'sovenco_news_subtitle',
			array(
				'label'     => esc_html__('Section Subtitle', 'sovenco'),
				'section' 		=> 'sovenco_news_settings',
				'description'   => '',
			)
		);

        // Description
        $wp_customize->add_setting( 'sovenco_news_desc',
            array(
                'sanitize_callback' => 'sovenco_sanitize_text',
                'default'           => '',
            )
        );
        $wp_customize->add_control( new sovenco_Editor_Custom_Control(
            $wp_customize,
            'sovenco_news_desc',
            array(
                'label' 		=> esc_html__('Section Description', 'sovenco'),
                'section' 		=> 'sovenco_news_settings',
                'description'   => '',
            )
        ));

		// hr
		$wp_customize->add_setting( 'sovenco_news_settings_hr',
			array(
				'sanitize_callback' => 'sovenco_sanitize_text',
			)
		);
		$wp_customize->add_control( new sovenco_Misc_Control( $wp_customize, 'sovenco_news_settings_hr',
			array(
				'section'     => 'sovenco_news_settings',
				'type'        => 'hr'
			)
		));

		// Number of post to show.
		$wp_customize->add_setting( 'sovenco_news_number',
			array(
				'sanitize_callback' => 'sovenco_sanitize_number',
				'default'           => '3',
			)
		);
		$wp_customize->add_control( 'sovenco_news_number',
			array(
				'label'     	=> esc_html__('Number of post to show', 'sovenco'),
				'section' 		=> 'sovenco_news_settings',
				'description'   => '',
			)
		);

		// Blog Button
		$wp_customize->add_setting( 'sovenco_news_more_link',
			array(
				'sanitize_callback' => 'esc_url',
				'default'           => '#',
			)
		);
		$wp_customize->add_control( 'sovenco_news_more_link',
			array(
				'label'       => esc_html__('More News button link', 'sovenco'),
				'section'     => 'sovenco_news_settings',
				'description' => esc_html__(  'It should be your blog page link.', 'sovenco' )
			)
		);
		$wp_customize->add_setting( 'sovenco_news_more_text',
			array(
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => esc_html__('Read Our Blog', 'sovenco'),
			)
		);
		$wp_customize->add_control( 'sovenco_news_more_text',
			array(
				'label'     	=> esc_html__('More News Button Text', 'sovenco'),
				'section' 		=> 'sovenco_news_settings',
				'description'   => '',
			)
		);

	/*------------------------------------------------------------------------*/
    /*  Section: Contact
    /*------------------------------------------------------------------------*/
    $wp_customize->add_panel( 'sovenco_contact' ,
		array(
			'priority'        => 270,
			'title'           => esc_html__( 'Section: Contact', 'sovenco' ),
			'description'     => '',
			'active_callback' => 'sovenco_showon_frontpage'
		)
	);

	$wp_customize->add_section( 'sovenco_contact_settings' ,
		array(
			'priority'    => 3,
			'title'       => esc_html__( 'Section Settings', 'sovenco' ),
			'description' => '',
			'panel'       => 'sovenco_contact',
		)
	);

		// Show Content
		$wp_customize->add_setting( 'sovenco_contact_disable',
			array(
				'sanitize_callback' => 'sovenco_sanitize_checkbox',
				'default'           => '',
			)
		);
		$wp_customize->add_control( 'sovenco_contact_disable',
			array(
				'type'        => 'checkbox',
				'label'       => esc_html__('Hide this section?', 'sovenco'),
				'section'     => 'sovenco_contact_settings',
				'description' => esc_html__('Check this box to hide this section.', 'sovenco'),
			)
		);

		// Section ID
		$wp_customize->add_setting( 'sovenco_contact_id',
			array(
				'sanitize_callback' => 'sovenco_sanitize_text',
				'default'           => esc_html__('contact', 'sovenco'),
			)
		);
		$wp_customize->add_control( 'sovenco_contact_id',
			array(
				'label'     => esc_html__('Section ID:', 'sovenco'),
				'section' 		=> 'sovenco_contact_settings',
				'description'   => esc_html__( 'The section id, we will use this for link anchor.', 'sovenco' )
			)
		);

		// Title
		$wp_customize->add_setting( 'sovenco_contact_title',
			array(
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => esc_html__('Get in touch', 'sovenco'),
			)
		);
		$wp_customize->add_control( 'sovenco_contact_title',
			array(
				'label'     => esc_html__('Section Title', 'sovenco'),
				'section' 		=> 'sovenco_contact_settings',
				'description'   => '',
			)
		);

		// Sub Title
		$wp_customize->add_setting( 'sovenco_contact_subtitle',
			array(
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => esc_html__('Section subtitle', 'sovenco'),
			)
		);
		$wp_customize->add_control( 'sovenco_contact_subtitle',
			array(
				'label'     => esc_html__('Section Subtitle', 'sovenco'),
				'section' 		=> 'sovenco_contact_settings',
				'description'   => '',
			)
		);

        // Description
        $wp_customize->add_setting( 'sovenco_contact_desc',
            array(
                'sanitize_callback' => 'sovenco_sanitize_text',
                'default'           => '',
            )
        );
        $wp_customize->add_control( new sovenco_Editor_Custom_Control(
            $wp_customize,
            'sovenco_contact_desc',
            array(
                'label' 		=> esc_html__('Section Description', 'sovenco'),
                'section' 		=> 'sovenco_contact_settings',
                'description'   => '',
            )
        ));


	$wp_customize->add_section( 'sovenco_contact_content' ,
		array(
			'priority'    => 6,
			'title'       => esc_html__( 'Section Content', 'sovenco' ),
			'description' => '',
			'panel'       => 'sovenco_contact',
		)
	);
		// Contact form 7 guide.
		$wp_customize->add_setting( 'sovenco_contact_cf7_guide',
			array(
				'sanitize_callback' => 'sovenco_sanitize_text'
			)
		);
		$wp_customize->add_control( new sovenco_Misc_Control( $wp_customize, 'sovenco_contact_cf7_guide',
			array(
				'section'     => 'sovenco_contact_content',
				'type'        => 'custom_message',
				'description' => wp_kses_post( 'In order to display contact form please install <a target="_blank" href="https://wordpress.org/plugins/contact-form-7/">Contact Form 7</a> plugin and then copy the contact form shortcode and paste it here, the shortcode will be like this <code>[contact-form-7 id="xxxx" title="Example Contact Form"]</code>', 'sovenco' )
			)
		));

		// Contact Form 7 Shortcode
		$wp_customize->add_setting( 'sovenco_contact_cf7',
			array(
				'sanitize_callback' => 'sovenco_sanitize_text',
				'default'           => '',
			)
		);
		$wp_customize->add_control( 'sovenco_contact_cf7',
			array(
				'label'     	=> esc_html__('Contact Form 7 Shortcode.', 'sovenco'),
				'section' 		=> 'sovenco_contact_content',
				'description'   => '',
			)
		);

		// Show CF7
		$wp_customize->add_setting( 'sovenco_contact_cf7_disable',
			array(
				'sanitize_callback' => 'sovenco_sanitize_checkbox',
				'default'           => '',
			)
		);
		$wp_customize->add_control( 'sovenco_contact_cf7_disable',
			array(
				'type'        => 'checkbox',
				'label'       => esc_html__('Hide contact form completely.', 'sovenco'),
				'section'     => 'sovenco_contact_content',
				'description' => esc_html__('Check this box to hide contact form.', 'sovenco'),
			)
		);

		// Contact Text
		$wp_customize->add_setting( 'sovenco_contact_text',
			array(
				'sanitize_callback' => 'sovenco_sanitize_text',
				'default'           => '',
			)
		);
		$wp_customize->add_control( new sovenco_Editor_Custom_Control(
			$wp_customize,
			'sovenco_contact_text',
			array(
				'label'     	=> esc_html__('Contact Text', 'sovenco'),
				'section' 		=> 'sovenco_contact_content',
				'description'   => '',
			)
		));

		// hr
		$wp_customize->add_setting( 'sovenco_contact_text_hr', array( 'sanitize_callback' => 'sovenco_sanitize_text' ) );
		$wp_customize->add_control( new sovenco_Misc_Control( $wp_customize, 'sovenco_contact_text_hr',
			array(
				'section'     => 'sovenco_contact_content',
				'type'        => 'hr'
			)
		));

		// Address Box
		$wp_customize->add_setting( 'sovenco_contact_address_title',
			array(
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => '',
			)
		);
		$wp_customize->add_control( 'sovenco_contact_address_title',
			array(
				'label'     	=> esc_html__('Contact Box Title', 'sovenco'),
				'section' 		=> 'sovenco_contact_content',
				'description'   => '',
			)
		);

		// Contact Text
		$wp_customize->add_setting( 'sovenco_contact_address',
			array(
				'sanitize_callback' => 'sovenco_sanitize_text',
				'default'           => '',
			)
		);
		$wp_customize->add_control( 'sovenco_contact_address',
			array(
				'label'     => esc_html__('Address', 'sovenco'),
				'section' 		=> 'sovenco_contact_content',
				'description'   => '',
			)
		);

		// Contact Phone
		$wp_customize->add_setting( 'sovenco_contact_phone',
			array(
				'sanitize_callback' => 'sovenco_sanitize_text',
				'default'           => '',
			)
		);
		$wp_customize->add_control( 'sovenco_contact_phone',
			array(
				'label'     	=> esc_html__('Phone', 'sovenco'),
				'section' 		=> 'sovenco_contact_content',
				'description'   => '',
			)
		);

		// Contact Email
		$wp_customize->add_setting( 'sovenco_contact_email',
			array(
				'sanitize_callback' => 'sanitize_email',
				'default'           => '',
			)
		);
		$wp_customize->add_control( 'sovenco_contact_email',
			array(
				'label'     	=> esc_html__('Email', 'sovenco'),
				'section' 		=> 'sovenco_contact_content',
				'description'   => '',
			)
		);

		// Contact Fax
		$wp_customize->add_setting( 'sovenco_contact_fax',
			array(
				'sanitize_callback' => 'sovenco_sanitize_text',
				'default'           => '',
			)
		);
		$wp_customize->add_control( 'sovenco_contact_fax',
			array(
				'label'     	=> esc_html__('Fax', 'sovenco'),
				'section' 		=> 'sovenco_contact_content',
				'description'   => '',
			)
		);

		/**
		 * Hook to add other customize
		 */
		do_action( 'sovenco_customize_after_register', $wp_customize );

}
add_action( 'customize_register', 'sovenco_customize_register' );
/**
 * Selective refresh
 */
require get_template_directory() . '/inc/customizer-selective-refresh.php';


/*------------------------------------------------------------------------*/
/*  sovenco Sanitize Functions.
/*------------------------------------------------------------------------*/

function sovenco_sanitize_file_url( $file_url ) {
	$output = '';
	$filetype = wp_check_filetype( $file_url );
	if ( $filetype["ext"] ) {
		$output = esc_url( $file_url );
	}
	return $output;
}


/**
 * Conditional to show more hero settings
 *
 * @param $control
 * @return bool
 */
function sovenco_hero_fullscreen_callback ( $control ) {
	if ( $control->manager->get_setting('sovenco_hero_fullscreen')->value() == '' ) {
        return true;
    } else {
        return false;
    }
}


function sovenco_sanitize_number( $input ) {
    return balanceTags( $input );
}

function sovenco_sanitize_hex_color( $color ) {
	if ( $color === '' ) {
		return '';
	}
	if ( preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) ) {
		return $color;
	}
	return null;
}

function sovenco_sanitize_checkbox( $input ) {
    if ( $input == 1 ) {
		return 1;
    } else {
		return 0;
    }
}

function sovenco_sanitize_text( $string ) {
	return wp_kses_post( balanceTags( $string ) );
}

function sovenco_sanitize_html_input( $string ) {
	return wp_kses_allowed_html( $string );
}

function sovenco_showon_frontpage() {
	return is_page_template( 'template-frontpage.php' );
}

function sovenco_gallery_source_validate( $validity, $value ){
	if ( ! class_exists( 'sovenco_PLus' ) ) {
		if ( $value != 'page' ) {
			$validity->add('notice', esc_html__('Upgrade to sovenco Plus to unlock this feature.', 'sovenco' ) );
		}
	}
	return $validity;
}
/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function sovenco_customize_preview_js() {
    wp_enqueue_script( 'sovenco_customizer_liveview', get_template_directory_uri() . '/assets/js/customizer-liveview.js', array( 'customize-preview', 'customize-selective-refresh' ), false, true );
}
add_action( 'customize_preview_init', 'sovenco_customize_preview_js', 65 );



add_action( 'customize_controls_enqueue_scripts', 'opneress_customize_js_settings' );
function opneress_customize_js_settings(){
    if ( ! function_exists( 'sovenco_get_actions_required' ) ) {
        return;
    }

    $actions = sovenco_get_actions_required();
    $number_action = $actions['number_notice'];

    wp_localize_script( 'customize-controls', 'sovenco_customizer_settings', array(
        'number_action' => $number_action,
        'is_plus_activated' => class_exists( 'sovenco_PLus' ) ? 'y' : 'n',
        'action_url' => admin_url( 'themes.php?page=ft_sovenco&tab=actions_required' ),
    ) );
}

/**
 * Customizer Icon picker
 */
function sovenco_customize_controls_enqueue_scripts(){
    wp_localize_script( 'customize-controls', 'C_Icon_Picker',
        apply_filters( 'c_icon_picker_js_setup',
            array(
                'search'    => esc_html__( 'Search', 'sovenco' ),
                'fonts' => array(
                    'font-awesome' => array(
                        // Name of icon
                        'name' => esc_html__( 'Font Awesome', 'sovenco' ),
                        // prefix class example for font-awesome fa-fa-{name}
                        'prefix' => 'fa',
                        // font url
                        'url' => esc_url( add_query_arg( array( 'ver'=> '4.7.0' ), get_template_directory_uri() .'/assets/css/font-awesome.min.css' ) ),
                        // Icon class name, separated by |
                        'icons' => 'fa-glass|fa-music|fa-search|fa-envelope-o|fa-heart|fa-star|fa-star-o|fa-user|fa-film|fa-th-large|fa-th|fa-th-list|fa-check|fa-times|fa-search-plus|fa-search-minus|fa-power-off|fa-signal|fa-cog|fa-trash-o|fa-home|fa-file-o|fa-clock-o|fa-road|fa-download|fa-arrow-circle-o-down|fa-arrow-circle-o-up|fa-inbox|fa-play-circle-o|fa-repeat|fa-refresh|fa-list-alt|fa-lock|fa-flag|fa-headphones|fa-volume-off|fa-volume-down|fa-volume-up|fa-qrcode|fa-barcode|fa-tag|fa-tags|fa-book|fa-bookmark|fa-print|fa-camera|fa-font|fa-bold|fa-italic|fa-text-height|fa-text-width|fa-align-left|fa-align-center|fa-align-right|fa-align-justify|fa-list|fa-outdent|fa-indent|fa-video-camera|fa-picture-o|fa-pencil|fa-map-marker|fa-adjust|fa-tint|fa-pencil-square-o|fa-share-square-o|fa-check-square-o|fa-arrows|fa-step-backward|fa-fast-backward|fa-backward|fa-play|fa-pause|fa-stop|fa-forward|fa-fast-forward|fa-step-forward|fa-eject|fa-chevron-left|fa-chevron-right|fa-plus-circle|fa-minus-circle|fa-times-circle|fa-check-circle|fa-question-circle|fa-info-circle|fa-crosshairs|fa-times-circle-o|fa-check-circle-o|fa-ban|fa-arrow-left|fa-arrow-right|fa-arrow-up|fa-arrow-down|fa-share|fa-expand|fa-compress|fa-plus|fa-minus|fa-asterisk|fa-exclamation-circle|fa-gift|fa-leaf|fa-fire|fa-eye|fa-eye-slash|fa-exclamation-triangle|fa-plane|fa-calendar|fa-random|fa-comment|fa-magnet|fa-chevron-up|fa-chevron-down|fa-retweet|fa-shopping-cart|fa-folder|fa-folder-open|fa-arrows-v|fa-arrows-h|fa-bar-chart|fa-twitter-square|fa-facebook-square|fa-camera-retro|fa-key|fa-cogs|fa-comments|fa-thumbs-o-up|fa-thumbs-o-down|fa-star-half|fa-heart-o|fa-sign-out|fa-linkedin-square|fa-thumb-tack|fa-external-link|fa-sign-in|fa-trophy|fa-github-square|fa-upload|fa-lemon-o|fa-phone|fa-square-o|fa-bookmark-o|fa-phone-square|fa-twitter|fa-facebook|fa-github|fa-unlock|fa-credit-card|fa-rss|fa-hdd-o|fa-bullhorn|fa-bell|fa-certificate|fa-hand-o-right|fa-hand-o-left|fa-hand-o-up|fa-hand-o-down|fa-arrow-circle-left|fa-arrow-circle-right|fa-arrow-circle-up|fa-arrow-circle-down|fa-globe|fa-wrench|fa-tasks|fa-filter|fa-briefcase|fa-arrows-alt|fa-users|fa-link|fa-cloud|fa-flask|fa-scissors|fa-files-o|fa-paperclip|fa-floppy-o|fa-square|fa-bars|fa-list-ul|fa-list-ol|fa-strikethrough|fa-underline|fa-table|fa-magic|fa-truck|fa-pinterest|fa-pinterest-square|fa-google-plus-square|fa-google-plus|fa-money|fa-caret-down|fa-caret-up|fa-caret-left|fa-caret-right|fa-columns|fa-sort|fa-sort-desc|fa-sort-asc|fa-envelope|fa-linkedin|fa-undo|fa-gavel|fa-tachometer|fa-comment-o|fa-comments-o|fa-bolt|fa-sitemap|fa-umbrella|fa-clipboard|fa-lightbulb-o|fa-exchange|fa-cloud-download|fa-cloud-upload|fa-user-md|fa-stethoscope|fa-suitcase|fa-bell-o|fa-coffee|fa-cutlery|fa-file-text-o|fa-building-o|fa-hospital-o|fa-ambulance|fa-medkit|fa-fighter-jet|fa-beer|fa-h-square|fa-plus-square|fa-angle-double-left|fa-angle-double-right|fa-angle-double-up|fa-angle-double-down|fa-angle-left|fa-angle-right|fa-angle-up|fa-angle-down|fa-desktop|fa-laptop|fa-tablet|fa-mobile|fa-circle-o|fa-quote-left|fa-quote-right|fa-spinner|fa-circle|fa-reply|fa-github-alt|fa-folder-o|fa-folder-open-o|fa-smile-o|fa-frown-o|fa-meh-o|fa-gamepad|fa-keyboard-o|fa-flag-o|fa-flag-checkered|fa-terminal|fa-code|fa-reply-all|fa-star-half-o|fa-location-arrow|fa-crop|fa-code-fork|fa-chain-broken|fa-question|fa-info|fa-exclamation|fa-superscript|fa-subscript|fa-eraser|fa-puzzle-piece|fa-microphone|fa-microphone-slash|fa-shield|fa-calendar-o|fa-fire-extinguisher|fa-rocket|fa-maxcdn|fa-chevron-circle-left|fa-chevron-circle-right|fa-chevron-circle-up|fa-chevron-circle-down|fa-html5|fa-css3|fa-anchor|fa-unlock-alt|fa-bullseye|fa-ellipsis-h|fa-ellipsis-v|fa-rss-square|fa-play-circle|fa-ticket|fa-minus-square|fa-minus-square-o|fa-level-up|fa-level-down|fa-check-square|fa-pencil-square|fa-external-link-square|fa-share-square|fa-compass|fa-caret-square-o-down|fa-caret-square-o-up|fa-caret-square-o-right|fa-eur|fa-gbp|fa-usd|fa-inr|fa-jpy|fa-rub|fa-krw|fa-btc|fa-file|fa-file-text|fa-sort-alpha-asc|fa-sort-alpha-desc|fa-sort-amount-asc|fa-sort-amount-desc|fa-sort-numeric-asc|fa-sort-numeric-desc|fa-thumbs-up|fa-thumbs-down|fa-youtube-square|fa-youtube|fa-xing|fa-xing-square|fa-youtube-play|fa-dropbox|fa-stack-overflow|fa-instagram|fa-flickr|fa-adn|fa-bitbucket|fa-bitbucket-square|fa-tumblr|fa-tumblr-square|fa-long-arrow-down|fa-long-arrow-up|fa-long-arrow-left|fa-long-arrow-right|fa-apple|fa-windows|fa-android|fa-linux|fa-dribbble|fa-skype|fa-foursquare|fa-trello|fa-female|fa-male|fa-gratipay|fa-sun-o|fa-moon-o|fa-archive|fa-bug|fa-vk|fa-weibo|fa-renren|fa-pagelines|fa-stack-exchange|fa-arrow-circle-o-right|fa-arrow-circle-o-left|fa-caret-square-o-left|fa-dot-circle-o|fa-wheelchair|fa-vimeo-square|fa-try|fa-plus-square-o|fa-space-shuttle|fa-slack|fa-envelope-square|fa-wordpress|fa-openid|fa-university|fa-graduation-cap|fa-yahoo|fa-google|fa-reddit|fa-reddit-square|fa-stumbleupon-circle|fa-stumbleupon|fa-delicious|fa-digg|fa-pied-piper-pp|fa-pied-piper-alt|fa-drupal|fa-joomla|fa-language|fa-fax|fa-building|fa-child|fa-paw|fa-spoon|fa-cube|fa-cubes|fa-behance|fa-behance-square|fa-steam|fa-steam-square|fa-recycle|fa-car|fa-taxi|fa-tree|fa-spotify|fa-deviantart|fa-soundcloud|fa-database|fa-file-pdf-o|fa-file-word-o|fa-file-excel-o|fa-file-powerpoint-o|fa-file-image-o|fa-file-archive-o|fa-file-audio-o|fa-file-video-o|fa-file-code-o|fa-vine|fa-codepen|fa-jsfiddle|fa-life-ring|fa-circle-o-notch|fa-rebel|fa-empire|fa-git-square|fa-git|fa-hacker-news|fa-tencent-weibo|fa-qq|fa-weixin|fa-paper-plane|fa-paper-plane-o|fa-history|fa-circle-thin|fa-header|fa-paragraph|fa-sliders|fa-share-alt|fa-share-alt-square|fa-bomb|fa-futbol-o|fa-tty|fa-binoculars|fa-plug|fa-slideshare|fa-twitch|fa-yelp|fa-newspaper-o|fa-wifi|fa-calculator|fa-paypal|fa-google-wallet|fa-cc-visa|fa-cc-mastercard|fa-cc-discover|fa-cc-amex|fa-cc-paypal|fa-cc-stripe|fa-bell-slash|fa-bell-slash-o|fa-trash|fa-copyright|fa-at|fa-eyedropper|fa-paint-brush|fa-birthday-cake|fa-area-chart|fa-pie-chart|fa-line-chart|fa-lastfm|fa-lastfm-square|fa-toggle-off|fa-toggle-on|fa-bicycle|fa-bus|fa-ioxhost|fa-angellist|fa-cc|fa-ils|fa-meanpath|fa-buysellads|fa-connectdevelop|fa-dashcube|fa-forumbee|fa-leanpub|fa-sellsy|fa-shirtsinbulk|fa-simplybuilt|fa-skyatlas|fa-cart-plus|fa-cart-arrow-down|fa-diamond|fa-ship|fa-user-secret|fa-motorcycle|fa-street-view|fa-heartbeat|fa-venus|fa-mars|fa-mercury|fa-transgender|fa-transgender-alt|fa-venus-double|fa-mars-double|fa-venus-mars|fa-mars-stroke|fa-mars-stroke-v|fa-mars-stroke-h|fa-neuter|fa-genderless|fa-facebook-official|fa-pinterest-p|fa-whatsapp|fa-server|fa-user-plus|fa-user-times|fa-bed|fa-viacoin|fa-train|fa-subway|fa-medium|fa-y-combinator|fa-optin-monster|fa-opencart|fa-expeditedssl|fa-battery-full|fa-battery-three-quarters|fa-battery-half|fa-battery-quarter|fa-battery-empty|fa-mouse-pointer|fa-i-cursor|fa-object-group|fa-object-ungroup|fa-sticky-note|fa-sticky-note-o|fa-cc-jcb|fa-cc-diners-club|fa-clone|fa-balance-scale|fa-hourglass-o|fa-hourglass-start|fa-hourglass-half|fa-hourglass-end|fa-hourglass|fa-hand-rock-o|fa-hand-paper-o|fa-hand-scissors-o|fa-hand-lizard-o|fa-hand-spock-o|fa-hand-pointer-o|fa-hand-peace-o|fa-trademark|fa-registered|fa-creative-commons|fa-gg|fa-gg-circle|fa-tripadvisor|fa-odnoklassniki|fa-odnoklassniki-square|fa-get-pocket|fa-wikipedia-w|fa-safari|fa-chrome|fa-firefox|fa-opera|fa-internet-explorer|fa-television|fa-contao|fa-500px|fa-amazon|fa-calendar-plus-o|fa-calendar-minus-o|fa-calendar-times-o|fa-calendar-check-o|fa-industry|fa-map-pin|fa-map-signs|fa-map-o|fa-map|fa-commenting|fa-commenting-o|fa-houzz|fa-vimeo|fa-black-tie|fa-fonticons|fa-reddit-alien|fa-edge|fa-credit-card-alt|fa-codiepie|fa-modx|fa-fort-awesome|fa-usb|fa-product-hunt|fa-mixcloud|fa-scribd|fa-pause-circle|fa-pause-circle-o|fa-stop-circle|fa-stop-circle-o|fa-shopping-bag|fa-shopping-basket|fa-hashtag|fa-bluetooth|fa-bluetooth-b|fa-percent|fa-gitlab|fa-wpbeginner|fa-wpforms|fa-envira|fa-universal-access|fa-wheelchair-alt|fa-question-circle-o|fa-blind|fa-audio-description|fa-volume-control-phone|fa-braille|fa-assistive-listening-systems|fa-american-sign-language-interpreting|fa-deaf|fa-glide|fa-glide-g|fa-sign-language|fa-low-vision|fa-viadeo|fa-viadeo-square|fa-snapchat|fa-snapchat-ghost|fa-snapchat-square|fa-pied-piper|fa-first-order|fa-yoast|fa-themeisle|fa-google-plus-official|fa-font-awesome|fa-handshake-o|fa-envelope-open|fa-envelope-open-o|fa-linode|fa-address-book|fa-address-book-o|fa-address-card|fa-address-card-o|fa-user-circle|fa-user-circle-o|fa-user-o|fa-id-badge|fa-id-card|fa-id-card-o|fa-quora|fa-free-code-camp|fa-telegram|fa-thermometer-full|fa-thermometer-three-quarters|fa-thermometer-half|fa-thermometer-quarter|fa-thermometer-empty|fa-shower|fa-bath|fa-podcast|fa-window-maximize|fa-window-minimize|fa-window-restore|fa-window-close|fa-window-close-o|fa-bandcamp|fa-grav|fa-etsy|fa-imdb|fa-ravelry|fa-eercast|fa-microchip|fa-snowflake-o|fa-superpowers|fa-wpexplorer|fa-meetup'

                        ),
                )

            )
        )
    );
}

add_action( 'customize_controls_enqueue_scripts', 'sovenco_customize_controls_enqueue_scripts' );
