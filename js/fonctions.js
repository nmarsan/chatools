/*jQuery(document).ready(function (){
	
	
});
*/
jQuery(document).ready(function (){		//modif heure embauche	jQuery('.heure_embauche').click(function(){		jQuery(this).parent().find('.valid_heure_embauche, .value_heure_embauche, .heure_embauche').toggle();			return false;	});	jQuery('.valid_heure_embauche').click(function(){			var newVal=jQuery(this).parent().find('.value_heure_embauche').val();		if(newVal==''){			newVal='8h50';		}		var href=jQuery(this).attr('href')+'&heure='+newVal;			jQuery.ajax({				url: href,				success: function(data) {									}			});				jQuery(this).parent().find('.heure_embauche').text(newVal)		jQuery(this).parent().find('.valid_heure_embauche, .value_heure_embauche, .heure_embauche').toggle();		return false;	});		
	jQuery.datepicker.setDefaults(jQuery.datepicker.regional['']);

	jQuery("#date_jour").datepicker(jQuery.datepicker.regional['fr']);
	jQuery("#date_jour").datepicker('option', {dateFormat: 'DD dd MM yy'});

	jQuery('.actionShowWeek').click(function(){
		jQuery('.'+jQuery(this).attr('id')).slideDown();
		jQuery('.btnH'+jQuery(this).attr('id')).show();
		jQuery('.btnD'+jQuery(this).attr('id')).hide();
		return false;
	});
	
	jQuery('.actionHideWeek').click(function(){
		jQuery('.'+jQuery(this).attr('id')).slideUp();
		jQuery('.btnH'+jQuery(this).attr('id')).hide();
		jQuery('.btnD'+jQuery(this).attr('id')).show();
		return false;
	});
	
	jQuery('#open_all').click(function(){
		jQuery('.contentWeek').show();
		jQuery('.actionHideWeek').show();
		jQuery('.actionShowWeek').hide();
	});
	jQuery('#close_all').click(function(){
		jQuery('.contentWeek').hide();
		jQuery('.actionHideWeek').hide();
		jQuery('.actionShowWeek').show();
	});
	
});