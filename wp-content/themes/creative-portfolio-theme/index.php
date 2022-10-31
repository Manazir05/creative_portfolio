<?php 
get_header();
?>

<?php 

  $render = array();
  $categories = array(
      'homepage_static_image',
      'homepage_rich_image',
      'homepage_cms_image',
      'homepage_video_image'
  );

  // retrieve image from options
  foreach( $categories as $category ) {
      $image_id  = get_option( $category );
      $image_id ? $image_src = wp_get_attachment_image_src( $image_id , 'full') : $image_src = false;
      $image_src ? $render[$category] = $image_src[0] : $render[$category] = get_template_directory_uri() . '/assets/homepage_placeholder.jpg';    
  }

?>

    <!------- Start Main Section ------>
    <main>
      <!--------Start Portfolio Category Section-------->
      <section class="services">
        <!------Start Static & Animated GIFs Service-------->
        <div class="service-list static-service">
          <div class="featured-img">
            <img src="<?php echo $render['homepage_static_image']; ?>" alt="" />
          </div>
          <div class="content">
            <div class="content-text">
              <h2><?php echo get_option( 'homepage_static_title' ); ?></h2>
              <a href="<?php echo get_home_url(); ?>/static" class="service-link"
                >VIEW SAMPLES
                <i aria-hidden="true" class="fas fa-caret-right"></i
              ></a>
            </div>
          </div>
        </div>
        <!-------End Static & Animated GIFs Service--------->

        <!----------Start HTML5 & Rich Media Service---------->
        <div class="service-list html5-service">
          <div class="featured-img">
            <img src="<?php echo $render['homepage_rich_image']; ?>" alt="" />
          </div>
          <div class="content">
            <div class="content-text">
              <h2><?php echo get_option( 'homepage_rich_title' ); ?></h2>
              <a href="<?php echo get_home_url(); ?>/html5" class="service-link"
                >VIEW SAMPLES
                <i aria-hidden="true" class="fas fa-caret-right"></i
              ></a>
            </div>
          </div>
        </div>
        <!----------End HTML5 & Rich Media Service---------->

        <!----------Start Development & CMS Service---------->
        <div class="service-list development-cms-service">
          <div class="featured-img">
            <img src="<?php echo $render['homepage_cms_image']; ?>" alt="" />
          </div>
          <div class="content">
            <div class="content-text">
              <h2><?php echo get_option( 'homepage_cms_title' ); ?>​</h2>
              <a href="<?php echo get_home_url(); ?>/dcms" class="service-link"
                >VIEW SAMPLES
                <i aria-hidden="true" class="fas fa-caret-right"></i
              ></a>
            </div>
          </div>
        </div>
        <!---------End Development & CMS Service---------->

        <!---------Start Video Production Service----------->
        <div class="service-list video-service">
          <div class="featured-img">
            <img src="<?php echo $render['homepage_video_image']; ?>" alt="" />
          </div>
          <div class="content">
            <div class="content-text">
              <h2><?php echo get_option( 'homepage_video_title' ); ?></h2>
              <a href="<?php echo get_home_url(); ?>/video" class="service-link"
                >VIEW SAMPLES
                <i aria-hidden="true" class="fas fa-caret-right"></i
              ></a>
            </div>
          </div>
        </div>
        <!----------End Video Production Service------------>
      </section>
      <!--------End Portfolio Category Section-------->
    </main>
    <!---------End Main Section-------->


<?php get_footer(); ?>