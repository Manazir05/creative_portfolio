<?php

class HtmlRich {

    public function __construct() {

		if ( is_admin() ) {

			// initiate hooks for admin user
			add_action( 'init', array( &$this, 'init' ) );
            add_action( 'cmb2_admin_init', array( &$this, 'html5_content_category_metabox' ) );	
            add_action( 'admin_enqueue_scripts', array( &$this, 'cmb2_validation_script_html5' ) );
			add_action( 'save_post', array( &$this, 'html5_meta_boxes_save' ), 1, 2 );
			add_action( 'admin_notices', array( &$this, 'sep_admin_notices' ), 10);		
			add_filter( 'removable_query_args', array( &$this, 'sep_add_removable_arg'), 5 );			
			
			// ajax
			add_action( 'wp_ajax_sep_chk_ajax_html5', array( &$this, "sep_chk_ajax_html5_thumbnail") );
			add_action( 'wp_ajax_sep_get_dimension_posts', array( &$this, "sep_get_dimension_posts_html") );
			add_action( 'wp_ajax_nopriv_sep_get_dimension_posts', array( &$this, "sep_get_dimension_posts_html") );

			// manage custom grid
			add_filter( 'manage_html5_posts_columns', array( &$this, 'sep_filter_html5_columns') );			
			add_action( 'manage_html5_posts_custom_column', array( &$this, 'sep_fill_html5_columns'), 10, 2);
		}
	}


	/**
	 * Modify grid view columns 
	 */
	public function sep_filter_html5_columns( $columns ) {
        $columns = array(
            'cb' => $columns['cb'],
            'title' => __( 'Title' ),
			'thumbnail_image' => __( 'Banner Thumbnail' ),
			'creative_name' => __( 'Creative Name' ),
			'banner_size' => __( 'Banner Size' ),
            'date' => __( 'Published At' ),
        );
        return $columns;
    }


	/**
	 * Show custom post data
	 */
	public function sep_fill_html5_columns( $column, $post_id ) {        
        switch ( $column ) {	
			// show image dimensions	
			case 'creative_name':
				$name = get_post_meta( $post_id, 'html_creative_name', true );

				if ( ! $name ) {
					_e( 'n/a' );  
				} else {
					echo $name;
				}				
				break;
			case 'banner_size':
				$bin_size = get_post_meta( $post_id, 'html_ad_dimension', true );

				if ( ! $bin_size ) {
					_e( 'n/a' );  
				} else {					
					echo $bin_size;								
				}				
			break;
			case 'thumbnail_image':
				$banner_thumb_img = get_post_meta( $post_id, 'upload_html_thumbnail_id', true );

				if ( ! $banner_thumb_img ) {
					_e( 'n/a' );  
				} else {
					$img_url = (wp_get_attachment_image_src( $banner_thumb_img, 'thumbnail' ) );					
					echo '<img src='.$img_url[0].' />';
				}				
			break;
		}
	}
	
	/**
	 * Register the custom post type
	 */
	public function init() {
        register_post_type(
		'html5', 
		array( 
			'public' => false, // it's not public, it shouldn't have it's own permalink,
			'publicly_queryable' => false, // should not be able to preview/view it
			'show_ui' => true, // able to edit it in wp-admin
			'label' => 'HTML5 & Rich Media',
			'supports' => array('title'),
			'exclude_from_search' => true, // should exclude it from search results
			'show_in_nav_menus' => false, // shouldn't be able to add it to menus
			'has_archive' => false, // shouldn't have archive page
			'rewrite' => false, // shouldn't have rewrite rules
			)
		);

	}

