<!-- start Simple Custom CSS and JS -->
<script type="text/javascript">
jQuery(document).ready(function($){
    if (jQuery.isFunction(jQuery.fn.select2)) {
    }
  
    $("select").select2({
        minimumResultsForSearch: Infinity
    });

    
 	$("#post_type").change(function() {
     
        if( $(this).children('option:selected').index() == 3 ) {
            $(this).val('property');
            $("#project_type").val('development');
            $("#property_address").attr('name','property_address_suburb_postcode');
        } else  if( $(this).children('option:selected').index() == 4 ) {
            $(this).val('property');
            $("#project_type").val('currentproject');
            $("#property_address").attr('name','property_address_suburb_postcode');
        }  else {
            $("#project_type").val('');
            $("#property_address").attr('name','property_address');
        }
    });
  
     if (jQuery('.epl-search-form .more-options').length > 0) {
        jQuery('.epl-search-form .more-options').on('click', function (e) {
            e.preventDefault();

            if ($.trim($(this).text()) === '+ More Options') {
                $(this).text('- Fewer Options');
            } else {
                $(this).text('+ More Options');        
           }
            if (jQuery(this).closest('.epl-search-form').find('.advanced-search-sec').length > 0) {
                jQuery(this).closest('.epl-search-form').find('.advanced-search-sec').slideToggle(function () {
                    
                });
            }

        });
    }
});

</script>
<!-- end Simple Custom CSS and JS -->
