<?php


if ( ! defined('ABSPATH')) exit;  // if direct access

//////////////////////////////////////////////////////////////////
// Customizer - Add Settings
//////////////////////////////////////////////////////////////////
function bug_blog_register_theme_customizer( $wp_customize ) {

	// Add Sections


	$wp_customize->add_section( 'bug_blog_site_layout' , array(
		'title'      => __('Site layout', 'bug-blog'),
		'priority'   => 1,
	) );


	$wp_customize->add_setting(
		'bug_blog_site_layout_type',
		array(
			'default'     => 'full_width',
			'sanitize_callback' => 'sanitize_text_field'
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'bug_blog_site_layout_type',
			array(
				'label'      => __('Site wrapper width style', 'bug-blog'),
				'section'    => 'bug_blog_site_layout',
				'settings'   => 'bug_blog_site_layout_type',
				'type'		 => 'select',
				'choices'        => array(
					'full_width' => __('Full Width', 'bug-blog'),
					'box_width' => __('Box Width', 'bug-blog'),


				),
				'priority'	 => 1
			)
		)
	);


	$wp_customize->add_setting(
		'site_wrapper_width',
		array(
			'default'     => '1280px',
			'sanitize_callback' => 'sanitize_text_field'
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'site_wrapper_width',
			array(
				'label'      => __('Box width value(px or %)', 'bug-blog'),
				'section'    => 'bug_blog_site_layout',
				'settings'   => 'site_wrapper_width',
				'type'		 => 'text',

				'priority'	 => 1
			)
		)
	);


	$wp_customize->add_setting(
		'site_wrapper_bg_color',
		array(
			'default'     => '#FFFFFF',
			'sanitize_callback' => 'sanitize_hex_color'
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'site_wrapper_bg_color',
			array(
				'label'      => __('Wrapper Background Color', 'bug-blog'),
				'section'    => 'bug_blog_site_layout',
				'settings'   => 'site_wrapper_bg_color',
				'priority'	 => 2
			)
		)
	);


	$wp_customize->add_setting(
		'container_width',
		array(
			'default'     => '1170px',
			'sanitize_callback' => 'sanitize_text_field'
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'container_width',
			array(
				'label'      => __('Site container width(px or %)', 'bug-blog'),
				'section'    => 'bug_blog_site_layout',
				'settings'   => 'container_width',
				'type'		 => 'text',

				'priority'	 => 3
			)
		)
	);





	/*Header Options*/

	$wp_customize->add_section( 'bug_blog_header' , array(
		'title'      => __('Header', 'bug-blog'),
		'priority'   => 2,
	) );


	$wp_customize->add_setting(
		'header_theme',
		array(
			'default'     => 'header_1',
			'sanitize_callback' => 'sanitize_text_field'
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'header_theme',
			array(
				'label'      => __('Header templates', 'bug-blog'),
				'section'    => 'bug_blog_header',
				'settings'   => 'header_theme',
				'type'		 => 'select',
				'choices'        => array(
					'header_1' => __('Theme 1', 'bug-blog'),
					'header_2' => __('Theme 2', 'bug-blog'),
					'header_3' => __('Theme 3', 'bug-blog'),
					'header_4' => __('Theme 4', 'bug-blog'),
					'header_5' => __('Theme 5', 'bug-blog'),
					'header_6' => __('Theme 6', 'bug-blog'),
					'header_7' => __('Theme 7', 'bug-blog'),
				),
				'priority'	 => 1
			)
		)
	);


	$wp_customize->add_setting(
		'site_header_bg_color',
		array(
			'default'     => '#FFFFFF',
			'sanitize_callback' => 'sanitize_hex_color'
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'site_header_bg_color',
			array(
				'label'      => __('Header Background Color', 'bug-blog'),
				'section'    => 'bug_blog_header',
				'settings'   => 'site_header_bg_color',
				'priority'	 => 2
			)
		)
	);




	$wp_customize->add_setting(
		'site_header_text_color',
		array(
			'default'     => '#FFFFFF',
			'sanitize_callback' => 'sanitize_hex_color'
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'site_header_text_color',
			array(
				'label'      => __('Header Text Color', 'bug-blog'),
				'section'    => 'bug_blog_header',
				'settings'   => 'site_header_text_color',
				'priority'	 => 3
			)
		)
	);


	$wp_customize->add_setting(
		'site_header_link_color',
		array(
			'default'     => '#FFFFFF',
			'sanitize_callback' => 'sanitize_hex_color'
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'site_header_link_color',
			array(
				'label'      => __('Header Link Color', 'bug-blog'),
				'section'    => 'bug_blog_header',
				'settings'   => 'site_header_link_color',
				'priority'	 => 3
			)
		)
	);











	/* Footer Options*/
	$wp_customize->add_section( 'bug_blog_footer' , array(
   		'title'      => __('Footer', 'bug-blog'),
   		'priority'   => 3,
	) );



	$wp_customize->add_setting(
		'footer_theme',
		array(
			'default'     => 'footer_1',
			'sanitize_callback' => 'sanitize_text_field'
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'footer_theme',
			array(
				'label'      => __('Footer templates', 'bug-blog'),
				'section'    => 'bug_blog_footer',
				'settings'   => 'footer_theme',
				'type'		 => 'select',
				'choices'        => array(
					'footer_1' => __('Theme 1', 'bug-blog'),
					'footer_2' => __('Theme 2', 'bug-blog'),
					'footer_3' => __('Theme 3', 'bug-blog'),
				),
				'priority'	 => 1
			)
		)
	);



	$wp_customize->add_setting(
		'site_footer_bg_color',
		array(
			'default'     => '#FFFFFF',
			'sanitize_callback' => 'sanitize_hex_color'
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'site_footer_bg_color',
			array(
				'label'      => __('Footer Background Color', 'bug-blog'),
				'section'    => 'bug_blog_footer',
				'settings'   => 'site_footer_bg_color',
				'priority'	 => 2
			)
		)
	);




	$wp_customize->add_setting(
		'site_footer_text_color',
		array(
			'default'     => '#FFFFFF',
			'sanitize_callback' => 'sanitize_hex_color'
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'site_header_text_color',
			array(
				'label'      => __('Footer Text Color', 'bug-blog'),
				'section'    => 'bug_blog_footer',
				'settings'   => 'site_footer_text_color',
				'priority'	 => 3
			)
		)
	);


	$wp_customize->add_setting(
		'site_footer_link_color',
		array(
			'default'     => '#FFFFFF',
			'sanitize_callback' => 'sanitize_hex_color'
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'site_footer_link_color',
			array(
				'label'      => __('Footer Link Color', 'bug-blog'),
				'section'    => 'bug_blog_footer',
				'settings'   => 'site_footer_link_color',
				'priority'	 => 4
			)
		)
	);


	$wp_customize->add_setting(
			'bug_blog_poweredby',
			array(
				'default'     => false,
				'sanitize_callback' => 'sanitize_text_field'
			)
		);

			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					'bug_blog_poweredby',
					array(
						'label'      => __('Disable Poweredby', 'bug-blog'),
						'section'    => 'bug_blog_footer',
						'settings'   => 'bug_blog_poweredby',
						'type'		 => 'checkbox',
						'priority'	 => 5
					)
				)
			);


		$wp_customize->add_setting(
			'bug_blog_dev_by',
			array(
				'default'     => false,
				'sanitize_callback' => 'sanitize_text_field'
			)
		);

			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					'bug_blog_dev_by',
					array(
						'label'      => __('Disable Develop by', 'bug-blog'),
						'section'    => 'bug_blog_footer',
						'settings'   => 'bug_blog_dev_by',
						'type'		 => 'checkbox',
						'priority'	 => 6
					)
				)
			);


		$wp_customize->add_setting(
			'bug_blog_copyright_text',
			array(
				'sanitize_callback' => 'wp_kses_post'
			)
		);

			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					'bug_blog_copyright_text',
					array(
						'label'      => __('Footer Copyright Text', 'bug-blog'),
						'section'    => 'bug_blog_footer',
						'settings'   => 'bug_blog_copyright_text',
						'type'		 => 'textarea',
						'priority'	 => 7
					)
				)
			);



	/* Article Options*/
	$wp_customize->add_section( 'bug_blog_article' , array(
		'title'      => __('Article', 'bug-blog'),
		'priority'   => 4,
	) );


	$wp_customize->add_setting(
		'article_theme',
		array(
			'default'     => 'article_1',
			'sanitize_callback' => 'sanitize_text_field'
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'article_theme',
			array(
				'label'      => __('Article templates', 'bug-blog'),
				'section'    => 'bug_blog_article',
				'settings'   => 'article_theme',
				'type'		 => 'select',
				'choices'        => array(
					'article_1' => __('Theme 1', 'bug-blog'),
					'article_2' => __('Theme 2', 'bug-blog'),
					'article_3' => __('Theme 3', 'bug-blog'),
					'article_4' => __('Theme 4', 'bug-blog'),

				),
				'priority'	 => 1
			)
		)
	);



		// Header and logo
		$wp_customize->add_setting(
	        'bug_blog_logo',
	        array(
	        	'sanitize_callback' => 'esc_url_raw'
	        )
	    );

		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'bug_blog_logo',
				array(
					'label'      => __('Upload Logo', 'bug-blog'),
					'section'    => 'title_tagline',
					'settings'   => 'bug_blog_logo',
					'priority'	 => 60
				)
			)
		);




	$wp_customize->add_section( 'bug_blog_social' , array(
		'title'      => __('Social', 'bug-blog'),
		'priority'   => 5,
	) );


