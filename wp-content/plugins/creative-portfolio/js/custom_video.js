jQuery(document).ready(function($) {

	$("#minor-publishing").hide(); // hide publish editable elements
	$(".handle-actions.hide-if-no-js").hide(); // hide draggable-header buttons
	$(".hndle").css('cursor', 'default'); // prevent cursor-draggable state 

    // remove default html5 validation
	var form = document.getElementById( "post" );
	form.noValidate = true;

    // start form validation
    var frmvalidator  = new Validator( "post" );

	frmvalidator.EnableOnPageErrorDisplay(); // to show all error in a field wise div --< id="form_fieldName_errorloc" >--
    frmvalidator.EnableMsgsTogether(); // to show all the error messages together

	// video required
	frmvalidator.addValidation("video_file", "req_file", "Video file upload is required.");

	// video thumbnail
	frmvalidator.addValidation("video_thumbnail", "req_file", "Video thumbnail image is required.");

    // Header title
    frmvalidator.addValidation("video_title", "req", "Input is mandatory for this field.");
	//frmvalidator.addValidation("video_title", "alnum_s", "Please enter alpha numeric characters only.");
	frmvalidator.addValidation("video_title", "maxlen=60", "Maximum 60 characters allowed.");
	$("#video_title").keyup(function(){
		dynamicLength( jQuery(this), 60, "#video_title_error" );
	});

    // description
	frmvalidator.addValidation("video_description", "req", "Input is mandatory for this field.");
	frmvalidator.addValidation("video_description", "alnum_s_m", "Alpha Numeric characters only.");
	frmvalidator.addValidation("video_description", "maxlen=250", "Maximum 250 characters allowed.");
	$("#video_description").keyup(function(){
		dynamicLength( jQuery(this), 250, "#video_desc_error");
	});

} );


// solution to publish/update button de-activation issue
jQuery('#publish').on('click', function() {
	jQuery.this.removeClass('disabled');
	//jQuery.this.prop('disabled', false);
});

jQuery('a[rel="video_file"]').on('click', function() {
	jQuery('#video_file_show').empty();
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
		
		if( cmb.field == "video_file" ) {	

			var vidAttachment = cmb.frames[cmb.field].state().get('selection').toJSON();
			$( "#video_file_show" ).empty();
			$( "#video_file_show" ).append('<video width = "200" height = "auto" controls> <source src ='+vidAttachment[0].url+' ></video>');

			vidAttachment = vidAttachment.filter(
				value => ( value.type == 'video' && value.mime == "video/mp4" )
			);

			if ( vidAttachment.length < 1 ) {
				alert("Attention!! Attached file must of type .mp4 video.");
				$( "#"+cmb.field+"-status" ).empty();
				$( "#"+cmb.field ).val( '' );
				$( "#"+cmb.field+"_id" ).val( '' );
				$( '#video_file_show' ).empty();
			}

			vidAjaxCall( vidAttachment[0].id, cmb.field );
		}
		
		if( cmb.field == "video_thumbnail" ) {
			
			var setImageWidth  = 824;
			var setImageHeight = 464;
			var attachment = cmb.frames[cmb.field].state().get('selection').toJSON(); 

			attachment = attachment.filter(
				value => ( ( ( value.width == setImageWidth && value.height == setImageHeight ) || ( value.width == (2 * setImageWidth) && value.height == (2 * setImageHeight) ) ) && value.type == 'image' )
			);

			if ( attachment.length < 1 ) {
				alert("Attention!! *Image must be either of "+setImageWidth+"x"+setImageHeight+" dimension or Retina (2x) dimension.");
				$( "#"+cmb.field+"-status" ).empty();
				$( "#"+cmb.field ).val( '' );
				$( "#"+cmb.field+"_id" ).val( '' );
			}
		}

		$('a[rel="video_file"]').on('click', function() {
			$('#video_file_show').empty();
		});
	});
});

function vidAjaxCall( mediaID, fieldName ) {

	var queryString = window.location.search;
    var urlParams 	= new URLSearchParams(queryString);
    var postID   	= urlParams.get('post');

    var ajaxObject = {
		init: function () {
			ajaxObject.callAjaxMethod();
		},
		callAjaxMethod:function(){
			var data = {
				'action': 'sep_chk_ajax_video',
				'ID' : mediaID,
				'imageCategory' : fieldName+"_id",
				'postID' : postID,
			};		  
			jQuery.ajax({
				url: vidObject.ajaxurl,
				type: 'POST',
				data: data,
				success: function (response) {
					if ( response ) {
						alert("Attention!! This video has already been used for another Video production post. Try again.");
						jQuery( ".cmb2-id-"+fieldName ).show();
						jQuery( "#"+fieldName+"-status" ).empty();
						jQuery( "#"+fieldName ).val( '' );
						jQuery( "#"+fieldName+"_id" ).val( '' );
						jQuery('#video_file_show').empty();
					} 
					// else {
					// 	jQuery('#video_file_show').append('<video width = "300" height = "auto" controls>'+
					// 		'<source src = "'+videoURL+'">'+
					// 		'</video>');
					// }
					
				}
			});
		}
	}
	ajaxObject.init();	
}