<?php

class Static_Animated {


    public function __construct() {
		
		if ( is_admin() ) {

			// initiate hooks for admin user
			add_action( 'init', array( &$this, 'init' ) );	
			add_action( 'admin_enqueue_scripts', array( &$this, 'sep_specific_script_static' ) );		
			add_filter( 'manage_static_posts_columns', array( &$this, 'sep_filter_static_columns') );			
			add_action( 'manage_static_posts_custom_column', array( &$this, 'sep_fill_static_columns'), 10, 2);
			add_action( 'cmb2_admin_init', array( &$this, 'static_content_category_metabox' ) );
			add_action( 'add_meta_boxes', array( &$this, 'meta_boxes' ) );
			add_action( 'save_post', array( &$this, 'meta_boxes_save' ), 1, 2 );
			add_action( 'admin_notices', array( &$this, 'sep_admin_notices' ), 10);		
			add_filter( 'removable_query_args', array( &$this, 'sep_add_removable_arg'), 5 );

			// ajax hooks
			add_action( 'wp_ajax_sep_ajax_render_static', array( &$this,  "sep_ajax_render_static_images") );
			add_action( 'wp_ajax_nopriv_sep_ajax_render_static', array( &$this,  "sep_ajax_render_static_images") );
		}
	}


	/**
	 * Register the custom post type
	 */
	public function init() {
        register_post_type(
		'static', 
		array( 
			'public' => false, // it's not public, it shouldn't have it's own permalink,
			'publicly_queryable' => false, // should not be able to preview/view it
			'show_ui' => true, // able to edit it in wp-admin
			'label' => 'Static & Animated Gifs' ,
			'supports' => array('title'),
			'exclude_from_search' => true, // should exclude it from search results
			'show_in_nav_menus' => false, // shouldn't be able to add it to menus
			'has_archive' => false, // shouldn't have archive page
			'rewrite' => false, // shouldn't have rewrite rules
			)
		);		
	}


	/**
	 * Modify grid view columns 
	 */
	public function sep_filter_static_columns( $columns ) {
        $columns = array(
            'cb' => $columns['cb'],
            'title' => __( 'Title' ),
			'category' => __( 'Content Type' ),
			'image_dimensions' => __( 'Dimensions' ),
            'date' => __( 'Published At' ),
          );
        return $columns;
    }


	/**
	 * Show custom post data
	 */
	public function sep_fill_static_columns( $column, $post_id ) {        
        switch ( $column ) {	
			// show image dimensions	
			case 'image_dimensions':
				$dimensions = get_post_meta( $post_id, 'static_gif_dimension', false );

				if ( ! $dimensions ) {
					_e( 'n/a' );  
				} else {
					echo "<ul>";
					foreach ( $dimensions as $dimension ) {
						echo "<li>". $dimension ."</li>";
					}
					echo "</ul>";
				}				
				break;
			case 'category':
					$category = get_post_meta( $post_id, 'static_content_category', true );
	
					if ( ! $category ) {
						_e( 'n/a' );  
					} else {
						echo $category;				
					}				
				break;
		}
	}


	/**
	 * Generate additional fields for this custom category
	 */
	public function static_content_category_metabox() {

		$cmb_content = new_cmb2_box( array(
			'id'            => 'static_content_category_id',
			'title'         => esc_html__( 'Content Specification', 'cmb2' ),
			'object_types'  => array( 'static' ), // Post type
			'context'    => 'normal',
			'priority'   => 'high'
		) );

		$cmb_content->add_field( array(
			'name'             => __( 'Select Content Category( <span style="color:red">*</span> )', 'cmb2' ),
			'id'               => 'static_content_category',
			'type'             => 'radio_inline',
			'options_cb' => array( &$this, 'sep_render_ad_units'),
			'attributes' => array(
				'required' => 'required',
			),
		) );

		$cmb_content->add_field( array(
			'name'    => __( 'Image Dimensions( <span style="color:red">*</span> )', 'cmb2' ),
			'id'      => 'static_gif_dimension',
			'type'    => 'radio_inline',		
			'options_cb' => array( &$this, 'sep_render_dimensions'),
			'classes_cb' => array( &$this, 'sep_render_img_dimension_class'),
			'attributes' => array(
				'required' => 'required',
			),
		) );

		// Post status
		$cmb_content->add_field( array(
			'name'    => __( ucwords('Post visibility status ( <span style="color:red">*</span> )'), 'cmb2' ),
			'desc'    => esc_html__( 'Post status to determine frontend visibility', 'cmb2' ),
			'id'      => 'static_post_visibility',
			'type'    => 'radio_inline',
			'options' => array(
				'Active' => esc_html__( 'Active', 'cmb2' ),
				'Inactive' => esc_html__( 'Inactive', 'cmb2' ),
			),
			'default' => 'Active',
			//'after'=> '<div id="post_html_post_visibility_errorloc" class="error_strings"></div><br/>',
		) );
	}

