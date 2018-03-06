<?php

if ( ! defined('ABSPATH')) exit;  // if direct access

?>
<article id="page-<?php the_ID(); ?>" <?php post_class(); ?>>

    <div class="entry-thumbnail">
		<?php the_post_thumbnail(); ?>
    </div>

    <h1 class="title entry-title"><?php the_title(); ?></h1>

    <div class="entry-meta-top">

    </div>

    <div class="entry-content">
		<?php the_content(); ?>
    </div>

    <div class="entry-meta-bottom">

    </div>

	<?php
	if ( comments_open() || get_comments_number() ) : ?>
        <div class="margin-top-40">
			<?php comments_template(); ?>
        </div>
	<?php endif; ?>
</article>




