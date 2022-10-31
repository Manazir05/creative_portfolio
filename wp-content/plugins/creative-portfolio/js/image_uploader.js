jQuery( function($) {

	// on upload button click
	$('body').on( 'click', '.sep-upl', function(e) {

		e.preventDefault();

		var button = $(this);
		custom_uploader = wp.media ({
			title: 'Insert image',
			library : {
				// uploadedTo : wp.media.view.settings.post.id, // attach to the current post?
				type : 'image'
			},
			button: {
				text: 'Use this image' // button label text
			},
			multiple: false,
		}).on('select', function() { // it also has "open" and "close" events
			var attachment = custom_uploader.state().get('selection').first().toJSON();
			//button.html('<img width="100" height="100" src="' + attachment.url + '">').next().show().next().val(attachment.id);
			button.next().next().val(attachment.id).next().next().attr('src', attachment.url).attr('width', 300).show();
		}).open();
	
	});

	// on remove button click
	$('body').on('click', '.sep-rmv', function(e){	

		e.preventDefault();
		var button = $(this);
		button.next().val(''); // emptying the hidden field
		button.next().next().next().attr('src', '').hide();
		button.hide().prev().html('Upload image');
	});

} );