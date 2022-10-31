jQuery(document).ready(function($) {

	$("#minor-publishing").hide(); // hide publish editable elements
	$(".handle-actions.hide-if-no-js").hide(); // hide draggable-header buttons
	$(".hndle").css('cursor', 'default'); // prevent cursor-draggable state	
	$('a[rel="external"]').hide();

	const queryString = window.location.search;
	const urlParams   = new URLSearchParams(queryString);
	const urlData     = urlParams.get('action');
	var ad_dimension  = $( "input[name=html_ad_dimension]:checked" );
	//var banner_size   = $( "input[name=html_banner_size]:checked" );

	if ( urlData !== 'edit' ) {
		$( ".cmb2-id-html-banner-image" ).css("display", "none"); 
		// $( ".cmb2-id-html-ad-dimension" ).css("display", "none");
		// $( ".cmb2-id-html-banner-align" ).css("display", "none");
	} 
	// else if ( banner_size.val() == '300x250' && urlData === 'edit' ) {
	// 	$( ".cmb2-id-html-ad-dimension" ).css("display", "none");
	// 	$( ".cmb2-id-html-banner-image" ).css("display", "none"); 
	// 	$( ".cmb2-id-html-banner-align" ).css("display", "visible");
	// }
	// else if ( banner_size.val() == 'Other' && (ad_dimension.val() == '728x90' || ad_dimension.val() == '970x250') ) {
	// 	$( ".cmb2-id-html-banner-align" ).css("display", "none");
	// }

	// banner size selection
	//$( "input[name=html_banner_size]" ).click( function() {

	// $( "input[name=html_banner_size]" ).click( function() {

	// 	if( $(this).is(':checked') && $(this).val() == 'Other' ) {
			
	// 		$( ".cmb2-id-html-ad-dimension" ).show();

	// 		// if( $( "input[name=html_ad_dimension]" ).is(':checked') ) {
	// 		// 	if( $(this).val() == '300x600' || $(this).val() == '160x600' ) {
	// 		// 		$( ".cmb2-id-html-banner-align" ).show();
	// 		// 	} else {
	// 		// 		$( "input[name=html_banner_align]" ).attr('checked',false);
	// 		// 		$( ".cmb2-id-html-banner-align" ).hide();
	// 		// 	}
	// 		// } else {
	// 		// 	$( "input[name=html_banner_align]" ).attr('checked',false);
	// 		// 	$( ".cmb2-id-html-banner-align" ).hide();
	// 		// }		
	// 	} 
	// 	else if ( $(this).val() == '300x250' ) {
			
	// 		$( ".cmb2-id-html-ad-dimension" ).hide();
	// 		$( "input[name=html_ad_dimension]" ).prop( 'checked', false );
	// 		$( ".cmb2-id-html-banner-image" ).hide();
	// 		$( "#html_banner_image-status" ).empty();
	// 		$( "#html_banner_image" ).val( '' );
	// 		$( "#html_banner_image_id" ).val( '' );
	// 		$( ".cmb2-id-html-banner-align" ).show();
	// 	}
	// });

	// banner dimension select
	$( "input[name=html_ad_dimension]:not(:checked)" ).click( function() {

		if( $(this).is(':checked') ) {
			
			$( ".cmb2-id-html-banner-image" ).show();
			$( "#html_banner_image-status" ).empty();
			$( "#html_banner_image" ).val( '' );
			$( "#html_banner_image_id" ).val( '' );
			$('#rich_media_show').empty();

			// if( $(this).val() == '300x600' || $(this).val() == '160x600' ) {
			// 	$( ".cmb2-id-html-banner-align" ).show();
			// } else {
			// 	$( "input[name=html_banner_align]" ).attr('checked',false);
			// 	$( ".cmb2-id-html-banner-align" ).hide();
			// }
		}
	});
	
	//remove default html5 validation
	var form = document.getElementById( "post" );
	form.noValidate = true;

    // // start form validation
    var frmvalidator  = new Validator( "post" );

	frmvalidator.EnableOnPageErrorDisplay(); // to show all error in a field wise div --< id="form_fieldName_errorloc" >--
	//frmvalidator.EnableOnPageErrorDisplaySingleBox(); // to show all error in a single div/box --< id="form_errorloc" >--
    frmvalidator.EnableMsgsTogether(); // to show all the error messages together

	// Thumbnail required
	frmvalidator.addValidation("upload_html_thumbnail", "req_file", "Image upload is required for this field.");

	// Creative Name field validations
	frmvalidator.addValidation("html_creative_name", "req", "Input is mandatory for this field.");
	//frmvalidator.addValidation("html_creative_name", "alnum_s", "Please enter alpha numeric characters only.");
	frmvalidator.addValidation("html_creative_name", "maxlen=60", "Maximum 60 characters allowed.");
	$("#html_creative_name").keyup(function(){
		dynamicLength( jQuery(this), 60, "#creative_error" );
	});

	// Banner Size
	//frmvalidator.addValidation("html_banner_size", "selone", "You must select an option.");

	// AD Dimension
	frmvalidator.addValidation("html_ad_dimension", "selone", "You must select an option.");
	// frmvalidator.addValidation("html_ad_dimension", "selone", "You must select an option.",
	// 	"VWZ_IsChecked(document.forms['post'].elements['html_banner_size'],'Other')" );

	// Banner image
	frmvalidator.addValidation("html_banner_image","req","Image upload is required for this field.",
		"VWZ_IsRadioChecked(document.forms['post'].elements['html_ad_dimension'])" );

	// Creative production tools
	frmvalidator.addValidation("html_production_tools", "req", "Input is mandatory for this field.");
	frmvalidator.addValidation("html_production_tools", "alnum_s_m", "Please enter comma separated alpha numeric characters only.");
	frmvalidator.addValidation("html_production_tools", "maxlen=50", "Maximum 50 characters allowed.");
	$("#html_production_tools").keyup(function(){
		dynamicLength( jQuery(this), 50, "#cpt_error");
	});

	// Features
	frmvalidator.addValidation("html_features", "req", "Input is mandatory for this field.");
	frmvalidator.addValidation("html_features", "alnum_s_m", "Please enter comma separated alpha numeric characters only.");
	frmvalidator.addValidation("html_features", "maxlen=80", "Maximum 80 characters allowed.");
	$("#html_features").keyup(function(){
		dynamicLength( jQuery(this), 80, "#feature_error");
	});

	// Slider image
	frmvalidator.addValidation("html_slider_image", "req_file", "Image upload is required for this field.");

	// Slider description
	frmvalidator.addValidation("html_slider_description", "req", "Input is mandatory for this field.");
	//frmvalidator.addValidation("html_slider_description", "alnum_s_m", "Alpha Numeric characters only.");
	frmvalidator.addValidation("html_slider_description", "maxlen=250", "Maximum 250 characters allowed.");
	$("#html_slider_description").keyup(function(){
		dynamicLength( jQuery(this), 250, "#desc_error");
	});

	//frmvalidator.addValidation("html_slider_preview_siteurl", "req", "Input is mandatory for this field.");
	
	// function isValidUrl()  {

	// 	$("#post_html_slider_preview_siteurl_errorloc").empty();
	// 	var frm = document.forms["post"];
	// 	var urlString = frm.html_slider_preview_siteurl.value;			

	// 	var urlPattern = new RegExp('^(https?:\\/\\/)?'+ // validate protocol
	// 	'((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // validate domain name
	// 	'((\\d{1,3}\\.){3}\\d{1,3}))'+ // validate OR ip (v4) address
	// 	'(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // validate port and path
	// 	'(\\?[;&a-z\\d%_.~+=-]*)?'+ // validate query string
	// 	'(\\#[-a-z\\d_]*)?$','i'); // validate fragment locator		

	// 	if ( !!urlPattern.test( urlString ) == false ) {
	// 		$("#post_html_slider_preview_siteurl_errorloc").text("Please Provide a valid URL");
	// 	} 

	// 	return !!urlPattern.test( urlString );

	// }
	
	// frmvalidator.setAddnlValidationFunction(isValidUrl);

	// Banner alignment
	// frmvalidator.addValidation("html_banner_align", "selone", "You must select an option.",
	// 	"VWZ_IsChecked(document.forms['post'].elements['html_banner_size'],'300x250')" );
	// frmvalidator.addValidation("html_banner_align", "selone", "You must select an option.",
	// 	"VWZ_IsChecked(document.forms['post'].elements['html_ad_dimension'],'300x600')" );
	// frmvalidator.addValidation("html_banner_align", "selone", "You must select an option.",
	// 	"VWZ_IsChecked(document.forms['post'].elements['html_ad_dimension'],'160x600')" );


} );


