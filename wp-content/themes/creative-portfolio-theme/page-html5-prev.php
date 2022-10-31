<?php
/* Template Name: html5 */

get_header();

$title = get_option('homepage_rich_title');

if( wp_is_mobile() ) {

  $media_device  = "phone";
  $post_per_page = 4;

  $stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA'])?$_SERVER['HTTP_X_OPERAMINI_PHONE_UA']:(isset($_SERVER['HTTP_DEVICE_STOCK_UA'])?$_SERVER['HTTP_DEVICE_STOCK_UA']:''));
  if ( preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower( $_SERVER['HTTP_USER_AGENT'] ))
       || preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua) ) {
        
      $media_device  = "tablet";
      $post_per_page = 9;
  }

} else {
  $media_device  = "desktop";
  $post_per_page = 16;
}


?>

<main>
  <!-------Start HTML5 & Rich Media Title Container------->
  <section class="html5-rich-media-title">
    <div class="title-text">
      <h1><?php echo strtoupper($title); ?></h1>
    </div>
  </section>
  <!-------End HTML5 & Rich Media Title Container------->
  
  <!---------Start GIf Ad Gallery Section-------->
  <section class="ad-gallery">
    <!--------------Start Showcase Ad Container------------>
    <div class="ad-showcase">

      <!--------Start GIFs Container------->
      <div class="gif-ad static-gif-container">
        <div class="size-300x250">

          <?php

          global $wp_query;

          // open when mobile ready
          if ( $media_device == 'desktop' ) {
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
              'posts_per_page' => $post_per_page,
              'post_status' => 'publish',
              'orderby' => array( 'post_date' => 'DESC' )
            );
          } 
          elseif ( $media_device == 'phone' ) {

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
              'posts_per_page' => $post_per_page,
              'post_status' => 'publish',
              'orderby' => array( 'post_date' => 'DESC' )
            );
          } else {
            
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
              'posts_per_page' => $post_per_page,
              'post_status' => 'publish',
              'orderby' => array( 'post_date' => 'DESC' )
            );
          }

          $post_query = new WP_Query($args);

          if ($post_query->have_posts()) {

            while ($post_query->have_posts()) {

              $post_query->the_post();
              $post_id   = get_the_ID();
              $post_type = get_post_type();
              $post_thumbnail = get_post_meta(get_the_ID(), 'upload_html_thumbnail', true);
              $post_dimension = get_post_meta(get_the_ID(), 'html_ad_dimension', true);
              $template_url   = get_home_url() . '/ad-details?html5=' . $post_id;

          ?>
              <div class="allads list-300x250 <?php echo $screen_banner; ?>">
                <a href="<?php echo $template_url; ?>">
                  <img src="<?php echo $post_thumbnail; ?>" alt="<?php the_title(); ?>" />
                </a>
              </div>
          <?php
            }
          }
          ?>
          <!--------Image Boxes----------->

        </div>

        <div id="spin" style="margin: auto;width: 0%;"></div>

      </div>
      <!--------------End GIFs Container---------------->
    </div>
    <!--------------End Showcase Ad Container------------>
  </section>

  <?php

  if ($post_query->max_num_pages > 1) {
    echo '<button class="load-more">Load More</button>';
  }

  $ajaxArray = array(
    'ajaxURL' => admin_url('admin-ajax.php'), // WordPress AJAX
    //'posts' => json_encode( $post_query->query_vars ), // everything about your loop is here
    'current_page' => $post_query->query_vars['paged'] ? $post_query->query_vars['paged'] : 1,
    'max_page' => $post_query->max_num_pages,
    'posts_per_page' => $post_query->query_vars['posts_per_page'],
  );



  ?>

  <section class="static-page-spacer"></section>
  <!--------------Start Other Page Link Section-------------->
  <section class="other-service-page">

    <div class="other-service-link">
      <a href="<?php echo get_home_url(); ?>/static" class="block">
        <div class="service-link__title_view">
          <h2 class="page-title">Static & Animated GIFs</h2>
          <span>View Samples
            <i aria-hidden="true" class="fas fa-caret-right"></i></span>
        </div>
      </a>
    </div>

    <div class="other-service-link">
      <a href="<?php echo get_home_url(); ?>/dcms" class="block">
        <div class="service-link__title_view">
          <h2 class="page-title">Development & CMS</h2>
          <span>View Samples
            <i aria-hidden="true" class="fas fa-caret-right"></i></span>
        </div>
      </a>
    </div>

    <div class="other-service-link">
      <a href="<?php echo get_home_url(); ?>/video" class="block">
        <div class="service-link__title_view">
          <h2 class="page-title">Video Production</h2>
          <span>View Samples
            <i aria-hidden="true" class="fas fa-caret-right"></i></span>
        </div>
      </a>
    </div>

  </section>
  <!--------------End Other Page Link Section-------------->

  <section class="static-page-spacer"></section>
</main>


<?php
 get_footer();
?>

<script>
  jQuery(document).ready(function($) {

    let device;

    // device detection
    if ( window.matchMedia("(min-width: 1200px)").matches ) { // desktop

      device = "desktop";
    }
    else { // mobile device

      device = "phone";

      const userAgent = navigator.userAgent.toLowerCase();
      const isTablet = /(ipad|tablet|(android(?!.*mobile))|(windows(?!.*phone)(.*touch))|kindle|playbook|silk|(puffin(?!.*(IP|AP|WP))))/.test(userAgent);

      if ( isTablet ) { // tablet device
        device = "tab";
      }
    }

    var current_page = "<?php echo $ajaxArray['current_page']; ?>";
    var max_page = "<?php echo $ajaxArray['max_page']; ?>";

    $('.load-more').click(function() {

      var per_page = "<?php echo $ajaxArray['posts_per_page']; ?>";
      var ajaxURL = "<?php echo $ajaxArray['ajaxURL']; ?>";
      var isMobile = "<?php echo $media_device; ?>";

      // get wp-home url <get_home_url()> in javascript
      var WpJsonUrl = document.querySelector('link[rel="https://api.w.org/"]').href;
      var homeurl = WpJsonUrl.replace('/wp-json/', '');
      var spinLogo = '<?php echo get_template_directory_uri(); ?>/assets/images/loading.gif';

      var button = $(this),
        data = {
          'action': 'loadmore',
          'page': current_page,
          'per_page_initial': per_page,
          'device': isMobile,
        };

      $.ajax({
        url: ajaxURL,
        data: data,
        dataType: "json",
        type: 'POST',
        beforeSend: function(xhr) {
          $("#spin").append('<img src=' + spinLogo + ' />');
        },
        success: function(data) {

          if (data) {

            var looper = data.content;
            var posts = data.post_ids;
            var imgPath, detailPath;

            $.each(looper, function(index, element) {

              var imgPath = element.upload_html_thumbnail[0];
              var bannerDimension = element.html_ad_dimension[0];
              var detailPath = homeurl + '/ad-details/?html5=' + posts[index];
              var screenBanner;

              $('.size-300x250').append('<div class="allads list-300x250 ' + screenBanner + ' ">' +
                '<a href="' + detailPath + '">' +
                '<img src="' + imgPath + '" alt="' + element.html_creative_name[0] + '" />' +
                '</a>' +
                '</div>');

            });

            current_page = data.page_update;

            if (current_page == data.max_page) {

              button.remove(); // if last page, remove the button
            }

            // we can also fire the "post-load" event here if we use a plugin that requires it
            // $( document.body ).trigger( 'post-load' );

          } else {
            button.remove(); // if no data, remove the button as well
          }

        },
        complete: function() {
          $("#spin").empty();
        },
      });
    });

  });
</script>