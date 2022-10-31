<?php 

function add_css_js()
{
  global $wp_query;
  
  $ver_param = date('dmYHis');

  // stylesheets
  wp_enqueue_style( 'sep-homepage-style', get_template_directory_uri() . '/assets/css/style.css', false, $ver_param );
  

  if ( is_page_template( 'page-static.php' ) ) {
    
    wp_enqueue_style( 'sep-static-animated-style', get_template_directory_uri() . '/assets/css/static_animated_gif.css', false, $ver_param );
    wp_enqueue_style( 'sep-lightbox-style', get_template_directory_uri() . '/assets/css/lightbox.css', false, $ver_param );
    
    // lightbox
    wp_enqueue_script(
      'sep-portfolio-static-lightbox',
      get_stylesheet_directory_uri() .'/assets/js/lightbox.js',
      array( 'jquery' ),
      $ver_param,
      true
    );      
    wp_enqueue_script(
      'sep-portfolio-static-main',
      get_stylesheet_directory_uri() .'/assets/js/main.js',
      array( 'jquery' ),
      $ver_param,
      true
    );
    $variables = array(
      'ajaxurl' => admin_url( 'admin-ajax.php' ),
      'tmplate_url' => get_template_directory_uri()
    );
    wp_localize_script('sep-portfolio-static-main', "nObject", $variables);
  }
  elseif ( is_page_template( 'page-html5.php' ) ) {      
    wp_enqueue_style( 'sep-html-listing-style', get_template_directory_uri() . '/assets/css/html5_rich_media.css', false, $ver_param );
    wp_enqueue_script('jquery');
  } 
  elseif ( is_page_template( 'page-dcms.php' ) ) {      
    wp_enqueue_style( 'sep-dcms-listing-style', get_template_directory_uri() . '/assets/css/development_cms.css', false, $ver_param );
    wp_enqueue_script('jquery');
  } 
  elseif ( is_page_template( 'page-video.php' ) ) {      
    wp_enqueue_style( 'sep-dcms-listing-style', get_template_directory_uri() . '/assets/css/video_production.css', false, $ver_param );
    wp_enqueue_script('jquery');
  }     
  elseif ( is_page_template( 'page-ad-details.php' ) ) {
    wp_enqueue_style( 'sep-html-ad-details-style', get_template_directory_uri() . '/assets/css/ad_details.css', false, $ver_param );
    wp_enqueue_style( 'sep-html-ad-swiper-style', get_template_directory_uri() . '/assets/css/swiper_bundle.css', false, $ver_param );
    wp_enqueue_script( 'sep-html-swiper-script', get_stylesheet_directory_uri() .'/assets/js/swiper-bundle.js', array( 'jquery' ), $ver_param, true );
    wp_enqueue_script( 'sep-html-details-script', get_stylesheet_directory_uri() .'/assets/js/ad-details.js', array( 'jquery' ), $ver_param, true );
    $variables = array(
      'ajaxurl' => admin_url( 'admin-ajax.php' )
    );
    wp_localize_script('sep-html-details-script', "ajaxObject", $variables);
  }
    
}
add_action('wp_enqueue_scripts', 'add_css_js');



// function portfolio_setup_theme() {
    
    //add_theme_support( 'post-thumbnails' );
    // set_post_thumbnail_size( 160, 600, true, array( 'center', 'center' ) );
    // add_image_size('new-size', 300,250, true);
    // remove_image_size('new-size');

//}
//add_action( 'after_setup_theme', 'portfolio_setup_theme' );



/**
 * HtML5 Ajax initiate
 */
