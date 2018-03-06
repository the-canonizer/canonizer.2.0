<?php if (!defined('ABSPATH')) exit;  // if direct access ?>

            <?php bug_blog_site_footer_before(); ?>
            <footer class="site-footer <?php bug_blog_site_footer_class(); ?>">
	            <?php bug_blog_site_footer(); ?>
            </footer> <!-- .footer end -->
            <?php bug_blog_site_footer_after(); ?>

            <?php bug_blog_site_wrapper_bottom(); ?>
        </div> <!-- .site-wrapper end-->
        <?php bug_blog_site_wrapper_after(); ?>

    <?php wp_footer(); ?>
    </body>
</html>