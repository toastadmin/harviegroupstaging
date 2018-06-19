var jssor_epl_slider = [];

//responsive code begin
//you can remove responsive code if you don't want the slider scales while window resizes
function epl_Slider_ScaleSlider() {

    jQuery.each(jssor_epl_slider, function( index, $slideElem ) {
      var parentWidth = $slideElem.$Elmt.parentNode.clientWidth;
        if (parentWidth) {
            $slideElem.$ScaleWidth(Math.max(Math.min(parentWidth, 1920), 150));
        }
        else {
            window.setTimeout(epl_Slider_ScaleSlider, 30);
        }
    });
    
    if(eplSliderIsArchive == 1)
        epl_slider_fix_grid();
}

/** fix slider width , height when grid is default view **/
function epl_slider_fix_grid() {
	if( jQuery('.epl-listing-grid-view').length ) {
	 	if(epl_frontend_vars.epl_default_view_type == 'grid' || eplGetCookie('preferredView') == 'grid') {
	 	
			jQuery.each(jssor_epl_slider, function( index, $slideElem ) {
			   $slideElem.$ScaleWidth( jQuery('.epl-listing-grid-view div').width() );
			});
		} else {
			jQuery.each(jssor_epl_slider, function( index, $slideElem ) {
			   $slideElem.$ScaleWidth( jQuery('.epl-slider-archive-wrapper').width() );
			});
		}
	}
	else if( jQuery('.property-featured-image-wrapper').length ) {
	 	if(epl_frontend_vars.epl_default_view_type != 'grid' || eplGetCookie('preferredView') != 'grid') {
	 	
			jQuery.each(jssor_epl_slider, function( index, $slideElem ) {
			   $slideElem.$ScaleWidth( jQuery('.property-featured-image-wrapper').width() );
			});
		} 
	}
}
 jQuery(document).ready(function ($) {

 	
 	/* adjust slider in grid & list view */
	jQuery('.epl-switch-view ul li').click(function(){
		
		var view = jQuery(this).data('view');
		if(view == 'grid'){
			jQuery.each(jssor_epl_slider, function( index, $slideElem ) {
			   $slideElem.$ScaleWidth( jQuery('.epl-listing-grid-view div').width() );
			});
		} else {
			jQuery.each(jssor_epl_slider, function( index, $slideElem ) {
			   $slideElem.$ScaleWidth( jQuery('.epl-slider-archive-wrapper').width() );
			});
		}
		
	});
 
 	// fancybox on slider images //
 	if($('.epl_slider_popup_image').length > 0) {
 		$('.epl_slider_popup_image').fancybox({
 			beforeLoad : function() {
 				jQuery.each(jssor_epl_slider, function( index, $slideElem ) {
					 $slideElem.$Pause(); 
				});
 			},
 			afterClose : function() {
 				jQuery.each(jssor_epl_slider, function( index, $slideElem ) {

                    if(eplSliderOptions.$AutoPlay == true){
					   $slideElem.$Play(); 
                    }
				});
 			}
 		});
 	}
    var $_SlideshowTransitionsOpts = {
     //Fade in R
       fade_in_l : {$Duration: 1200, x: -0.3, $During: { $Left: [0.3, 0.7] }, $Easing: { $Left: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear }, $Opacity: 2 } ,
        //Fade out L
    fade_in_r : { $Duration: 1200, x: 0.3, $SlideOut: true, $Easing: { $Left: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear }, $Opacity: 2 },
    //Zoom- in
    zoom_in : {$Duration: 1200, $Zoom: 1, $Easing: { $Zoom: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseOutQuad }, $Opacity: 2 },
    //Zoom+ out
   zoom_out : {$Duration: 1000, $Zoom: 11, $SlideOut: true, $Easing: { $Zoom: $JssorEasing$.$EaseInExpo, $Opacity: $JssorEasing$.$EaseLinear }, $Opacity: 2 },
    //Rotate Zoom- in
    rotate_zoom_in : {$Duration: 1200, $Zoom: 1, $Rotate: 1, $During: { $Zoom: [0.2, 0.8], $Rotate: [0.2, 0.8] }, $Easing: { $Zoom: $JssorEasing$.$EaseSwing, $Opacity: $JssorEasing$.$EaseLinear, $Rotate: $JssorEasing$.$EaseSwing }, $Opacity: 2, $Round: { $Rotate: 0.5} },
    //Rotate Zoom+ out
   rotate_zoom_out : {$Duration: 1000, $Zoom: 11, $Rotate: 1, $SlideOut: true, $Easing: { $Zoom: $JssorEasing$.$EaseInExpo, $Opacity: $JssorEasing$.$EaseLinear, $Rotate: $JssorEasing$.$EaseInExpo }, $Opacity: 2, $Round: { $Rotate: 0.8} },

    //Zoom HDouble- in
   hdouble_zoom_in :  {$Duration: 1200, x: 0.5, $Cols: 2, $Zoom: 1, $Assembly: 2049, $ChessMode: { $Column: 15 }, $Easing: { $Left: $JssorEasing$.$EaseInCubic, $Zoom: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear }, $Opacity: 2 },
    //Zoom HDouble+ out
   hdouble_zoom_out : {$Duration: 1200, x: 4, $Cols: 2, $Zoom: 11, $SlideOut: true, $Assembly: 2049, $ChessMode: { $Column: 15 }, $Easing: { $Left: $JssorEasing$.$EaseInExpo, $Zoom: $JssorEasing$.$EaseInExpo, $Opacity: $JssorEasing$.$EaseLinear }, $Opacity: 2 },

    //Rotate Zoom- in L
    rotate_zoom_in_left : {$Duration: 1200, x: 0.6, $Zoom: 1, $Rotate: 1, $During: { $Left: [0.2, 0.8], $Zoom: [0.2, 0.8], $Rotate: [0.2, 0.8] }, $Easing: { $Left: $JssorEasing$.$EaseSwing, $Zoom: $JssorEasing$.$EaseSwing, $Opacity: $JssorEasing$.$EaseLinear, $Rotate: $JssorEasing$.$EaseSwing }, $Opacity: 2, $Round: { $Rotate: 0.5} },
    //Rotate Zoom+ out R
   rotate_zoom_out_right :  {$Duration: 1000, x: -4, $Zoom: 11, $Rotate: 1, $SlideOut: true, $Easing: { $Left: $JssorEasing$.$EaseInExpo, $Zoom: $JssorEasing$.$EaseInExpo, $Opacity: $JssorEasing$.$EaseLinear, $Rotate: $JssorEasing$.$EaseInExpo }, $Opacity: 2, $Round: { $Rotate: 0.8} },
    //Rotate Zoom- in R
    rotate_zoom_in_right : {$Duration: 1200, x: -0.6, $Zoom: 1, $Rotate: 1, $During: { $Left: [0.2, 0.8], $Zoom: [0.2, 0.8], $Rotate: [0.2, 0.8] }, $Easing: { $Left: $JssorEasing$.$EaseSwing, $Zoom: $JssorEasing$.$EaseSwing, $Opacity: $JssorEasing$.$EaseLinear, $Rotate: $JssorEasing$.$EaseSwing }, $Opacity: 2, $Round: { $Rotate: 0.5} },
    //Rotate Zoom+ out L
   rotate_zoom_out_left : {$Duration: 1000, x: 4, $Zoom: 11, $Rotate: 1, $SlideOut: true, $Easing: { $Left: $JssorEasing$.$EaseInExpo, $Zoom: $JssorEasing$.$EaseInExpo, $Opacity: $JssorEasing$.$EaseLinear, $Rotate: $JssorEasing$.$EaseInExpo }, $Opacity: 2, $Round: { $Rotate: 0.8} },

    //Rotate HDouble- in
   rotate_hdouble_in :   {$Duration: 1200, x: 0.5, y: 0.3, $Cols: 2, $Zoom: 1, $Rotate: 1, $Assembly: 2049, $ChessMode: { $Column: 15 }, $Easing: { $Left: $JssorEasing$.$EaseInCubic, $Top: $JssorEasing$.$EaseInCubic, $Zoom: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseOutQuad, $Rotate: $JssorEasing$.$EaseInCubic }, $Opacity: 2, $Round: { $Rotate: 0.7} },
    //Rotate HDouble- out
    rotate_hdouble_out :  {$Duration: 1000, x: 0.5, y: 0.3, $Cols: 2, $Zoom: 1, $Rotate: 1, $SlideOut: true, $Assembly: 2049, $ChessMode: { $Column: 15 }, $Easing: { $Left: $JssorEasing$.$EaseInExpo, $Top: $JssorEasing$.$EaseInExpo, $Zoom: $JssorEasing$.$EaseInExpo, $Opacity: $JssorEasing$.$EaseLinear, $Rotate: $JssorEasing$.$EaseInExpo }, $Opacity: 2, $Round: { $Rotate: 0.7} },
    //Rotate VFork in
    rotate_vfork : {$Duration: 1200, x: -4, y: 2, $Rows: 2, $Zoom: 11, $Rotate: 1, $Assembly: 2049, $ChessMode: { $Row: 28 }, $Easing: { $Left: $JssorEasing$.$EaseInCubic, $Top: $JssorEasing$.$EaseInCubic, $Zoom: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseOutQuad, $Rotate: $JssorEasing$.$EaseInCubic }, $Opacity: 2, $Round: { $Rotate: 0.7} },
    //Rotate HFork in
    rotate_vfork : {$Duration: 1200, x: 1, y: 2, $Cols: 2, $Zoom: 11, $Rotate: 1, $Assembly: 2049, $ChessMode: { $Column: 19 }, $Easing: { $Left: $JssorEasing$.$EaseInCubic, $Top: $JssorEasing$.$EaseInCubic, $Zoom: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseOutQuad, $Rotate: $JssorEasing$.$EaseInCubic }, $Opacity: 2, $Round: { $Rotate: 0.8} }
    };
    
    /** additional transitions **/

               $_SlideshowTransitionsOpts["Fade"] = { $Duration: 1200, $Opacity: 2 };


            $_SlideshowTransitionsOpts["Fade in T"] = { $Duration: 1200, y: 0.3, $During: { $Top: [0.3, 0.7] }, $Easing: { $Top: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear }, $Opacity: 2 };

            $_SlideshowTransitionsOpts["Fade in B"] = { $Duration: 1200, y: -0.3, $During: { $Top: [0.3, 0.7] }, $Easing: { $Top: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear }, $Opacity: 2 };

            $_SlideshowTransitionsOpts["Fade in LR"] = { $Duration: 1200, x: 0.3, $Cols: 2, $During: { $Left: [0.3, 0.7] }, $ChessMode: { $Column: 3 }, $Easing: { $Left: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear }, $Opacity: 2 };

            $_SlideshowTransitionsOpts["Fade in LR Chess"] = { $Duration: 1200, y: 0.3, $Cols: 2, $During: { $Top: [0.3, 0.7] }, $ChessMode: { $Column: 12 }, $Easing: { $Top: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear }, $Opacity: 2 };

            $_SlideshowTransitionsOpts["Fade in TB"] = { $Duration: 1200, y: 0.3, $Rows: 2, $During: { $Top: [0.3, 0.7] }, $ChessMode: { $Row: 12 }, $Easing: { $Top: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear }, $Opacity: 2 };

            $_SlideshowTransitionsOpts["Fade in TB Chess"] = { $Duration: 1200, x: 0.3, $Rows: 2, $During: { $Left: [0.3, 0.7] }, $ChessMode: { $Row: 3 }, $Easing: { $Left: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear }, $Opacity: 2 };
  
            $_SlideshowTransitionsOpts["Fade in Corners"] = { $Duration: 1200, x: 0.3, y: 0.3, $Cols: 2, $Rows: 2, $During: { $Left: [0.3, 0.7], $Top: [0.3, 0.7] }, $ChessMode: { $Column: 3, $Row: 12 }, $Easing: { $Left: $JssorEasing$.$EaseInCubic, $Top: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear }, $Opacity: 2 };
  
            $_SlideshowTransitionsOpts["Fade out L"] = { $Duration: 1200, x: 0.3, $SlideOut: true, $Easing: { $Left: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear }, $Opacity: 2 };
  
            $_SlideshowTransitionsOpts["Fade out R"] = { $Duration: 1200, x: -0.3, $SlideOut: true, $Easing: { $Left: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear }, $Opacity: 2 };
  
            $_SlideshowTransitionsOpts["Fade out T"] = { $Duration: 1200, y: 0.3, $SlideOut: true, $Easing: { $Top: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear }, $Opacity: 2 };
  
            $_SlideshowTransitionsOpts["Fade out B"] = { $Duration: 1200, y: -0.3, $SlideOut: true, $Easing: { $Top: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear }, $Opacity: 2 };
  
            $_SlideshowTransitionsOpts["Fade out LR"] = { $Duration: 1200, x: 0.3, $Cols: 2, $SlideOut: true, $ChessMode: { $Column: 3 }, $Easing: { $Left: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear }, $Opacity: 2 };
  
            $_SlideshowTransitionsOpts["Fade out LR Chess"] = { $Duration: 1200, y: -0.3, $Cols: 2, $SlideOut: true, $ChessMode: { $Column: 12 }, $Easing: { $Top: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear }, $Opacity: 2 };
;

            $_SlideshowTransitionsOpts["Fade out TB"] = { $Duration: 1200, y: 0.3, $Rows: 2, $SlideOut: true, $ChessMode: { $Row: 12 }, $Easing: { $Top: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear }, $Opacity: 2 };

            $_SlideshowTransitionsOpts["Fade out TB Chess"] = { $Duration: 1200, x: -0.3, $Rows: 2, $SlideOut: true, $ChessMode: { $Row: 3 }, $Easing: { $Left: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear }, $Opacity: 2 };

            $_SlideshowTransitionsOpts["Fade out Corners"] = { $Duration: 1200, x: 0.3, y: 0.3, $Cols: 2, $Rows: 2, $During: { $Left: [0.3, 0.7], $Top: [0.3, 0.7] }, $SlideOut: true, $ChessMode: { $Column: 3, $Row: 12 }, $Easing: { $Left: $JssorEasing$.$EaseInCubic, $Top: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear }, $Opacity: 2 };

            $_SlideshowTransitionsOpts["Fade Fly in L"] = { $Duration: 1200, x: 0.3, $During: { $Left: [0.3, 0.7] }, $Easing: { $Left: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear }, $Opacity: 2, $Outside: true };
 
            $_SlideshowTransitionsOpts["Fade Fly in R"] = { $Duration: 1200, x: -0.3, $During: { $Left: [0.3, 0.7] }, $Easing: { $Left: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear }, $Opacity: 2, $Outside: true };
 
            $_SlideshowTransitionsOpts["Fade Fly in T"] = { $Duration: 1200, y: 0.3, $During: { $Top: [0.3, 0.7] }, $Easing: { $Top: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear }, $Opacity: 2, $Outside: true };
 
            $_SlideshowTransitionsOpts["Fade Fly in B"] = { $Duration: 1200, y: -0.3, $During: { $Top: [0.3, 0.7] }, $Easing: { $Top: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear }, $Opacity: 2, $Outside: true };

            $_SlideshowTransitionsOpts["Fade Fly in LR"] = { $Duration: 1200, x: 0.3, $Cols: 2, $During: { $Left: [0.3, 0.7] }, $ChessMode: { $Column: 3 }, $Easing: { $Left: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear }, $Opacity: 2, $Outside: true };


            $_SlideshowTransitionsOpts["Fade Fly in LR Chess"] = { $Duration: 1200, y: 0.3, $Cols: 2, $During: { $Top: [0.3, 0.7] }, $ChessMode: { $Column: 12 }, $Easing: { $Top: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear }, $Opacity: 2, $Outside: true };
            

            $_SlideshowTransitionsOpts["Fade Fly in TB"] = { $Duration: 1200, y: 0.3, $Rows: 2, $During: { $Top: [0.3, 0.7] }, $ChessMode: { $Row: 12 }, $Easing: { $Top: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear }, $Opacity: 2, $Outside: true };

            $_SlideshowTransitionsOpts["Fade Fly in TB Chess"] = { $Duration: 1200, x: 0.3, $Rows: 2, $During: { $Left: [0.3, 0.7] }, $ChessMode: { $Row: 3 }, $Easing: { $Left: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear }, $Opacity: 2, $Outside: true };

            $_SlideshowTransitionsOpts["Fade Fly in Corners"] = { $Duration: 1200, x: 0.3, y: 0.3, $Cols: 2, $Rows: 2, $During: { $Left: [0.3, 0.7], $Top: [0.3, 0.7] }, $ChessMode: { $Column: 3, $Row: 12 }, $Easing: { $Left: $JssorEasing$.$EaseInCubic, $Top: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear }, $Opacity: 2, $Outside: true };

             $_SlideshowTransitionsOpts["Fade Fly out L"] = { $Duration: 1200, x: 0.3, $SlideOut: true, $Easing: { $Left: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear }, $Opacity: 2, $Outside: true };
 
            $_SlideshowTransitionsOpts["Fade Fly out R"] = { $Duration: 1200, x: -0.3, $SlideOut: true, $Easing: { $Left: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear }, $Opacity: 2, $Outside: true };
           

            $_SlideshowTransitionsOpts["Fade Fly out T"] = { $Duration: 1200, y: 0.3, $SlideOut: true, $Easing: { $Top: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear }, $Opacity: 2, $Outside: true };
            

            $_SlideshowTransitionsOpts["Fade Fly out B"] = { $Duration: 1200, y: -0.3, $SlideOut: true, $Easing: { $Top: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear }, $Opacity: 2, $Outside: true };
           

            $_SlideshowTransitionsOpts["Fade Fly out LR"] = { $Duration: 1200, x: 0.3, $Cols: 2, $SlideOut: true, $ChessMode: { $Column: 3 }, $Easing: { $Left: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear }, $Opacity: 2, $Outside: true };
            

            $_SlideshowTransitionsOpts["Fade Fly out LR Chess"] = { $Duration: 1200, y: 0.3, $Cols: 2, $SlideOut: true, $ChessMode: { $Column: 12 }, $Easing: { $Top: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear }, $Opacity: 2, $Outside: true };
           

            $_SlideshowTransitionsOpts["Fade Fly out TB"] = { $Duration: 1200, y: 0.3, $Rows: 2, $SlideOut: true, $ChessMode: { $Row: 12 }, $Easing: { $Top: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear }, $Opacity: 2, $Outside: true };
           

            $_SlideshowTransitionsOpts["Fade Fly out TB Chess"] = { $Duration: 1200, x: 0.3, $Rows: 2, $SlideOut: true, $ChessMode: { $Row: 3 }, $Easing: { $Left: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear }, $Opacity: 2, $Outside: true };
            

            $_SlideshowTransitionsOpts["Fade Fly out Corners"] = { $Duration: 1200, x: 0.3, y: 0.3, $Cols: 2, $Rows: 2, $During: { $Left: [0.3, 0.7], $Top: [0.3, 0.7] }, $SlideOut: true, $ChessMode: { $Column: 3, $Row: 12 }, $Easing: { $Left: $JssorEasing$.$EaseInCubic, $Top: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear }, $Opacity: 2, $Outside: true };
            

            $_SlideshowTransitionsOpts["Fade Clip in H"] = { $Duration: 1200, $Delay: 20, $Clip: 3, $Assembly: 260, $Easing: { $Clip: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear }, $Opacity: 2 };
            

            $_SlideshowTransitionsOpts["Fade Clip in V"] = { $Duration: 1200, $Delay: 20, $Clip: 12, $Assembly: 260, $Easing: { $Clip: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear }, $Opacity: 2 };
           

            $_SlideshowTransitionsOpts["Fade Clip out H"] = { $Duration: 1200, $Delay: 20, $Clip: 3, $SlideOut: true, $Assembly: 260, $Easing: { $Clip: $JssorEasing$.$EaseOutCubic, $Opacity: $JssorEasing$.$EaseLinear }, $Opacity: 2 };
           
            $_SlideshowTransitionsOpts["Fade Clip out V"] = { $Duration: 1200, $Delay: 20, $Clip: 12, $SlideOut: true, $Assembly: 260, $Easing: { $Clip: $JssorEasing$.$EaseOutCubic, $Opacity: $JssorEasing$.$EaseLinear }, $Opacity: 2 };
            

            $_SlideshowTransitionsOpts["Fade Stairs"] = { $Duration: 800, $Delay: 30, $Cols: 8, $Rows: 4, $Formation: $JssorSlideshowFormations$.$FormationStraightStairs, $Assembly: 2050, $Opacity: 2 };
           
            $_SlideshowTransitionsOpts["Fade Random"] = { $Duration: 1000, $Delay: 80, $Cols: 8, $Rows: 4, $Opacity: 2 };
           
            $_SlideshowTransitionsOpts["Fade Swirl"] = { $Duration: 800, $Delay: 30, $Cols: 8, $Rows: 4, $Formation: $JssorSlideshowFormations$.$FormationSwirl, $Opacity: 2 };
           
            $_SlideshowTransitionsOpts["Fade ZigZag"] = { $Duration: 800, $Delay: 30, $Cols: 8, $Rows: 4, $Formation: $JssorSlideshowFormations$.$FormationZigZag, $Assembly: 260, $Opacity: 2 };
           
           /** additional transitions end **/
     var $_SlideshowTransitions = [];
     for(var transKey in $_SlideshowTransitionsOpts ) {
     	if( transKey == $_transitionChosen ) {
     		$_SlideshowTransitions.push($_SlideshowTransitionsOpts[transKey]);
     	}
     }
    /** add default fields **/
    
    eplSliderOptions['$SlideshowOptions']['$Class'] 				= $JssorSlideshowRunner$;
    eplSliderOptions['$SlideshowOptions']['$Transitions'] 			= $_SlideshowTransitions;
    eplSliderOptions['$ArrowNavigatorOptions']['$Class'] 			= $JssorArrowNavigator$;
    eplSliderOptions['$ThumbnailNavigatorOptions']['$Class'] 		= $JssorThumbnailNavigator$;
    
    $( ".epl_slider_container" ).each(function( index ) {
      jssor_epl_slider.push(new $JssorSlider$($(this).attr('id'), eplSliderOptions));
       
    });
    

    epl_Slider_ScaleSlider();
   

    $(window).bind("load", epl_Slider_ScaleSlider);
    $(window).bind("resize", epl_Slider_ScaleSlider);
    $(window).bind("orientationchange", epl_Slider_ScaleSlider);
    //responsive code end
});
