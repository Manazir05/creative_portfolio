<?php
/* Template Name: ad-details */
get_header('ad-details');

if (!function_exists('WP_Filesystem')) {
  include_once 'wp-admin/includes/file.php';
}

WP_Filesystem();

$post_id = $_GET['html5'];
$data    = get_post_meta($post_id);
$data    = maybe_unserialize($data);

$category_title = get_option('homepage_rich_title');
$banner_dimension = $data['html_ad_dimension'][0];
$banner_image = $data['html_banner_image'][0];

$target_file = wp_basename($banner_image, '.zip');
$folder      = "Rich_Media_Archive";
$destination = wp_upload_dir();
$upload_dir  = $destination['baseurl'];
$target_path = $upload_dir . '/' . $folder . '/' . $target_file . '/index.html';

?>



<!-- Start: Mobile Device Ad details rendering -->
<main id='mobile-container' style="display:none">

  <!--------------Start Takeover Section------------->
  <section class="takeover-not-desktop">

    <!------------Start Takeover Slider Container------------>
    <div class="takeover-slider">

      <div class="ad-box-slider">
        <!------------Start Ad Image Slider Container---------->
        <div class="ad-image">
          <div class="swiperMobile">
            
            <div class="swiper-wrapper">
              <!-- slider images here -->              
            </div>

          </div>
        </div>
        <!------------End Ad Image Slider Container------------>

        <!----------Start Next & Prev Button----------->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        <!----------End Next & Prev Button----------->
      </div>

    </div>
    <!-----------End Takeover Slider Container---------------->
  </section>
  <!----------End Takeover Section------------------->


  <!-------------Start Ad Details Section------------->
  <section class="ad-details">
    <!-----------Start Ad Details Button---------->
    <div class="ad-details-btn">
      <button id="AdDetailsMobileBtn" class="ad-details__btn">
        Ad Details
        <i aria-hidden="true" class="fas fa-caret-right ad-details-arrow"></i>
      </button>
    </div>
    <!-----------End Ad Details Button---------->

    <!-----------Start Ad Information Container----------->

    <div class="ad-information" id="AdDetailsMobileBtnInfo">
      <div class="swiperDetails">
        <div class="swiper-wrapper">
          <!-- Ad details information here -->     
        </div>
      </div>
    </div>
    
    <!-----------End Ad Information Container----------->
  </section>
  <!-------------End Ad Details Section-------------->

</main>
<!-- End: Mobile Device Ad details rendering -->


