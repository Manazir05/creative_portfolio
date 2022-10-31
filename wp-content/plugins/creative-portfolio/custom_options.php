<?php

class CustomOptions {

    /* ------------------------------------------------------------------------ *
     * Setting Registration
     * ------------------------------------------------------------------------ */

    function __construct() {		

        add_action( "admin_menu", array( &$this, 'sep_custom_options_submenu' ) );
        add_action( 'admin_init', array( &$this, 'sep_initialize_custom_settings' ) );
        add_filter( 'upload_mimes', array( &$this, 'sep_custom_mime_types' ) );        
	}

    /**
     * Create Custom option menu 
     */
    function sep_custom_options_submenu() {
        add_options_page(
            'Portfolio Options',
            'Custom Portfolio Options',
            'administrator',
            'portfolio_options',
            array( $this, 'sep_custom_page_content' )
        );

        add_action( 'admin_enqueue_scripts', array( &$this, 'sep_include_js' ) );        
    }


    /**
     * Allow svg type files
     */
    function sep_custom_mime_types($mimes) {
        $mimes['svg'] = 'image/svg+xml';
        return $mimes;
    }

    /**
     * Render content to display on the option page 
     */
    function sep_custom_page_content () {
        echo '<div class="wrap">
        <h1>Portfolio Custom Settings</h1>
        <form method="post" action="options.php">';    

            settings_fields( 'portfolio_options' );       // settings group name
            do_settings_sections( 'portfolio_options' ); // just a page slug
            submit_button();

        echo '</form></div>';        
    }


