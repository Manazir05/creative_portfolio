jQuery( function($) {

    $('#add_unit_btn').click( function(e) {

        e.preventDefault();

        if( $("#add_dimension").is(":visible") ) {
            $('#add_dimension').hide();
            $('#add_unit_btn').html('ADD');
        } else {
            $('#add_dimension').show();
            $('#add_unit_btn').html('Update');
        }
   
        var addWidth  = $("#add_width").val();
        var addHeight = $("#add_height").val();
        
        if( ( addWidth  && addHeight ) && ( addWidth > 0 && addHeight > 0 ) ) {
            
            var dimensionExist = $("#image_dimensions").val();
            var dimensions = dimensionExist.split(',');
            var eachDimension = [];
            var heights = [];
            var widths = [];

            $.each( dimensions , function( index, value ) {
                eachDimension.push(value.split('x'));           
            });

            for( var k = 0; k < eachDimension.length; k++ ) {
                widths.push(eachDimension[k][0]);
                heights.push(eachDimension[k][1]);
            }

            if( ( jQuery.inArray( addWidth, widths ) == -1 ) || ( jQuery.inArray( addHeight, heights ) == -1 ) ) {
                var newDimension = addWidth+'x'+addHeight;
                var newDimensiotSet = dimensionExist. concat(','+newDimension.toString());
                $('#image_dimensions').val(newDimensiotSet);
            }
        }
    });


    $('#remove_unit_btn').click( function(e) {

        e.preventDefault();

        if( $("#remove_dimension").is(":visible") ) {
            $('#remove_dimension').hide();
            $('#remove_unit_btn').html('Remove');
        } else {
            $('#remove_dimension').show();
            $('#remove_unit_btn').html('Save');
        }
   
        var removeWidth  = $("#remove_width").val();
        var removeHeight = $("#remove_height").val();
        
        if( ( removeWidth  && removeHeight ) && ( removeWidth != 0 && removeHeight != 0 ) ) {
            
            var dimensionExist = $("#image_dimensions").val();
            var dimensions = dimensionExist.split(',');
            var eachDimension = [];
            var heights = [];
            var widths = [];

            $.each( dimensions , function( index, value ) {
                eachDimension.push( value.split('x') );           
            });

            for( var k = 0; k < eachDimension.length; k++ ) {
                widths.push(eachDimension[k][0]);
                heights.push(eachDimension[k][1]);
            }

            if( ( jQuery.inArray( removeWidth, widths ) > -1 ) && ( jQuery.inArray( removeHeight, heights ) > -1 ) ) {
                
                var reqDimension = removeWidth+'x'+removeHeight;

                dimensions = jQuery.grep( dimensions, function(value) {
                    if ( value != reqDimension ) {
                        return value;
                    }
                    
                } );

                $('#image_dimensions').val(dimensions);
            }
        }        
        
    });



    /**
     * 
     */

	

    $('#add_html_btn').click( function(e) {

        e.preventDefault();

        if( $("#add_html_dimension").is(":visible") ) {
            $('#add_html_dimension').hide();
            $('#add_html_btn').html('ADD');
        } else {
            $('#add_html_dimension').show();
            $('#add_html_btn').html('Update');
        }
   
        var addWidth  = $("#add_html_width").val();
        var addHeight = $("#add_html_height").val();
        
        if( ( addWidth  && addHeight ) && ( addWidth > 0 && addHeight > 0 ) ) {
            
            var dimensionExist = $("#html_dimensions").val();
            var dimensions = dimensionExist.split(',');
            var eachDimension = [];
            var heights = [];
            var widths = [];

            $.each( dimensions , function( index, value ) {
                eachDimension.push(value.split('x'));           
            });

            for( var k = 0; k < eachDimension.length; k++ ) {
                widths.push(eachDimension[k][0]);
                heights.push(eachDimension[k][1]);
            }

            if( ( jQuery.inArray( addWidth, widths ) == -1 ) || ( jQuery.inArray( addHeight, heights ) == -1 ) ) {
                var newDimension = addWidth+'x'+addHeight;
                var newDimensiotSet = dimensionExist. concat(','+newDimension.toString());
                $('#html_dimensions').val(newDimensiotSet);
            }
        }
    });


    $('#remove_html_btn').click( function(e) {

        e.preventDefault();

        if( $("#remove_html_dimension").is(":visible") ) {
            $('#remove_html_dimension').hide();
            $('#remove_html_btn').html('Remove');
        } else {
            $('#remove_html_dimension').show();
            $('#remove_html_btn').html('Save');
        }
   
        var removeWidth  = $("#remove_html_width").val();
        var removeHeight = $("#remove_html_height").val();
        
        if( ( removeWidth  && removeHeight ) && ( removeWidth != 0 && removeHeight != 0 ) ) {
            
            var dimensionExist = $("#html_dimensions").val();
            var dimensions = dimensionExist.split(',');
            var eachDimension = [];
            var heights = [];
            var widths = [];

            $.each( dimensions , function( index, value ) {
                eachDimension.push( value.split('x') );           
            });

            for( var k = 0; k < eachDimension.length; k++ ) {
                widths.push(eachDimension[k][0]);
                heights.push(eachDimension[k][1]);
            }

            if( ( jQuery.inArray( removeWidth, widths ) > -1 ) && ( jQuery.inArray( removeHeight, heights ) > -1 ) ) {
                
                var reqDimension = removeWidth+'x'+removeHeight;

                dimensions = jQuery.grep( dimensions, function(value) {
                    if ( value != reqDimension ) {
                        return value;
                    }
                    
                } );

                $('#html_dimensions').val(dimensions);
            }
        }        
        
    });


} );