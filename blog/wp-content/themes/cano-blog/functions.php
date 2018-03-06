<?php

if ( ! defined('ABSPATH')) exit;  // if direct access


if ( ! isset( $content_width ) ) {	$content_width = 1170; }


function bug_blog_setup(){
    /*
     * Make theme available for translation.
     * Translations can be filed in the /languages/ directory.
     * If you're building a theme based on bug_blog, use a find and replace
     * to change 'bug_blog' to the name of your theme in all the template files.
     */
    load_theme_textdomain( 'bug-blog' );

    // Add default posts and comments RSS feed links to head.
    add_theme_support( 'automatic-feed-links' );

    /*
     * Let WordPress manage the document title.
     * By adding theme support, we declare that this theme does not use a
     * hard-coded <title> tag in the document head, and expect WordPress to
     * provide it for us.
     */
    add_theme_support( 'title-tag' );

    /*
     * Enable support for Post Thumbnails on posts and pages.
     *
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support( 'post-thumbnails' );

    // This theme uses wp_nav_menu() in one location.
    register_nav_menus( array(
        'header-menu' => esc_html__( 'Header Menu', 'bug-blog' ),
        'header-top' => esc_html__( 'Header top', 'bug-blog' ),

        'header-secondary' => esc_html__( 'Header secondary', 'bug-blog' ),



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

    // Set up the WordPress core custom background feature.
    add_theme_support( 'custom-background', apply_filters( 'bug_blog_custom_background_args', array(
        'default-color' => 'ffffff',
        'default-image' => '',
    ) ) );
}

add_action( 'after_setup_theme', 'bug_blog_setup' );

function bug_blog_add_editor_styles() {
	add_editor_style( 'custom-editor-style.css' );
}
add_action( 'admin_init', 'bug_blog_add_editor_styles' );



function bug_blog_front_scripts() {

	// CSS File
	wp_enqueue_style('bootstrap-css', get_template_directory_uri() . '/assets/css/bootstrap.min.css', array(), '3.3.6', 'all');
	wp_enqueue_style('font-awesome', get_template_directory_uri() . '/assets/css/font-awesome.min.css', array(), '4.4.0', 'all');
	wp_enqueue_style('slicknav-css', get_template_directory_uri() . '/assets/css/slicknav.css', array(), NULL);
	wp_enqueue_style( 'bug_blog-stylesheet', get_stylesheet_uri() );
	wp_enqueue_style('bug_blog-responsive', get_template_directory_uri() . '/assets/css/responsive.css', array(), NULL);

	// Google Fonts
	wp_enqueue_style( 'google-font-open-sans', 'https://fonts.googleapis.com/css?family=Muli:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i', array(), NULL );
	wp_enqueue_style( 'Material+Icons', 'https://fonts.googleapis.com/icon?family=Material+Icons', array(), NULL );
	wp_enqueue_style( 'Muli', 'https://fonts.googleapis.com/css?family=Muli:400,700,800,900', array(), NULL );

	// JS Files
	wp_enqueue_script( 'jquery-bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array('jquery'), '3.3.6', TRUE );
	wp_enqueue_script( 'jquery-smoothscroll', get_template_directory_uri() . '/assets/js/smoothscroll.js', array('jquery'), '0.9.9', TRUE );
	wp_enqueue_script( 'jquery-slicknav', get_template_directory_uri() . '/assets/js/jquery.slicknav.js', array('jquery'), NULL, TRUE );
	wp_enqueue_script( 'jquery-fitvids', get_template_directory_uri() . '/assets/js/jquery.fitvids.js', array('jquery'), '1.1', TRUE );
	wp_enqueue_script( 'bug_blog-scripts', get_template_directory_uri() . '/assets/js/scripts.js', array('jquery'), NULL, TRUE );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}


	$article_template = get_theme_mod('article_theme');

	if ( is_singular('post') && !empty($article_template)  ) {

		wp_enqueue_style($article_template, get_template_directory_uri() . '/assets/css/article-templates/'.$article_template.'.css', array(), time(), 'all');
	}




}
add_action( 'wp_enqueue_scripts', 'bug_blog_front_scripts' );




function bug_blog_admin_enqueue_scripts() {


	wp_enqueue_script('jquery-ui-sortable');
	wp_enqueue_style( 'wp-color-picker' );

	wp_enqueue_script( 'bug_blog_scripts', get_template_directory_uri() . '/assets/admin/js/scripts.js', array( 'wp-color-picker' ), false, true );

	// CSS File
	wp_enqueue_style('bug-blog-admin-css', get_template_directory_uri() . '/assets/admin/css/style.css', array(), '4.4.0', 'all');
	wp_enqueue_style('font-awesome-css', get_template_directory_uri() . '/assets/css/font-awesome.min.css', array(), '4.4.0', 'all');


	// JS Files
	//wp_enqueue_script( 'scripts', get_template_directory_uri() . '/assets/admin/js/scripts.js', array('jquery'), '3.3.6', TRUE );

}

add_action( 'admin_enqueue_scripts', 'bug_blog_admin_enqueue_scripts' );



//////////////////////////////////////////////////////////////////
// COMMENTS LAYOUT
//////////////////////////////////////////////////////////////////

if(!function_exists('bug_blog_comment')):

	function bug_blog_comment($comment, $args, $depth){
		//$GLOBALS['comment'] = $comment;
		switch ( $comment->comment_type ) :
			case 'pingback' :
			case 'trackback' :
				// Display trackbacks differently than normal comments.
				?>
				<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
				<p><?php _e('Pingback:', 'bug-blog'); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( '(Edit)', 'bug-blog' ), '<span class="edit-link">', '</span>' ); ?></p>
				<?php
				break;
			default :

				global $post;
				?>
			<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
				<div id="comment-<?php comment_ID(); ?>" class="comment-body media">

					<div class="comment-avartar">
						<?php
						echo get_avatar( $comment, $args['avatar_size'] );
						?>
					</div>
					<div class="comment-context media-body">
						<div class="comment-head">
							<?php
							printf( '<h3 class="comment-author">%1$s</h3>',
								get_comment_author_link());
							?>
							<span class="comment-date"><?php echo get_comment_date() ?></span>
						</div>

						<?php if ( '0' == $comment->comment_approved ) : ?>
							<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.',
									'bug-blog' ); ?></p>
						<?php endif; ?>

						<div class="comment-content">
							<?php comment_text(); ?>
						</div>

						<?php edit_comment_link( __( 'Edit', 'bug-blog' ), '<span class="edit-link comment-meta">', '</span>' ); ?>

						<span class="comment-reply comment-meta">
							<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'bug-blog'
							), 'after' => '', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
						</span>

					</div>

				</div>
				<?php
				break;
		endswitch;
	}

endif;



	function bug_blog_social_links(){

	    $social_links['facebook'] =array(
            'name'=> __('Facebook','bug-blog'),
            'link_icon'=> '<i class="fa fa-facebook-square"></i>',
            'link_url'=> '#',
        );

		$social_links['twitter'] =array(

			'link_icon'=> '<i class="fa fa-twitter-square"></i>',
			'link_url'=> '#',
		);

		$social_links['google-plus'] =array(
			'name'=> __('Google+','bug-blog'),
			'link_icon'=> '<i class="fa fa-google-plus-square"></i>',
			'link_url'=> '#',
		);

		$social_links['pinterest'] =array(
			'name'=> __('Pinterest','bug-blog'),
			'link_icon'=> '<i class="fa fa-pinterest-square"></i>',
			'link_url'=> '#',
		);

        return apply_filters('bug_blog_social_links', $social_links);
    }




function sanitize_bug_blog_social($html){


	$html_tags = array(
		'i' => array(
			'class' => array(),
		),
	);

    //$html = wp_kses_post($html, $html_tags);
	return esc_html($html, $html_tags);


	//return esc_html($html);
}












require_once get_template_directory() . '/includes/sidebars-widgets.php';
require_once get_template_directory() . '/includes/hooks/template-hooks.php';

//Custom Widgets
require_once get_template_directory()  . '/includes/widgets/blog-posts.php';
require_once get_template_directory()  . '/includes/widgets/social-icons.php';
require_once get_template_directory()  . '/includes/widgets/advertisement.php';
require_once get_template_directory()  . '/includes/widgets/about-me.php';

include_once( get_template_directory() . '/kirki/kirki.php' );
// Theme Customizer
include(get_template_directory().'/includes/customizer/customizer-settings.php');
include(get_template_directory().'/includes/customizer/color-customize.php');
include(get_template_directory().'/includes/customizer/custom-control.php');

require get_template_directory() . '/includes/custom-header.php';

include(get_template_directory().'/includes/thrid-party/woocommerce.php');
