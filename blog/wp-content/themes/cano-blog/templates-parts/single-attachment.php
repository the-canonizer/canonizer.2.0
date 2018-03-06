<?php

if ( ! defined('ABSPATH')) exit;  // if direct access

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <div class="entry-thumbnail">
        <img src="<?php echo wp_get_attachment_url(); ?>">
    </div>

    <h1 class="title entry-title">
        <a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a>
    </h1>
    <div class="entry-content">
		<?php the_content(); ?>
    </div>
    <div class="entry-tags text-left"><?php the_tags(); ?></div>

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
        <div class="padding-content white-color margin-top-40">
			<?php comments_template(); ?>
        </div>
	<?php endif; ?>
</article>