    /**
     * Register custom settings
     */
    function sep_initialize_custom_settings() {

        // Register a section. This is necessary since the options must belong to one. 
        add_settings_section(
            'custom_general_settings_section',                    // ID used to identify this section and with which to register options
            'Custom Settings',                                   // Title to be displayed on the administration page
            '',                                                 // Callback used to render the description of the section
            'portfolio_options'                                // Page on which to add this section of options
        );
        
        // Next, these are the fields for saving the option values
        add_settings_field( 
            'ad_unit_types',                                  // ID used to identify the field throughout the plugin/theme
            'Static & Animated GIFs Ad Type',                // The label to the left of the option interface element
            array( &$this, 'sep_ad_unit_types_callback'),   //  callback                                            
            'portfolio_options',                           // The page slug on which this option will be displayed
            'custom_general_settings_section',            // The name of the section to which this field belongs            
        );

        add_settings_field( 
            'image_dimensions',                                  // ID used to identify the field throughout the plugin/theme
            'Applicable Dimensions (Static & Animated GIFs)',   // The label to the left of the option interface element
            array( &$this, 'sep_static_dimensions_callback'),  //  callback                                            
            'portfolio_options',                              // The page slug on which this option will be displayed
            'custom_general_settings_section',               // The name of the section to which this field belongs            
        );

        add_settings_field( 
            'html_dimensions',                                   // ID used to identify the field throughout the plugin/theme
            'Applicable Dimensions (HTML5 and Rich Media)',     // The label to the left of the option interface element
            array( &$this, 'sep_html_dimensions_callback'),    // callback                                            
            'portfolio_options',                              // The page slug on which this option will be displayed
            'custom_general_settings_section',               // The name of the section to which this field belongs            
        );

        add_settings_field( 
            'copyright_text',                                   // ID used to identify the field throughout the plugin/theme
            'Enter copyright',                                 // The label to the left of the option interface element
            array( &$this, 'sep_display_copy_right'),         // callback                                            
            'portfolio_options',                             // The page slug on which this option will be displayed
            'custom_general_settings_section',              // The name of the section to which this field belongs            
        );

        add_settings_field( 
            'uploaded_logo',                                  // ID used to identify the field throughout the plugin/theme
            'Upload Logo',                                   // The label to the left of the option interface element
            array( &$this, 'sep_display_logo_uploader'),    //  callback                                            
            'portfolio_options',                           // The page slug on which this option will be displayed
            'custom_general_settings_section',            // The name of the section to which this field belongs            
        );

        // Finally, register the fields with WordPress
        register_setting(
            'portfolio_options',            // The page slug on which this option will be displayed
            'ad_unit_types',                // ID used to identify the field throughout the plugin/theme
        );

        register_setting(
            'portfolio_options',            // The page slug on which this option will be displayed
            'image_dimensions'             // ID used to identify the field throughout the plugin/theme
        );

        register_setting(
            'portfolio_options',         // The page slug on which this option will be displayed
            'html_dimensions'           // ID used to identify the field throughout the plugin/theme
        );

        register_setting(
            'portfolio_options',          // The page slug on which this option will be displayed
            'copyright_text',            // ID used to identify the field throughout the plugin/theme
        );

        register_setting(
            'portfolio_options',         // The page slug on which this option will be displayed
            'uploaded_logo'             // ID used to identify the field throughout the plugin/theme
        );


        /* ------------------------------------------------------------------------ *
         * Homepage Static & Animated category settings
         * ------------------------------------------------------------------------ */

        add_settings_section(
            'homepage_static_settings_section',                    // ID used to identify this section and with which to register options
            'Static & Animated Gifs',                             // Title to be displayed on the administration page
            '',                                                  // Callback used to render the description of the section
            'portfolio_options'                                 // Page on which to add this section of options
        );

        add_settings_field( 
            'homepage_static_image',                                  // ID used to identify the field throughout the plugin/theme
            'Upload Image',                                          // The label to the left of the option interface element
            array( &$this, 'sep_display_homepage_static_image' ),   //  callback                                            
            'portfolio_options',                                   // The page slug on which this option will be displayed
            'homepage_static_settings_section',                   // The name of the section to which this field belongs            
        );

        add_settings_field( 
            'homepage_static_title',                                 // ID used to identify the field throughout the plugin/theme
            'Enter title',                                          // The label to the left of the option interface element
            array( &$this, 'sep_display_homepage_static_title' ),  //  callback                                            
            'portfolio_options',                                  // The page slug on which this option will be displayed
            'homepage_static_settings_section',                  // The name of the section to which this field belongs            
        );

        register_setting(
            'portfolio_options',
            'homepage_static_image'
        );

        register_setting(
            'portfolio_options',
            'homepage_static_title',
        );


        /* ------------------------------------------------------------------------ *
         * HTML5 & Rich Media category settings
         * ------------------------------------------------------------------------ */

        add_settings_section(
            'homepage_rich_media_settings_section',           
            'HTML5 & Rich Media',  
            '',
            'portfolio_options'                            
        );

        add_settings_field( 
            'homepage_rich_image',
            'Upload Image',
            array( &$this, 'sep_display_homepage_rich_image' ),
            'portfolio_options',
            'homepage_rich_media_settings_section',
        );

        add_settings_field( 
            'homepage_rich_title',
            'Enter title',
            array( &$this, 'sep_display_homepage_rich_title' ),
            'portfolio_options',
            'homepage_rich_media_settings_section',
        );

        add_settings_field( 
            'html_industry_type',
            'Industry types',
            array( &$this, 'sep_add_html_industry' ),
            'portfolio_options',
            'homepage_rich_media_settings_section',
        );

        add_settings_field( 
            'html_ad_format',
            'AD formats',
            array( &$this, 'sep_add_html_ad_format' ),
            'portfolio_options',
            'homepage_rich_media_settings_section',
        );

        register_setting(
            'portfolio_options',
            'homepage_rich_image'
        );

        register_setting(
            'portfolio_options',
            'homepage_rich_title'
        );

        register_setting(
            'portfolio_options',
            'html_industry_type'
        );

        register_setting(
            'portfolio_options',
            'html_ad_format'
        );

        /* ------------------------------------------------------------------------ *
         * Development & CMS category settings
         * ------------------------------------------------------------------------ */

        add_settings_section(
            'homepage_development_cms_settings_section',           
            'Development & CMS',  
            '',
            'portfolio_options'                            
        );

        add_settings_field( 
            'homepage_cms_image',
            'Upload Image',
            array( &$this, 'sep_display_homepage_cms_image' ),
            'portfolio_options',
            'homepage_development_cms_settings_section',
        );

        add_settings_field( 
            'homepage_cms_title',
            'Enter title',
            array( &$this, 'sep_display_homepage_cms_title' ),
            'portfolio_options',
            'homepage_development_cms_settings_section',
        );

        add_settings_field( 
            'dcms_category',
            'CMS Category Titles',
            array( &$this, 'sep_add_dcms_category' ),
            'portfolio_options',
            'homepage_development_cms_settings_section',
        );

        register_setting(
            'portfolio_options',
            'homepage_cms_image'
        );

        register_setting(
            'portfolio_options',
            'homepage_cms_title',
        );

        register_setting(
            'portfolio_options',
            'dcms_category'
        );

        /* ------------------------------------------------------------------------ *
         * Video Production category settings
         * ------------------------------------------------------------------------ */

        add_settings_section(
            'homepage_video_settings_section',           
            'Video Production',  
            '',
            'portfolio_options'                            
        );

        add_settings_field( 
            'homepage_video_image',
            'Upload Image',
            array( &$this, 'sep_display_homepage_video_image' ),
            'portfolio_options',
            'homepage_video_settings_section',
        );

        add_settings_field( 
            'homepage_video_title',
            'Enter title',
            array( &$this, 'sep_display_homepage_video_title' ),
            'portfolio_options',
            'homepage_video_settings_section',
        );

        add_settings_field( 
            'video_category',
            'Video Production',
            array( &$this, 'sep_add_video_category' ),
            'portfolio_options',
            'homepage_video_settings_section',
        );

        register_setting(
            'portfolio_options',
            'homepage_video_image'
        );

        register_setting(
            'portfolio_options',
            'homepage_video_title',
        );    
        
        register_setting(
            'portfolio_options',
            'video_category'
        );

        // title fields validation and error notice
        add_filter( 'sanitize_option_homepage_static_title', array( &$this, 'sep_sanitize_any_text_field' ), 10, 2 );
        add_filter( 'sanitize_option_homepage_rich_title', array( &$this, 'sep_sanitize_any_text_field' ), 10, 2 );
        add_filter( 'sanitize_option_homepage_cms_title', array( &$this, 'sep_sanitize_any_text_field' ), 10, 2 );
        add_filter( 'sanitize_option_homepage_video_title', array( &$this, 'sep_sanitize_any_text_field' ), 10, 2 );

        // textarea fields validation and error msg
        add_filter( 'sanitize_option_copyright_text', array( &$this, 'sep_sanitize_any_textarea' ), 10, 2 );
        add_filter( 'sanitize_option_html_industry_type', array( &$this, 'sep_sanitize_any_textarea' ), 10, 2 );
        add_filter( 'sanitize_option_html_ad_format', array( &$this, 'sep_sanitize_any_textarea' ), 10, 2 );
        add_filter( 'sanitize_option_dcms_category', array( &$this, 'sep_sanitize_any_textarea' ), 10, 2 );
        add_filter( 'sanitize_option_video_category', array( &$this, 'sep_sanitize_any_textarea' ), 10, 2 );
    }


