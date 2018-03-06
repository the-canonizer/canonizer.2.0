<?php

if ( ! defined('ABSPATH')) exit;  // if direct access

?>

	<h1 class="title page-header">
		<?php the_title(); ?>
	</h1>
	<div class="entry-thumbnail">
		<?php the_post_thumbnail(); ?>
	</div>
	<div class="entry-content">
		<?php the_content(); ?>
	</div>
    <?php
    wp_link_pages( array(
        'before' => '<div class="page-links">' . __( 'Pages:', 'bug-blog' ),
        'after'  => '</div>',
    ) );
    ?>





