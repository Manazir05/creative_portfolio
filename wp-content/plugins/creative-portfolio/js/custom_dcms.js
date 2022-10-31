jQuery(document).ready(function($) {

	$("#minor-publishing").hide(); // hide publish editable elements
	$(".handle-actions.hide-if-no-js").hide(); // hide draggable-header buttons
	$(".hndle").css('cursor', 'default'); // prevent cursor-draggable state 

    //remove default html5 validation
	var form = document.getElementById( "post" );
	form.noValidate = true;

    // // start form validation
    var frmvalidator  = new Validator( "post" );

	frmvalidator.EnableOnPageErrorDisplay(); // to show all error in a field wise div --< id="form_fieldName_errorloc" >--
	//frmvalidator.EnableOnPageErrorDisplaySingleBox(); // to show all error in a single div/box --< id="form_errorloc" >--
    frmvalidator.EnableMsgsTogether(); // to show all the error messages together

	// image required
	frmvalidator.addValidation("dcms_image", "req_file", "Image upload is required for this field.");

    // Header title
    frmvalidator.addValidation("dcms_title", "req", "Input is mandatory for this field.");
	frmvalidator.addValidation("dcms_title", "alnum_s", "Please enter alpha numeric characters only.");
	frmvalidator.addValidation("dcms_title", "maxlen=30", "Maximum 30 characters allowed.");
	$("#dcms_title").keyup(function(){
		dynamicLength( jQuery(this), 30, "#dcms_title_error" );
	});

    // description
	frmvalidator.addValidation("dcms_description", "req", "Input is mandatory for this field.");
	frmvalidator.addValidation("dcms_description", "alnum_s_m", "Alpha Numeric characters only.");
	frmvalidator.addValidation("dcms_description", "maxlen=220", "Maximum 220 characters allowed.");
	$("#dcms_description").keyup(function(){
		dynamicLength( jQuery(this), 220, "#dcms_desc_error");
	});

    frmvalidator.addValidation("dcms_preview_link", "req", "Input is mandatory for this field.");
	
	function isValidUrl()  {

		$("#post_dcms_preview_link_errorloc").empty();
		var frm = document.forms["post"];
		var urlString = frm.dcms_preview_link.value;			

		var urlPattern = new RegExp('^(https?:\\/\\/)?'+ // validate protocol
		'((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // validate domain name
		'((\\d{1,3}\\.){3}\\d{1,3}))'+ // validate OR ip (v4) address
		'(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // validate port and path
		'(\\?[;&a-z\\d%_.~+=-]*)?'+ // validate query string
		'(\\#[-a-z\\d_]*)?$','i'); // validate fragment locator		

		if ( !!urlPattern.test( urlString ) == false ) {
			$("#post_dcms_preview_link_errorloc").text("Please Provide a valid URL");
		} 

		return !!urlPattern.test( urlString );

	}
	
	frmvalidator.setAddnlValidationFunction(isValidUrl);

} );


// solution to publish/update button de-activation issue
jQuery('#publish').on('click', function() {
	jQuery.this.removeClass('disabled');
	//jQuery.this.prop('disabled', false);
});


function dynamicLength( inputSelector, reqLength, msgBox) {

	var charLength = jQuery(inputSelector).val().length;
	if( charLength > reqLength ) {
		jQuery( msgBox ).text("Please keep input value within "+reqLength+" characters.");
	} else {
		jQuery( msgBox ).empty();
	}
}


// CMB2 file upload used to modify/ validate selection
jQuery(function ($) {
	$(document).on('cmb_media_modal_select', function (evt, media, cmb) {

		if( cmb.field == "dcms_image" ) {	
			
			var setImageWidth  = 618;
			var setImageHeight = 328;
			var attachment = cmb.frames[cmb.field].state().get('selection').toJSON(); 

			attachment = attachment.filter(
				value => ( ( ( value.width == setImageWidth && value.height == setImageHeight ) || ( value.width == (2 * setImageWidth) && value.height == (2 * setImageHeight) ) ) && value.type == 'image' )
			);

			if ( attachment.length < 1 ) {
				alert("Attention!! *Image must be either of "+setImageWidth+"x"+setImageHeight+" dimension or Retina (2x) dimension.");
				$( ".cmb2-id-"+cmb.field ).show();
				$( "#"+cmb.field+"-status" ).empty();
				$( "#"+cmb.field ).val( '' );
				$( "#"+cmb.field+"_id" ).val( '' );
			}

			ajaxCall( attachment[0].id, cmb.field );			
		}				
	});
});

function ajaxCall( mediaID, fieldName ) {

	var queryString = window.location.search;
    var urlParams 	= new URLSearchParams(queryString);
    var postID   	= urlParams.get('post');

    var ajaxObject = {
		init: function () {
			ajaxObject.callAjaxMethod();
		},
		callAjaxMethod:function(){
			var data = {
				'action': 'sep_chk_ajax_dcms',
				'ID' : mediaID,
				'imageCategory' : fieldName+"_id",
				'postID' : postID,
			};		  
			jQuery.ajax({
				url: dcmsObject.ajaxurl,
				type: 'POST',
				data: data,
				success: function (response) {

					if ( response ) {
						alert("Attention!! This image has already been used for another Development & CMS post. Try again.");
						jQuery( ".cmb2-id-"+fieldName ).show();
						jQuery( "#"+fieldName+"-status" ).empty();
						jQuery( "#"+fieldName ).val( '' );
						jQuery( "#"+fieldName+"_id" ).val( '' );
					}
				} 
			});
		}
	}
	ajaxObject.init();	
}