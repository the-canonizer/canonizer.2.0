<?php

if ( ! defined('ABSPATH')) exit;  // if direct access

/**
 * Implements the Custom Header feature
 * http://codex.wordpress.org/Custom_Headers
 *
 * @package TextBook
 */

/**
 * Set up the WordPress core custom header feature.
 *
 * @uses bug_blog_header_style()
 */
function bug_blog_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'bug_blog_custom_header_args', array(
		'default-image'          => '',
		'default-text-color'     => '000000',
		'width'                  => 1980,
		'height'                 => 960,
		'flex-height'            => true,
		'wp-head-callback'       => 'bug_blog_header_style',
	) ) );
}
add_action( 'after_setup_theme', 'bug_blog_custom_header_setup' );

if ( ! function_exists( 'bug_blog_header_style' ) ) :
	/**
	 * Styles the header image and text displayed on the blog
	 *
	 * @see bug_blog_custom_header_setup().
	 */
	function bug_blog_header_style() {
		$header_text_color = get_header_textcolor();

		
		add_theme_support( 'custom-header' ) 
		
		// If we get this far, we have custom styles. Let's do this.
		?>
        <style type="text/css">
            <?php
				// Has the text been hidden?
				if ( 'blank' == $header_text_color ) :
			?>
            .site-title,
            .site-description {
                position: absolute;
                clip: rect(1px, 1px, 1px, 1px);
            }
            <?php
				// If the user has set a custom color for the text use that.
				else :
			?>
            .site-title a,
            .site-description {
                color: #<?php echo esc_attr( $header_text_color ); ?>;
            }
            <?php endif; ?>
        </style>
		<?php
	}
endif; // bug_blog_header_style