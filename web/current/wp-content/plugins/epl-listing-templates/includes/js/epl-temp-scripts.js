jQuery(document).ready(function($) {

	function init_masonry(sel,child) {
		jQuery(sel).masonry({
		  percentPosition: true,
		  columnWidth: child,
		  itemSelector: child,
		  gutter: 0
		});
	}

	function trigger_views() {
		/* force default view to grid if its set to default */
		if( eplGetCookie('preferredView') !== undefined) {
			var preferredView = eplGetCookie('preferredView');
			setTimeout(function () {
				jQuery('.epl-switch-view ul li[data-view="'+preferredView+'"]').trigger('click');
			},300);

		} else if(epl_frontend_vars.epl_default_view_type == 'grid') {
			setTimeout(function () {
				jQuery('.epl-switch-view ul li[data-view="grid"]').trigger('click');
			},300);
		}
	}

	$(window).resize(function () {

	    if (jQuery(window).innerWidth() < 500) {
	    	var $masonryTarget = $('.epl-theme-property-blog-wrapper'),
		        $hasMasonry = $masonryTarget.data('masonry') ? true : false;
		    if ($masonryTarget.length > 0 && $hasMasonry) {
		      // Destroy masonry if exists.
		      $masonryTarget.masonry('destroy');
		    }
	    } else {
	        trigger_views();
	    }
	});


	trigger_views();

	/* switch views : grid & list on property archive pages */
	jQuery(document).on('click','.epl-switch-view ul li',function(){

		if (jQuery(window).innerWidth() < 500) {
			jQuery('.epl-theme-property-blog-wrapper').masonry().masonry('destroy');
			return false;
		}


		if( jQuery('.epl-masonry-forced').length ) {

			init_masonry('.epl-theme-property-blog-wrapper','.epl-masonry-forced');
		} else {

			var view = jQuery(this).data('view');
			jQuery('.epl-switch-view ul li').removeClass('epl-current-view');
			jQuery(this).addClass('epl-current-view');

			if(view == 'grid'){

				jQuery('.epl-property-blog').addClass('epl-listing-grid-view');
				if(listingsMasonEnabled == 1) {
					init_masonry('.epl-theme-property-blog-wrapper','.epl-listing-grid-view');
				}

			} else {

				if(listingsMasonEnabled == 1) {
					jQuery('.epl-theme-property-blog-wrapper').masonry().masonry('destroy');
					jQuery('.epl-property-blog').removeClass('epl-listing-grid-view');
				} else {
					jQuery('.epl-property-blog').removeClass('epl-listing-grid-view');
				}
			}
		}


	});
});