    /**
	 * Generate additional fields for this custom category
	 */
	function html5_content_category_metabox() {


		isset( $_GET['post'] ) ? $post_media_file = get_post_meta( $_GET['post'], 'html_banner_image', true ) : $post_media_file = false;

		if ($post_media_file) {

			WP_Filesystem();

			$dimension 	 = get_post_meta( $_GET['post'], 'html_ad_dimension', true );
			$dimData 	 = explode("x",$dimension);
			$iwidth		 = $dimData[0];
			$iheight	 = $dimData[1];
			$target_file = wp_basename( $post_media_file , '.zip' );
			$folder      = "Rich_Media_Archive";
			$destination = wp_upload_dir();
			$upload_dir  = $destination['baseurl'];
			$target_path = $upload_dir.'/'.$folder.'/'.$target_file.'/index.html';
				
			$media_file = '<iframe src = "'.$target_path.'" width = "'.$iwidth.'" height = "'.$iheight.'" scrolling="no"> </iframe>';
		
		} else {
			$media_file = '';
		}

		/* -------------------------------------------------------------------------------------- *
     	 * Section:1 -> ADD custom meta box for Listing Page thumbnail image selection <single>
     	 * -------------------------------------------------------------------------------------- */

		$html_thumbnail = new_cmb2_box( array(
			'id'            => 'html_content_thumbnail_id',
			'title'         => esc_html__( ucwords('Banner Thumbnail (300x250)'), 'cmb2' ),
			'object_types'  => array( 'html5' ), // Post type
			'context'    => 'normal',
			'priority'   => 'high'
		) );

		// thumbnail image
		$html_thumbnail->add_field( array(
			'name'    => __( ucwords('Upload Thumbnail Image ( <span style="color:red">*</span> )'), 'cmb2' ),
			'desc'    => esc_html__( '*Image must be either of 300x250 dimension or Retina (2x) dimension.', 'cmb2' ),
			'id'      => 'upload_html_thumbnail',
			'type'    => 'file',
			'options' => array(
				'url' => false,
			),
			'text'    => array(
				'add_upload_file_text' => 'Upload Image'
			),
			'query_args' => array(
				'type' => array(
				    'image/gif',
				    'image/jpeg',
				    'image/png',
				),
			),
			//'preview_size' => array(100,100), // Image size to use when previewing in the admin.
            'after'=> '<div id="post_upload_html_thumbnail_errorloc" class="error_strings"></div><br/>',
		) );


		/* -------------------------------------------------------------------------------------- *
     	 * Section:2 -> AD Details page with CMB2 for Banner selection and it's details <single>
     	 * -------------------------------------------------------------------------------------- */


		$html_content = new_cmb2_box( array(
			'id'            => 'html_content_category_id',
			'title'         => esc_html__( ucwords('AD Details Information'), 'cmb2' ),
			'object_types'  => array( 'html5' ), // Post type
			'context'    => 'normal',
			'priority'   => 'high'
		) );

		// creative name 
		$html_content->add_field( array(
			'name'    => __( 'Creative Name ( <span style="color:red">*</span> )', 'cmb2' ),
			//'name'    => ( 'Creative Name (<strong>*</strong>)', 'cmb2' ),
			'id'      => 'html_creative_name',
			'type'    => 'text',            
            'after'=> '<div id="post_html_creative_name_errorloc" class="error_strings"></div><br/>',
			'after_field' => '<div id="creative_error"></div><br/>',
		) );

		// Industry
		$html_content->add_field( array(
			'name'    => esc_html__( ucwords('Industry'), 'cmb2' ),
			'id'      => 'html_industry',
			'type'    => 'select',
			// 'options'    => array(
			// 	'' => esc_html__( '', 'cmb2' ),
			// ),
			'options_cb' => array( &$this, 'sep_render_industries' ),
            'after'=> '<div id="post_html_industry_errorloc" class="error_strings"></div><br/>',
		) );

		// Banner size
		// $html_content->add_field( array(
		// 	'name'    => esc_html__( ucwords('Banner Size (*)'), 'cmb2' ),
		// 	'desc'    => esc_html__( 'Additional banner upload not required for a 300x250 banner.', 'cmb2' ),
		// 	'id'      => 'html_banner_size',
		// 	'type'    => 'radio_inline',	
		// 	//'show_option_none' => '300x250',
		// 	'options' => array(
		// 		'300x250' => esc_html__( '300x250', 'cmb2' ),
		// 		'Other'   => esc_html__( 'Other', 'cmb2' ),
		// 	),
		// 	'after'=> '<div id="post_html_banner_size_errorloc" class="error_strings"></div><br/>',
		// ) );

		// Ad dimensions
		$html_content->add_field( array(
			'name'    => __( ucwords('Ad Dimensions ( <span style="color:red">*</span> )'), 'cmb2' ),
			'id'      => 'html_ad_dimension',
			'type'    => 'radio_inline',
			'options_cb' => array( &$this, 'sep_render_dimensions'),
			'after'=> '<div id="post_html_ad_dimension_errorloc" class="error_strings"></div><br/>',
		) );

		// Banner alignment
		// $html_content->add_field( array(
		// 	'name'    => esc_html__( ucwords('Post Alignment (*)'), 'cmb2' ),
		// 	'desc'    => esc_html__( 'Banner alignment in Ad details page', 'cmb2' ),
		// 	'id'      => 'html_banner_align',
		// 	'type'    => 'radio_inline',
		// 	'options' => array(
		// 		'left' => esc_html__( 'Left', 'cmb2' ),
		// 		'right' => esc_html__( 'Right', 'cmb2' ),
		// 	),
		// 	'after'=> '<div id="post_html_banner_align_errorloc" class="error_strings"></div><br/>',
		// ) );

		// Banner image
		$html_content->add_field( array(
			'name'    => __( ucwords('Banner Content ( <span style="color:red">*</span> )'), 'cmb2' ),
			'desc'    => esc_html__( 'Upload compressed .zip file for html5 rich media banner content.', 'cmb2' ),
			'id'      => 'html_banner_image',
			'type'    => 'file',
			'options' => array(
				'url' => false, // Hide the text input for the url
				//'file_download_text' => ' ',
			),
			'text'    => array(
				'add_upload_file_text' => 'Upload File' // Change upload button text
			),
			// query_args are passed to wp.media's library query.
			'query_args' => array(
				'type' => array(
				    'application/zip',
				),
			),			
			'after'=> '<div id="post_html_banner_image_errorloc" class="error_strings"></div><br/>',			
			'after_field' => '<div id="rich_media_show">'.$media_file.'</div><br/>',
		) );

		// Ad format
		$html_content->add_field( array(
			'name'    => esc_html__( ucwords('Ad Format'), 'cmb2' ),
			'id'      => 'html_ad_format',
			'type'    => 'select',					
			'options_cb' => array( &$this, 'sep_render_ad_formats' ),
		) );

		// Creative production tools
		$html_content->add_field( array(
			'name'    => __( ucwords('Creative production tools ( <span style="color:red">*</span> )'), 'cmb2' ),
			'id'      => 'html_production_tools',
			'type'    => 'text',
			'after'=> '<div id="post_html_production_tools_errorloc" class="error_strings"></div><br/>',
			'after_field' => '<div id="cpt_error"></div><br/>',
		) );	

		// Features
		$html_content->add_field( array(
			'name'    => __( ucwords('Features ( <span style="color:red">*</span> )'), 'cmb2' ),
			'id'      => 'html_features',
			'type'    => 'text',
            'after'=> '<div id="post_html_features_errorloc" class="error_strings"></div><br/>',
			'after_field' => '<div id="feature_error"></div><br/>',
		) );


		/* -------------------------------------------------------------------------------------- *
		 * Section:3 -> Add custom meta box for takeover/slider section 
		 * -------------------------------------------------------------------------------------- */


		$html_slider = new_cmb2_box( array(
			'id'            => 'html_content_slider_id',
			'title'         => esc_html__( ucwords('Enter Slider Contents'), 'cmb2' ),
			'object_types'  => array( 'html5' ), // Post type
			'context'    => 'normal',
			'priority'   => 'high'
		) );

		// slider screenshot image
		$html_slider->add_field( array(
			'name'    => __( ucwords('Upload Takeover Image ( <span style="color:red">*</span> )'), 'cmb2' ),
			'desc'    => esc_html__( '*Image must be either of 418x235 dimension or Retina (2x) dimension.', 'cmb2' ),
			'id'      => 'html_slider_image',
			'type'    => 'file',
			'options' => array(
				'url' => false,
			),
			'text'    => array(
				'add_upload_file_text' => 'Upload Image'
			),
			'query_args' => array(
				'type' => array(
				    'image/gif',
				    'image/jpeg',
				    'image/png',
				),
			),
			'after'=> '<div id="post_html_slider_image_errorloc" class="error_strings"></div><br/>',
		) );

		// banner description
		$html_slider->add_field( array(
			'name'    => __( ucwords('Enter Description ( <span style="color:red">*</span> )'), 'cmb2' ),
			'id'      => 'html_slider_description',
			'type'    => 'textarea_small',
            'after'=> '<div id="post_html_slider_description_errorloc" class="error_strings"></div><br/>',
			'after_field' => '<div id="desc_error"></div><br/>',
			// 'attributes' => array(
			// 	'maxlength' => '250',
			// ),
		) );

		// banner site url
		// $html_slider->add_field( array(
		// 	'name'    => esc_html__( ucwords('Enter Site Preview URL (*)'), 'cmb2' ),
		// 	'id'      => 'html_slider_preview_siteurl',
		// 	'type' => 'text',    
        //     'after'=> '<div id="post_html_slider_preview_siteurl_errorloc" class="error_strings"></div><br/>',
		// 	'after_field' => '<div id="prev_url_error"></div><br/>',
		// 	//'protocols' => array( 'http', 'https' ), // Array of allowed protocols   
		// ) );

		// Post status
		$html_slider->add_field( array(
			'name'    => __( ucwords('Post visibility status ( <span style="color:red">*</span> )'), 'cmb2' ),
			'desc'    => esc_html__( 'Post status to determine frontend visibility', 'cmb2' ),
			'id'      => 'html_post_visibility',
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
	 * Callbacks for rendering checkboxes from image-dimensions option 
	 */
	function sep_render_dimensions() {
	
		global $pagenow;
		global $post;

		$status = get_post_status( $post->ID );
		$dimensions = array();		

		$img_dimension_data = get_option('image_dimensions');
		$dimension_set = explode(',', $img_dimension_data);

		foreach( $dimension_set as $dimension ) {
			$dimensions[trim($dimension)] = esc_html__( trim($dimension), 'cmb2' );	
		}

		return $dimensions;
	}

	/**
	 * render industries for dropdown
	 */
	function sep_render_industries() {

		global $post;
		$options = array();

        $option_data = get_option( 'html_industry_type' );
        $data_set = explode( ',', $option_data );

        foreach( $data_set as $data ) {
            $options[trim($data)] = esc_html__( trim($data), 'cmb2' );
        }

		return $options;
	}


	/**
	 * render ad-formats for dropdown
	 */
	function sep_render_ad_formats() {
	
		global $post;	

		$options = array();

        $option_data = get_option( 'html_ad_format' );
        $data_set = explode( ',', $option_data );

        foreach( $data_set as $data ) {
            $options[trim($data)] = esc_html__( trim($data), 'cmb2' );
        }

		return $options;
	}


	/**
	 * Save meta boxes
	 * Runs when a post is saved and does an action which the write panel save scripts can hook into.
	 */
	public function html5_meta_boxes_save( $post_id, $post ) {
		if ( empty( $post_id ) || empty( $post ) || empty( $_POST ) ) return;
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
		if ( is_int( wp_is_post_revision( $post ) ) ) return;
		if ( is_int( wp_is_post_autosave( $post ) ) ) return;
		if ( ! current_user_can( 'edit_post', $post_id ) ) return;
		if ( $post->post_type != 'html5' ) return;
			
		$this->process_html5_meta( $post_id, $post );
	}
	
	/**
	 * Function for processing and storing all image data.
	 */
	private function process_html5_meta( $post_id, $post ) {

		isset( $_POST['publish'] ) ? $mode = 'create' : $mode = 'edit'; //determine post state of create or edit

		// all required fields (empty check)
		$thumbnail_image = $_POST['upload_html_thumbnail'];
		$creative_name   = $_POST['html_creative_name'];
		$cp_tools 		 = $_POST['html_production_tools'];
		$features		 = $_POST['html_features'];
		$slider_image 	 = $_POST['html_slider_image'];
		$slider_desc 	 = $_POST['html_slider_description'];
		$ad_dimension 	 = $_POST['html_ad_dimension'];
		$banner_file	 = $_POST['html_banner_image'];
		$banner_file_id	 = $_POST['html_banner_image_id'];		
		//$slider_url 	 = $_POST['html_slider_preview_siteurl'];

		if ( empty( $thumbnail_image ) || empty( $creative_name ) || empty( $cp_tools ) || empty( $features ) || empty( $slider_image ) || empty( $slider_desc ) ) {			
			
			if ( $mode == 'create' ) {
				add_filter( 'redirect_post_location', array( &$this, 'sep_html5_redirect_required_empty' ), 10 , 2 );
				wp_delete_post( $post_id , true ); // force delete the post	if required field remains empty
			} elseif ( $mode == 'edit' ) {
				add_filter( 'redirect_post_location', array( &$this, 'sep_html5_redirect_required_empty_edit' ), 10 , 2 );
			}	
		}	

		if ( !$ad_dimension || !$banner_file ) {

			if ( $mode == 'create' ) {
				add_filter( 'redirect_post_location', array( &$this, 'sep_html5_banner_image_empty' ), 10 , 2 );
				wp_delete_post( $post_id , true ); // force delete the post	if no banner img found
			} elseif ( $mode == 'edit' ) {
				add_filter( 'redirect_post_location', array( &$this, 'sep_html5_banner_image_empty_edit' ), 10 , 2 );
			}
		}

		/**
		 * Zip file upload
		 * Unzip
		 */
		$data = array(
			'post' => $post_id,
			'file' => $banner_file,
		);
		$folder   = "html5_rich_folder";
		$uploader = new ZIP_Uploader( $folder );
		$result   = $uploader->upload( $data );

		// handle zip upload errors
		if( is_wp_error( $result ) ) {
			
			$code = $result->get_error_code();
			$error_msg = $result->get_error_message();

			if ( $mode == 'create' ) {
				switch( $code ) {		
					case 'file-error' :
						add_filter( 'redirect_post_location', array( &$this, 'sep_zip_file_error_create' ), 10 , 2 );
						wp_delete_post( $post_id , true ); // force delete the post	if required field remains empty
					break;
					case 'extract-fail' :
						add_filter( 'redirect_post_location', array( &$this, 'sep_zip_file_extract_fail_create' ), 10 , 2 );
						wp_delete_post( $post_id , true ); // force delete the post	if required field remains empty
					break;
					case 'improper-file' :
						add_filter( 'redirect_post_location', array( &$this, 'sep_zip_file_wrong_create' ), 10 , 2 );
						wp_delete_post( $post_id , true ); // force delete the post	if required field remains empty
					break;
				}
			} elseif ( $mode == 'edit' ) {

				switch( $code ) {		
					case 'file-error' :
						add_filter( 'redirect_post_location', array( &$this, 'sep_zip_file_error_edit' ), 10 , 2 );
					break;
					case 'extract-fail' :
						add_filter( 'redirect_post_location', array( &$this, 'sep_zip_file_extract_fail_edit' ), 10 , 2 );
					break;
					case 'improper-file' :
						add_filter( 'redirect_post_location', array( &$this, 'sep_zip_file_wrong_edit' ), 10 , 2 );
					break;
				}

			}
		}

	}


	/**
	 * redirect hook functions to prevent post save default redirection and
	 * stage mechanism to throw appropriate admin notice
	 */
	function sep_html5_redirect_required_empty( $location, $post_id ) {
		$location = add_query_arg( 'required-field', $post_id, 'post-new.php?post_type=html5' );	
		return $location;		
	}

	function sep_html5_redirect_required_empty_edit( $location, $post_id ) {
		$location = add_query_arg( array('post' => $post_id, 'required-field-edit' => $post_id, 'action'=> 'edit'), 'post.php' );
		return $location;		
	}

	function sep_html5_banner_image_empty( $location, $post_id ) {
		$location = add_query_arg( 'banner-img', $post_id, 'post-new.php?post_type=html5' );	
		return $location;		
	}

	function sep_html5_banner_image_empty_edit( $location, $post_id ) {
		$location = add_query_arg( array('post' => $post_id, 'banner-img-edit' => $post_id, 'action'=> 'edit'), 'post.php' );
		return $location;		
	}

	function sep_zip_file_error_create( $location, $post_id ) {
		$location = add_query_arg( 'file-error', $post_id, 'post-new.php?post_type=html5' );	
		return $location;			
	}

	function sep_zip_file_error_edit( $location, $post_id ) {
		
		delete_post_meta($post_id, 'html_banner_image');
		delete_post_meta($post_id, 'html_banner_image_id');
		$location = add_query_arg( array('post' => $post_id, 'file-error-edit' => $post_id, 'action'=> 'edit'), 'post.php' );
		return $location;		
	}

	function sep_zip_file_extract_fail_create( $location, $post_id ) {
		$location = add_query_arg( 'extract-fail', $post_id, 'post-new.php?post_type=html5' );	
		return $location;			
	}

	function sep_zip_file_extract_fail_edit( $location, $post_id ) {
		
		delete_post_meta($post_id, 'html_banner_image');
		delete_post_meta($post_id, 'html_banner_image_id');
		$location = add_query_arg( array('post' => $post_id, 'extract-fail-edit' => $post_id, 'action'=> 'edit'), 'post.php' );
		return $location;		
	}

	function sep_zip_file_wrong_create( $location, $post_id ) {
		$location = add_query_arg( 'improper-file', $post_id, 'post-new.php?post_type=html5' );	
		return $location;			
	}

	function sep_zip_file_wrong_edit( $location, $post_id ) {

		delete_post_meta($post_id, 'html_banner_image');
		delete_post_meta($post_id, 'html_banner_image_id');
		$location = add_query_arg( array('post' => $post_id, 'improper-file-edit' => $post_id, 'action'=> 'edit'), 'post.php' );
		return $location;		
	}

	
	/**
	 * check redirected url for additional query args
	 * if found with our own url param, thorow admin notice after redirection
	 */
	function sep_admin_notices() {

		$notice = new AdminNotice();

		if ( isset( $_GET['required-field'] ) ) {
			$notice->displayError( 'Post save failed! Please confirm input for all the required fields.' );
		} 
		else if ( isset( $_GET['required-field-edit'] ) ) {
			$notice->displayWarning( 'Attention!! Data input for the required fields have not been confirmed.' );
		}
		elseif( isset( $_GET['banner-img'] ) ) {
			$notice->displayError( 'Post save failed! Please confirm successful upload of the banner image.' );
		}
		else if ( isset( $_GET['banner-img-edit'] ) ) {
			$notice->displayWarning( 'Warning!! The Banner image has not been selected.' );
		}		
		elseif( isset( $_GET['file-error'] ) ) {
			$notice->displayError( 'Post save failed! Please confirm upload of a proper compressed .zip file.' );
		}
		else if ( isset( $_GET['file-error-edit'] ) ) {
			$notice->displayWarning( 'Warning!! The selected .zip file has issues and could not be extracted.' );
		}
		elseif( isset( $_GET['extract-fail'] ) ) {
			$notice->displayError( 'Post save failed! Please try again to upload the .zip file' );
		}
		else if ( isset( $_GET['extract-fail-edit'] ) ) {
			$notice->displayWarning( 'Warning!! The selected .zip could not be extracted. Please try again.' );
		}
		elseif( isset( $_GET['improper-file'] ) ) {
			$notice->displayError( 'Post save failed! Please upload proper .zip file for rich media content.' );
		}
		else if ( isset( $_GET['improper-file-edit'] ) ) {
			$notice->displayWarning( 'Warning!! This .zip file is not accurate for rich media content. Extraction Failed. Please try again.' );
		}
		
	}

	/**
	 * remove additional query args after page load
	 */
	public function sep_add_removable_arg ($args) {
		array_push($args, 'required-field', 'required-field-edit', 'banner-img', 'banner-img-edit' , 'file-error', 'file-error-edit' ,'extract-fail', 'extract-fail-edit','improper-file','improper-file-edit');
		return $args;
	}


	/* ------------------------------------------------------------------------ *
     * AJAX functions
     * ------------------------------------------------------------------------ */


	function sep_chk_ajax_html5_thumbnail() {

		$media_image_id = $_POST['ID'];
		$image_category = $_POST['imageCategory'];
		$post_id = $_POST['postID'];

		$args = array(
			'post_type'  => 'html5',
			'meta_key'   => $image_category,
			'meta_query' => array(
				array(
					'key'     => $image_category,
					'value'   => $media_image_id ,
					'compare' => '=',
				),
			),
			'post__not_in' => [$post_id],
		);

		$the_query = new WP_Query( $args );

		if ( $the_query->have_posts() ) :
			echo true;
		else :
			echo false;
		endif;

		wp_die(); // this is required to terminate immediately and return a proper response

	}

	function sep_get_dimension_posts_html() {
		
		global $wpdb;
		$req_post_id = $_POST['postID'];
		$device 	 = $_POST['device'];
		$all_posts 	 = array();

		if ( $device == "desktop" ) {
			$results = $wpdb->get_results("
				SELECT
				p.ID as post_id
				FROM {$wpdb->prefix}posts p LEFT JOIN
				{$wpdb->prefix}postmeta pmstatus     
				ON pmstatus.post_id = p.id AND pmstatus.meta_key = 'html_post_visibility' INNER JOIN
				{$wpdb->prefix}postmeta pmdim     
				ON pmdim.post_id = p.id AND pmdim.meta_key = 'html_ad_dimension'
				WHERE p.post_type = 'html5'			
				AND p.ID <> '$req_post_id'
				AND pmstatus.meta_value = 'Active' AND pmdim.meta_value != '320x50'
				AND pmdim.meta_value != '300x50' ORDER BY p.post_date DESC;"
			);
		} elseif ( $device == "phone" ) {
			$results = $wpdb->get_results("
				SELECT
				p.ID as post_id
				FROM {$wpdb->prefix}posts p LEFT JOIN
				{$wpdb->prefix}postmeta pmstatus     
				ON pmstatus.post_id = p.id AND pmstatus.meta_key = 'html_post_visibility' INNER JOIN
				{$wpdb->prefix}postmeta pmdim     
				ON pmdim.post_id = p.id AND pmdim.meta_key = 'html_ad_dimension'
				WHERE p.post_type = 'html5'				
		 		AND p.ID <> '$req_post_id'
				AND pmstatus.meta_value = 'Active' AND pmdim.meta_value != '728x90'
				AND pmdim.meta_value != '970x250' ORDER BY p.post_date DESC;"
			);	
		} else {
			$results = $wpdb->get_results("
				SELECT
				p.ID as post_id
				FROM {$wpdb->prefix}posts p LEFT JOIN
				{$wpdb->prefix}postmeta pmstatus     
				ON pmstatus.post_id = p.id AND pmstatus.meta_key = 'html_post_visibility' INNER JOIN
				{$wpdb->prefix}postmeta pmdim     
				ON pmdim.post_id = p.id AND pmdim.meta_key = 'html_ad_dimension'
				WHERE p.post_type = 'html5'				
		 		AND p.ID <> '$req_post_id'
				AND pmstatus.meta_value = 'Active'
				AND pmdim.meta_value != '728x90'
				AND pmdim.meta_value != '970x250'
				AND pmdim.meta_value != '320x50'
				AND pmdim.meta_value != '300x50' ORDER BY p.post_date DESC;"
			);			
		}

		// set the initial post to the starting index for proper slider rendering
		$on_view_post = get_post_meta( $req_post_id );
		$on_view_post['post_id'] = array( $req_post_id );
		
		// add post id to query result
		foreach ( $results as $result ) {			
			$interim = get_post_meta( $result->post_id );
			$interim['post_id'] = array( $result->post_id );
			$all_posts[] = $interim;
		}

		array_unshift($all_posts , $on_view_post);

		echo json_encode( $all_posts );

		wp_die();

	}

	
	/**
	 * Enqueue required scripts
	 */
    function cmb2_validation_script_html5( $pagearg ) {
        
        global $post;

        $ver_param = date('dmYHis');
            
        // check if we are on custom post edit or add new page
        if ( $pagearg == 'post-new.php' || $pagearg == 'post.php') {
            if ( 'html5' === $post->post_type ) {
				wp_enqueue_style( 'html5-style', plugin_dir_url( __FILE__ ) . 'css/custom_error.css', false, $ver_param );
                wp_enqueue_script( 'gen_validatorv4-js', plugin_dir_url( __FILE__ ) .'js/gen_validatorv4.js',  array( 'jquery' ), $ver_param, true );
                wp_enqueue_script( 'html5-custom-js', plugin_dir_url( __FILE__ ) .'js/custom_html.js', array( 'jquery' ), $ver_param, true );
				$variables = array(
					'ajaxurl' => admin_url( 'admin-ajax.php' )
				);
				wp_localize_script('html5-custom-js', "htmbObject", $variables);

				// html5-> type post specific deregister script
				wp_deregister_script('postbox');
            }
        }
    }


}

$richMedia = new HtmlRich();

?>