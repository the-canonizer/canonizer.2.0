<?php

if ( ! defined('ABSPATH')) exit;  // if direct access

	?>

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
    <h1 class="title entry-title">
        <a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a>
    </h1>

    <div class="entry-thumbnail">
        <?php the_post_thumbnail(); ?>
    </div>

    <div class="entry-content">
		<?php the_content(); ?>
    </div>
    <div class="entry-meta-bottom">
        <div class="entry-tags text-left"><?php the_tags(); ?></div>
    </div>

