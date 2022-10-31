<?php

class DevelopmentCms {

    public function __construct() {

		if ( is_admin() ) {

			add_action( 'init', array( &$this, 'init' ) );
            add_action( 'cmb2_admin_init', array( &$this, 'dcms_content_category_metabox' ) );	
            add_action( 'admin_enqueue_scripts', array( &$this, 'cmb2_validation_script_dcms' ) );
			add_action( 'save_post', array( &$this, 'dcms_meta_boxes_save' ), 99, 2 );			
			add_action( 'admin_notices', array( &$this, 'sep_admin_notices' ), 99);		
			add_filter( 'removable_query_args', array( &$this, 'sep_removable_arg'), 99 );

            // manage custom grid ()=> dcms
			add_filter( 'manage_dcms_posts_columns', array( &$this, 'sep_filter_dcms_columns') );			
			add_action( 'manage_dcms_posts_custom_column', array( &$this, 'sep_fill_dcms_columns'), 10, 2);
			
			// backend ajax -> duplicate image check
			add_action( 'wp_ajax_sep_chk_ajax_dcms', array( &$this, "sep_chk_ajax_dcms_image") );
			
		}
	}


    /**
	 * Register the custom post type
	 */
	public function init() {
        register_post_type(
		'dcms', 
		array( 
			'public' => false, // it's not public, it shouldn't have it's own permalink,
			'publicly_queryable' => false, // should not be able to preview/view it
			'show_ui' => true, // able to edit it in wp-admin
			'label' => 'Development & CMS',
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
	public function sep_filter_dcms_columns( $columns ) {
        $columns = array(
            'cb' => $columns['cb'],
            'title' => __( 'Title' ),
			'thumbnail_image' => __( 'Image' ),
			'header_title' => __( 'Header Title' ),
			'category' => __( 'Category' ),
            'date' => __( 'Published At' ),
          );
        return $columns;
    }

	/**
	 * Show custom post data
	 */
	public function sep_fill_dcms_columns( $column, $post_id ) {        
        switch ( $column ) {	
			// show image dimensions	
			case 'header_title':
				$name = get_post_meta( $post_id, 'dcms_title', true );

				if ( ! $name ) {
					_e( 'n/a' );  
				} else {
					echo $name;
				}				
				break;
			case 'category':
				$dcms_category = get_post_meta( $post_id, 'dcms_category', true );

				if ( ! $dcms_category ) {
					_e( 'n/a' );  
				} else {
					echo $dcms_category;			
				}				
			break;
			case 'thumbnail_image':
				$dcms_thumb_img = get_post_meta( $post_id, 'dcms_image_id', true );

				if ( ! $dcms_thumb_img ) {
					_e( 'n/a' );  
				} else {
					$img_url = (wp_get_attachment_image_src( $dcms_thumb_img, 'thumbnail' ) );					
					echo '<img src='.$img_url[0].' />';
				}				
			break;
		}
	}

    /**
	 * Generate additional fields for this custom category
	 */
    public function dcms_content_category_metabox() {

		$dcms_content = new_cmb2_box( array(
			'id'            => 'dcms_content_category_id',
			'title'         => esc_html__( ucwords('Development and CMS Elements'), 'cmb2' ),
			'object_types'  => array( 'dcms' ), // Post type
			'context'    => 'normal',
			'priority'   => 'high'
		) );

		// image
        $dcms_content->add_field( array(
			'name'    => __( ucwords('Image ( <span style="color:red">*</span> )'), 'cmb2' ),
			'desc'    => esc_html__( '*Image must be either of 618x328 dimension or Retina (2x) dimension.', 'cmb2' ),
			'id'      => 'dcms_image',
			'type'    => 'file',
			'options' => array(
				'url' => false, // Hide the text input for the url
			),
			'text'    => array(
				'add_upload_file_text' => 'Upload Image' // Change upload button text
			),
			// query_args are passed to wp.media's library query.
			'query_args' => array(
				'type' => array(
				    'image/gif',
				    'image/jpeg',
				    'image/png',
				),
			),			
			'after'=> '<div id="post_dcms_image_errorloc" class="error_strings"></div><br/>',	
		) );

        // preview link
		$dcms_content->add_field( array(
			'name'    => __( ucwords('View Project Link ( <span style="color:red">*</span> )'), 'cmb2' ),
			'id'      => 'dcms_preview_link',
			'type' => 'text',    
            'after'=> '<div id="post_dcms_preview_link_errorloc" class="error_strings"></div><br/>',
			'after_field' => '<div id="prev_url_error"></div><br/>',			
		) );

		// Category title
		$dcms_content->add_field( array(
			'name'    => esc_html__( ucwords('Category'), 'cmb2' ),
			'id'      => 'dcms_category',
			'type'    => 'select',
			'options_cb' => array( &$this, 'sep_render_dcms_categories' ),
            'after'=> '<div id="post_dcms_category_errorloc" class="error_strings"></div><br/>',
		) );

        // Header title
        $dcms_content->add_field( array(
			'name'    => __( ucwords('Header title ( <span style="color:red">*</span> )'), 'cmb2' ),
			'id'      => 'dcms_title',
			'type'    => 'text',
			'after'=> '<div id="post_dcms_title_errorloc" class="error_strings"></div><br/>',
			'after_field' => '<div id="dcms_title_error"></div><br/>',
		) );

		// Excerpt
		$dcms_content->add_field( array(
			'name'    => __( ucwords('Excerpt ( <span style="color:red">*</span> )'), 'cmb2' ),
			'id'      => 'dcms_description',
			'type'    => 'textarea_small',
            'after'=> '<div id="post_dcms_description_errorloc" class="error_strings"></div><br/>',
			'after_field' => '<div id="dcms_desc_error"></div><br/>',
		) );

		// Post status
		$dcms_content->add_field( array(
			'name'    => __( ucwords('Post visibility status ( <span style="color:red">*</span> )'), 'cmb2' ),
			'desc'    => esc_html__( 'Post status to determine frontend visibility', 'cmb2' ),
			'id'      => 'dcms_post_visibility',
			'type'    => 'radio_inline',
			'options' => array(
				'Active' => esc_html__( 'Active', 'cmb2' ),
				'Inactive' => esc_html__( 'Inactive', 'cmb2' ),
			),
			'default' => 'Active',
		) );
    }


	/**
	 * Save meta boxes
	 * Runs when a post is saved and does an action which the write panel save scripts can hook into.
	 */
	public function dcms_meta_boxes_save( $post_id, $post ) {
		if ( empty( $post_id ) || empty( $post ) || empty( $_POST ) ) return;
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
		if ( is_int( wp_is_post_revision( $post ) ) ) return;
		if ( is_int( wp_is_post_autosave( $post ) ) ) return;
		if ( ! current_user_can( 'edit_post', $post_id ) ) return;
		if ( $post->post_type != 'dcms' ) return;
			
		$this->process_dcms_meta( $post_id, $post );
	}
	
	/**
	 * Function for processing and storing all image data.
	 */
	private function process_dcms_meta( $post_id, $post ) {

		isset( $_POST['publish'] ) ? $mode = 'create' : $mode = 'edit'; //determine post state of create or edit

		// all required fields (empty check)
		$post_image    = $_POST['dcms_image'];
		$project_link  = $_POST['dcms_preview_link'];
		$category 	   = $_POST['dcms_category'];
		$header_title  = $_POST['dcms_title'];
		$excerpt 	   = $_POST['dcms_description'];

		if ( empty( $post_image ) || empty( $project_link ) || empty( $category ) || empty( $header_title ) || empty( $excerpt ) ) {			
			
			if ( $mode == 'create' ) {
				add_filter( 'redirect_post_location', array( &$this, 'sep_dcms_redirect_required_empty' ), 10 , 2 );
				wp_delete_post( $post_id , true ); // force delete the post	if required field remains empty
			} elseif ( $mode == 'edit' ) {
				add_filter( 'redirect_post_location', array( &$this, 'sep_dcms_redirect_required_empty_edit' ), 10 , 2 );
			}		
			return;
		}		
	}


	/**
	 * redirect hook functions to prevent post save default redirection and
	 * stage mechanism to throw appropriate admin notice
	 */
	function sep_dcms_redirect_required_empty( $location, $post_id ) {
		$location = add_query_arg( 'dcms-required-field', $post_id, 'post-new.php?post_type=dcms' );	
		return $location;
	}

	function sep_dcms_redirect_required_empty_edit( $location, $post_id ) {
		$location = add_query_arg( array('post' => $post_id, 'dcms-required-field-edit' => $post_id, 'action'=> 'edit'), 'post.php' );
		return $location;
	}

	/**
	 * check redirected url for additional query args
	 * if found with our own url param, thorow admin notice after redirection
	 */
	function sep_admin_notices() {

		$notice = new AdminNotice();

		if ( isset( $_GET['dcms-required-field'] ) ) {
			$notice->displayError( 'Post save failed! Please confirm input for all the required fields.' );
		} 
		else if ( isset( $_GET['dcms-required-field-edit'] ) ) {
			$notice->displayWarning( 'Attention!! Data input for the required fields have not been confirmed.' );
		}		
	}

	/**
	 * remove additional query args after page load
	 */
	public function sep_removable_arg ($args) {
		array_push($args, 'dcms-required-field', 'dcms-required-field-edit' );
		return $args;
	}


    /**
	 * Render select options from custom global options
	 */
    function sep_render_dcms_categories() {
	
		global $post;
		$options = array();

        $option_data = get_option( 'dcms_category' );
        $data_set = explode( ',', $option_data );

        foreach( $data_set as $data ) {
            $options[trim($data)] = esc_html__( trim($data), 'cmb2' );
        }

		return $options;
	}



	/* ------------------------------------------------------------------------ *
     * AJAX functions
     * ------------------------------------------------------------------------ */


	function sep_chk_ajax_dcms_image() {

		$media_image_id = $_POST['ID'];
		$image_category = $_POST['imageCategory'];
		$post_id = $_POST['postID'];

		$args = array(
			'post_type'  => 'dcms',
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


    /**
	 * Enqueue required scripts
	 */
    function cmb2_validation_script_dcms( $pagearg ) {
        
        global $post;

        $ver_param = date('dmYHis');
            
        // check if we are on custom post edit or add new page
        if ( $pagearg == 'post-new.php' || $pagearg == 'post.php') {
            if ( 'dcms' === $post->post_type ) {
				wp_enqueue_style( 'dcms-style', plugin_dir_url( __FILE__ ) . 'css/custom_error.css', false, $ver_param );
                wp_enqueue_script( 'dcms-gen-validatorv4', plugin_dir_url( __FILE__ ) .'js/gen_validatorv4.js',  array( 'jquery' ), $ver_param, true );
                wp_enqueue_script( 'dcms-custom-js', plugin_dir_url( __FILE__ ) .'js/custom_dcms.js', array( 'jquery' ), $ver_param, true );
				$variables = array(
					'ajaxurl' => admin_url( 'admin-ajax.php' )
				);
				wp_localize_script( 'dcms-custom-js', "dcmsObject", $variables );

				// html5 -> type post specific deregister script
				wp_deregister_script('postbox');
            }
        }
    }


}

$cms = new DevelopmentCms();

?>