<!-- Start: Desktop/ Large screened Device Ad details rendering -->
<main id='desktop-container' style="display:none">
  
  <!-------------Start Ad Details Section------------->
  <section class="ad-details">
    <!-----------Start Ad Details Button---------->
    <div class="ad-details-btn">
      <button id="AdDetailsBtn" class="ad-details__btn">
        Ad Details
        <i aria-hidden="true" class="fas fa-caret-right ad-details-arrow"></i>
      </button>
    </div>
    <!-----------End Ad Details Button---------->

    <!-----------Start Ad Information Container----------->
    <div class="ad-information" id="AdDetailsBtnInfo">
      <div class="left-info">
        <p>
          <span class="bold">Creative Name: </span><span id="detailTitle"></span>
        </p>
        <p>
          <span class="bold">Industry:</span><span id="detailIndustry"></span>
        </p>
        <p>
          <span class="bold">Ad Dimension: </span><span id="detailSize"></span>
        </p>
      </div>
      <div class="right-info">
        <p>
          <span class="bold">Ad Format:</span><span id="detailFormat"></span>
        </p>
        <p>
          <span class="bold">Creative Production Tools:</span><span id="detailTools"></span>
        </p>
        <p>
          <span class="bold">Features:</span><span id="detailFeatures"></span>
        </p>
      </div>
    </div>
    <!-----------End Ad Information Container----------->
  </section>
  <!-------------End Ad Details Section------------->

  <!----------Start 728x90 Banner Section--------->
  <section class="size-728x90__banner">
    <div class="size-728x90 ad"></div>
  </section>
  <!----------End 728x90 Banner Section--------->

  <!----------Start 970x250 Banner Section--------->
  <section class="size-970x250__banner">
    <div class="size-970x250"></div>
  </section>
  <!----------End 970x250 Banner Section--------->

  <section class="size-300x250__banner-section">

    <div class="size-300x250"></div>

    <div class="size-508x250__banner"></div>
    <div class="size-388x250__banner">
      <div class="size-170x20"></div>
      <div class="size-376x50"></div>
      <div class="size-329x50"></div>
      <div class="size-356x20"></div>
      <div class="size-188x20"></div>
      <div class="size-388x20"></div>
    </div>
  </section>

  <!-------------Start Banner Layout Section--------------->
  <section class="show-banner">

    <div class="size-300x250__banner-box">

      <div class="size-300x600"></div>
      <div class="size-735x600">
        <div class="size-735x600__inside">
          <div class="size-259x176"></div>
          <div class="size-259x176"></div>
          <div class="size-177x176">
            <div class="size-177x40"></div>
            <div class="size-110x20"></div>
            <div class="size-177x20"></div>
            <div class="size-177x20"></div>
            <div class="size-80x20"></div>
          </div>
        </div>
        <div class="size-735x600__inside">
          <div class="size-259x120">
            <div class="size-176x20"></div>
            <div class="size-259x20"></div>
            <div class="size-249x20"></div>
            <div class="size-228x20"></div>
          </div>
          <div class="size-259x120">
            <div class="size-176x20"></div>
            <div class="size-249x20"></div>
            <div class="size-249x20"></div>
            <div class="size-228x20"></div>
          </div>
          <div class="size-177x120">
            <div class="size-110x20"></div>
            <div class="size-177x20"></div>
            <div class="size-177x20"></div>
            <div class="size-80x20"></div>
          </div>
        </div>
        <div class="size-735x600__inside">
          <div class="size-735x20"></div>
          <div class="size-735x20"></div>
          <div class="size-615x20"></div>
          <div class="size-615x20"></div>
          <div class="size-354x20"></div>
        </div>
        <div class="size-735x600__inside">
          <div class="size-728x90"></div>
        </div>
      </div>
      <div class="size-160x600"></div>
      <div class="size-728x90__banner-bottom-box">
        <div class="left-layout">
          <div class="size-612x17"></div>
          <div class="size-612x17"></div>
          <div class="size-508x17"></div>
          <div class="size-612x17"></div>
          <div class="size-508x17"></div>
        </div>
        <div class="right-layout">
          <div class="size-612x17"></div>
          <div class="size-612x17"></div>
          <div class="size-508x17"></div>
          <div class="size-612x17"></div>
          <div class="size-508x17"></div>
        </div>
      </div>
    </div>
  </section>
  <!-------------End Banner Layout Section--------------->

  <!--------------Start Takeover Section------------->
  <section class="takeover">
    <!--------Start Takeover Title---------->
    <div class="takeover-title">
      <h3><?php echo $category_title; ?></h3>
    </div>
    <!--------End Takeover Title---------->
    <!---------Start Large Laptop Mockup Box--------->
    <div class="mock"></div>
    <!---------End  Large Laptop Mockup Box--------->
    <!---------Start Small Laptop Mockup Box--------->
    <div class="small-desktop"></div>
    <!---------End Small Laptop Mockup Box--------->

    <!------------Start Takeover Slider Container------------>
    <div class="takeover-slider">

      <!---------Start Campaign Title Slider------>
      <div class="swiper">
        <!-- Additional required wrapper -->
        <div class="swiper-wrapper">
          <!-- Slides -->
        </div>
      </div>
      <!---------End Campaign Title Slider------>

      <div class="ad-box-slider">
        <!------------Start Ad Image Slider Container---------->
        <div class="ad-image">
          <div class="swiper2">
            <div class="swiper-wrapper">
              <!-- slider images here -->
            </div>
          </div>
        </div>
        <!------------End Ad Image Slider Container------------>

        <div class="description_live">
          <!------------Start Ad Desciption Slider Container---------->
          <div class="swiper3">
            <div class="swiper-wrapper">
              <!-- slides desc and preview-links -->
            </div>
          </div>
          <!------------End Ad Desciption Slider Container------------>
        </div>

        <!----------Start Next & Prev Button----------->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        <!----------End Next & Prev Button----------->
      </div>

    </div>
    <!-----------End Takeover Slider Container---------------->
  </section>
  <!----------End Takeover Section---------------------->
</main>
<!-- End: Desktop/ Large screened Device Ad details rendering -->




<script>
  var bannerDimension = "<?php echo $banner_dimension; ?>";
  var postID = "<?php echo $post_id; ?>";
  var contentPath = "<?php echo $target_path; ?>";
</script>

<?php
get_footer();
?>