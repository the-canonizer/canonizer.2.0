<?php

if ( ! defined('ABSPATH')) exit;  // if direct access

    //---------------------------------------------------------------------------
    // About me widget
    //---------------------------------------------------------------------------

    class bug_blog_about_me_Widget extends WP_Widget{

        public function __construct() {
            parent::__construct(
                'bug_blog_about_me', // Base ID
                __('Bug blog - About me', 'bug-blog'), // Name
                array('description' => __('Displaying about me', 'bug-blog'),) // Args
            );
        }

        public function form($instance) {

            $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
	        $about_me_data      = isset($instance['about_me']) ? $instance['about_me'] : array();

	        $profile_src_url = isset($about_me_data['profile_src_url']) ? $about_me_data['profile_src_url']: '';
	        $profile_name = isset($about_me_data['profile_name']) ? $about_me_data['profile_name']: '';
	        $profile_descriptions = isset($about_me_data['profile_descriptions']) ? $about_me_data['profile_descriptions']: '';

	        //var_dump($about_me_data);

            ?>
            
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title :', 'bug-blog');
                    ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo sanitize_text_field($title); ?>" />
            </p>

            <div class="widget-about-me">


                <?php

                if(!empty($about_me_data)):

                    ?>
                    <div class="item">

                        <p>
                            <?php _e('Profile image URL:','bug-blog')?><br>
                            <input type="text" placeholder="http://website.com/assets/profile.png" value="<?php echo esc_url_raw($profile_src_url); ?>" name="<?php echo esc_attr($this->get_field_name('about_me')); ?>[profile_src_url]">
                        </p>

                        <p>
                            <?php _e('Name:','bug-blog')?><br>
                            <input type="text" placeholder="Jhon" value="<?php echo esc_attr($profile_name); ?>" name="<?php echo esc_attr($this->get_field_name('about_me')); ?>[profile_name]">
                        </p>

                        <p>
                            <?php _e('Profile descriptions:','bug-blog')?><br>
                            <textarea style="width: 100%;" name="<?php echo esc_attr($this->get_field_name('about_me')).'[profile_descriptions]'; ?>"><?php echo esc_html($profile_descriptions); ?></textarea>

                        </p>



                    </div>
                    <?php




                else:
	                $time = time();
	                ?>
                    <div class="item">
                        <p>
	                        <?php _e('Profile image:','bug-blog')?><br>
                            <input type="text" placeholder="http://website.com/assets/profile.png" value="<?php echo esc_url_raw($profile_src_url); ?>" name="<?php echo esc_attr($this->get_field_name('about_me')); ?>[profile_src_url]">
                        </p>

                        <p>
	                        <?php _e('Name:','bug-blog')?><br>
                            <input type="text" placeholder="" value="<?php echo esc_attr($profile_name); ?>" name="<?php echo esc_attr($this->get_field_name('about_me')); ?>[profile_name]">
                        </p>

                        <p>
	                        <?php _e('Profile descriptions:','bug-blog')?><br>
                            <textarea style="width: 100%;" name="<?php echo esc_attr($this->get_field_name('about_me')).'[profile_descriptions]'; ?>"><?php echo esc_html($profile_descriptions); ?></textarea>

                        </p>
                    </div>
	                <?php

                endif;

                ?>



            </div>

        <?php
        }

        public function update($new_instance, $old_instance)
        {
            $instance                 = array();
            $instance['title'] = strip_tags($new_instance['title']);

	        $instance['about_me']      = (!empty($new_instance['about_me'])) ? $new_instance['about_me']: array();



            return $instance;
        }

        public function widget($args, $instance)
        {

            $title = apply_filters('widget_title', empty($instance['title']) ? __('About me', 'bug-blog') :
            $instance['title'], $instance, $this->id_base);


            $about_me_data = $instance['about_me'];
	        $profile_src_url = esc_url_raw($about_me_data['profile_src_url']);
	        $profile_name = esc_attr($about_me_data['profile_name']);
	        $profile_descriptions = esc_html($about_me_data['profile_descriptions']);

            echo $args[ 'before_widget' ];
            if (!empty($title))
                echo $args[ 'before_title' ] . $title . $args[ 'after_title' ];


            ?>

            <div class="about-me">

                <div class="thumb"><img src="<?php echo esc_url_raw($profile_src_url); ?>" /> </div>
                <div class="name"><?php echo esc_attr($profile_name); ?></div>
                <p class="descriptions"><?php echo esc_html($profile_descriptions); ?></p>

            </div>




            <?php
            echo $args[ 'after_widget' ];
        }
    }


    // register widgets
    if (!function_exists('bug_blog_about_me_register')) {
        function bug_blog_about_me_register()
        {
            register_widget('bug_blog_about_me_Widget');
        }

       add_action('widgets_init', 'bug_blog_about_me_register');
    }

