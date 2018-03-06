<?php

if ( ! defined('ABSPATH')) exit;  // if direct access


function bug_blog_site_wrapper_before(){

	do_action('bug_blog_site_wrapper_before');
}

function bug_blog_site_wrapper_class(){

	//apply_filters('bug_blog_site_wrapper_class', '');

	$site_wrapper_class = apply_filters('bug_blog_site_wrapper_class', '');

	echo esc_attr($site_wrapper_class);
}


function bug_blog_article_class(){

	//apply_filters('bug_blog_site_wrapper_class', '');

	$article_class = apply_filters('bug_blog_article_class', '');

	return esc_attr($article_class);
}


function bug_blog_article_inner_class(){

	//apply_filters('bug_blog_site_wrapper_class', '');

	$article_inner_class = apply_filters('bug_blog_article_inner_class', '');

	return esc_attr($article_inner_class);
}






function bug_blog_site_wrapper_top(){

    do_action('bug_blog_site_wrapper_top');

}

function bug_blog_site_header_before(){
	do_action('bug_blog_site_header_before');
}

function bug_blog_site_header_class(){

	//apply_filters('bug_blog_site_header_class', '');

	$site_header_class = apply_filters('bug_blog_site_header_class', '');

	echo esc_attr($site_header_class);
}

function bug_blog_site_header(){

	do_action('bug_blog_site_header');

}
function bug_blog_site_header_after(){

	do_action('bug_blog_site_header_after');
}

function bug_blog_site_footer_before(){

	do_action('bug_blog_site_footer_before');
}

function bug_blog_site_footer_class(){

	apply_filters('bug_blog_site_footer_class', '');

	$site_footer_class = apply_filters('bug_blog_site_footer_class', '');

	echo esc_attr($site_footer_class);

}

function bug_blog_site_footer(){

	do_action('bug_blog_site_footer');
}

function bug_blog_site_footer_after(){

	do_action('bug_blog_site_footer_after');
}


function bug_blog_site_wrapper_bottom(){

	do_action('bug_blog_site_wrapper_bottom');
}



function bug_blog_site_wrapper_after(){

	do_action('bug_blog_site_wrapper_after');
}


function bug_blog_site_main_before(){

	do_action('bug_blog_site_main_before');
}

function bug_blog_site_main_top(){

	do_action('bug_blog_site_main_top');
}

function bug_blog_site_main_bottom(){
	do_action('bug_blog_site_main_bottom');
}

function bug_blog_site_main_after(){
	do_action('bug_blog_site_main_after');
}

function bug_blog_content_area_before(){
	do_action('bug_blog_content_area_before');
}

function bug_blog_template_part($template){

    if(is_singular('post')):
	    get_template_part( 'templates-parts/single', 'content');

    elseif (is_singular('page')):
	    get_template_part( 'templates-parts/page', 'content');

    elseif (is_attachment()):
	    get_template_part( 'templates-parts/single', 'attachment');

    elseif (is_404()):
	    get_template_part( 'templates-parts/page', '404');



    else:
	    get_template_part( 'templates-parts/single', 'content');

    endif;

}

add_action('bug_blog_post_content_template', 'bug_blog_post_content_template_html');

function bug_blog_post_content_template_html(){

	if(is_singular('post')):
		get_template_part( 'templates-parts/single', 'content');

    elseif (is_singular('page')):
		get_template_part( 'templates-parts/page', 'content');

    elseif (is_attachment()):
		get_template_part( 'templates-parts/single', 'attachment');

    elseif (is_404()):
		get_template_part( 'templates-parts/page', '404');

	else:
		get_template_part( 'templates-parts/single', 'content');

	endif;

}

function bug_blog_article_before(){

	do_action('bug_blog_article_before');
}

function bug_blog_article_template(){

	do_action('bug_blog_article_template');
}

function bug_blog_article_after(){

	do_action('bug_blog_article_after');
}


