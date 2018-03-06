<?php

if ( ! defined('ABSPATH')) exit;  // if direct access

    //---------------------------------------------------------------------------
    // Social icons widget
    //---------------------------------------------------------------------------

    class bug_blog_social_Widget extends WP_Widget{

        public function __construct() {
            parent::__construct(
                'bug_blog_social_links', // Base ID
                __('Bug blog - Social Links', 'bug-blog'), // Name
                array('description' => __('Displaying social icons', 'bug-blog'),) // Args
            );
        }

        public function form($instance) {

            $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
	        $social_links      = (isset($instance['social_links'])) ? $instance['social_links'] : array();


	        //var_dump($instance);

            ?>
            
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title :', 'bug-blog');
                    ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo sanitize_text_field($title); ?>" />
            </p>

            <div class="widget-social-input">
                <div class="add button" name-data="<?php echo esc_attr( $this->get_field_name('social_links') ); ?>"><?php _e('Add', 'bug-blog'); ?></div>
                <div class="item-list ui-sortable">
                <?php



                if(!empty($social_links)):
                foreach ($social_links as $index=>$link_data){

                    //var_dump($link_data);

                    $icon = esc_attr($link_data['icon']);
	                $url = esc_url_raw($link_data['url']);
	                $bg_color = esc_attr($link_data['bg_color']);
	                $color = esc_attr($link_data['color']);

                    ?>
                    <div class="item">

                        <input type="text" placeholder="fa-facebook" value="<?php echo esc_attr($icon); ?>" name="<?php echo esc_attr($this->get_field_name('social_links')); ?>[<?php echo esc_attr($index); ?>][icon]">
                        <input type="text" placeholder="profile url" value="<?php echo esc_url_raw($url); ?>" name="<?php echo esc_attr( $this->get_field_name('social_links') ); ?>[<?php echo esc_attr($index); ?>][url]">
                        <br>
                        <input class="bug_blog_color" type="text" placeholder="#ffffff" value="<?php echo esc_attr($bg_color); ?>" name="<?php echo esc_attr( $this->get_field_name('social_links') ); ?>[<?php echo esc_attr($index); ?>][bg_color]">
                        <input class="bug_blog_color" type="text" placeholder="#fffff" value="<?php echo esc_attr($color); ?>" name="<?php echo esc_attr( $this->get_field_name('social_links') ); ?>[<?php echo esc_attr($index); ?>][color]">
                        <span class="remove"><i class="fa fa-times" aria-hidden="true"></i></span>
                        <span class="sort"><i class="fa fa-bars" aria-hidden="true"></i></span>
                    </div>
                    <?php


                }

                else:
	                $time = time();
	                ?>
                    <div class="item">

                        <input type="text" placeholder="fa-facebook" value="" name="<?php echo esc_attr( $this->get_field_name('social_links') ); ?>[<?php echo esc_attr($time); ?>][icon]">
                        <input type="text" placeholder="profile url" value="" name="<?php echo esc_attr( $this->get_field_name('social_links') ); ?>[<?php echo esc_attr($time); ?>][url]">
                        <input class="bug_blog_color" type="text" placeholder="#fffff" value="" name="<?php echo esc_attr( $this->get_field_name('social_links') ); ?>[<?php echo esc_attr($time); ?>][bg_color]">
                        <input class="bug_blog_color" type="text" placeholder="#fffff" value="" name="<?php echo esc_attr( $this->get_field_name('social_links') ); ?>[<?php echo esc_attr($time); ?>][color]">
                        <span class="remove"><i class="fa fa-times" aria-hidden="true"></i></span>
                        <span class="sort"><i class="fa fa-bars" aria-hidden="true"></i></span>
                    </div>
	                <?php

                endif;

                ?>
                </div>


            </div>

        <?php
        }

        public function update($new_instance, $old_instance)
        {
            $instance                 = array();
            $instance['title'] = strip_tags($new_instance['title']);

	        $instance['social_links']      = (!empty($new_instance['social_links'])) ? $new_instance['social_links']: array();


            return $instance;
        }

        public function widget($args, $instance)
        {

            $title = apply_filters('widget_title', empty($instance['title']) ? __('Social Icons', 'bug-blog') :
                $instance['title'], $instance, $this->id_base);


	            $social_links = $instance['social_links'];


            echo $args[ 'before_widget' ];
            if (!empty($title))
                echo $args[ 'before_title' ] . $title . $args[ 'after_title' ];


            ?>

            <div class="social-link">
                <ul class="list-inline">

                    <?php
                    if(!empty($social_links))
                    foreach ($social_links as $link_data){
	                    $icon = $link_data['icon'];
	                    $url = $link_data['url'];
	                    $bg_color = $link_data['bg_color'];
	                    $color = $link_data['color'];

	                    if (!empty($url)) { ?>
                            <li ><a style="<?php if(!empty($bg_color)) echo 'background-color:'.esc_attr($bg_color).';'; ?><?php if(!empty($color)) echo 'color:'.esc_attr($color).';'; ?>" href="<?php echo esc_url($url) ?>" class="facebook"><i class="fa <?php echo esc_attr($icon); ?>"></i></a></li>
	                    <?php }
                    }

                       ?>

 
                </ul>                  
            </div>   


            <?php
            echo $args[ 'after_widget' ];
        }
    }


    // register widgets
    if (!function_exists('bug_blog_social_icon_register')) {
        function bug_blog_social_icon_register()
        {
            register_widget('bug_blog_social_Widget');
        }

       add_action('widgets_init', 'bug_blog_social_icon_register');
    }

