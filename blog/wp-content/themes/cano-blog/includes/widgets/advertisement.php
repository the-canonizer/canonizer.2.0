<?php

if ( ! defined('ABSPATH')) exit;  // if direct access

    //---------------------------------------------------------------------------
    // Ads Banner widget
    //---------------------------------------------------------------------------

    class bug_blog_advertisement_Widget extends WP_Widget{

        public function __construct() {
            parent::__construct(
                'bug_blog_advertisement', // Base ID
                __('Bug blog - Ads Banner', 'bug-blog'), // Name
                array('description' => __('Displaying ads banner', 'bug-blog'),) // Args
            );
        }

        public function form($instance) {



            $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
	        $advertisement_data      = (isset($instance['advertisement'])) ? $instance['advertisement'] : array();

	        $widget_id = $this->get_field_id('id');

	        //var_dump($this);

            ?>
            
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title :', 'bug-blog');
                    ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo sanitize_text_field($title); ?>" />
            </p>

            <div id="<?php echo esc_attr($widget_id); ?>" class="widget-advertisement">
                <div class="add button" widget-id="<?php echo esc_attr($widget_id); ?>" name-data="<?php echo esc_attr( $this->get_field_name('advertisement') ); ?>"><?php _e('Add', 'bug-blog'); ?></div>
                <div class="item-list ui-sortable">
                <?php



                if(!empty($advertisement_data)):
                foreach ($advertisement_data as $index=>$link_data){

                   //var_dump($link_data);

                    $banner_src_url = esc_url_raw($link_data['banner_src_url']);
	                $banner_target_url = esc_url_raw($link_data['banner_target_url']);
	                $banner_width = esc_attr($link_data['banner_width']);
	                $banner_height = esc_attr($link_data['banner_height']);


                    ?>
                    <div class="item">

                        <p>
                            <?php _e('Banner image url:','bug-blog')?><br>
                            <input type="text" placeholder="http://website.com/assets/banner.png" value="<?php echo esc_url_raw($banner_src_url); ?>" name="<?php echo esc_attr($this->get_field_name('advertisement')); ?>[<?php echo esc_attr($index); ?>][banner_src_url]">
                        </p>

                        <p>
                            <?php _e('Banner target url:','bug-blog')?><br>
                            <input type="text" placeholder="http://go.clientwebsite.com" value="<?php echo esc_url_raw($banner_target_url); ?>" name="<?php echo esc_attr($this->get_field_name('advertisement')); ?>[<?php echo esc_attr($index); ?>][banner_target_url]">
                        </p>

                        <p>
                            <input type="text" placeholder="Width: 350px" value="<?php echo esc_attr($banner_width); ?>" name="<?php echo esc_attr($this->get_field_name('advertisement')); ?>[<?php echo esc_attr($index); ?>][banner_width]">
                            <input type="text" placeholder="Height: 180px" value="<?php echo esc_attr($banner_height); ?>" name="<?php echo esc_attr($this->get_field_name('advertisement')); ?>[<?php echo esc_attr($index); ?>][banner_height]">

                        </p>


                        <span class="remove"><i class="fa fa-times" aria-hidden="true"></i></span>
                        <span class="sort"><i class="fa fa-bars" aria-hidden="true"></i></span>
                    </div>
                    <?php


                }

                else:
	                $time = time();
	                ?>
                    <div class="item">

                        <p>
                            <?php _e('Banner image url:','bug-blog')?><br>
                            <input type="text" placeholder="http://website.com/assets/banner.png" value="" name="<?php echo esc_attr($this->get_field_name('advertisement')); ?>[<?php echo esc_attr($time); ?>][banner_src_url]">
                        </p>
                        <p>
                            <?php _e('Banner target url:','bug-blog')?><br>
                            <input type="text" placeholder="http://go.clientwebsite.com" value="" name="<?php echo esc_attr($this->get_field_name('advertisement')); ?>[<?php echo esc_attr($time); ?>][banner_target_url]">
                        </p>

                        <p>
                            <input type="text" placeholder="Width: 350px" value="" name="<?php echo esc_attr($this->get_field_name('advertisement')); ?>[<?php echo esc_attr($time); ?>][banner_width]">
                            <input type="text" placeholder="Height: 180px" value="" name="<?php echo esc_attr($this->get_field_name('advertisement')); ?>[<?php echo esc_attr($time); ?>][banner_height]">

                        </p>




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

	        $instance['advertisement']      = (!empty($new_instance['advertisement'])) ? $new_instance['advertisement']: array();


            return $instance;
        }

        public function widget($args, $instance)
        {

            $title = apply_filters('widget_title', empty($instance['title']) ? __('Ads Banner', 'bug-blog') :
                $instance['title'], $instance, $this->id_base);


	            $advertisement_data = $instance['advertisement'];


            echo $args[ 'before_widget' ];
            if (!empty($title))
                echo $args[ 'before_title' ] . $title . $args[ 'after_title' ];


            ?>

            <div class="advertisement-link">
                <ul class="list-inline">

                    <?php
                    if(!empty($advertisement_data))
                    foreach ($advertisement_data as $link_data){

	                    $banner_src_url = esc_url_raw($link_data['banner_src_url']);
	                    $banner_target_url = esc_url_raw($link_data['banner_target_url']);
	                    $banner_width = esc_attr($link_data['banner_width']);
	                    $banner_height = esc_attr($link_data['banner_height']);

	                    //var_dump($advertisement_data);

	                    if (!empty($banner_target_url)) { ?>
                            <li ><a href="<?php echo esc_url_raw($banner_target_url); ?>" class="banner-link"><img style="width:<?php echo esc_attr($banner_width); ?> ;height:<?php echo esc_attr($banner_height); ?> " src="<?php echo esc_url_raw($banner_src_url); ?>" /></a></li>
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
    if (!function_exists('bug_blog_advertisement_register')) {
        function bug_blog_advertisement_register()
        {
            register_widget('bug_blog_advertisement_Widget');
        }

       add_action('widgets_init', 'bug_blog_advertisement_register');
    }