// solution to publish/update button de-activation issue
jQuery('#publish').on('click', function() {
	jQuery.this.removeClass('disabled');
	//jQuery.this.prop('disabled', false);
});

jQuery('a[rel="html_banner_image"]').on('click', function() {
	jQuery('#rich_media_show').empty();
});


// CMB2 file upload used to modify/ validate selection
jQuery(function ($) {
	$(document).on('cmb_media_modal_select', function (evt, media, cmb) {

		var imgDimension  = $( "input[name=html_ad_dimension]:checked" ).val();

		if( cmb.field == "upload_html_thumbnail" ) {
			
			var setThumbnailWidth  = 300;
			var setThumbnailHeight = 250;
			var attachmentTH = cmb.frames[cmb.field].state().get('selection').toJSON();
			
			attachmentTH = attachmentTH.filter(
				value => ( ( ( value.width == setThumbnailWidth && value.height == setThumbnailHeight ) || ( value.width == (2 * setThumbnailWidth) && value.height == (2 * setThumbnailHeight) ) ) && value.type == 'image' )
			);

			if ( attachmentTH.length < 1 ) {
				alert("Attention!! *Image must be either of 300x250 dimension or Retina (2x) dimension.");
				$( ".cmb2-id-"+cmb.field ).show();
				$( "#"+cmb.field+"-status" ).empty();
				$( "#"+cmb.field ).val( '' );
				$( "#"+cmb.field+"_id" ).val( '' );
			}

			ajaxCall( attachmentTH[0].id, cmb.field );
			
		}
		else if ( cmb.field == "html_banner_image" ) {
			
			$( "#rich_media_show" ).empty();
			var get_file_name = $("#"+cmb.field+"-status").children().children().find('strong').text();
			var dimensionInFile = get_file_name.split('_');		

			if ( typeof(imgDimension) !== "undefined" ) {
				var selectedDimension = imgDimension.split('x');
				var selectedWidth     = selectedDimension[0];
				var selectedHeight    = selectedDimension[1];
			} else {
				alert("Please select content type and Dimension");
			}

			if( dimensionInFile[0] !== imgDimension ) {
				alert("Attention!! Selected dimension does not correspond with the size in file name");
				$( ".cmb2-id-"+cmb.field ).show();
				$( "#"+cmb.field+"-status" ).empty();
				$( "#"+cmb.field ).val( '' );
				$( "#"+cmb.field+"_id" ).val( '' );
				$( '#rich_media_show' ).empty();
			}

			var attachment = cmb.frames[cmb.field].state().get('selection').toJSON();

			attachment = attachment.filter(
				value => ( value.mime == "application/zip" )
			);

			if ( attachment.length < 1 ) {
				alert("Attention!! Please select a compressed .zip file");
				$( ".cmb2-id-"+cmb.field ).show();
				$( "#"+cmb.field+"-status" ).empty();
				$( "#"+cmb.field ).val( '' );
				$( "#"+cmb.field+"_id" ).val( '' );
			}
			
			ajaxCall( attachment[0].url, cmb.field );

		}
		else if( cmb.field == "html_slider_image" ) {

			var attachmentSlider = cmb.frames[cmb.field].state().get('selection').toJSON();
			var setTakeoverWidth  = 418;
			var setTakeoverHeight = 235;

			attachmentSlider = attachmentSlider.filter(
				value => ( ( ( value.width == setTakeoverWidth && value.height == setTakeoverHeight ) || ( value.width == (2 * setTakeoverWidth) && value.height == (2 * setTakeoverHeight) ) ) && value.type == 'image' )
			);

			if ( attachmentSlider.length < 1 ) {
				alert("Attention!! *Image must be either of 418x235 dimension or Retina (2x) dimension.");
				$( ".cmb2-id-"+cmb.field ).show();
				$( "#"+cmb.field+"-status" ).empty();
				$( "#"+cmb.field ).val( '' );
				$( "#"+cmb.field+"_id" ).val( '' );
			}

			ajaxCall( attachmentSlider[0].id, cmb.field );			
		}

		$('a[rel="html_banner_image"]').on('click', function() {
			$('#rich_media_show').empty();
		});

		$('a[rel="external"]').hide();
		
	});
});


