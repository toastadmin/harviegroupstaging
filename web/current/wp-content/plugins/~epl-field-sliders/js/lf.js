jQuery(document).ready(function($) {

    $('.epl-search-mobile').find('#property_price_from, #property_price_to').each( function() {
       var id =  $(this).attr('id');
       $(this).attr('id','epl_mobile_'+id);
       
    });
    
    Number.prototype.formatMoney = function(c, d, t){
        var n = this,
            c = isNaN(c = Math.abs(c)) ? 2 : c,
            d = d == undefined ? "." : d,
            t = t == undefined ? "," : t,
            s = n < 0 ? "-" : "",
            i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
            j = (j = i.length) > 3 ? j % 3 : 0;
        return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
    };

	var epl_lf_ranges = {
		'epl_lp_property_price' : {
			els 		: ['property_price_from','property_price_to'],
			label 		: 'Price ',
			prefix		: '$',
			suffix		: '',
			seperator	: ' - '
		},
		'epl_lp_property_price_rental' : {
			els 		: ['property_price_from','property_price_to'],
			label 		: 'Price ',
			prefix		: '$',
			suffix		: '',
			seperator	: ' - ',
			min 		: 0,
			max 		: 5000
		},
		'epl_mobile_lp_property_price' : {
			els 		: ['epl_mobile_property_price_from','epl_mobile_property_price_to'],
			label 		: 'Price ',
			prefix		: '$',
			suffix		: '',
			seperator	: ' - '
		},
		'epl_mobile_lp_property_price_rental' : {
			els 		: ['epl_mobile_property_price_from','epl_mobile_property_price_to'],
			label 		: 'Price ',
			prefix		: '$',
			suffix		: '',
			seperator	: ' - ',
			min 		: 0,
			max 		: 5000
		},
		// 'epl_lp_property_bedrooms' : {
		// 	els 		: ['property_bedrooms_min','property_bedrooms_max'],
		// 	label 		: 'Bedrooms',
		// 	prefix		: '',
  //           suffix		: '',
		// 	seperator	: ' - '
		// },
		// 'epl_lp_property_land_area' : {
		// 	els 	: ['property_land_area_min','property_land_area_max'],
		// 	label 	: 'Land Area ',
		// 	prefix	: '',
  //           suffix	: 'm<sup>2</sup>',
		// 	seperator	: ' - ',
  //           min : 0,
  //           max : 2000,
  //           step: 10
		// },
  //       'epl_lp_property_building_area' : {
  //           els 	: ['property_building_area_min','property_building_area_max'],
  //           label 	: 'Building Area ',
  //           prefix	: '',
  //           suffix	: 'sq',
  //           seperator	: ' - ',
  //           min : 0,
  //           max : 30,
  //           step: 1


  //       }


	}

	$.each(epl_lf_ranges,function(key, value) {
		elExists = true;
		$.each(value.els,function(k,v) {
			if(!$('#'+v).length) {
				elExists = false;
			}else {
				$('#'+v).closest('.epl-search-row').hide();
			}
		});
		if(elExists) {

			if(key == 'epl_lp_property_price_rental' || key == 'epl_mobile_lp_property_price_rental' ) {

				var min = value.min;
				var max = value.max;
				var defaultValues = [ 0, 5000 ];
			} else {
	            var defaultValues = [ 0, isNaN(Number($('#'+value.els[1]+' option:last').val())) ? value.max : Number($('#'+value.els[1]+' option:last').val()) ];
				var min = isNaN(Number($('#'+value.els[1]+' option:first').val())) ? value.min : Number($('#'+value.els[1]+' option:first').val());
				var max = isNaN(Number($('#'+value.els[1]+' option:last').val())) ? value.max : Number($('#'+value.els[1]+' option:last').val());
			}
			$('#'+value.els[0])
				.closest('.epl-search-row')
				.after('<div class=" '+key+'_wrap epl-lf-range-slider epl-search-row-half epl-search-row-select fm-block epl-search-row-full "><label id="label_'+key+'" class="epl-search-label fm-label" for="'+key+'">'+value.label+'</label><div class="field"><div id="'+key+'"></div></div></div>')
			$( "#"+key ).slider({
				range: true,
				step: 200 ,
				min : min,
				max : max,
				values: defaultValues,
				slide: function( event, ui ) {
					$( '#'+value.els[0] ).append(new Option((ui.values[ 0 ]).formatMoney(0), ui.values[ 0 ]));
					$( '#'+value.els[0] ).append(new Option((ui.values[ 1 ]).formatMoney(0), ui.values[ 1 ]));
					$( '#'+value.els[1] ).append(new Option((ui.values[ 0 ]).formatMoney(0), ui.values[ 0 ]));
					$( '#'+value.els[1] ).append(new Option((ui.values[ 1 ]).formatMoney(0), ui.values[ 1 ]));
					$( '#'+value.els[0] ).val( ui.values[ 0 ] ).change();
					$( '#'+value.els[1] ).val( ui.values[ 1 ] ).change();
					$( "#label_"+key ).html( value.label + '<span class="epl-lf-label-txt">' + value.prefix + (ui.values[ 0 ]).formatMoney(0) + value.suffix +  value.seperator + value.prefix +  (ui.values[ 1 ]).formatMoney(0) +  value.suffix + '</span>');
				},
				stop: function( event, ui ) {
					// $( '#'+value.els[0] ).val( ui.values[ 0 ] );
					// $( '#'+value.els[1] ).val( ui.values[ 1 ] );
					// $( '#'+value.els[1] ).trigger('change');
				},
                create: function( event, ui ) {
                    $( "#label_"+key ).html( value.label + '<span class="epl-lf-label-txt">' + value.prefix + (defaultValues[ 0 ]).formatMoney(0) + value.suffix +  value.seperator + value.prefix +  (defaultValues[ 1 ]).formatMoney(0) +  value.suffix + '</span>');
                },
			});
		}
	});


});
