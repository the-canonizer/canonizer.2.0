<?php if ( ! defined('ABSPATH')) exit;  // if direct access ?>


<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <link rel="shortcut icon" href="" />

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

    <?php bug_blog_site_wrapper_before(); ?>
    <div class="site-wrapper <?php bug_blog_site_wrapper_class(); ?>">
	    <?php bug_blog_site_wrapper_top(); ?>

	    <?php bug_blog_site_header_before(); ?>
        <header class="site-header <?php bug_blog_site_header_class(); ?>">
	        <?php bug_blog_site_header(); ?>
        </header> <!-- .header end -->
        <?php bug_blog_site_header_after(); ?>

