jQuery(document).ready(function($){

	/* render default map for single listings */
	if ( $('#epl-default-map').length ) {
	
		var eplgeocoder, epldefaultmap;
		var listingcoordinates 	= $('#epl-default-map').data('cord');
		var listingaddress	= $('#epl-default-map').data('address');
		var listingmapzoom	= $('#epl-default-map').data('zoom');
		var listid		= $('#epl-default-map').data('id');
		var suburb_mode		= $('#epl-default-map').data('suburb_mode');
		
		// use listing coordinates if already present
		if(listingcoordinates != '') {
		
			listingcoordinates = listingcoordinates.split(',');
			
			function renderdefaultmap() {
			  eplgeocoder = new google.maps.Geocoder();
			  eplmapcord = new google.maps.LatLng(listingcoordinates[0],listingcoordinates[1]);

			  var mapOptions = {
				center: eplmapcord,
				zoom: listingmapzoom,
			  }
			  
			  epldefaultmap = new google.maps.Map(document.getElementById('epl-default-map'), mapOptions);
			  
			  var epldefaultmarker = new google.maps.Marker({
				  map: epldefaultmap,
				  position: eplmapcord
			  });
			}
			renderdefaultmap();

		} else {
			
			// if listing coordinates not present, use address to fetch them
			if(listingaddress != '') { 
				
				/* geocode listing address if coordinates are not already set */
				function eplcodeAddress() {
					
				  eplgeocoder = new google.maps.Geocoder();
				  eplgeocoder.geocode( { 'address': listingaddress}, function(results, status) {
				  
						if (status == google.maps.GeocoderStatus.OK) {
					
						  var mapOptions = {
							center: results[0].geometry.location,
							zoom: listingmapzoom,
						  }
						  
						  epldefaultmap = new google.maps.Map(document.getElementById('epl-default-map'), mapOptions);
						  
						  var epldefaultmarker = new google.maps.Marker({
							  map: epldefaultmap,
							  position: results[0].geometry.location
						  });
							
							// dont save suburb coordinates as listing coordinates
							if(suburb_mode != 1) {
								$.ajax({
									type: "POST",
									url: epl_frontend_vars.ajaxurl,
									data: { 
											action: "epl_update_listing_coordinates",
											coordinates: results[0].geometry.location.toString() ,
											listid:listid
										}
								})
								.done(function( msg ) {
									// successfully updated geocode
								});
							}
						
						} else {
							// error in geocoding
						}
				  });
				}
				
				eplcodeAddress();

			}
		}
		

	}

})