function sep_html5_initial_ajax_handler() { 
	
  $initially_loaded = $_POST['per_page_initial'];
  $media_device = $_POST['device'];
  $initial_load_posts = $_POST['initial'];
  $paged = 0; 
  $next_page = $paged + 1;

  if ( $media_device == 'phone' ) {
    # WP Meta query Sample
    $args = array(
      'post_type' => 'html5',
      'meta_query' => array(
        array(
          'key'     => 'html_post_visibility',
          'value'   => 'Active' ,
          'compare' => '=',
        ),
        'meta_query' => array(      
          'relation' => 'AND',                  
          array(
            'key'     => 'html_ad_dimension',
            'value'   => '728x90',
            'compare' => '!=',
          ),
          array(
            'key'     => 'html_ad_dimension',
            'value'   => '970x250',
            'compare' => '!=',
          ),
        ),
      ),
      'posts_per_page' => $initial_load_posts,
      'paged'       => $paged,
      'post_status' => 'publish',
      'orderby' => array( 'post_date' =>  'DESC' )
    );
    
  } 
  elseif ( $media_device == 'tab' ) {
    
    $args = array(
      'post_type' => 'html5',
      'meta_query' => array(
        array(
          'key'     => 'html_post_visibility',
          'value'   => 'Active' ,
          'compare' => '=',
        ),
        'meta_query' => array(      
          'relation' => 'AND',                  
          array(
            'key'     => 'html_ad_dimension',
            'value'   => '728x90',
            'compare' => '!=',
          ),
          array(
            'key'     => 'html_ad_dimension',
            'value'   => '970x250',
            'compare' => '!=',
          ),
          array(
            'key'     => 'html_ad_dimension',
            'value'   => '320x50',
            'compare' => '!=',
          ),
          array(
            'key'     => 'html_ad_dimension',
            'value'   => '300x50',
            'compare' => '!=',
          ),
        ),
      ),
      'posts_per_page' => $initial_load_posts,
      'paged'       => $paged,
      'post_status' => 'publish',
      'orderby' => array( 'post_date' =>  'DESC' )
    );
  }
  else {
    
    $args = array(
      'post_type' => 'html5',
      'meta_query' => array(
        array(
          'key'     => 'html_post_visibility',
          'value'   => 'Active' ,
          'compare' => '=',
        ),
        'meta_query' => array(      
          'relation' => 'AND',                  
          array(
            'key'     => 'html_ad_dimension',
            'value'   => '320x50',
            'compare' => '!=',
          ),
          array(
            'key'     => 'html_ad_dimension',
            'value'   => '300x50',
            'compare' => '!=',
          ),
        ),
      ),
      'posts_per_page' => $initial_load_posts,
      'paged'       => $paged,
      'post_status' => 'publish',
      'orderby' => array( 'post_date' =>  'DESC' )
    );
    
  } 

  $post_query = new WP_Query($args); 
 
	if( $post_query->have_posts() ) :
    $posts_html = true; 
  else:
    $posts_html = false; 
	endif;

  foreach ($post_query->posts as $post) 
  {
    $arr[] = get_post_meta( $post->ID );
    $postID[] = $post->ID;
  }

  echo json_encode( array(
		'max_page' => $post_query->max_num_pages,
		'post_ids' => $postID,
		'content' =>  $arr,
    'page_update' => $next_page,
	) );

	wp_die(); // here we exit the script and even no wp_reset_query() required!
}
 
add_action('wp_ajax_html5_initial', 'sep_html5_initial_ajax_handler'); // wp_ajax_{action}
add_action('wp_ajax_nopriv_html5_initial', 'sep_html5_initial_ajax_handler'); // wp_ajax_nopriv_{action}



/**
 * HtML5 Ajax Loadmore
 */
function sep_loadmore_ajax_handler() { 
	
  $current_page = $_POST['page'];  
  $media_device = $_POST['device'];
  $initially_loaded = $_POST['initial'];
  $load_number_of_posts = $_POST['load'];

  // prepare our arguments for the query  
  if( ( $load_number_of_posts != $initially_loaded ) && ( $current_page == 1 ) ) {

    // will work if $initially_loaded % $load_number_of_posts == 0
    $paged = round( ($initially_loaded / $load_number_of_posts) + 1 );

  } else {
    $paged = $current_page + 1; 
  }

  if ( $media_device == 'phone' ) {
    # WP Meta query Sample
    $args = array(
      'post_type' => 'html5',
      'meta_query' => array(
        array(
          'key'     => 'html_post_visibility',
          'value'   => 'Active' ,
          'compare' => '=',
        ),
        'meta_query' => array(      
          'relation' => 'AND',                  
          array(
            'key'     => 'html_ad_dimension',
            'value'   => '728x90',
            'compare' => '!=',
          ),
          array(
            'key'     => 'html_ad_dimension',
            'value'   => '970x250',
            'compare' => '!=',
          ),
        ),
      ),
      'posts_per_page' => $load_number_of_posts,
      'paged'       => $paged,
      'post_status' => 'publish',
      'orderby' => array( 'post_date' =>  'DESC' )
    );
    
  } 
  elseif ( $media_device == 'tab' ) {
    
    $args = array(
      'post_type' => 'html5',
      'meta_query' => array(
        array(
          'key'     => 'html_post_visibility',
          'value'   => 'Active' ,
          'compare' => '=',
        ),
        'meta_query' => array(      
          'relation' => 'AND',                  
          array(
            'key'     => 'html_ad_dimension',
            'value'   => '728x90',
            'compare' => '!=',
          ),
          array(
            'key'     => 'html_ad_dimension',
            'value'   => '970x250',
            'compare' => '!=',
          ),
          array(
            'key'     => 'html_ad_dimension',
            'value'   => '320x50',
            'compare' => '!=',
          ),
          array(
            'key'     => 'html_ad_dimension',
            'value'   => '300x50',
            'compare' => '!=',
          ),
        ),
      ),
      'posts_per_page' => $load_number_of_posts,
      'paged'       => $paged,
      'post_status' => 'publish',
      'orderby' => array( 'post_date' =>  'DESC' )
    );
  }
  else {
    
    $args = array(
      'post_type' => 'html5',
      'meta_query' => array(
        array(
          'key'     => 'html_post_visibility',
          'value'   => 'Active' ,
          'compare' => '=',
        ),
        'meta_query' => array(      
          'relation' => 'AND',                  
          array(
            'key'     => 'html_ad_dimension',
            'value'   => '320x50',
            'compare' => '!=',
          ),
          array(
            'key'     => 'html_ad_dimension',
            'value'   => '300x50',
            'compare' => '!=',
          ),
        ),
      ),
      'posts_per_page' => $load_number_of_posts,
      'paged'       => $paged,
      'post_status' => 'publish',
      'orderby' => array( 'post_date' =>  'DESC' )
    );
    
  }

  $post_query = new WP_Query($args); 
 
	if( $post_query->have_posts() ) :
    $posts_html = true; 
  else:
    $posts_html = false; 
	endif;

  foreach ($post_query->posts as $post) 
  {
    $arr[] = get_post_meta( $post->ID );
    $postID[] = $post->ID;
  }

  echo json_encode( array(
		'max_page' => $post_query->max_num_pages,
		'post_ids' => $postID,
		'content' =>  $arr,
    'page_update' => $paged,
	) );

	wp_die();
}
 
