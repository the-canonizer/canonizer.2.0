<?php

if ( ! defined('ABSPATH')) exit;  // if direct access

$bug_blog_logo = get_theme_mod('bug_blog_logo');
$bug_blog_social_links = get_theme_mod('bug_blog_social_links');

	?>

<div class="row">
    <div class="container">
        <div class="col-md-8">

		    <?php dynamic_sidebar('footer-widget'); ?>

        </div>
        <div class="col-md-4">

	        <?php
	        if(!empty($bug_blog_logo)):
		        ?>
                <div class="footer-logo">
                    <a href="<?php echo esc_url(home_url()); ?>"><img src="<?php echo esc_url(get_theme_mod('bug_blog_logo'));
				        ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>"></a>
                </div><!-- /Logo -->
		        <?php
	        else:
		        ?>
                <div class="footer-logo">
                    <h1><a href="<?php echo esc_url(home_url()); ?>"><?php echo esc_attr(get_bloginfo('name')); ?></a></h1>
                </div><!-- /Logo -->
		        <?php

	        endif;
	        ?>
            <p class="site-description"><?php echo esc_attr(get_bloginfo('description')); ?></p>
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

<div class="footer-bottom">
    <div class="row">
        <div class="container">

			<?php
			$bug_blog_poweredby = get_theme_mod('bug_blog_poweredby');
			$bug_blog_dev_by = get_theme_mod('bug_blog_dev_by');

			$bug_blog_copyright_text = get_theme_mod('bug_blog_copyright_text');

			?>

            <div class="col-md-12">
				<?php if(empty($bug_blog_poweredby)):?>
                    <span class="poweredby"><?php echo __('Proudly powered by WordPress','bug-blog'); ?></span> |
					<?php
				endif;
				?>

				<?php if(empty($bug_blog_dev_by)):?>
                    <span class="dev-credit"><?php echo sprintf(__('Theme by: Bug Blog by <a href="%s">%s</a>','bug-blog'),'https://dartthemes.com','https://dartthemes.com'); ?>  </span> |
					<?php
				endif;
				?>


				<?php if(empty($bug_blog_copyright_text)):?>
                    <span class="footer-copyright"><?php echo sprintf(__('Copyright %s %s','bug-blog'), date('Y'), esc_url( home_url() )); ?></span>
					<?php
				else:
					?>
                    <span class="footer-copyright"><?php echo esc_html($bug_blog_copyright_text); ?></span>
					<?php

				endif;
				?>





            </div>

        </div>



    </div>
</div>