add_action('bug_blog_article_template','bug_blog_article_template_html');
function bug_blog_article_template_html(){

	$article_template = get_theme_mod('article_theme');

	if(empty($article_template)){

		$article_template =  'article_1';
	}


	if($article_template=='article_1'){
		include(get_template_directory().'/templates-parts/article-templates/template-1.php');
	}
    elseif ($article_template=='article_2'){
		include(get_template_directory().'/templates-parts/article-templates/template-2.php');
	}
    elseif ($article_template=='article_3'){
		include(get_template_directory().'/templates-parts/article-templates/template-3.php');
	}
    elseif ($article_template=='article_4'){
		include(get_template_directory().'/templates-parts/article-templates/template-4.php');
	}

}

add_filter('bug_blog_article_class','bug_blog_article_class_extra');

function bug_blog_article_class_extra($article_class){

	$article_template = get_theme_mod('article_theme');

	if(empty($article_template)){

		$article_template = '';
	}

	return $article_template.' '. $article_class;
}





add_action('bug_blog_article_after','bug_blog_article_after_html');
function bug_blog_article_after_html(){

    ?>
    <div class="next-previous-post clearfix">
        <!-- Previous Post -->
        <div class="previous-post pull-left">
			<?php previous_post_link('<div class="nav-previous">%link</div>', __('<i class="fa fa-angle-left"></i> Previous Post', 'bug-blog')); ?>
        </div>

        <!-- Next Post -->
        <div class="next-post pull-right text-right">
			<?php next_post_link('<div class="nav-next">%link</div>', __('Next Post <i class="fa fa-angle-right"></i>', 'bug-blog')); ?>
        </div>
    </div>
    <?php

	if ( comments_open() || get_comments_number() ) : ?>
        <div class="margin-top-40">
			<?php comments_template(); ?>
        </div>
	<?php endif;

}









function bug_blog_page_content_template(){

	do_action('bug_blog_page_content_template');
}



function bug_blog_content_area_after(){

	do_action('bug_blog_content_area_after');
}
function bug_blog_site_main_class(){

	$main_class = apply_filters('bug_blog_site_main_class', '');

	echo esc_attr($main_class);
}
function bug_blog_content_area_class(){

	$content_area_class = apply_filters('bug_blog_content_area_class', '');

	echo esc_attr($content_area_class);
}

function bug_blog_archive_single_post(){

	do_action('bug_blog_archive_single_post');
}


function bug_blog_content_area_top(){

	do_action('bug_blog_content_area_top');
}

function bug_blog_content_area_bottom(){

	do_action('bug_blog_content_area_bottom');
}


function bug_blog_page_template(){

	do_action('bug_blog_page_template');
}

function bug_blog_post_content_template(){

	do_action('bug_blog_post_content_template');
}







add_action('bug_blog_content_area_top','bug_blog_arcive_title');
function bug_blog_arcive_title(){

    if(is_category() || is_tag() || is_author() || is_year() || is_month() || is_day() || is_tax() || is_post_type_archive()):
	    ?>
        <div class="archive-header">

            <h4 class="archive-title">
		        <?php
		        the_archive_title();
		        ?>
            </h4>
            <p class="archive-description"><?php echo the_archive_description(); ?></p>
        </div>

	    <?php
    elseif (is_search()):
	    ?>
        <div class="archive-header">

            <h4 class="archive-title">
	            <?php printf( __( 'Search Results: <strong>%s</strong>', 'bug-blog' ),'<span>' . get_search_query() . '</span>' ); ?>
            </h4>
        </div>

	    <?php



    endif;


}



add_action('bug_blog_site_main_top','bug_blog_site_main_top_container');
function bug_blog_site_main_top_container(){

    ?>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
    <?php
}



add_action('bug_blog_site_main_bottom','bug_blog_site_main_bottom_container');

