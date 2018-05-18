jQuery('head').append('<link rel="stylesheet" href="https://canonizer.com/canonizer/public/css/canonizer.css" type="text/css" />');


/* Following function will call REST getcamoutline API to get the complete tree outline */

/* Params :  topic_num, camp_num */

/* Developer : Karun Gautam */


function outlinecall(topic_num,camp_num) {

	jQuery.getJSON('http://production.canonizer.com/api/v1/getcampoutline/'+topic_num+'/'+camp_num)
	  .done(function(data){
		 jQuery('.post').append("<div class='tree treeview'><ul class='mainouter'>"+data.outline+"</ul></div>");
	})

}

function camptree() {
	
	jQuery('.tree li:has(ul)').addClass('parent_li').find(' > span ').attr('title', 'Collapse');
    jQuery('.tree li.parent_li > span').on('click', function (e) {
        var children = jQuery(this).parent('li.parent_li').find(' > ul > li');
        if (children.is(":visible")) {
            children.hide('fast');
            jQuery(this).attr('title', 'Expand this branch').find(' > i').addClass('fa-arrow-right').removeClass('fa-arrow-down');
			e.stopImmediatePropagation();
        } else {
            children.show('fast');
            jQuery(this).attr('title', 'Collapse this branch').find(' > i').addClass('fa-arrow-down').removeClass('fa-arrow-right');
			e.stopImmediatePropagation();
        }
				
        e.stopPropagation();
    });

    jQuery('.tree').find('li.parent_li').find(' > ul > li').hide('fast');
	jQuery('.treeview').find('li.parent_li').find(' > ul > li').show('fast');
	
}

jQuery('document').ready(function () { 
    
	
    
});