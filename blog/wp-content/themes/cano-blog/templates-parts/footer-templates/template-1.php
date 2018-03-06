<?php

if ( ! defined('ABSPATH')) exit;  // if direct access

	?>

<div class="row">
    <div class="container">

        <div class="col-md-12">
            <div class="footer-widget">
				<?php dynamic_sidebar('footer-widget'); ?>
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
                   <!-- <span class="poweredby"><?php echo __('Proudly powered by WordPress','bug-blog'); ?></span> |-->
					<?php
				endif;
				?>

				<?php if(empty($bug_blog_dev_by)):?>
                   <!-- <span class="dev-credit"><?php echo sprintf(__('Theme by: Bug Blog by <a href="%s">%s</a>','bug-blog'),'https://dartthemes.com','https://dartthemes.com'); ?>  </span> | -->
					<?php
				endif;
				?>


				<?php if(empty($bug_blog_copyright_text)):?>
                    <span class="footer-copyright">Copyright 2018 Canonizer.2.0<?php //echo sprintf(__('Copyright %s %s','bug-blog'), date('Y'), esc_url( home_url() )); ?></span>
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
