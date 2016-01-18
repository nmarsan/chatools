/*jQuery(document).ready(function (){
	
	
});
*/
jQuery(document).ready(function (){
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