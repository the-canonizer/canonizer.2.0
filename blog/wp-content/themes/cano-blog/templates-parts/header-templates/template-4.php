<?php

if ( ! defined('ABSPATH')) exit;  // if direct access

    $bug_blog_logo = get_theme_mod('bug_blog_logo');
    $bug_blog_social_links = get_theme_mod('bug_blog_social_links');
	?>
    <div class="row">
        <div class="container">
            <div class="col-md-3">
                <?php
                if(!empty($bug_blog_logo)):
                    ?>
                    <div class="main-logo">
                        <a href="<?php echo esc_url(home_url()); ?>"><img src="<?php echo esc_url(get_theme_mod('bug_blog_logo'));
                            ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>"></a>
                    </div><!-- /Logo -->
                    <?php
                else:
                    ?>
                    <div class="main-logo">
                        <h1><a href="<?php echo esc_url(home_url()); ?>"><?php echo esc_attr(get_bloginfo('name')); ?></a></h1>
                    </div><!-- /Logo -->
                    <?php

                endif;
                ?>

            </div>
            <div class="col-md-6">
                <div id="" class="navigation">
                    <?php wp_nav_menu( array('container' => false, 'theme_location' => 'header-menu', 'menu_class' => 'menu float-left')); ?>
                </div>
                <div class="menu-mobile"></div>
            </div>

            <div class="col-md-3">
                <div class="social-link">
		            <?php
		            foreach ($bug_blog_social_links as $link){
			            $link_icon = $link['link_icon'];
			            $link_url = $link['link_url'];
			            ?>
                        <a href="<?php echo esc_url($link_url); ?>"><?php echo $link_icon; ?></a>
			            <?php
		            }
		            ?>

                </div>
            </div>



        </div>
    </div>

