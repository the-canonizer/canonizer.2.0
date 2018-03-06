<?php

if ( ! defined('ABSPATH')) exit;  // if direct access
?>


<form role="search" method="get" id="searchform" action="<?php echo esc_url(home_url( '/' )); ?>">
    <div>
       <input type="text" placeholder="<?php esc_attr_e('Search &hellip;', 'bug-blog'); ?>" name="s" id="s" value="<?php echo get_search_query(); ?>" />
	 </div>
</form>