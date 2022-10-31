<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name = "keywords" content = "HTML5, Rich Media, Banner, Html5 banner sample, Rich media sample" />
    <title><?php echo get_bloginfo( 'name' ); ?><?php wp_title('-'); ?></title>
    <link rel="icon" type="image/x-icon" href="<?php echo get_template_directory_uri(); ?>/assets/images/creative_fev.svg">
    
    <?php wp_head(); ?>
    
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css?v=20220826930"
    />
  </head>

  <?php
    $image_id  = get_option( 'uploaded_logo' );
    $image_id  ? $image_src = wp_get_attachment_image_src( $image_id, 'full' ) : $image_src = false;
    $image_src ? $render = $image_src[0] : $render = ''; 
  ?>

  <body>
    <!------Start Header Section------>
    <header class="header">
      <div class="header-container">
        
        <!--Start Logo Here-->
        <div class="logo">
          <a href="<?php echo get_home_url(); ?>"><img src="<?php echo $render; ?>" alt="Creative" /></a>
        </div>
        <!---End Logo Here---->

        <nav class="nav container">
          <div class="nav__menu" id="nav-menu">
            <ul class="nav__list">
              <li class="nav__item">
                <a href="#" class="nav__link active-link">VISIT Portfolio</a>
              </li>
              <li class="nav__item">
                <a href="#" class="nav__link">Static and Animated GIFs</a>
              </li>
              <li class="nav__item">
                <a href="#" class="nav__link">HTML5 & Rich Media</a>
              </li>
              <li class="nav__item">
                <a href="#" class="nav__link">Development & CMS</a>
              </li>
              <li class="nav__item">
                <a href="#" class="nav__link">Video Production</a>
              </li>
            </ul>
            <i class="fa-solid fa-xmark-large nav__close" id="nav-close">X</i>
          </div>

          <div class="nav__toggle" id="nav-toggle">
            <i class="fa-solid fa-bars"></i>
          </div>
        </nav>
        
        <!--Start Visit Button-->
        <div class="visit-button">
          <a
            href="<?php echo get_home_url(); ?>/html5"
            class="visit-back"
          >
            BACK
            <i aria-hidden="true" class="fas fa-caret-right arrow-right"></i>
          </a>
        </div>
        <!--End Visit Button---->
        
      </div>
    </header>
    <!-------End Header Section-->