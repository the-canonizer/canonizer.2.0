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

            <div class="next-previous-post clearfix">
                <!-- Previous Post -->
                <div class="previous-post pull-left">
			        <?php previous_posts_link('<div class="nav-previous">%link</div>', __('<i class="fa fa-angle-left"></i> Previous Post', 'bug-blog')); ?>
                </div>

                <!-- Next Post -->
                <div class="next-post pull-right text-right">
			        <?php next_posts_link('<div class="nav-next">%link</div>', __('Next Post <i class="fa fa-angle-right"></i>', 'bug-blog')); ?>
                </div>
            </div>

	        <?php bug_blog_content_area_after(); ?>

            <?php bug_blog_site_main_bottom(); ?>
        </div>
        <?php bug_blog_site_main_after(); ?>


<?php get_footer(); ?>