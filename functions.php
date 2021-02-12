<?php
add_action( 'after_setup_theme', 'canadianguide_setup' );

function canadianguide_setup() {
load_theme_textdomain( 'canadianguide', get_template_directory() . '/languages' );
add_theme_support( 'title-tag' );
add_theme_support( 'automatic-feed-links' );
add_theme_support( 'post-thumbnails' );
add_theme_support( 'html5', array( 'search-form' ) );

global $content_width;
if ( ! isset( $content_width ) ) { $content_width = 1920; }

function register_menus() {
    register_nav_menus( 
        array(
            'main-menu' => esc_html__( 'Main', 'canadianguide' ),
            'footer-menu' => esc_html__( 'Foote', 'canadianguide')
    ));
}
add_action( 'init', 'register_menus' );

function check_login() {
    if ( is_user_logged_in() ) 
    {
        // code
    }
}
add_action('init', 'check_login'); 

add_action( 'wp_enqueue_scripts', 'canadianguide_load_scripts' );
function canadianguide_load_scripts() {
    wp_enqueue_style( 'canadianguide-style', get_stylesheet_uri() );
    wp_enqueue_script( 'jquery' );
}


add_action( 'wp_footer', 'canadianguide_footer_scripts' );
function canadianguide_footer_scripts() {
?>

<?php
}


add_filter( 'document_title_separator', 'canadianguide_document_title_separator' );
function canadianguide_document_title_separator( $sep ) {
    $sep = '|';
    return $sep;
}

add_filter( 'the_title', 'canadianguide_title' );
function canadianguide_title( $title ) {
    if ( $title == '' ) {
    return '...';
    } else {
    return $title;
    }
}

add_filter( 'the_content_more_link', 'canadianguide_read_more_link' );
function canadianguide_read_more_link() {
    if ( ! is_admin() ) {
    return ' <a href="' . esc_url( get_permalink() ) . '" class="more-link">...</a>';
    }
}

add_filter( 'excerpt_more', 'canadianguide_excerpt_read_more_link' );
function canadianguide_excerpt_read_more_link( $more ) {
    if ( ! is_admin() ) {
    global $post;
    return ' <a href="' . esc_url( get_permalink( $post->ID ) ) . '" class="more-link">...</a>';
    }
}

add_filter( 'intermediate_image_sizes_advanced', 'canadianguide_image_insert_override' );
    function canadianguide_image_insert_override( $sizes ) {
    unset( $sizes['medium_large'] );
    return $sizes;
}

add_action( 'widgets_init', 'canadianguide_widgets_init' );
function canadianguide_widgets_init() {
    
    register_sidebar( array(
        'name' => esc_html__( 'Sidebar Widget Area', 'canadianguide' ),
        'id' => 'primary-widget-area',
        'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
        ) );

    register_sidebar( array(
        'name'          => 'Footer area one',
        'id'            => 'footer_area_one',
        'description'   => 'This widget area discription',
        'before_widget' => '<section class="footer-area footer-area-one">',
        'after_widget'  => '</section>',
        'before_title'  => '<h4>',
        'after_title'   => '</h4>',
      ));
    
      register_sidebar( array(
        'name'          => 'Footer area two',
        'id'            => 'footer_area_two',
        'description'   => 'This widget area discription',
        'before_widget' => '<section class="footer-area footer-area-two">',
        'after_widget'  => '</section>',
        'before_title'  => '<h4>',
        'after_title'   => '</h4>',
      ));
    
      register_sidebar( array(
        'name'          => 'Footer area three',
        'id'            => 'footer_area_three',
        'description'   => 'This widget area discription',
        'before_widget' => '<section class="footer-area footer-area-three">',
        'after_widget'  => '</section>',
        'before_title'  => '<h4>',
        'after_title'   => '</h4>',
      ));
    
      register_sidebar( array(
        'name'          => 'Footer area four',
        'id'            => 'footer_area_four',
        'description'   => 'This widget area discription',
        'before_widget' => '<section class="footer-area footer-area-three">',
        'after_widget'  => '</section>',
        'before_title'  => '<h4>',
        'after_title'   => '</h4>',
      ));
}

add_action( 'wp_head', 'canadianguide_pingback_header' );
function canadianguide_pingback_header() {
    if ( is_singular() && pings_open() ) {
    printf( '<link rel="pingback" href="%s" />' . "\n", esc_url( get_bloginfo( 'pingback_url' ) ) );
    }
}
add_action( 'comment_form_before', 'canadianguide_enqueue_comment_reply_script' );
function canadianguide_enqueue_comment_reply_script() {
    if ( get_option( 'thread_comments' ) ) {
    wp_enqueue_script( 'comment-reply' );
    }
}
function canadianguide_custom_pings( $comment ) {
?>
<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>"><?php echo comment_author_link(); ?></li>
<?php
}
add_filter( 'get_comments_number', 'canadianguide_comment_count', 0 );
function canadianguide_comment_count( $count ) {
if ( ! is_admin() ) {
global $id;
$get_comments = get_comments( 'status=approve&post_id=' . $id );
$comments_by_type = separate_comments( $get_comments );
return count( $comments_by_type['comment'] );
} else {
return $count;
}
}

//SET CSS FOLDER
add_action( 'wp_enqueue_scripts', 'enqueue_theme_css' );

function enqueue_theme_css()
{
    wp_enqueue_style(
        'default',
        get_template_directory_uri() . '/css/default.css'
    );
}


/**
 * Registers an editor stylesheet in a sub-directory.
 */
function add_editor_styles_sub_dir() {
    add_editor_style( trailingslashit( get_template_directory_uri() ) . 'css/editor-style.css' );
}
add_action( 'after_setup_theme', 'add_editor_styles_sub_dir' );

//Custom Headers
$args = array(
	'flex-width'    => true,
	'width'         => 1000,
	'flex-height'    => true,
	'height'        => 200,
	'default-image' => get_template_directory_uri() . '/images/header.jpg',
);
add_theme_support( 'custom-header', $args );

}
  