	/**
	 * Add meta boxes
	 */
	function meta_boxes() {
		add_meta_box( 'static_file_list', __( 'Add Unit Images' ), array( &$this, 'sep_static_image_meta_box' ), 'static', 'normal', 'high' );
	}
	
	/**
	 * Display the image meta box
	 */
	function sep_static_image_meta_box() {

		global $pagenow;
		global $post;
		
		$img_set = '';
		$image_sources = array();
		$image_data = get_post_meta( $post->ID, 'static_file_list', true );
		
		if ( !empty( $image_data ) ) {

			$img_set = implode(',' , $image_data );

			foreach( $image_data as $images ) {				
				$image_sources[$images] = wp_get_attachment_url( $images );				
			}
		}		

		?>

		<style>
			.sep-remove-file-button {
				background: url(../wp-content/plugins/creative-portfolio/assets/images/ico-delete.png);
			 	height: 16px;
			 	width: 16px;
			 	position: absolute;
			 	text-indent: -9999px;
				top : 0px;
				left: 0px;
			}

			#image_set > li {
				display: inline-block;
				position: relative;
			}

		</style>

		<p>
			<button id="add-book-image"><?php _e( 'Select images' ) ?></button>
			<input type="hidden" name="upload_image_list" id="upload_image_list" value="<?php echo $img_set; ?>" />
		</p>
		<ul id="image_set">

			<?php 
			foreach( $image_sources as $img_id => $img_src ) {
				?>
				<li data-id="<?php echo $img_id; ?>" >
					<img id="<?php echo $img_id; ?>" src="<?php echo $img_src ?>" width="150" style="padding:5px;" />
					<p style="display: inline-block;">
						<a href="#" rel="static_file_list[<?php echo $img_id; ?>]" class="sep-remove-file-button sep-gallery-remove" >Remove Image</a>
					</p>
				</li>
				<?php
			}
			?>

		</ul>