add_action('wp_ajax_loadmore', 'sep_loadmore_ajax_handler'); // wp_ajax_{action}
add_action('wp_ajax_nopriv_loadmore', 'sep_loadmore_ajax_handler'); // wp_ajax_nopriv_{action}


/**
 * Development and CMS Ajax
 */
// function sep_dcms_loadmore_ajax_handler() { 
	
//   $current_page = $_POST['page'];
//   $initially_loaded = $_POST['per_page_initial'];
//   $load_number_of_posts = 1; 
  
//   // prepare our arguments for the query  
//   if( ( $load_number_of_posts != $initially_loaded ) && ( $current_page == 1 ) ) {

//     // will work if $initially_loaded % $load_number_of_posts == 0
//     $paged = round( ($initially_loaded / $load_number_of_posts) + 1 );

//   } else {
//     $paged = $current_page + 1; 
//   }

//   $args = array(
//     'post_type' => 'dcms',
//     'meta_query' => array(
//       array(
//         'key'     => 'dcms_post_visibility',
//         'value'   => 'Active' ,
//         'compare' => '=',
//       ),
//     ),
//     'posts_per_page' => $load_number_of_posts,
//     'paged'       => $paged,
//     'post_status' => 'publish',
//     'orderby' => array( 'post_date' =>  'DESC' )
//   );

//   $post_query = new WP_Query($args); 
 
// 	if( $post_query->have_posts() ) :
//     $posts_html = true; 
//   else:
//     $posts_html = false; 
// 	endif;

//   foreach ($post_query->posts as $post) 
//   {
//     $arr[] = get_post_meta( $post->ID );
//     $postID[] = $post->ID;
//   }

//   echo json_encode( array(
// 		'max_page' => $post_query->max_num_pages,
// 		'post_ids' => $postID,
// 		'content' =>  $arr,
//     'page_update' => $paged,
// 	) );

// 	wp_die();
// }
// add_action('wp_ajax_loadmore_dcms', 'sep_dcms_loadmore_ajax_handler'); 
// add_action('wp_ajax_nopriv_loadmore_dcms', 'sep_dcms_loadmore_ajax_handler'); 


/**
 * Video Production Ajax
 */
// function sep_video_loadmore_ajax_handler() { 
	
//   $current_page = $_POST['page'];
//   $initially_loaded = $_POST['per_page_initial'];
//   $load_number_of_posts = 1; 
  
//   // prepare our arguments for the query  
//   if( ( $load_number_of_posts != $initially_loaded ) && ( $current_page == 1 ) ) {

//     // will work if $initially_loaded % $load_number_of_posts == 0
//     $paged = round( ($initially_loaded / $load_number_of_posts) + 1 );

//   } else {
//     $paged = $current_page + 1; 
//   }

//   $args = array(
//     'post_type' => 'video',
//     'meta_query' => array(
//       array(
//         'key'     => 'video_post_visibility',
//         'value'   => 'Active' ,
//         'compare' => '=',
//       ),
//     ),
//     'posts_per_page' => $load_number_of_posts,
//     'paged'       => $paged,
//     'post_status' => 'publish',
//     'orderby' => array( 'post_date' =>  'DESC' )
//   );

//   $post_query = new WP_Query($args); 
 
// 	if( $post_query->have_posts() ) :
//     $posts_html = true; 
//   else:
//     $posts_html = false; 
// 	endif;

//   foreach ($post_query->posts as $post) 
//   {
//     $arr[] = get_post_meta( $post->ID );
//     $postID[] = $post->ID;
//   }

//   echo json_encode( array(
// 		'max_page' => $post_query->max_num_pages,
// 		'post_ids' => $postID,
// 		'content' =>  $arr,
//     'page_update' => $paged,
// 	) );

// 	wp_die();
// }
// add_action('wp_ajax_loadmore_video', 'sep_video_loadmore_ajax_handler'); 
// add_action('wp_ajax_nopriv_loadmore_video', 'sep_video_loadmore_ajax_handler');