function ajaxCall( mediaID, fieldName ) {

	var queryString = window.location.search;
    var urlParams 	= new URLSearchParams(queryString);
    var postID   	= urlParams.get('post');
	var aFieldName  = ( fieldName == 'html_banner_image' ) ? fieldName : fieldName+"_id";

    var ajaxObject = {
		init: function () {
			ajaxObject.callAjaxMethod();
		},
		callAjaxMethod:function(){
			var data = {
				'action': 'sep_chk_ajax_html5',
				'ID' : mediaID,
				'imageCategory' : aFieldName,
				'postID' : postID,
			};		  
			jQuery.ajax({
				url: htmbObject.ajaxurl,
				type: 'POST',
				data: data,
				success: function (response) {

					if ( response ) {
						alert("Attention!! This file has already been used for another HTML5 post. Try again.");
						jQuery( ".cmb2-id-"+fieldName ).show();
						jQuery( "#"+fieldName+"-status" ).empty();
						jQuery( "#"+fieldName ).val( '' );
						jQuery( "#"+fieldName+"_id" ).val( '' );
						jQuery( '#rich_media_show' ).empty();
					}
				} 
			});
		}
	}
	ajaxObject.init();	
}


function dynamicLength( inputSelector, reqLength, msgBox) {

	var charLength = jQuery(inputSelector).val().length;
	if( charLength > reqLength ) {
		jQuery( msgBox ).text("Please keep input value within "+reqLength+" characters.");
	} else {
		jQuery( msgBox ).empty();
	}
}

