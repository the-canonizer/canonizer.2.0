<?php

if ( ! defined('ABSPATH')) exit;  // if direct access

$bug_blog_article_class = bug_blog_article_class();

//var_dump($bug_blog_article_class);

?>
<?php bug_blog_article_before(); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class($bug_blog_article_class); ?>>
    <div class="article-inner <?php bug_blog_article_inner_class(); ?>">
	    <?php bug_blog_article_template(); ?>
    </div>
</article>
<?php bug_blog_article_after(); ?>
