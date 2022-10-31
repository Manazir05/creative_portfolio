<?php

class VideoProduction {

    public function __construct() {
		
		add_action( 'init', array( &$this, 'init' ) );
		add_action( 'cmb2_admin_init', array( &$this, 'video_content_category_metabox' ) );	
		add_action( 'admin_enqueue_scripts', array( &$this, 'cmb2_validation_script_video' ) );
		add_action( 'save_post', array( &$this, 'video_meta_boxes_save' ), 99, 2 );			
		add_action( 'admin_notices', array( &$this, 'sep_admin_notices' ), 99);		
		add_filter( 'removable_query_args', array( &$this, 'sep_removable_arg' ), 99 );

		// manage custom grid
		add_filter( 'manage_video_posts_columns', array( &$this, 'sep_filter_video_columns') );
		add_action( 'manage_video_posts_custom_column', array( &$this, 'sep_fill_video_columns' ), 10, 2);
		
		// backend ajax -> duplicate file check
		add_action( 'wp_ajax_sep_chk_ajax_video', array( &$this, 'sep_chk_ajax_video_file' ) );   
	}
	
		
	/**
	 * Register the custom post type
	 */
	public function init() {
	    register_post_type(
		'video', 
		array( 
			'public' => false, // it's not public, it shouldn't have it's own permalink,
			'publicly_queryable' => false, // should not be able to preview/view it
			'show_ui' => true, // able to edit it in wp-admin
			'label' => 'Video Production',
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
	public function sep_filter_video_columns( $columns ) {
        $columns = array(
            'cb' => $columns['cb'],
            'title' => __( 'Title' ),
			'video_file' => __( 'Media' ),
			'category' => __( 'Content Category' ),
			'video_title' => __( 'Content Title' ),
            'date' => __( 'Published At' ),
          );
        return $columns;
    }

	/**
	 * Show custom post data
	 */
	public function sep_fill_video_columns( $column, $post_id ) {        
        switch ( $column ) {
			// show selected image
			case 'category':
				$data1 = get_post_meta( $post_id, 'video_category', true );
				if ( ! $data1 ) {
					_e( 'n/a' );  
				} else {
					echo $data1;
				}
				break;		
			// show image dimensions	
			case 'video_title':
				$data2 = get_post_meta( $post_id, 'video_title', true );
				if ( ! $data2 ) {
					_e( 'n/a' );  
				} else {
					echo $data2;
				}
				break;
			case 'video_file':
				$video_data = get_post_meta( $post_id, 'video_file', true );

				if ( ! $video_data ) {
					_e( 'n/a' );  
				} else {
					echo '<video width = "100" height = "auto" controls>
				<source src = "'.$video_data.'"> </video>';			
				}				
				break;
		}
	}

	
	/**
	 * Hook in and add a demo metabox. Can only happen on the 'cmb2_admin_init' or 'cmb2_init' hook.
	 */
	public function video_content_category_metabox() {

		isset( $_GET['post'] ) ? $post_video_file = get_post_meta( $_GET['post'], 'video_file', true ) : $post_video_file = false;

		if ($post_video_file) {
			$video_file = '<video width = "200" height = "auto" controls><source src = "'.$post_video_file.'"> </video>';
		} else {
			$video_file = '';
		}

		/**
		 * Sample metabox to demonstrate each field type included
		 */
		$cmb_video = new_cmb2_box( array(
			'id'            => 'video_content_category_id',
			'title'         => esc_html__( 'Additional Info', 'cmb2' ),
			'object_types'  => array( 'video' ), // Post type
			'context'    => 'normal',
			'priority'   => 'high'
		) );

		// Video file
		$cmb_video->add_field( array(
			'name' => __( 'Upload Video ( <span style="color:red">*</span> )', 'cmb2' ),
			'desc' => esc_html__( 'Upload a .mp4 video file.', 'cmb2' ),
			'id'   => 'video_file',
			'type' => 'file',
			'options' => array (
				 'url' => false,
			),
			'after'=> '<div id="post_video_file_errorloc" class="error_strings"></div><br/>',
			'after_field' => '<div id="video_file_show">'.$video_file.'</div><br/>',
		) );

		// Video thumbnail
		$cmb_video->add_field( array(
			'name' => __( 'Upload Video Thumbnail ( <span style="color:red">*</span> )', 'cmb2' ),
			'desc'    => esc_html__( '*Image must be either of 824x464 dimension or Retina (2x) dimension.', 'cmb2' ),
			'id'   => 'video_thumbnail',
			'type' => 'file',
			'options' => array (
				 'url' => false,
			),
			'after'=> '<div id="post_video_thumbnail_errorloc" class="error_strings"></div><br/>',
		) );

		// Category
		$cmb_video->add_field( array(
			'name'    => esc_html__( ucwords('Category'), 'cmb2' ),
			'id'      => 'video_category',
			'type'    => 'select',
			'options_cb' => array( &$this, 'sep_render_video_categories' ),
            'after'=> '<div id="post_video_category_errorloc" class="error_strings"></div><br/>',
		) );

		// Header title
		$cmb_video->add_field( array(
			'name'    => __( ucwords('Header title ( <span style="color:red">*</span> )'), 'cmb2' ),
			'id'      => 'video_title',
			'type'    => 'text',
			'after'=> '<div id="post_video_title_errorloc" class="error_strings"></div><br/>',
			'after_field' => '<div id="video_title_error"></div><br/>',
		) );

		// Excerpt
		$cmb_video->add_field( array(
			'name'    => __( ucwords('Excerpt ( <span style="color:red">*</span> )'), 'cmb2' ),
			'id'      => 'video_description',
			'type'    => 'textarea_small',
            'after'=> '<div id="post_video_description_errorloc" class="error_strings"></div><br/>',
			'after_field' => '<div id="video_desc_error"></div><br/>',
		) );

		// Post status
		$cmb_video->add_field( array(
			'name'    => __( ucwords('Post visibility status ( <span style="color:red">*</span> )'), 'cmb2' ),
			'desc'    => esc_html__( 'Post status to determine frontend visibility', 'cmb2' ),
			'id'      => 'video_post_visibility',
			'type'    => 'radio_inline',
			'options' => array(
				'Active' => esc_html__( 'Active', 'cmb2' ),
				'Inactive' => esc_html__( 'Inactive', 'cmb2' ),
			),
			'default' => 'Active',
		) );
	}


	/**
	 * Render select options from custom global options
	 */
    function sep_render_video_categories() {
	
		global $post;
		$options = array();

        $option_data = get_option( 'video_category' );
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
	public function video_meta_boxes_save( $post_id, $post ) {
		if ( empty( $post_id ) || empty( $post ) || empty( $_POST ) ) return;
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
		if ( is_int( wp_is_post_revision( $post ) ) ) return;
		if ( is_int( wp_is_post_autosave( $post ) ) ) return;
		if ( ! current_user_can( 'edit_post', $post_id ) ) return;
		if ( $post->post_type != 'video' ) return;
			
		$this->process_video_meta( $post_id, $post );
	}
	
	/**
	 * Function for processing and storing all image data.
	 */
	private function process_video_meta( $post_id, $post ) {

		isset( $_POST['publish'] ) ? $mode = 'create' : $mode = 'edit'; //determine post state of create or edit

		// all required fields (empty check)
		$video_file = $_POST['video_file'];
		$video_category = $_POST['video_category'];
		$video_title = $_POST['video_title'];
		$excerpt = $_POST['video_description'];

		if ( empty( $video_file ) || empty( $video_category ) || empty( $video_title ) || empty( $excerpt ) ) {			
			
			if ( $mode == 'create' ) {
				add_filter( 'redirect_post_location', array( &$this, 'sep_video_redirect_required_empty' ), 10 , 2 );
				wp_delete_post( $post_id , true ); // force delete the post	if required field remains empty
			} elseif ( $mode == 'edit' ) {
				add_filter( 'redirect_post_location', array( &$this, 'sep_video_redirect_required_empty_edit' ), 10 , 2 );
			}		
			return;
		}		
	}

	/**
	 * redirect hook functions to prevent post save default redirection and
	 * stage mechanism to throw appropriate admin notice
	 */
	function sep_video_redirect_required_empty( $location, $post_id ) {
		$location = add_query_arg( 'vid-required-field', $post_id, 'post-new.php?post_type=video' );	
		return $location;
	}

	function sep_video_redirect_required_empty_edit( $location, $post_id ) {
		$location = add_query_arg( array('post' => $post_id, 'vid-required-field-edit' => $post_id, 'action'=> 'edit'), 'post.php' );
		return $location;
	}

	/**
	 * check redirected url for additional query args
	 * if found with our own url param, thorow admin notice after redirection
	 */
	function sep_admin_notices() {

		$notice = new AdminNotice();

		if ( isset( $_GET['vid-required-field'] ) ) {
			$notice->displayError( 'Post save failed! Please confirm input for all the required fields.' );
		} 
		else if ( isset( $_GET['vid-required-field-edit'] ) ) {
			$notice->displayWarning( 'Attention!! Data input for the required fields have not been confirmed.' );
		}		
	}

	/**
	 * remove additional query args after page load
	 */
	public function sep_removable_arg ($args) {
		array_push($args, 'vid-required-field', 'vid-required-field-edit' );
		return $args;
	}


	/* ------------------------------------------------------------------------ *
     * AJAX Functions
     * ------------------------------------------------------------------------ */


	function sep_chk_ajax_video_file() {

		$media_image_id = $_POST['ID'];
		$image_category = $_POST['imageCategory'];
		$post_id = $_POST['postID'];

		$args = array(
			'post_type'  => 'video',
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
    function cmb2_validation_script_video( $pagearg ) {
        
        global $post;

        $ver_param = date('dmYHis');
            
        // check if we are on custom post edit or add new page
        if ( $pagearg == 'post-new.php' || $pagearg == 'post.php') {
            if ( 'video' === $post->post_type ) {
				wp_enqueue_style( 'video-style', plugin_dir_url( __FILE__ ) . 'css/custom_error.css', false, $ver_param );
                wp_enqueue_script( 'video-gen-validatorv4', plugin_dir_url( __FILE__ ) .'js/gen_validatorv4.js',  array( 'jquery' ), $ver_param, true );
                wp_enqueue_script( 'video-custom-js', plugin_dir_url( __FILE__ ) .'js/custom_video.js', array( 'jquery' ), $ver_param, true );
				$variables = array(
					'ajaxurl' => admin_url( 'admin-ajax.php' )
				);
				wp_localize_script( 'video-custom-js', "vidObject", $variables );

				// html5 -> type post specific deregister script
				wp_deregister_script('postbox');
            }
        }
    }
	
}

$video = new VideoProduction();

?>