    /**
     * textarea sanitaization
     */
    function sep_sanitize_any_textarea ( $data, $option ) {
        
        switch ( $option ) {
            case 'copyright_text':
                $field = 'copyright_text';
                $field_label = 'Copyright';
            break;
            case 'html_industry_type':
                $field = 'html_industry_type';
                $field_label = 'Industry Types';
            break;
            case 'html_ad_format':
                $field = 'html_ad_format';
                $field_label = 'Ad Formats';
            break;
            case 'dcms_category':
                $field = 'dcms_category';
                $field_label = 'Development Category Title';
            break;
        }

        if ( is_null( $data ) || empty( $data ) || !preg_match("/.*\S.*$/", $data ) ) {
            
            $type = 'error';
            $message = $field_label.' field can not be empty';
            add_settings_error(
                $field,
                'blank_'.$field,
                $message,
                $type
            );
            
            // Use the current database value to cancel saving with new value.
            $data = get_option( $field );

        } else {

            $illegal_chars  = "@#$%^&*()+=-[]';./{}|:<>?~";
            $escaped_text   = esc_textarea( sanitize_textarea_field( $data ) );
            $sanitized_text = str_replace( strpbrk( $escaped_text , $illegal_chars ), '', $escaped_text );
            $replaced_text  = preg_replace( "/[^[0-9a-zA-Z]+(,[0-9a-zA-Z]+)*$/", '', $sanitized_text );   
            $data = $replaced_text;       
            
            if ( strpbrk( $escaped_text , $illegal_chars ) ) {
                $type = 'warning';
                $message = $field_label.' field will not allow special characters';  
                
                add_settings_error(
                    $field,
                    'special_'.$field,
                    $message,
                    $type
                );
            }  
        }  

        return $data;    
    }