function bug_blog_site_main_bottom_container(){

	?>
            </div> <!-- .col-md-8 -->
            <div class="col-md-4">
                <?php dynamic_sidebar('blog-widget'); ?>
            </div> <!-- .col-md-8 -->
        </div><!-- .row -->
    </div> <!-- .container -->


	<?php
}






//add_action('bug_blog_site_main_after','bug_blog_site_main_after_container');
function bug_blog_site_main_after_container(){

    ?>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
	            <?php dynamic_sidebar('blog-sidebar'); ?>
            </div> <!-- .col-md-8 -->
        </div><!-- .row -->
    </div> <!-- .container -->

    <?php
}







add_action('bug_blog_site_header','bug_blog_site_header_html');


function bug_blog_site_header_html(){

	$header_template = get_theme_mod('header_theme');

	if(empty($header_template)){
		$header_template =  'header_1';
	}

	if($header_template=='header_1'){
		include(get_template_directory().'/templates-parts/header-templates/template-1.php');

	}
	elseif ($header_template=='header_2'){
		include(get_template_directory().'/templates-parts/header-templates/template-2.php');
    }
    elseif ($header_template=='header_3'){
		include(get_template_directory().'/templates-parts/header-templates/template-3.php');
	}

    elseif ($header_template=='header_4'){
		include(get_template_directory().'/templates-parts/header-templates/template-4.php');
	}

    elseif ($header_template=='header_5'){
		include(get_template_directory().'/templates-parts/header-templates/template-5.php');
	}

    elseif ($header_template=='header_6'){
		include(get_template_directory().'/templates-parts/header-templates/template-6.php');
	}

    elseif ($header_template=='header_7'){
		include(get_template_directory().'/templates-parts/header-templates/template-7.php');
	}




	?>


	<?php
}


add_filter('bug_blog_site_header_class','bug_blog_site_header_class_extra');


function bug_blog_site_header_class_extra($header_class){

	$header_template = get_theme_mod('header_theme');

	if(empty($header_template)){

		$header_template =  'header_1';
    }

	return $header_template.' '.$header_class;
}









add_action('bug_blog_archive_single_post','bug_blog_archive_single_post_loop');

function bug_blog_archive_single_post_loop(){

    ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <div class="entry-thumbnail">
			<?php the_post_thumbnail(); ?>
        </div>

        <h1 class="title entry-title">
            <a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a>
        </h1>

        <div class="entry-meta-top">

        <span class="author vcard">
                        <?php _e('By: ', 'bug-blog');
                        printf('<a class="url fn n" href="%1$s">%2$s</a>',
	                        esc_url(get_author_posts_url(get_the_author_meta('ID'))),
	                        esc_html(get_the_author())
                        ) ?>
        </span>
            <span class="posted-date"><?php the_time('M d, Y') ?></span>
		    <?php if (get_the_category_list()): ?>

                <span class="categories">
                <?php echo get_the_category_list(_x(', ', 'Used between list items, there is a space after the comma.', 'bug-blog')); ?>
            </span>
		    <?php endif; ?>

        </div>

        <div class="entry-content">
			<?php the_excerpt(); ?>
            <a class="read-more" href="<?php echo esc_url( get_permalink() ); ?>"><?php echo __('Continue reading', 'bug-blog'); ?></a>
        </div>



    </article>
    <?php
}





add_action('bug_blog_site_footer','bug_blog_site_footer_html');


function bug_blog_site_footer_html(){

	$footer_template = get_theme_mod('footer_theme');
	if(empty($footer_template)){

		$footer_template =  'footer_1';
	}

	if($footer_template=='footer_1'){
		include(get_template_directory().'/templates-parts/footer-templates/template-1.php');
	}
    elseif ($footer_template=='footer_2'){
		include(get_template_directory().'/templates-parts/footer-templates/template-2.php');
	}

    elseif ($footer_template=='footer_3'){
		include(get_template_directory().'/templates-parts/footer-templates/template-3.php');
	}


}






add_action('bug_blog_content_area_bottom','bug_blog_content_area_bottom_pagination');