		<script type="text/javascript">
			jQuery(document).ready(function($) {

				$(".hndle").css('cursor', 'default'); // prevent cursor-draggable state 
				$("#minor-publishing").hide();

				var pagenow = '<?php echo $pagenow; ?>';

				$( "#static_file_list" ).css("display", "block");

				if( pagenow == 'post-new.php' ) {

					$( ".sep-class-dimension-set" ).css("display", "none");
					$( "#static_file_list" ).css("display", "none");

					$( "input[name=static_content_category]" ).click( function() {
						if( $(this).is(':checked') ) {
							$( ".sep-class-dimension-set" ).show();
						}
					});

					$( "input[name=static_gif_dimension]" ).click( function() {
						if( $(this).is(':checked') ) {
							$( "#static_file_list" ).show();
						}
					});
				}				
				
				// on upload button click
				$( "#add-book-image" ).click( function(e) {

					e.preventDefault();		
					
					var custom_uploader, mimer;
					var categoryValue = $('input[name=static_content_category]:checked').val();

					( categoryValue == 'GIF' ) ? mimer = ['image/gif'] : mimer = ['image/png','image/jpeg','image/webp'];					

					if ( typeof( custom_uploader ) !== "undefined" ) {
						custom_uploader.close();
					}

					var button = $(this);

					custom_uploader = wp.media ({
						title: 'Insert image',
						library : {
							// uploadedTo : wp.media.view.settings.post.id, // attach to the current post
							type : mimer,
						},
						button: {
							text: 'Use this image' // button label text
						},
						multiple: 'add',
					});

					custom_uploader.on('select', function() {
						
						$('#image_set').empty();
						var saveData   = [];
						var attachment = custom_uploader.state().get('selection').toJSON();	
						var firstAttachmentLength = attachment.length;
						var contentType  = $( "input[name=static_content_category]:checked" ).val();
						var imgDimension = $( "input[name=static_gif_dimension]:checked" ).val();

						// check selection status for content type and dimension
						if ( typeof(contentType) !== "undefined" && typeof(imgDimension) !== "undefined" ) {
							var selectedDimension = imgDimension.split('x');
							var selectedWidth  = selectedDimension[0];
							var selectedHeight = selectedDimension[1];
						} else {
							alert( "Please select content type and Dimension" );
						}

						/**
						 * validate each selected/ uploaded image according to selected dimension
						 * 2x of selected dimension is also allowed
						 */
						attachment = attachment.filter(
							value => ( value.width == selectedWidth && value.height == selectedHeight ) || ( value.width == (2 * selectedWidth) && value.height == (2 * selectedHeight) )
 						);
							
						var secondAttachmentLength = attachment.length; // check number of attachments after filtering
						var removedImages = firstAttachmentLength - secondAttachmentLength;

						$.each( attachment, function( index, attributes ) {		
							$('#image_set').prepend( '<li data-id="'+ attributes.id +'"><img id="'+ attributes.id +'" src="'+ attributes.url +'" width="150" style="padding:5px;" ></img><p style="display: inline-block;"><a href="#" rel="static_file_list['+ attributes.id +']" class="sep-remove-file-button sep-gallery-remove" >Remove Image</a></p></li>' );
							saveData.push(attributes.id);
						});

						$('#upload_image_list').val( saveData ); //store data into hidden field value	

						( removedImages > 0 ) ? alert("Alert!! "+removedImages+" image/ images were removed from selection for not matching the selected dimensions") : null;

						$( '#image_set' ).sortable( 'refresh' );

					});

					//Open modal
					custom_uploader.open();

				});

				// dimension change before post save
				$( "input[name=static_gif_dimension]" ).click( function() {
					if( $(this).is(':checked') ) {
						alert("Attention!! Please select files according to selected dimensions");
						$( "#static_file_list" ).show();
						$( "#image_set" ).empty();
						$( "#upload_image_list" ).val( '' );
					}
				});

				// dimension change before post save
				$(document).on('click', '.sep-gallery-remove', function(event) {

					event.preventDefault();

					var imgID = $(this).parent().prev("img").attr('id');

					const hiddenField = $('#upload_image_list');
					var hiddenFieldValue = hiddenField.val().split(",");
					
					hiddenFieldValue = jQuery.grep( hiddenFieldValue, function(value) {
						if ( value != imgID ) {
							return value;
						}                    
                	} );

					hiddenField.val( hiddenFieldValue );					

					$(this).parent().prev("img").remove();
					$(this).remove();

					$( '#image_set' ).sortable( 'refresh' );
				});


				$( '#image_set' ).sortable({
					items: 'li',
					cursor: '-webkit-grabbing', // mouse cursor
					scrollSensitivity: 40,
					/*
					set custom CSS styles while this element is dragging
					start:function(event,ui){
						ui.item.css({'background-color':'grey'});
					},
					*/
					stop: function( event, ui ){
						ui.item.removeAttr( 'style' );

						let sort = new Array(); // array of image IDs
						const container = $(this); // image_set

						// each time after dragging we resort our array
						container.find( 'li' ).each( function( index ) {
							sort.push( $(this).attr( 'data-id' ) );
						});
						// add the array value to the hidden input field
						$('#upload_image_list').val( sort.join() );
					}
				});
		
			});
		</script>
		<?php
	}


	/**
	 * Callback for rendering ad unit types option 
	 */
	function sep_render_ad_units () {
 
		global $pagenow;
		global $post;
		$units = array();

		$status = get_post_status( $post->ID );

		if ( $pagenow == 'post.php' && $status != 'draft' ) {
			$ad_unit_data = get_post_meta( $post->ID, 'static_content_category', true );
			$units[trim($ad_unit_data)] = esc_html__( trim($ad_unit_data), 'cmb2' );
		} 
		else {
			$ad_unit_data = get_option('ad_unit_types');
			$unit_set = explode(',', $ad_unit_data);

			foreach( $unit_set as $unit ) {
				$units[trim($unit)] = esc_html__( trim($unit), 'cmb2' );
			}
		}

		return $units;
	}


	/**
	 * Callback for rendering checkboxes from image-dimensions option 
	 */
	function sep_render_dimensions () {
	
		global $pagenow;
		global $post;

		$status = get_post_status( $post->ID );

		$dimensions = array();

		if ( $pagenow == 'post.php' && $status != 'draft' ) {
			$img_dimension_data = get_post_meta( $post->ID, 'static_gif_dimension', true );
			$dimensions[trim($img_dimension_data)] = esc_html__( trim($img_dimension_data), 'cmb2' );
		} 
		else {
			$img_dimension_data = get_option('image_dimensions');
			$dimension_set = explode(',', $img_dimension_data);

			foreach( $dimension_set as $dimension ) {
				$dimensions[trim($dimension)] = esc_html__( trim($dimension), 'cmb2' );
			}
		}

		return $dimensions;
	}


	/**
	 * Manually render a classes for custom meta box field
	 */
	function sep_render_img_dimension_class( $field_args, $field ) {
		
		$class = 'sep-class-dimension-set';	
		return $class;
	}


	/**
	 * Save meta boxes
	 * Runs when a post is saved and does an action which the write panel save scripts can hook into.
	 */
	public function meta_boxes_save( $post_id, $post ) {
		if ( empty( $post_id ) || empty( $post ) || empty( $_POST ) ) return;
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
		if ( is_int( wp_is_post_revision( $post ) ) ) return;
		if ( is_int( wp_is_post_autosave( $post ) ) ) return;
		if ( ! current_user_can( 'edit_post', $post_id ) ) return;
		if ( $post->post_type != 'static' ) return;
			
		$this->process_static_meta( $post_id, $post );
	}
	
	/**
	 * Function for processing and storing all image data.
	 */
	private function process_static_meta( $post_id, $post ) {

		//$content_type = $_POST['static_content_category'];
		//$selected_dimension = $_POST['static_gif_dimension'];

		isset( $_POST['publish'] ) ? $mode = 'create' : $mode = 'edit'; //determine post state of create or edit

		$selected_files = $_POST['upload_image_list'];
		$files = array();
		$duplicates = array();
		$final_files= array();
		$selected_files ? $files = explode( ',', $selected_files ) : $files = '';		

		if ( !$selected_files && $mode == 'create' ) {			
			add_filter( 'redirect_post_location', array( &$this, 'sep_static_redirect_location' ), 10 , 2 );
			wp_delete_post( $post_id , true ); // force delete the post	
			return;		
		} 
		elseif ( !$selected_files && $mode == 'edit' ) {
			add_filter( 'redirect_post_location', array( &$this, 'sep_static_redirect_blank_update' ), 10 , 2 );
			return;	
		}

		// duplicate attachment id check for a static post
		foreach ( $files as $file_media_id ) {
			$args = array(
				'post_type'  => 'static',
				'meta_key'   => 'static_file_list',
				'meta_query' => array(
					array(
						'key'     => 'static_file_list',
						'value'   => $file_media_id ,
						'compare' => 'LIKE',
					),
				),
				'post__not_in' => [$post_id],
			);

			$the_query = new WP_Query( $args );

			if ( $the_query->have_posts() ) :
				$duplicates[]  = $file_media_id;			
			else :
				$final_files[] = $file_media_id;
			endif;
		}	
	
		if ( (count($final_files) < 1)  && $mode == 'create' ) {			
			add_filter( 'redirect_post_location', array( &$this, 'sep_static_redirect_location_duplicate' ), 10 , 2 );	
			wp_delete_post( $post_id , true ); // force delete the post	
			return;		
		} 
		elseif ( (count($final_files) < 1)  && $mode == 'edit' ) {
			add_filter( 'redirect_post_location', array( &$this, 'sep_static_redirect_edit_duplicate' ), 10 , 2 );	
			return;		
		} 
		else {

			if ( count($final_files) < count($files) ) {
				add_filter( 'redirect_post_location', array( &$this, 'sep_static_redirect_duplicate_notice' ), 10 , 2 );	
				update_post_meta( $post_id, 'static_file_list', $final_files );
			} else {
				update_post_meta( $post_id, 'static_file_list', $final_files );
			}			
		}
		
	}

	/**
	 * redirect hook functions to prevent post save default redirection
	 * Also - stage mechanism to throw appropriate admin notice
	 */
	function sep_static_redirect_location( $location, $post_id ) {
		//$location = add_query_arg( 'static_id', $post_id, $location );
		$location = add_query_arg( 'no-img', $post_id, 'post-new.php?post_type=static' );
		return $location;		
	}
	
	function sep_static_redirect_location_duplicate( $location, $post_id ) {
		$location = add_query_arg( 'add-duplicate', $post_id, 'post-new.php?post_type=static' );
		return $location;		
	}

	function sep_static_redirect_edit_duplicate( $location, $post_id ) {
		$location = add_query_arg( array('post' => $post_id, 'edit-duplicate' => $post_id, 'action'=> 'edit'), 'post.php' );
		return $location;		
	}

	function sep_static_redirect_duplicate_notice( $location, $post_id ) {

		$location = add_query_arg( array('post' => $post_id, 'duplicate-removed' => $post_id, 'action'=> 'edit'), 'post.php' );
		return $location;		
	}

	function sep_static_redirect_blank_update( $location, $post_id ) {
		$location = add_query_arg( array('post' => $post_id, 'all-blank-edit' => $post_id, 'action'=> 'edit'), 'post.php' );
		return $location;		
	}


	/**
	 * check redirected url for additional query args
	 * if found with our own url param, throw admin notice after redirection
	 */
	function sep_admin_notices() {

		$notice = new AdminNotice();

		if ( isset( $_GET['no-img'] ) ) {
			$notice->displayError( 'No image selected! Static and animated Ad unit could not be saved. Please try again.' );
		} 
		else if ( isset( $_GET['add-duplicate'] ) ) {
			$notice->displayError( 'Unable to save as all selected images were duplicate for this post! Please try again.' );
		}
		else if ( isset( $_GET['duplicate-removed'] ) ) {
			$notice->displaySuccess( 'Post saved. All duplicate files were removed while saving.' );
		}
		else if ( isset( $_GET['all-blank-edit'] ) ) {
			$notice->displayWarning( 'Post update failed. Minimum one image must be selected' );
		}
		else if ( isset( $_GET['edit-duplicate'] ) ) {
			$notice->displayWarning( 'Post update failed. Images selected already belongs to similar posts.' );
		}
	}

	/**
	 * remove additional query args after page load
	 */
	public function sep_add_removable_arg ($args) {
		array_push($args, 'no-img', 'add-duplicate', 'duplicate-removed', 'all-blank-edit', 'edit-duplicate');
		return $args;
	}
	
		


	/* ------------------------------------------------------------------------ *
     * AJAX functions
     * ------------------------------------------------------------------------ */


	function sep_ajax_render_static_images() {

		if ( !wp_verify_nonce( $_REQUEST['nonce'], "sep_ajax_render_nonce")) {
			exit("No naughty business please");
		}  		
		
		$selected_category  = $_POST['category'];
		$selected_dimension = $_POST['dimension'];		
		$all_images = array();
		$images = array();
			
		global $wpdb;
		$results = $wpdb->get_results("
		SELECT
		 	p.ID as post_id, pmcat.meta_value as category, pmdim.meta_value as dimension, pmfile.meta_value as files
			FROM {$wpdb->prefix}posts p LEFT JOIN
			{$wpdb->prefix}postmeta pmcat    
			ON pmcat.post_id = p.id AND pmcat.meta_key = 'static_content_category' INNER JOIN   
			{$wpdb->prefix}postmeta pmdim     
			ON pmdim.post_id = p.id AND pmdim.meta_key = 'static_gif_dimension' INNER JOIN
			{$wpdb->prefix}postmeta pmfile
			ON pmfile.post_id = p.id AND pmfile.meta_key = 'static_file_list' INNER JOIN
			{$wpdb->prefix}postmeta pmstatus     
			ON pmstatus.post_id = p.id AND pmstatus.meta_key = 'static_post_visibility'
			WHERE p.post_type = 'static'			
			AND pmstatus.meta_value = 'Active'
			AND pmcat.meta_value = '$selected_category'
			AND pmdim.meta_value = '$selected_dimension' ORDER BY p.post_modified DESC;"
		);
		
		foreach ( $results as $result ) {

			$proper_data = unserialize( $result->files );
			
			foreach( $proper_data as $image_id ) {
				$all_images[] = wp_get_attachment_url( $image_id );
			}
		}
		
		echo json_encode( $all_images );

		wp_die(); // this is required to terminate immediately and return a proper response

	}

	/**
	 * Enqueue required scripts
	 */
    function sep_specific_script_static( $pagearg ) {
        
        global $post;

        $ver_param = date('dmYHis');
            
        // check if we are on custom post edit or add new page
        if ( $pagearg == 'post-new.php' || $pagearg == 'post.php') {
            if ( 'static' === $post->post_type ) {

				// post specific {static} script deregister
				wp_deregister_script('postbox');
            }
        }
    }

}

$stats = new Static_Animated();


?>