    /**
     * input:text field sanitaization
     */
    function sep_sanitize_any_text_field ( $data, $option ) {
        
        switch ( $option ) {

            case 'homepage_static_title':
                if ( empty( $data ) ) {
                    add_settings_error( 'homepage_static_title', 'missing_static',
                        'Please enter a valid value into Static & Animated Gifs title', 'error');
    
                    // Use the current database value to cancel saving with new value.
                    $data = get_option( 'homepage_static_title' );

                } else {
                    $illegal_chars  = "@#$%^*()+=-[]';./{}|:<>?~\"";
                    //$escaped_text   = esc_html( sanitize_text_field( $data ) ); // // escapes & 
                    $sanitized_text = str_replace( strpbrk( $data , $illegal_chars ), '', $data );
                    $replaced_text  = preg_replace( "/[^[0-9a-zA-Z]+(,[0-9a-zA-Z]+)*$/", '', $sanitized_text ); 

                    if ( strpbrk( $data , $illegal_chars ) ) {                        
                        add_settings_error( 
                            'homepage_static_title', 
                            'special_static',
                            'Please avoid entering any special symbol into Static & Animated Gifs title',
                            'warning'
                        );
                    } 
                    $data = $replaced_text;
                }
    
                break;
            case 'homepage_rich_title':
                if ( empty( $data ) ) {

                    add_settings_error( 'homepage_rich_title', 'missing_rich',
                        'Please enter a valid value into HTML5 and Rich Media title', 'error');
    
                    $data = get_option( 'homepage_rich_title' );

                } else {
                    $illegal_chars  = "@#$%^*()+=-[]';./{}|:<>?~\"";
                    //$escaped_text   = esc_html( sanitize_text_field( $data ) ); // // escapes & 
                    $sanitized_text = str_replace( strpbrk( $data , $illegal_chars ), '', $data );
                    $replaced_text  = preg_replace( "/[^[0-9a-zA-Z]+(,[0-9a-zA-Z]+)*$/", '', $sanitized_text );

                    if ( strpbrk( $data , $illegal_chars ) ) {                        
                        add_settings_error( 
                            'homepage_rich_title',
                            'special_rich',
                            'Please avoid entering any special symbol into HTML5 and Rich Media title',
                            'warning');
                    } 
                    $data = $replaced_text;
                }    
                break;
            case 'homepage_cms_title':
                if ( empty( $data ) ) {

                    add_settings_error( 'homepage_cms_title', 'missing_cms',
                        'Please enter a valid value into Development & CMS title', 'error');
    
                    $data = get_option( 'homepage_cms_title' );

                } else {
                    $illegal_chars  = "@#$%^*()+=-[]';./{}|:<>?~\"";
                    //$escaped_text   = esc_html( sanitize_text_field( $data ) ); // // escapes & 
                    $sanitized_text = str_replace( strpbrk( $data , $illegal_chars ), '', $data );
                    $replaced_text  = preg_replace( "/[^[0-9a-zA-Z]+(,[0-9a-zA-Z]+)*$/", '', $sanitized_text );   

                    if ( strpbrk( $data , $illegal_chars ) ) {                        
                        add_settings_error( 
                            'homepage_cms_title',
                            'special_cms',
                            'Please avoid entering any special symbol into Development & CMS title',
                            'warning');
                    } 
                    $data = $replaced_text;
                }    
                break;
            case 'homepage_video_title':
                if ( empty( $data ) ) {

                    add_settings_error( 'homepage_video_title', 'missing_video',
                        'Please enter a valid value into Video Production title', 'error');
    
                    $data = get_option( 'homepage_video_title' );

                } else {
                    $illegal_chars  = "@#$%^*()+=-[]';./{}|:<>?~\"";
                    //$escaped_text   = esc_html( sanitize_text_field( $data ) ); // // escapes & 
                    $sanitized_text = str_replace( strpbrk( $data , $illegal_chars ), '', $data );
                    $replaced_text  = preg_replace( "/[^[0-9a-zA-Z]+(,[0-9a-zA-Z]+)*$/", '', $sanitized_text ); 

                    if ( strpbrk( $data , $illegal_chars ) ) {                        
                        add_settings_error( 
                            'homepage_video_title',
                            'special_video',
                            'Please avoid entering any special symbol into Video Production title',
                            'warning');
                    }
                    $data = $replaced_text;
                }    
                break;
        }
    
        return $data;
    }


