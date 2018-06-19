jQuery(document).ready(function($) {

	// single template
	
	if( $('#epl_display_single_property').length > 0 ) {

		var SingleTempPreview = '<div class="single-temp-preview epl-third-right"></div>';
		$('#epl_display_single_property').parent().after(SingleTempPreview);
		$('.single-temp-preview').siblings('.epl-half-left').toggleClass('epl-half-left epl-third-left');
		$('.single-temp-preview').siblings('.epl-half-right').toggleClass('epl-half-right epl-third-middle');
		var sel = $('#epl_display_single_property').val();
		if( sel > 0) {
			var img = epl_temp_url+'assets/'+epl_temp_single[sel]+'.jpg';
			$('.single-temp-preview').html('<a class="thickbox" href="'+img+'"><img src="'+img+'"/></a>');
		}
		$('#epl_display_single_property').on('change',function() {
			var sel = $(this).find('option:selected').val();
			if(epl_temp_single[sel] != undefined) {
				var img = epl_temp_url+'assets/'+epl_temp_single[sel]+'.jpg';
				$('.single-temp-preview').html('<a class="thickbox" href="'+img+'"><img src="'+img+'"/></a>');
			} 
		});
	}
	
	
	// archives template
	if( $('#epl_property_card_style').length > 0 ) {
		var ArchiveTempPreview = '<div class="archive-temp-preview epl-field"></div>';
		$('#epl_property_card_style').closest('.epl-field').after(ArchiveTempPreview);
		var sel = $('#epl_property_card_style').val();
		if( sel > 0) {
			var img = epl_temp_url+'assets/'+epl_temp_archive[sel]+'.jpg';
			$('.archive-temp-preview').html('<a class="thickbox" href="'+img+'"><img src="'+img+'"/></a>');
		}
		$('#epl_property_card_style').on('change',function() {
			sel = $(this).find('option:selected').val();
			if(epl_temp_archive[sel] != undefined) {
				var img = epl_temp_url+'assets/'+epl_temp_archive[sel]+'.jpg';
				$('.archive-temp-preview').html('<a class="thickbox" href="'+img+'"><img src="'+img+'"/></a>');
			} 
		});
	}
	
});
