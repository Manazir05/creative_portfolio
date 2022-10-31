<?php
/* Template Name: html5 */

get_header();

$title = get_option('homepage_rich_title');

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

      <!--------Start GIFs Container----------------->
      <div class="gif-ad static-gif-container">
        
        <div class="size-300x250"></div>

        <div id="spin" style="margin: auto;width: 0%;"></div>
        
        <div class="no-banner">
          <h2>Sorry! No banner available...</h2>
        </div>

      </div>
      <!--------------End GIFs Container---------------->

    </div>
    <!--------------End Showcase Ad Container------------>
  </section>


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

    let device = deviceDetect();  
    let per_page, current_page, initialLoad, loadMore; 

    // get wp-home url <get_home_url()> in javascript
    var WpJsonUrl = document.querySelector('link[rel="https://api.w.org/"]').href;
    var homeurl   = WpJsonUrl.replace('/wp-json/', '');
    var spinLogo  = '<?php echo get_template_directory_uri(); ?>/assets/images/loading.gif';
    var ajaxURL   = "<?php echo admin_url('admin-ajax.php'); ?>";

    // device wise number of banners to initially load and load more
    switch ( device ) {
      case 'phone':
        initialLoad = 4;
        loadMore    = 4;
        if ( window.matchMedia('(min-width: 481px)').matches ) {
          initialLoad = 3;
          loadMore    = 3;
        } 
        break;
      case 'tab':
        initialLoad = 9;
        loadMore    = 3;
        if ( window.matchMedia('(min-width: 1025px)').matches ) {
          initialLoad = 6;
          loadMore    = 3;
        } 
        break;
      case 'desktop':
        initialLoad = 16;
        loadMore    = 8;
        break;
    } 

    let initialData = {
      'action': 'html5_initial',
      'device': device,
      'initial' : initialLoad,
    };

    $.ajax({
      url: ajaxURL,
      data: initialData,
      dataType: "json",
      async: false,
      type: 'POST',
      beforeSend: function(xhr) {
          $("#spin").append('<img src=' + spinLogo + ' />');
      },
      success: function(data) {

        if (data) {

          var looper = data.content;
          var posts  = data.post_ids;
          var imgPath, detailPath;

          if ( looper.length > 0 ) {

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

          } else {
              $( ".no-banner" ).show( 'slow' );
          }          

          current_page = data.page_update;

          if ( data.max_page > 1 ) {
            $("#spin").after('<button class="load-more">Load More</button>');
          }

        } else {
          button.remove(); // if no data, remove the button as well
        }

      },
      complete: function() {
        $("#spin").empty();
      },

    });


    /**
     * Fire on load more click
     */
    $('.load-more').click(function() { 

      var button = $(this),
        data = {
          'action': 'loadmore',
          'device': device,          
          'page': current_page,
          'initial': initialLoad,
          'load' : loadMore,
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



    function deviceDetect() {
      
      let device,deviceOrientation;
      let screenWidth  = screen.width;
      let screenHeight = screen.height;

      // device detection
      if ( screen.orientation === undefined ) { // detect iOS/ipad/iphone/safari
          
        if (  screenWidth <= 480 ) { // phone
          device = "phone";
        }
        else if ( screenWidth > 480 && screenWidth <= 1024 ) { // tab device
          device = "tab";
        } 
        else {
          device = "desktop";
        }

      } 
      else {

        ( screenWidth > screenHeight ) ? deviceOrientation = 'landscape' : deviceOrientation = 'portrait';

        if ( ( deviceOrientation == 'portrait' && screenWidth <= 480 )
          || ( deviceOrientation == 'landscape' && screenHeight <= 480 ) ) { // phone

          device = "phone";
        }
        else if ( ( deviceOrientation == 'portrait' && (screenWidth > 480 && screenWidth <= 1024) )
        || ( deviceOrientation == 'landscape' && (screenHeight > 480 && screenHeight <= 1024) ) ) { // tab device

          device = "tab";
        } 
        else {

          device = "desktop";
        }

      }

      return device;
    }


  });

  /**
   * Re-load banners on device orientation
   */
  jQuery(window).on("orientationchange", function( e ) {
    location.reload();
  });

</script>