    /* ------------------------------------------------------------------------ *
     * Section Callbacks
     * ------------------------------------------------------------------------ */
    


    /* ------------------------------------------------------------------------ *
     * General content callbacks
     * ------------------------------------------------------------------------ */

    /**
     * the input field for setting static content dimensions
     */
    function sep_ad_unit_types_callback() {
        $html  = '<textarea style="width:40%" id="ad_unit_types" name="ad_unit_types" readonly>'. esc_html__( get_option('ad_unit_types') ) . '</textarea> <br>';      
        $html .= '<span>Enter Ad Unit types (comma-separated).</span>';
        echo $html;       
    }
    
    /**
     * the input field for setting static content dimensions
     */
    function sep_static_dimensions_callback() {

        $html  = '<textarea style="width:40%" id="image_dimensions" name="image_dimensions" readonly>'. get_option('image_dimensions') . '</textarea> <br>';
        // $html .= '<div id="add_dimension" style="display:none"> Width: <input type="number" id="add_width" /> Height: <input type="number" id="add_height" /> </div>';
        // $html .= '<div id="remove_dimension" style="display:none;"> Width: <input type="number" id="remove_width" /> Height: <input type="number" id="remove_height" /> </div> <br>';
        // $html .= '<button class="button button-primary" id="add_unit_btn">ADD</button>';
        // $html .= '<button class="button button-primary"  style="margin-left:5px; background-color:red;" id="remove_unit_btn">Remove</button>';   
        echo $html;       
    }

    /**
     * the input field for setting html5 and rich media dimensions
     */
    function sep_html_dimensions_callback() {
        $html  = '<textarea style="width:40%" id="html_dimensions" name="html_dimensions" readonly>'. get_option('html_dimensions') . '</textarea> <br>';      
        // $html .= '<div id="add_html_dimension" style="display:none"> Width: <input type="number" id="add_html_width" /> Height: <input type="number" id="add_html_height" /> </div>';
        // $html .= '<div id="remove_html_dimension" style="display:none"> Width: <input type="number" id="remove_html_width" /> Height: <input type="number" id="remove_html_height" /> </div> <br>';
        // $html .= '<button class="button button-primary" id="add_html_btn">ADD</button>';
        // $html .= '<button class="button button-primary" style="margin-left:5px; background-color:red;" id="remove_html_btn">Remove</button>';
        echo $html;       
    }

    /**
     * the input field for setting copy right
     */
    function sep_display_copy_right() {
        $html = '<textarea class="text-fields" style="width:40%" id="copyright_text" name="copyright_text" maxlength="40" required>'. get_option('copyright_text') . '</textarea>';      
        $html .= '<span style="color:blue;display:block;"> *Max character length : 40 </span>';
        echo $html;       
    }

    /**
     * the input field for uploading logo
     */
    function sep_display_logo_uploader( ) {    

        $image_id  = get_option('uploaded_logo');
        $image_id > 0 ? $image_src = wp_get_attachment_image_src( $image_id , 'full') : $image_src = false;

        if( $image_src ) {
            echo '<a href="#" class="sep-upl"></a>
                  <a href="#" class="sep-rmv">Remove image</a>
                  <input type="hidden" id="uploaded_logo" name="uploaded_logo" value="'.$image_id.'"> <br>
                  <img alt="no image" width="300" height="auto" src="' . $image_src[0] . '" />';        
        } else {
            echo '<a href="#" class="sep-upl">Upload image</a>
                  <a href="#" class="sep-rmv" style="display:none">Remove image</a>
                  <input type="hidden" name="uploaded_logo" id="uploaded_logo"> <br>
                  <img src="" />';        
        } 
    }

    
    /* ------------------------------------------------------------------------ *
     * Homepage Static & Animated content callbacks
     * ------------------------------------------------------------------------ */

