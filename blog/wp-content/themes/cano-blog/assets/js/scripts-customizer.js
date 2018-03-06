jQuery(document).ready(function($) {
    "use strict";
// *************  Multi_Input_Custom_control  *********************
    function customize_multi_write($element){
        var customize_multi_val = '';
        $element.find('.customize_multi_fields .customize_multi_single_field').each(function(){
            customize_multi_val += $(this).val()+'|';
        });
        $element.find('.customize_multi_value_field').val(customize_multi_val.slice(0, -1)).change();
    }
    function customize_multi_add_field(e){
        e.preventDefault();
        var $control = $(this).parents('.customize_multi_input');
        $control.find('.customize_multi_fields').append('<div class="set"><input type="text" value="" class="customize_multi_single_field" /><a href="#" class="customize_multi_remove_field">X</a></div>');
    }
    function customize_multi_single_field() {
        var $control = $(this).parents('.customize_multi_input');
        customize_multi_write($control);
    }
    function customize_multi_remove_field(e){
        e.preventDefault();
        var $this = $(this);
        var $control = $this.parents('.customize_multi_input');
        $this.parent().remove();
        customize_multi_write($control);
    }
    $(document).on('click', '.customize_multi_add_field', customize_multi_add_field)
        .on('keyup', '.customize_multi_single_field', customize_multi_single_field)
        .on('click', '.customize_multi_remove_field', customize_multi_remove_field);
    $('.customize_multi_input').each(function(){
        var $this = $(this);
        var multi_saved_value = $this.find('.customize_multi_value_field').val();
        if(multi_saved_value.length>0){
            var multi_saved_values = multi_saved_value.split("|");
            $this.find('.customize_multi_fields').empty();
            $.each(multi_saved_values, function( index, value ) {
                $this.find('.customize_multi_fields').append('<div class="set"><input type="text" value="'+value+'" class="customize_multi_single_field" /><a href="#" class="customize_multi_remove_field">X</a></div>');
            });
        }
    });
// *************  Multi_Input_Custom_control END *********************
});