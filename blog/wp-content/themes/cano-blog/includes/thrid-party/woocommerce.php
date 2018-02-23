<?php

if ( ! defined('ABSPATH')) exit;  // if direct access

add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
	add_theme_support( 'woocommerce' );
}


remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);



add_action('woocommerce_before_main_content', 'bug_blog_woocommerce_before_main_content', 10);
add_action('woocommerce_after_main_content', 'bug_blog_woocommerce_after_main_content', 10);

function bug_blog_woocommerce_before_main_content() {

	?>
	<div class="container">
		<div class="row">
			<div class="col-md-8">
	<?php

}

function bug_blog_woocommerce_after_main_content() {

	?>
	</div> <!-- .col-md-8 -->
			<div class="col-md-4">
				<?php dynamic_sidebar('blog-widget'); ?>
			</div> <!-- .col-md-8 -->
		</div><!-- .row -->
	</div> <!-- .container -->
	<?php

}