    /**
     * the input field for uploading image for Static section
     */
    function sep_display_homepage_static_image() {
     
        $image_id  = get_option('homepage_static_image');
        $image_id > 0 ? $image_src = wp_get_attachment_image_src( $image_id , 'full') : $image_src = false;

        if( $image_src ) {
            echo '<a href="#" class="sep-upl"></a>
                  <a href="#" class="sep-rmv">Remove image</a>
                  <input type="hidden" id="homepage_static_image" name="homepage_static_image" value="'.$image_id.'"> <br>
                  <img alt="no image" width="300" height="auto" src="' . $image_src[0] . '" />';        
        } else {
            echo '<a href="#" class="sep-upl">Upload image</a>
                  <a href="#" class="sep-rmv" style="display:none">Remove image</a>
                  <input type="hidden" name="homepage_static_image" id="homepage_static_image"> <br>
                  <img src="" />';        
        }
    }

    /**
     * the input field for setting title for Static section
     */
    function sep_display_homepage_static_title() {
        $html = '<input type="text" id="homepage_static_title" maxlength="25" style="width:30%" name="homepage_static_title" required value="'. get_option('homepage_static_title') . '"/>';
        $html .= '<span style="color:blue;display:block;"> *Max character length : 25 </span>';
        echo $html;       
    }


    /* ------------------------------------------------------------------------ *
     * HTML5 & Rich media content callbacks
     * ------------------------------------------------------------------------ */


    /**
     * the input field for uploading image for HTML5 & rich media section
     */
    function sep_display_homepage_rich_image() { 
        
        $image_id  = get_option('homepage_rich_image');
        $image_id > 0 ? $image_src = wp_get_attachment_image_src( $image_id , 'full') : $image_src = false;

        if( $image_src ) {
            echo '<a href="#" class="sep-upl"></a>
                  <a href="#" class="sep-rmv">Remove image</a>
                  <input type="hidden" id="homepage_rich_image" name="homepage_rich_image" value="'.$image_id.'"> <br>
                  <img alt="no image" width="300" src="' . $image_src[0] . '" />';        
        } else {
            echo '<a href="#" class="sep-upl">Upload image</a>
                  <a href="#" class="sep-rmv" style="display:none">Remove image</a>
                  <input type="hidden" name="homepage_rich_image" id="homepage_rich_image"> <br>
                  <img src="" />';        
        }
    }

    /**
     * the input field for setting title for HTML5 & rich media section
     */
    function sep_display_homepage_rich_title() {
        $html = '<input type="text" id="homepage_rich_title" maxlength="25" style="width:30%" name="homepage_rich_title" required value="'. get_option('homepage_rich_title') . '"/>';      
        $html .= '<span style="color:blue;display:block;"> *Max character length : 25 </span>';
        echo $html;       
    }

    /**
     * the select options for HTML5 Industry selection
     */
    function sep_add_html_industry() {
        $html = '<textarea class="text-fields" style="width:40%" id="html_industry_type" name="html_industry_type" required>'. get_option('html_industry_type') . '</textarea>';      
        $html .= '<span style="color:green;display:block;"> *Enter Comma <,> separated values </span>';
        echo $html;       
    }

    /**
     * the select options for HTML5 Ad format selection
     */
    function sep_add_html_ad_format() {
        $html = '<textarea class="text-fields" style="width:40%" id="html_ad_format" name="html_ad_format" required>'. get_option('html_ad_format') . '</textarea>';      
        $html .= '<span style="color:green;display:block;"> *Enter Comma <,> separated values </span>';
        echo $html;       
    }


    /* ------------------------------------------------------------------------ *
     * Development & CMS content callbacks
     * ------------------------------------------------------------------------ */