function bug_blog_content_area_bottom_pagination(){

    if(!is_singular()):


	    ?>
        <div class="pagination">
		    <?php

		    if ( get_query_var('paged') ) {

			    $paged = get_query_var('paged');

		    } elseif ( get_query_var('page') ) {

			    $paged = get_query_var('page');

		    } else {

			    $paged = 1;

		    }

		    global $wp_query;

		    $big = 999999999; // need an unlikely integer
		    $max_num_pages = $wp_query->max_num_pages;


		    echo paginate_links( array(
			    'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			    'format' => '?paged=%#%',
			    'current' => max( 1, $paged ),
			    'total' => $max_num_pages,
			    'prev_text'          => __('Previous','bug-blog'),
			    'next_text'          => __('Next','bug-blog'),
		    ) );

		    ?>

        </div>
	    <?php

    endif;


}


add_filter('bug_blog_site_footer_class', 'bug_blog_site_footer_class_extra');

function bug_blog_site_footer_class_extra($footer_class){

	$footer_template = get_theme_mod('footer_theme');

	if(empty($footer_template)){

		$footer_template =  'footer_1';
	}

	return $footer_template.' '.$footer_class;



}



/*
 *
 *
 add_action('bug_blog_archive_single_post','bug_blog_archive_single_post_html_hh', 0);

function bug_blog_archive_single_post_html_hh(){

    echo '<div style="background: #32ddf9;text-align:center;padding:10px 0px;color:#fff;">HTML under <code style="font-size: 15px;">.content-area</code> inside loop on archive page</div>';
}
 *
 * */




add_action('bug_blog_content_area_top','bug_blog_content_area_top_breadcrumb');

function bug_blog_content_area_top_breadcrumb(){

    if(is_singular()):
        ?>
        <div class="breadcrumb">
        <?php

	    global $post;
        $home = get_page(get_option('page_on_front'));
	    if($post->post_parent):



		    ?>

                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo esc_url( home_url() ); ?>"><?php echo __('Home', 'bug-blog'); ?></a></li>
				    <?php

				    for ($i = count($post->ancestors)-1; $i >= 0; $i--) {
					    if (($home->ID) != ($post->ancestors[$i]))
					    {
						    ?>
                            <li class="breadcrumb-item"><a href="<?php echo esc_url(get_permalink($post->ancestors[$i])); ?>"><?php echo esc_attr(get_the_title($post->ancestors[$i])); ?></a></li>
						    <?php
					    }
				    }

				    ?>
                    <li class="breadcrumb-item"><a href="<?php echo esc_url( get_permalink(get_the_id()) ); ?>"><?php echo esc_attr(get_the_title()); ?></a></li>

                </ol>

		    <?php
        else:

            ?>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo esc_url( home_url() ); ?>"><?php echo __('Home', 'bug-blog'); ?></a></li>
                <li class="breadcrumb-item"><a href="<?php echo esc_url( get_permalink(get_the_id()) ); ?>"><?php echo esc_attr(get_the_title()); ?></a></li>
            </ol>
            <?php

	    endif;

        ?>
        </div>
        <?php
    endif;



}





add_filter('bug_blog_sidebars','bug_blog_sidebars_extra');

function bug_blog_sidebars_extra($sidebars){

	$sidebars[] = array(
		'name'          => __( 'Home - Sidebar', 'bug-blog' ),
		'id'            => 'home-widget',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget col-md-6 col-lg-4 col-sm-6 col-xl-3 footer-widget %2$s"><div class="widget-wrapper">',
		'after_widget'  => '</div></aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	);

	$sidebars[] = array(
		'name'          => __( 'Page - Sidebar', 'bug-blog' ),
		'id'            => 'page-widget',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget col-md-6 col-lg-4 col-sm-6 col-xl-3 footer-widget %2$s"><div class="widget-wrapper">',
		'after_widget'  => '</div></aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	);


	return $sidebars;
}