/*
 *
 * 	$wp_customize->add_setting(
		'bug_blog_social_links',
		array(
			'default'     => '',
			'sanitize_callback' => 'sanitize_text_field'
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Social_links(
			$wp_customize,
			'bug_blog_social_links',
			array(
				'label'      => __('Social Profile Links', 'bug-blog'),
				'section'    => 'bug_blog_social',
				'settings'   => 'bug_blog_social_links',
				'type'		 => 'multi_input',

				'priority'	 => 1
			)
		)
	);
 * */


















			// Color general
			$wp_customize->add_setting(
				'bug_blog_theme_color',
				array(
					'default'     => '#00ACDF',
					'sanitize_callback' => 'sanitize_hex_color'
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'bug_blog_theme_color',
					array(
						'label'      => __('Theme Color', 'bug-blog'),
						'section'    => 'colors',
						'settings'   => 'bug_blog_theme_color',
						'priority'	 => 1
					)
				)
			);






			$wp_customize->add_setting(
				'bug_blog_anchor_color',
				array(
					'default'     => '#23b2dd',
					'sanitize_callback' => 'sanitize_hex_color'
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'bug_blog_anchor_color',
					array(
						'label'      => __('Anchor Color', 'bug-blog'),
						'section'    => 'colors',
						'settings'   => 'bug_blog_anchor_color',
						'priority'	 => 2
					)
				)
			);





			$wp_customize->add_setting(
				'bug_blog_anchor_hover_color',
				array(
					'default'     => '#00ACDF',
					'sanitize_callback' => 'sanitize_hex_color'
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'bug_blog_anchor_hover_color',
					array(
						'label'      => __('Anchor Hover Color', 'bug-blog'),
						'section'    => 'colors',
						'settings'   => 'bug_blog_anchor_hover_color',
						'priority'	 => 3
					)
				)
			);



	
 
}
add_action( 'customize_register', 'bug_blog_register_theme_customizer' );
?>