    /**
     * the input field for uploading image for HTML5 & rich media section
     */
    function sep_display_homepage_cms_image() {
        
        $image_id  = get_option('homepage_cms_image');
        $image_id > 0 ? $image_src = wp_get_attachment_image_src( $image_id , 'full') : $image_src = false;

        if( $image_src ) {
            echo '<a href="#" class="sep-upl"></a>
                  <a href="#" class="sep-rmv">Remove image</a>
                  <input type="hidden" id="homepage_cms_image" name="homepage_cms_image" value="'.$image_id.'"> <br>
                  <img alt="no image" width="300" height="auto" src="' . $image_src[0] . '" />';        
        } else {
            echo '<a href="#" class="sep-upl">Upload image</a>
                  <a href="#" class="sep-rmv" style="display:none">Remove image</a>
                  <input type="hidden" name="homepage_cms_image" id="homepage_cms_image"> <br>
                  <img src="" />';        
        }
    }

    /**
     * the input field for setting title for HTML5 & rich media section
     */
    function sep_display_homepage_cms_title() {
        $html = '<input type="text" id="homepage_cms_title" maxlength="25" style="width:30%" name="homepage_cms_title" required value="'. get_option('homepage_cms_title') . '"/>';      
        $html .= '<span style="color:blue;display:block;"> *Max character length : 25 </span>';
        echo $html;       
    }

    /**
     * the select options for Development and CMS selection
     */
    function sep_add_dcms_category() {
        $html = '<textarea class="text-fields" style="width:40%" id="dcms_category" name="dcms_category" required>'. get_option('dcms_category') . '</textarea>';      
        $html .= '<span style="color:green;display:block;"> *Enter Comma <,> separated values </span>';
        echo $html;       
    }


    /* ------------------------------------------------------------------------ *
     * Video Production content callbacks
     * ------------------------------------------------------------------------ */


    /**
     * the input field for uploading image for HTML5 & rich media section
     */
    function sep_display_homepage_video_image() { 
        
        $image_id  = get_option('homepage_video_image');
        $image_id > 0 ? $image_src = wp_get_attachment_image_src( $image_id , 'full') : $image_src = false;

        if( $image_src ) {
            echo '<a href="#" class="sep-upl"></a>
                  <a href="#" class="sep-rmv">Remove image</a>
                  <input type="hidden" id="homepage_video_image" name="homepage_video_image" value="'.$image_id.'"> <br>
                  <img alt="no image" width="300" src="' . $image_src[0] . '" />';        
        } else {
            echo '<a href="#" class="sep-upl">Upload image</a>
                  <a href="#" class="sep-rmv" style="display:none">Remove image</a>
                  <input type="hidden" name="homepage_video_image" id="homepage_video_image"> <br>
                  <img src="" />';        
        }
    }

    /**
     * the input field for setting title for HTML5 & rich media section
     */
    function sep_display_homepage_video_title() {
        $html = '<input type="text" id="homepage_video_title" maxlength="25" style="width:30%" name="homepage_video_title" required value="'. get_option('homepage_video_title') . '"/>';      
        $html .= '<span style="color:blue;display:block;"> *Max character length : 25 </span>';
        echo $html;       
    }

    /**
     * the select options for Video and Production
     */
    function sep_add_video_category() {
        $html = '<textarea class="text-fields" style="width:40%" id="video_category" name="video_category" required>'. get_option('video_category') . '</textarea>';      
        $html .= '<span style="color:green;display:block;"> *Enter Comma <,> separated values </span>';
        echo $html;       
    }
    

    /**
     * script includsion
     */
    function sep_include_js() {

        // add additional conditions just to not to load the scipts on each page
        
        if ( ! did_action( 'wp_enqueue_media' ) ) {
            wp_enqueue_media();
        }
   
        wp_register_script( 'my_plugin_script', plugin_dir_url(__FILE__) . 'js/image_uploader.js', array('jquery'));
        wp_enqueue_script( 'my_plugin_script' );

        wp_register_script( 'global_options_script', plugin_dir_url(__FILE__) . 'js/global_options.js', array('jquery'));
        wp_enqueue_script( 'global_options_script' );
    }

}


new CustomOptions();


?>