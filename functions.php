<?php
/**
 * sovenco functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package sovenco
 */

if ( ! function_exists( 'sovenco_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function sovenco_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on sovenco, use a find and replace
		 * to change 'sovenco' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'sovenco', get_template_directory() . '/languages' );

		/*
		 * Add default posts and comments RSS feed links to head.
		 */
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/**
		 * Excerpt for page
		 */
		add_post_type_support( 'page', 'excerpt' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );
		add_image_size( 'sovenco-blog-small', 300, 150, true );
		add_image_size( 'sovenco-small', 480, 300, true );
		add_image_size( 'sovenco-medium', 640, 400, true );

		/*
		 * This theme uses wp_nav_menu() in one location.
		 */
		register_nav_menus( array(
			'primary'      => esc_html__( 'Primary Menu', 'sovenco' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		/*
		 * This theme styles the visual editor to resemble the theme style.
		 */
		add_editor_style( array( 'assets/css/editor-style.css', sovenco_fonts_url() ) );

		/*
		 * WooCommerce support.
		 */
		add_theme_support( 'woocommerce' );

        /**
         * Add theme Support custom logo
         * @since WP 4.5
         * @sin 1.2.1
         */
        add_theme_support( 'custom-logo', array(
            'height'      => 36,
            'width'       => 160,
            'flex-height' => true,
            'flex-width'  => true,
            //'header-text' => array( 'site-title',  'site-description' ), //
        ) );


        // Recommend plugins
        add_theme_support( 'recommend-plugins', array(
            'contact-form-7' => array(
                'name' => esc_html__( 'Contact Form 7', 'sovenco' ),
                'active_filename' => 'contact-form-7/wp-contact-form-7.php',
            ),
            'famethemes-demo-importer' => array(
                'name' => esc_html__( 'Famethemes Demo Importer', 'sovenco' ),
                'active_filename' => 'famethemes-demo-importer/famethemes-demo-importer.php',
            ),
        ) );


        // Add theme support for selective refresh for widgets.
        add_theme_support( 'customize-selective-refresh-widgets' );

        // Add support for WooCommerce.
        add_theme_support( 'wc-product-gallery-zoom' );
        add_theme_support( 'wc-product-gallery-lightbox' );
        add_theme_support( 'wc-product-gallery-slider' );

	}
endif;
add_action( 'after_setup_theme', 'sovenco_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function sovenco_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'sovenco_content_width', 800 );
}
add_action( 'after_setup_theme', 'sovenco_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function sovenco_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'sovenco' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

    if ( class_exists( 'WooCommerce' ) ) {
        register_sidebar( array(
            'name'          => esc_html__( 'WooCommerce Sidebar', 'sovenco' ),
            'id'            => 'sidebar-shop',
            'description'   => '',
            'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            'after_widget'  => '</aside>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ) );
    }

}
add_action( 'widgets_init', 'sovenco_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function sovenco_scripts() {

    $theme = wp_get_theme( 'sovenco' );
    $version = $theme->get( 'Version' );

	wp_enqueue_style( 'sovenco-fonts', sovenco_fonts_url(), array(), $version );
	wp_enqueue_style( 'sovenco-animate', get_template_directory_uri() .'/assets/css/animate.min.css', array(), $version );
	wp_enqueue_style( 'sovenco-fa', get_template_directory_uri() .'/assets/css/font-awesome.min.css', array(), '4.7.0' );
	wp_enqueue_style( 'sovenco-bootstrap', get_template_directory_uri() .'/assets/css/bootstrap.min.css', false, $version );
	wp_enqueue_style( 'sovenco-style', get_template_directory_uri().'/style.css' );

	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'sovenco-js-plugins', get_template_directory_uri() . '/assets/js/plugins.js', array( 'jquery' ), $version, true );
	wp_enqueue_script( 'sovenco-js-bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array(), $version, true );

    // Animation from settings.
    $sovenco_js_settings = array(
        'sovenco_disable_animation'     => get_theme_mod( 'sovenco_animation_disable' ),
        'sovenco_disable_sticky_header' => get_theme_mod( 'sovenco_sticky_header_disable' ),
        'sovenco_vertical_align_menu'   => get_theme_mod( 'sovenco_vertical_align_menu' ),
        'hero_animation'   				 => get_theme_mod( 'sovenco_hero_option_animation', 'flipInX' ),
        'hero_speed'   					 => intval( get_theme_mod( 'sovenco_hero_option_speed', 5000 ) ),
        'hero_fade'   					 => intval( get_theme_mod( 'sovenco_hero_slider_fade', 750 ) ),
        'hero_duration'   				 => intval( get_theme_mod( 'sovenco_hero_slider_duration', 5000 ) ),
        'is_home'   					 => '',
        'gallery_enable'   				 => '',
    );
    // Load gallery scripts
    $galley_disable  = get_theme_mod( 'sovenco_gallery_disable' ) ==  1 ? true : false;
    $is_shop = false;
    if ( function_exists( 'is_woocommerce' ) ) {
        if ( is_woocommerce() ) {
            $is_shop = true;
        }
    }

    // Don't load scripts for woocommerce because it don't need.
    if ( ! $is_shop ) {
        if ( ! $galley_disable || is_customize_preview()) {
            $sovenco_js_settings['gallery_enable'] = 1;
            $display = get_theme_mod('sovenco_gallery_display', 'grid');
            if (!is_customize_preview()) {
                switch ($display) {
                    case 'masonry':
                        wp_enqueue_script('sovenco-gallery-masonry', get_template_directory_uri() . '/assets/js/isotope.pkgd.min.js', array(), $version, true);
                        break;
                    case 'justified':
                        wp_enqueue_script('sovenco-gallery-justified', get_template_directory_uri() . '/assets/js/jquery.justifiedGallery.min.js', array(), $version, true);
                        break;
                    case 'slider':
                    case 'carousel':
                        wp_enqueue_script('sovenco-gallery-carousel', get_template_directory_uri() . '/assets/js/owl.carousel.min.js', array(), $version, true);
                        break;
                    default:
                        break;
                }
            } else {
                wp_enqueue_script('sovenco-gallery-masonry', get_template_directory_uri() . '/assets/js/isotope.pkgd.min.js', array(), $version, true);
                wp_enqueue_script('sovenco-gallery-justified', get_template_directory_uri() . '/assets/js/jquery.justifiedGallery.min.js', array(), $version, true);
                wp_enqueue_script('sovenco-gallery-carousel', get_template_directory_uri() . '/assets/js/owl.carousel.min.js', array(), $version, true);
            }

        }
        wp_enqueue_style('sovenco-gallery-lightgallery', get_template_directory_uri() . '/assets/css/lightgallery.css');
    }

	wp_enqueue_script( 'sovenco-theme', get_template_directory_uri() . '/assets/js/theme.js', array(), $version, true );
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

    if ( is_front_page() && is_page_template( 'template-frontpage.php' ) ) {
        if ( get_theme_mod( 'sovenco_header_scroll_logo' ) ) {
            $sovenco_js_settings['is_home'] = 1;
        }
    }
	wp_localize_script( 'jquery', 'sovenco_js_settings', $sovenco_js_settings );

}
add_action( 'wp_enqueue_scripts', 'sovenco_scripts' );


if ( ! function_exists( 'sovenco_fonts_url' ) ) :
	/**
	 * Register default Google fonts
	 */
	function sovenco_fonts_url() {
	    $fonts_url = '';

	 	/* Translators: If there are characters in your language that are not
	    * supported by Open Sans, translate this to 'off'. Do not translate
	    * into your own language.
	    */
	    $open_sans = _x( 'on', 'Open Sans font: on or off', 'sovenco' );

	    /* Translators: If there are characters in your language that are not
	    * supported by Raleway, translate this to 'off'. Do not translate
	    * into your own language.
	    */
	    $raleway = _x( 'on', 'Raleway font: on or off', 'sovenco' );

	    if ( 'off' !== $raleway || 'off' !== $open_sans ) {
	        $font_families = array();

	        if ( 'off' !== $raleway ) {
	            $font_families[] = 'Raleway:400,500,600,700,300,100,800,900';
	        }

	        if ( 'off' !== $open_sans ) {
	            $font_families[] = 'Open Sans:400,300,300italic,400italic,600,600italic,700,700italic';
	        }

	        $query_args = array(
	            'family' => urlencode( implode( '|', $font_families ) ),
	            'subset' => urlencode( 'latin,latin-ext' ),
	        );

	        $fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	    }

	    return esc_url_raw( $fonts_url );
	}
endif;


if ( ! function_exists( 'sovenco_register_required_plugins' ) ) :
	/**
	 * Register the required plugins for this theme.
	 *
	 * In this example, we register five plugins:
	 * - one included with the TGMPA library
	 * - two from an external source, one from an arbitrary source, one from a GitHub repository
	 * - two from the .org repo, where one demonstrates the use of the `is_callable` argument
	 *
	 * The variable passed to tgmpa_register_plugins() should be an array of plugin
	 * arrays.
	 *
	 * This function is hooked into tgmpa_init, which is fired within the
	 * TGM_Plugin_Activation class constructor.
	 */
	function sovenco_register_required_plugins() {
		/*
		 * Array of plugin arrays. Required keys are name and slug.
		 * If the source is NOT from the .org repo, then source is also required.
		 */
		$plugins = array(
			array(
				'name'               => 'Contact Form 7', // The plugin name.
				'slug'               => 'contact-form-7', // The plugin slug (typically the folder name).
				'source'             => '', // The plugin source.
				'required'           => false, // If false, the plugin is only 'recommended' instead of required.
				'version'            => '4.2', // E.g. 1.0.0. If set, the active plugin must be this version or higher.
				'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
				'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
				'external_url'       => '', // If set, overrides default API URL and points to an external URL.
			),
		);

		/*
		 * Array of configuration settings. Amend each line as needed.
		 *
		 * TGMPA will start providing localized text strings soon. If you already have translations of our standard
		 * strings available, please help us make TGMPA even better by giving us access to these translations or by
		 * sending in a pull-request with .po file(s) with the translations.
		 *
		 * Only uncomment the strings in the config array if you want to customize the strings.
		 */
		$config = array(
			'id'           => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
			'default_path' => '',                      // Default absolute path to bundled plugins.
			'menu'         => 'tgmpa-install-plugins', // Menu slug.
			'parent_slug'  => 'themes.php',            // Parent menu slug.
			'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
			'has_notices'  => true,                    // Show admin notices or not.
			'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
			'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
			'is_automatic' => false,                   // Automatically activate plugins after installation or not.
			'message'      => '',                      // Message to output right before the plugins table.

			'strings'      => array(
				'page_title'                      => esc_html__( 'Install Required Plugins', 'sovenco' ),
				'menu_title'                      => esc_html__( 'Install Plugins', 'sovenco' ),
				'installing'                      => esc_html__( 'Installing Plugin: %s', 'sovenco' ), // %s = plugin name.
				'oops'                            => esc_html__( 'Something went wrong with the plugin API.', 'sovenco' ),
				'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'sovenco' ), // %1$s = plugin name(s).
				'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'sovenco' ), // %1$s = plugin name(s).
				'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %1$s plugin.', 'Sorry, but you do not have the correct permissions to install the %1$s plugins.', 'sovenco' ), // %1$s = plugin name(s).
				'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'sovenco' ), // %1$s = plugin name(s).
				'notice_ask_to_update_maybe'      => _n_noop( 'There is an update available for: %1$s.', 'There are updates available for the following plugins: %1$s.', 'sovenco' ), // %1$s = plugin name(s).
				'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %1$s plugin.', 'Sorry, but you do not have the correct permissions to update the %1$s plugins.', 'sovenco' ), // %1$s = plugin name(s).
				'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'sovenco' ), // %1$s = plugin name(s).
				'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'sovenco' ), // %1$s = plugin name(s).
				'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %1$s plugin.', 'Sorry, but you do not have the correct permissions to activate the %1$s plugins.', 'sovenco' ), // %1$s = plugin name(s).
				'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins', 'sovenco' ),
				'update_link' 					  => _n_noop( 'Begin updating plugin', 'Begin updating plugins', 'sovenco' ),
				'activate_link'                   => _n_noop( 'Begin activating plugin', 'Begin activating plugins', 'sovenco' ),
				'return'                          => esc_html__( 'Return to Required Plugins Installer', 'sovenco' ),
				'plugin_activated'                => esc_html__( 'Plugin activated successfully.', 'sovenco' ),
				'activated_successfully'          => esc_html__( 'The following plugin was activated successfully:', 'sovenco' ),
				'plugin_already_active'           => esc_html__( 'No action taken. Plugin %1$s was already active.', 'sovenco' ),  // %1$s = plugin name(s).
				'plugin_needs_higher_version'     => esc_html__( 'Plugin not activated. A higher version of %s is needed for this theme. Please update the plugin.', 'sovenco' ),  // %1$s = plugin name(s).
				'complete'                        => esc_html__( 'All plugins installed and activated successfully. %1$s', 'sovenco' ), // %s = dashboard link.
				'contact_admin'                   => esc_html__( 'Please contact the administrator of this site for help.', 'sovenco' ),
				'nag_type'                        => 'updated', // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
			),

		);

		tgmpa( $plugins, $config );
	}

endif;
add_action( 'tgmpa_register', 'sovenco_register_required_plugins' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Add theme info page
 */
require get_template_directory() . '/inc/dashboard.php';
