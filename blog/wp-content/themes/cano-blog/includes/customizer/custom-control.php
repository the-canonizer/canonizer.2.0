<?php

if ( ! defined('ABSPATH')) exit;  // if direct access

if( class_exists( 'WP_Customize_Control' ) ):
	class WP_Customize_Social_links extends WP_Customize_Control {
		public $type = 'social_links';

		public function render_content() {


		    //var_dump($this->link());

		    $bug_blog_social_links = bug_blog_social_links();

			?>
            <div class="social-links">
                <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
                <p><?php echo esc_html( $this->description ); ?></p>

	            <?php

	            foreach ($bug_blog_social_links as $link){

		            ?>
                    <div class="item">
                        <div class="name"><?php echo $link['name']; ?></div>
                        <div class=""><input placeholder="" name="" <?php $this->link(); ?> type="text" value="<?php echo esc_attr($link['icon']); ?>"></div>
                        <div class=""><input placeholder="social profile url" <?php $this->link(); ?> type="text" value="<?php echo $link['url']; ?>"></div>

                    </div>
		            <?php
	            }

	            ?>

            </div>


			<?php
		}
	}
endif;



Kirki::add_panel( 'bug_blog_contact', array(
	'priority'    => 10,
	'title'       => __( 'Contact & Social', 'bug-blog' ),
	//'description' => __( '', 'bug-blog' ),
) );;


Kirki::add_section( 'bug_blog_social', array(
	'title'          => __( 'Social Profile Links', 'bug-blog' ),
	'description'    => __( 'Social media site profile links.', 'bug-blog' ),
	'panel'          => 'bug_blog_contact', // Not typically needed.
	'priority'       => 160,
	'capability'     => 'edit_theme_options',
	'theme_supports' => '', // Rarely needed.
) );


Kirki::add_field( 'bug_blog_social_links', array(
	'type'        => 'repeater',
	'label'       => esc_attr__( 'Social Profile Links', 'bug-blog' ),
	'section'     => 'bug_blog_social',
	'priority'    => 10,
	'row_label' => array(
		'type' => 'text',
		'value' => esc_attr__('Social link', 'bug-blog' ),
	),
	'settings'    => 'bug_blog_social_links',
	'default'     => array( bug_blog_social_links()
	),
	'fields' => array(
		'link_icon' => array(
			'type'        => 'textarea',
			'label'       => esc_attr__( 'Link Icon', 'bug-blog' ),
			'description' => esc_attr__( 'This will be the icon for your link', 'bug-blog' ),
			'default'     => '',
			//'sanitize_callback' => 'sanitize_bug_blog_social'
	),
		'link_url' => array(
			'type'        => 'link',
			'label'       => esc_attr__( 'Link URL', 'bug-blog' ),
			'description' => esc_attr__( 'This will be the link URL', 'bug-blog' ),
			'default'     => '',
		),
	)
) );





