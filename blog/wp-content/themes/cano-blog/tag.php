<?php
if ( ! defined('ABSPATH')) exit;  // if direct access

get_header();
?>

        <?php bug_blog_site_main_before(); ?>
        <div class="site-main <?php bug_blog_site_main_class(); ?>">
	        <?php bug_blog_site_main_top(); ?>

            <?php bug_blog_content_area_before(); ?>
            <div class="content-area <?php bug_blog_content_area_class(); ?>">
	            <?php bug_blog_content_area_top(); ?>
                <?php while ( have_posts() ) : the_post(); ?>

                    <?php   //bug_blog_template_part( 'single_content' ); ?>
		            <?php bug_blog_archive_single_post(); ?>
                <?php endwhile; ?>
	            <?php bug_blog_content_area_bottom(); ?>
            </div>
	        <?php bug_blog_content_area_after(); ?>

            <?php bug_blog_site_main_bottom(); ?>
        </div>
        <?php bug_blog_site_main_after(); ?>


<?php get_footer(); ?>