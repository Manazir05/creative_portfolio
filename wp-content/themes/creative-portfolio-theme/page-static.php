<?php
/* Template Name: static */
get_header();

$nonce = wp_create_nonce("sep_ajax_render_nonce");
$title = get_option('homepage_static_title');

?>

<main>
  <!--------Start Static Animated & Gif Title------->
  <section class="static-animated-gif-title">
    <div class="title-text">
      <h1><?php echo strtoupper($title); ?></h1>
    </div>
  </section>
  <!--------Start Static Animated & Gif Title------->

  <!---------Start Ad Gallery Section----------->
  <section class="ad-gallery">
    <!------------Start Static & GIF and Ad Size Navbar----------->
    <div class="adsize-navbar" id="staticAnimatedGifContainerBtn">
      <nav class="adsize-navbar">
        <ul class="adsize-nav" data-nonce="<?php echo $nonce; ?>">
          <li id="staticBtn" class="activeCon containerButton" onclick="showStaticAndGifContainer('static-ad')">
            STATIC
          </li>
          <li id="gif-btn" class="containerButton" onclick="showStaticAndGifContainer('gif-ad')">
            GIF
          </li>
          <li class="active other-size-btn" onclick="showAdsBySize('list-300x250')" id="sizeBtn">
            <p>300x250</p>
          </li>
          <li class="other-size-btn" onclick="showAdsBySize('list-160x600')" id="160x600">
            <p>160x600</p>
          </li>
          <li class="other-size-btn" onclick="showAdsBySize('list-300x600')" id="300x600">
            <p>300x600</p>
          </li>
          <li class="other-size-btn" onclick="showAdsBySize('list-728x90')" id="728x90">
            <p>728x90</p>
          </li>
          <li class="other-size-btn" onclick="showAdsBySize('list-970x250')" id="970x250">
            <p>970x250</p>
          </li>
          <li class="other-size-btn" onclick="showAdsBySize('list-320x50')" id="320x50">
            <p>320x50</p>
          </li>
          <li class="other-size-btn" onclick="showAdsBySize('list-300x50')" id="300x50">
            <p>300x50</p>
          </li>
        </ul>
      </nav>
    </div>
    <!------------End Static & GIF and Ad Size Navbar----------->

    <!------------Start Display Static & GIF Ad Container----------->
    <div class="ad-showcase">

      <div id="spin" style="margin: auto;width: 0%;"></div>
      <div class="no-banner">
        <h2>Sorry! No banner available...</h2>
      </div>

      <!------------Start Static Ad Container ---------->
      <div class="static-ad static-gif-container">

        <!-----------Start 300x250 Static Ad---------->
        <div class="size-300x250"></div>
        <!-----------End 300x250 Static Ad------------>

        <!-----------Start 160x600 Static Ad---------->
        <div class="size-160x600"></div>
        <!-----------End 160x600 Static Ad------------>

        <!-----------Start 300x600 Static Ad---------->
        <div class="size-300x600"></div>
        <!-----------End 300x600 Static Ad------------>

        <!-----------Start 728x90 Static Ad----------->
        <div class="size-728x90"></div>
        <!-----------End 728x90 Static Ad------------->

        <!-----------Start 970x250 Static Ad---------->
        <div class="size-970x250"></div>
        <!-----------End 970x250 Static Ad------------>

        <!-----------Start 320x50 Static Ad----------->
        <div class="size-320x50"></div>
        <!-----------End 320x50 Static Ad------------->

        <!-----------Start 300x50 Static Ad---------->
        <div class="size-300x50"></div>
        <!-----------End 300x50 Static Ad------------>
      </div>
      <!------------ End Static Ad Container ------------>

      <!--------Start GIFs Ad Container---------->
      <div class="gif-ad static-gif-container">

        <!---------------Start 300x250 GIF Ad ------------>
        <div class="size-300x250"></div>
        <!--------------End 300x250 GIF Ad --------------->

        <!---------------Start 160x600 GIF Ad ------------>
        <div class="size-160x600"></div>
        <!--------------End 160x600 GIF Ad --------------->

        <!---------------Start 300x600 GIF Ad ------------>
        <div class="size-300x600"></div>
        <!--------------End 300x600 GIF Ad --------------->

        <!---------------Start 728x90 GIF Ad ------------->
        <div class="size-728x90"></div>
        <!--------------End 728x90 GIF Ad ---------------->

        <!---------------Start 970x250 GIF Ad ------------>
        <div class="size-970x250"></div>
        <!--------------End 970x250 GIF Ad --------------->

        <!---------------Start 320x50 GIF Ad ------------>
        <div class="size-320x50"></div>
        <!--------------End 320x50 GIF Ad --------------->

        <!---------------Start 300x50 GIF Ad ------------>
        <div class="size-300x50"></div>
        <!--------------End 300x50 GIF Ad --------------->
      </div>
      <!--------End GIFs Ad Container----------->

    </div>
    <!------------End Display Static & GIF Ad Container---------->
  </section>

  <!---------End Ad Gallery Section----------->

  <!-- <section class="static-page-spacer"></section> -->

  <div id="load"></div>

  <section class="static-page-spacer"></section>

  <!-----------Start Others Service Page Link---------->
  <section class="other-service-page">

    <div class="other-service-link">
      <a href="<?php echo get_home_url(); ?>/html5" class="block">
        <div class="service-link__title_view">
          <h2 class="page-title">HTML5 & Rich Media</h2>
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
  <!-----------End Others Service Page Link---------->
  <section class="static-page-spacer"></section>
</main>


<?php
  get_footer();
?>