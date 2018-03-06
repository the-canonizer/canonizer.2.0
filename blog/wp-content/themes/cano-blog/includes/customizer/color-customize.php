<?php

if ( ! defined('ABSPATH')) exit;  // if direct access

if ( ! function_exists( 'bug_blog_color_theme' ) ) :

function bug_blog_color_theme(){

    ?>

	<style>
		button:hover,
		input[type="button"]:hover,
		input[type="reset"]:hover,
		input[type="submit"]:hover,
		button:focus,
		input[type="button"]:focus,
		input[type="reset"]:focus,
		input[type="submit"]:focus,
		button:active,
		input[type="button"]:active,
		input[type="reset"]:active,
		input[type="submit"]:active,
		.user-profile ul.author-social-profile li a:hover,
		#respond input:focus[type="text"], 
		#respond input:focus[type="email"], 
		#respond input:focus[type="url"],
		#respond textarea:focus,
		#comments .comment-reply a:hover,
		.widget .social-link ul li a:hover,
		.next-previous-posts .previous-posts h2 a:hover,
		.next-previous-posts .next-posts h2 a:hover,
		a:hover.more-link,
		.post-social-button ul li a:hover,
		input:focus[type="text"],
		input:focus[type="email"],
		textarea:focus,
		.tagcloud a:hover,
		.sticky,
		.page-links a:hover
		{
			border-color: <?php echo esc_attr(get_theme_mod('bug_blog_theme_color')); ?>;
		}

		button:hover,
		input[type="button"]:hover,
		input[type="reset"]:hover,
		input[type="submit"]:hover,
		.user-profile ul.author-social-profile li a:hover,
		#comments .comment-reply a:hover,
		#blog-gallery-slider .carousel-control.left,
		#blog-gallery-slider .carousel-control.right,
		ul.menu ul a:hover,
		.menu ul ul a:hover,
		ul.cat-menu ul a:hover,
		.cat-menu ul ul a:hover,
		.tagcloud a:hover,
		.widget .social-link ul li a:hover,
		.next-previous-posts .previous-posts h2 a:hover,
		.next-previous-posts .next-posts h2 a:hover,
		.pagination li a:focus, 
		.pagination li a:hover, 
		.pagination li span:focus, 
		.pagination li span.current, 
		.pagination li span:hover,
		a:hover.more-link,
		.post-social-button ul li a:hover,
		.scroll-up a,
		.scroll-up a:hover,
		.scroll-up a:active,
		.footer-social a:hover,
		.owl-theme .owl-controls .owl-page.active span, 
		.owl-theme .owl-controls.clickable .owl-page:hover span,
		#instafeed .owl-controls .owl-buttons div,
		button.mfp-arrow,
		.next-previous-post a:hover,
		.page-links a:hover {
			background-color: <?php echo esc_attr(get_theme_mod('bug_blog_theme_color')); ?>;
		}

		
		.user-profile .profile-heading h3 a:hover,
		#comments .comment-author a:hover, 
		#respond .logged-in-as a:hover,
		.menu li.current-menu-item a, .menu li.current_page_item a, .menu li a:hover,
		.top-social a:hover,
		.top-search a:hover,
		#post-carousel .item .post-title ul li a,
		#post-carousel .item .post-title ul li a:hover,
		.cat-menu li.current-menu-item a, .cat-menu li.current_page_item a, .cat-menu li a:hover,
		.widget li a:hover,
		#wp-calendar tfoot a,
		.widget .latest-posts .entry-title a:hover,
		.entry-meta a:hover,
		article header.entry-header h1.entry-title a:hover,
		.copy-right-text a:hover,
		.single-related-posts header h3 a:hover{
			color: <?php echo esc_attr(get_theme_mod('bug_blog_theme_color')); ?>;
		}

		a{
			color: <?php echo esc_attr(get_theme_mod('bug_blog_anchor_color')); ?>;
		}

		a:hover,
		.top-search a.sactive{
			color: <?php echo esc_attr(get_theme_mod('bug_blog_anchor_hover_color')); ?>;
		}

        <?php

        $site_header_bg_color = get_theme_mod('site_header_bg_color');
        $site_header_text_color = get_theme_mod('site_header_text_color');
        $site_header_text_color = get_theme_mod('site_header_text_color');
        $site_header_link_color = get_theme_mod('site_header_link_color');

        $site_footer_bg_color = get_theme_mod('site_footer_bg_color');
        $site_footer_text_color = get_theme_mod('site_footer_text_color');
        $site_footer_text_color = get_theme_mod('site_footer_text_color');
        $site_footer_link_color = get_theme_mod('site_footer_link_color');

        $site_wrapper_width = get_theme_mod('site_wrapper_width');
        $site_wrapper_bg_color = get_theme_mod('site_wrapper_bg_color');
        $container_width = get_theme_mod('container_width');



        ?>

        .site-header{
            <?php if(!empty($site_header_bg_color)):?>
            background-color: <?php echo esc_attr($site_header_bg_color); ?>;
            <?php endif; ?>

            <?php if(!empty($site_header_text_color)):?>
            color: <?php echo esc_attr($site_header_text_color); ?>;
            <?php endif; ?>
        }

        .site-header p{
            <?php if(!empty($site_header_text_color)):?>
            color: <?php echo esc_attr($site_header_text_color); ?>;
            <?php endif; ?>
        }

        .site-header a{
            <?php if(!empty($site_header_link_color)):?>
            color: <?php echo esc_attr($site_header_link_color); ?> !important;
            <?php endif; ?>
        }



        .site-footer{
            <?php if(!empty($site_footer_bg_color)):?>
            background-color: <?php echo esc_attr($site_footer_bg_color); ?>;
            <?php endif; ?>

            <?php if(!empty($site_footer_text_color)):?>
            color: <?php echo esc_attr($site_footer_text_color); ?>;
            <?php endif; ?>
        }

        .site-footer p{
            <?php if(!empty($site_footer_text_color)):?>
            color: <?php echo esc_attr($site_footer_text_color); ?>;
            <?php endif; ?>
        }


        .site-footer a{
            <?php if(!empty($site_footer_link_color)):?>
            color: <?php echo esc_attr($site_footer_link_color); ?> !important;
            <?php endif; ?>
        }


        .site-wrapper{
            <?php if(!empty($site_wrapper_width)):?>
            width: <?php echo esc_attr($site_wrapper_width); ?> !important;
            <?php endif; ?>

            <?php if(!empty($site_wrapper_bg_color)):?>
            background-color: <?php echo esc_attr($site_wrapper_bg_color); ?>;
            <?php endif; ?>
            margin: 0 auto;
        }


        .container{
            <?php if(!empty($container_width)):?>
            width: <?php echo esc_attr($container_width); ?> !important;
            <?php endif; ?>
        }





	</style>
<?php
}
add_action('wp_head', 'bug_blog_color_theme');

endif;
