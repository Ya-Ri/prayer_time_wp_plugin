jQuery(document).ready(function(){
	jQuery('#cal-form').on('submit',function(e){
		prayerFormChange(this);
		e.preventDefault();
	});
	jQuery('#locForm').on('submit',function(e){
		prayerFormChange(this);
		e.preventDefault();
	});
	jQuery('#revealMeAnchore').on('click',function(e){
		jQuery('#locForm').slideToggle();
		e.preventDefault();
	});

	jQuery('#cal').on('click',function(e){
		jQuery('#cal').slideUp(400,function(){
			jQuery('#cal-form').slideDown();
		});
		e.preventDefault();
	}); 
	   jQuery('#ma-date').datepicker({
	        dateFormat : 'mm/dd/yy'
	    });
	
});

function prayerFormChange(form){
	jQuery('.ma-cal').slideUp(400,function(){jQuery(form).html('');});
	var data = jQuery(form).serializeArray();
	jQuery.post(ajax_url,data,function(response){
		jQuery('.ma-cal').html(response).delay(50);
		jQuery('.ma-cal').slideDown();
		resetjQuery();
	});
}

function resetjQuery(){
	jQuery('#cal-form').on('submit',function(e){
		prayerFormChange(this);
		e.preventDefault();
	});
	jQuery('#locForm').on('submit',function(e){
		prayerFormChange(this);
		e.preventDefault();
	});
	jQuery('#revealMeAnchore').on('click',function(e){
		jQuery('#locForm').slideToggle();
		e.preventDefault();
	});

	jQuery('#cal').on('click',function(e){
		jQuery('#cal').slideUp(400,function(){
			jQuery('#cal-form').slideDown();
		});
		e.preventDefault();
	}); 
	   jQuery('#ma-date').datepicker({
	        dateFormat : 'mm/dd/yy'
	    });
}
