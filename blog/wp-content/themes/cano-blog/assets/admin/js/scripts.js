jQuery(document).ready(function($) {


    jQuery('.bug_blog_color').wpColorPicker();


    $( ".widget-social-input .item-list" ).sortable({ handle: '.sort' });
    $( ".widget-advertisement .item-list" ).sortable({ handle: '.sort' });



    $(document).on('click', ".widget-social-input .item .remove, .widget-advertisement .item .remove ", function(){

        $(this).parent().remove();

    })





    $(document).on('click', ".widget-social-input .add", function(){

        var name_field = $(this).attr('name-data');

        var unique_key = $.now();


        var html = '<div class="item"><input type="text" placeholder="fa-facebook" value="" name="'+name_field+'['+unique_key+'][icon]"> <input type="text" placeholder="profile url" value="" name="'+name_field+'['+unique_key+'][url]"> <span class="remove"><i class="fa fa-times" aria-hidden="true"></i></span> <span class="sort"><i class="fa fa-bars" aria-hidden="true"></i></span><br><input class="bug_blog_color" placeholder="#ffffff" value="" name="'+name_field+'['+unique_key+'][bg_color]" type="text"> <input class="bug_blog_color" placeholder="#fffff" value="" name="'+name_field+'['+unique_key+'][color]" type="text"></div>';

        $(".widget-social-input").append(html);

        //$('.bug_blog_color').wpColorPicker();

    })



    $(document).on('click', ".widget-advertisement .add", function(){

        var name_field = $(this).attr('name-data');
        var widget_id = $(this).attr('widget-id');

        var unique_key = $.now();


        var html = '<div class="item"><p>Banner image url<br><input type="text" placeholder="http://website.com/assets/banner.png" value="" name="'+name_field+'['+unique_key+'][banner_src_url]"></p><p>Banner target url<br><input type="text" placeholder="http://go.clientwebsite.com" value="" name="'+name_field+'['+unique_key+'][banner_target_url]"></p><p><input type="text" placeholder="Width: 350px" value="" name="'+name_field+'['+unique_key+'][banner_width]"> <input type="text" placeholder="Height: 180px" value="" name="'+name_field+'['+unique_key+'][banner_height]"></p><span class="remove"><i class="fa fa-times" aria-hidden="true"></i></span><span class="sort"><i class="fa fa-bars" aria-hidden="true"></i></span></div>';

        $("#"+widget_id+" .item-list").append(html);

        //$('.bug_blog_color').wpColorPicker();

    })








})




