<?php 

class InitializeDatabase {
    
    /**
     * custom global option initial values on plugin activation 
     */
    private $option_args = array(
        'ad_unit_types'    => 'Static,GIF',
        'image_dimensions' => '300x250,300x600,160x600,728x90,970x250,320x50,300x50',
        'html_dimensions'  => '300x250,300x600,160x600,728x90,970x250,320x50,300x50',
        'copyright_text'   => '© 2022',
        'homepage_static_title' => 'Static & Animated GIFs',
        'homepage_rich_title'   => 'HTML5 & Rich Media',
        'html_industry_type'    => 'Health IT solutions, IT company, Construction, Software',
        'html_ad_format'        => 'HTML5',
        'homepage_cms_title'    => 'Development & CMS',
        'homepage_video_title'  => 'Video Production',
        'dcms_category'   => 'Web Development, CMS',
        'video_category'  => 'Video Editing'
    );
    
    // //callback function to initialize data on plugin activation
    function portfolio_settings_data_initialization() {        

        foreach( $this->option_args as $option_name => $option_value ) {
            add_option( $option_name, $option_value );
        }
    }

    // //callback function to remove data on plugin de-activation
    function portfolio_settings_data_rollback() {        

        foreach( $this->option_args as $option_name => $option_value ) {
            delete_option( $option_name );
        }
    }

}

?>