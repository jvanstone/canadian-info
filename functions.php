<?php
/**
 * Functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Canadian Guide
 * @subpackage canadian_guide
 * @since 1.0.0
 */


if ( ! function_exists( 'canadian_guide_setup' ) ) {

    /**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
   
    function canadian_guide_setup() {
        /*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Twenty Twenty-One, use a find and replace
		 * to change 'canadian_guide' to the name of your theme in all the template files.
		 */
        load_theme_textdomain( 'canadian_guide', get_template_directory() . '/languages' );

        // Add default posts and comments RSS feed links to head.
        add_theme_support( 'automatic-feed-links' );

        /*
		 * Let WordPress manage the document title.
		 * This theme does not use a hard-coded <title> tag in the document head,
		 * WordPress will provide it for us.
		 */
        add_theme_support( 'title-tag' );
        
        /*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
        add_theme_support( 'post-thumbnails' );


        register_nav_menus(
			array(
				'primary' => esc_html__( 'Primary menu', 'canadian_guide' ),
				'footer'  => __( 'Secondary menu', 'canadian_guide' ),
			)
		);

        /*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
				'navigation-widgets',
			)
		);

        /**
         * 
         * Create Custom Header for the Theme
         * 
         * @link https://developer.wordpress.org/themes/functionality/custom-headers/
         */
        $args = array(
            'width'         => 1000,
            'height'        => 300,
            'flex-width'    => true,
            'flex-height'   => true,
            'default-image' => get_template_directory_uri() . '/images/header.jpg',
            // Header image random rotation default
            'random-default'        => true,
        );
        add_theme_support( 'custom-header', $args );


        /**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		$logo_width  = 100;
		$logo_height = 100;

		add_theme_support(
			'custom-logo',
			array(
				'height'               => $logo_height,
				'width'                => $logo_width,
				'flex-width'           => false,
				'flex-height'          => false,
                'header-text'          => array( 'site-title', 'site-description' ),
				'unlink-homepage-logo' => false,
			)
		);

        // Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Add support for Block Styles.
		add_theme_support( 'wp-block-styles' );

		// Add support for full and wide align images.
		add_theme_support( 'align-wide' );

		// Add support for editor styles.
		add_theme_support( 'editor-styles' );
		$background_color = get_theme_mod( 'background_color', 'D1E4DD' );

		$editor_stylesheet_path = './assets/css/style-editor.css';

        // Note, the is_IE global variable is defined by WordPress and is used
		// to detect if the current browser is internet explorer.
		global $is_IE;
		if ( $is_IE ) {
			$editor_stylesheet_path = './assets/css/ie-editor.css';
		}

        // Enqueue editor styles.
		add_editor_style( $editor_stylesheet_path );
    }
}
add_action( 'after_setup_theme', 'canadian_guide_setup' );


/**
 * Register widget area.
 *
 * @since 1.0.0
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 *
 * @return void
 */
function canadian_guide_widgets_init() {

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer', 'canadian_guide' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here to appear in your footer.', 'canadian_guide' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'canadian_guide_widgets_init' );


/**
 * Enqueue scripts and styles.
 *
 * @since 1.0.0
 *
 * @return void
 */
function canadian_guide_scripts() {

	//Load Bootstrap CSS First to allow for Customization in style.css
	wp_enqueue_style( 'bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css' );

    // Note, the is_IE global variable is defined by WordPress and is used
	// to detect if the current browser is internet explorer.
	global $is_IE;
	if ( $is_IE ) {
		// If IE 11 or below, use a flattened stylesheet with static values replacing CSS Variables.
		wp_enqueue_style( 'canadian-guide-style', get_template_directory_uri() . '/assets/css/ie.css', array(), wp_get_theme()->get( 'Version' ) );
	} else {
		// If not IE, use the standard stylesheet.
		wp_enqueue_style( 'canadian-guide-style', get_template_directory_uri() . '/style.css', array(), wp_get_theme()->get( 'Version' ) );
	}

    wp_style_add_data( 'canadian-guide-style', 'rtl', 'replace' );

	// Print styles.
	wp_enqueue_style( 'canadian-guide-print-style', get_template_directory_uri() . '/assets/css/print.css', array(), wp_get_theme()->get( 'Version' ), 'print' );

	//remove unwanted stylesheets that are part of the basic WordPress build
	wp_dequeue_style( 'wp-block-library' );
    wp_dequeue_style( 'wp-block-library-theme' );
    wp_dequeue_style( 'wc-block-style' ); // Remove WooCommerce block CSS
    
    /*****
     * 
     * Add BootStrap to WP footer
     * 
     */
    add_action( 'wp_footer', 'canadian_guide_bootstrap_scripts' );
    function canadian_guide_bootstrap_scripts() {
        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'Popper' , 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js');
        wp_enqueue_script( 'Javascript' , 'https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js'); 
    }
   
}
add_action( 'wp_enqueue_scripts', 'canadian_guide_scripts' );


    /**
     * 
     *  A Filter to make Bootstrap Drop-down work better with WordPress
     * 
     */
   
    function theme_prefix_the_custom_logo() {
        if ( function_exists( 'the_custom_logo' ) ) {
            the_custom_logo();
        }
    }

    function change_logo_class($html)
    {
        $html = str_replace('custom-logo', 'logo', $html);
        return $html;
    }
    add_filter('get_custom_logo','change_logo_class');


    function add_menuclass($ulclass) {
        return preg_replace('/<a /', '<a class="nav-link" ', $ulclass);
    }
    add_filter('wp_nav_menu','add_menuclass');

	function add_image_fluid_class($content) {
		global $post;
		$pattern        = "/<figure class=\"[A-Za-z-]*\"><img (.*?)class=\".*?\"(.*?)><figcaption>(.*?)<\/figcaption><\/figure>/i";
		$replacement    = '<figure class="text-center my-3"><img class="figure-img img-fluid" $1$2><figcaption class="text-muted">$3</figcaption></figure>';
		$content        = preg_replace($pattern,$replacement,$content);
		return $content;
	 }
	 add_filter('the_content','add_image_fluid_class');
