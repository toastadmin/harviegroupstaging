jQuery(document).ready(function(){

	jQuery('ul.location-profiles-tabs li').click(function(){
		var tab_id = jQuery(this).attr('data-tab');

		jQuery('ul.location-profiles-tabs li').removeClass('location-profiles-current');
		jQuery('.location-profiles-tab-content').removeClass('location-profiles-current');

		jQuery(this).addClass('location-profiles-current');
		jQuery("#"+tab_id).addClass('location-profiles-current');
	})
	
})
