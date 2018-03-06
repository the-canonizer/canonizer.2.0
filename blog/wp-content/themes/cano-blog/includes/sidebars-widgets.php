<?php

if ( ! defined('ABSPATH')) exit;  // if direct access



add_action( 'widgets_init', 'bug_blog_widgets_init' );

function bug_blog_widgets_init(){

	$bug_blog_sidebars[] = array(
		'name'          => __( 'Blog Sidebar', 'bug-blog' ),
		'id'            => 'blog-widget',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget blog-widget %2$s"><div class="widget-wrapper">',
		'after_widget'  => '</div></aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	);

	$bug_blog_sidebars[] = array(
		'name'          => __( 'Footer', 'bug-blog' ),
		'id'            => 'footer-widget',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget col-md-6 col-lg-4 col-sm-6 col-xl-3 footer-widget %2$s"><div class="widget-wrapper">',
		'after_widget'  => '</div></aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	);





	$bug_blog_sidebars = apply_filters('bug_blog_sidebars', $bug_blog_sidebars);


	foreach ($bug_blog_sidebars as $sidebar){

		register_sidebar($sidebar);

	}





}