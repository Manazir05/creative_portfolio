/**
 *
 * Ad Details Page
 *
 */
const adDetailsBtn = document.getElementsByClassName("ad-details__btn");
const rightArrow = document.getElementsByClassName("ad-details-arrow");
let currentScrollPosition = 1,
showDetails = true;

/**
 * Change Ad Details Button Color & Arrow Rotation
 * @function
 */
const changeButtonColorAndArrowRotation = () => {
  for (let i = 0; i < adDetailsBtn.length; i++) {
    adDetailsBtn[i].addEventListener("click", function () {

      changeDetailAppearence();

      const adInformation = document.querySelector("#"+this.id+"Info");
      currentScrollPosition = window.scrollY;
      if (adInformation.style.display === "none") {
        adInformation.style.display = "flex";
      } else {
        adInformation.style.display = "none";
      }
    });
  }
};
changeButtonColorAndArrowRotation();

/**
 * Change Ad Details Appearence
 * @function
 */
const changeDetailAppearence = () => {
  adDetailsBtn[0].classList.toggle("active");
  rightArrow[0].classList.toggle("down");
  showDetails = !showDetails;
};

/**
 *
 * Change Ad Information On Scroll
 * @function
 */
const hideAdInfoOnScroll = () => {
  const adInformation = document.querySelector("#AdDetailsBtnInfo");
  // When the scroll is greater than 350 viewport height, Display: none; Ad Information
  if (Math.abs(this.scrollY - currentScrollPosition) >= 300 && showDetails) {
    adInformation.style.display = "none";
    changeDetailAppearence();
  }
};
window.addEventListener("scroll", hideAdInfoOnScroll);

let device,deviceOrientation;
let screenWidth  = screen.width;
let screenHeight = screen.height;

// device detection
if ( screen.orientation === undefined ) { // detect safari in iOS/ipad/iphone
    
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

  if ( deviceOrientation == 'portrait' && screenWidth <= 480 
    || deviceOrientation == 'landscape' && screenHeight <= 480 ) { // phone

    device = "phone";
  }
  else if ( ( deviceOrientation == 'portrait' && (screenWidth > 480 && screenWidth <= 1024) )
         || ( deviceOrientation == 'landscape' && (screenHeight > 480 && screenHeight <= 1024) ) ) { // tab device

    device = "tab";
  } 
  else { // desktop

    device = "desktop";
  }

}


/* ------------------------------------------------------------------------ *
 * Dynamic rendering : Desktop
 * ------------------------------------------------------------------------ */

if ( device == "desktop" ) { 

  jQuery(document).ready(function ($) {

    $("#desktop-container").show();

    var size, urlPath, richmediaFolder, filePath, fileName, extension, fileFolder;
  
    /**
     *
     * @name adDetails
     * @type {object}
     * @description - Ad Details of Different Ad Sizes 300x250, 160x600, 300x600, 728x90, 970x250
     *
     */  
      const adDetails = {
        "300x250": [],
        "160x600": [],
        "300x600": [],
        "728x90" : [],
        "970x250": [],
      };
  
      // empty appending blocks
      $(".swiper  > .swiper-wrapper").empty();
      $(".swiper2 > .swiper-wrapper").empty();
      $(".swiper3 > .swiper-wrapper").empty();
  
      var setAjaxObject = {
        init: function () {
          setAjaxObject.callAjaxMethod();
        },
        callAjaxMethod: function () {
          var data = {
            action: "sep_get_dimension_posts",
            postID: postID,
            device: device,
          };
  
          jQuery.ajax({
            url: ajaxObject.ajaxurl,
            type: "POST",
            dataType: "json",
            data: data,
            async: false,
            success: function (response) {
              jQuery.each(response, function (index, element) {
                
                /**
                 * Ad-details section on page load
                 *
                 */
                if (element.post_id.includes(postID)) {
                  
                  // Data : Ad-details section
                  detailTitle.innerText    = element.html_creative_name;
                  detailIndustry.innerText = element.html_industry;
                  detailFormat.innerText   = element.html_ad_format;
                  detailTools.innerText    = element.html_production_tools;
                  detailFeatures.innerText = element.html_features;
                  detailSize.innerText     = element.html_ad_dimension;
  
                  urlPath = contentPath.split("/");
                  richmediaFolder = urlPath[urlPath.length - 2];
                  filePath   = element.html_banner_image[0].split("/");
                  fileName   = filePath[filePath.length - 1];
                  extension  = fileName.split(".").pop();
                  fileFolder = fileName.split(".").shift();
  
                  if (richmediaFolder == fileFolder && extension == "zip") {
                    
                    if ( element.html_ad_dimension == "300x250" ||
                         element.html_ad_dimension == "160x600" ||
                         element.html_ad_dimension == "300x600" ) {

                      $(".size-300x250__banner-section").css("display", "flex");
                      $(".size-" + element.html_ad_dimension).append(
                        '<iframe src="' +
                          contentPath +
                          '" scrolling="auto" ></iframe>'
                      );

                    } else if ( element.html_ad_dimension == "728x90" ) {

                      $(".size-728x90__banner").css("display", "flex");
                      $(".size-728x90__banner")
                        .find(".size-" + element.html_ad_dimension)
                        .append(
                          '<iframe src="' +
                            contentPath +
                            '" scrolling="auto" ></iframe>'
                        );

                    } else if (element.html_ad_dimension == "970x250") {
                      
                      $(".size-970x250__banner").css("display", "flex");
                      $(".size-970x250__banner")
                        .find(".size-" + element.html_ad_dimension)
                        .append(
                          '<iframe src="' +
                            contentPath +
                            '" scrolling="auto" ></iframe>'
                        );
                    }
                  }
                } 
                else {

                  /**
                   * Takeover section on page load
                   */ 
                  $(".swiper > .swiper-wrapper").append(
                    '<div class="campaign-title swiper-slide"><h1 class="campaign-title__heading">' +
                      element.html_creative_name +
                      "</h1></div>"
                  );
                  $(".swiper2 > .swiper-wrapper").append(
                    '<div class="swiper-slide"><img src=' +
                      element.html_slider_image +
                      ' alt="" /></div>'
                  );
                  $(".swiper3 > .swiper-wrapper").append(
                    '<div class="swiper-slide ad-details-content">' +
                      '<div class="ad-text">' +
                      '<p class="ad-description">' +
                      element.html_slider_description +
                      "</p>" +
                      "</div>" +
                      '<div class="live-preview">' +
                      '<a href="' +
                      size +
                      "-" +
                      element.post_id +
                      '">VIEW SAMPLE <i aria-hidden="true" class="fas fa-caret-right"></i></a>' +
                      "</div>" +
                      "</div>"
                  );
                }
  
                size = element.html_ad_dimension[0];                               
                // populate array [size-wise]
                adDetails[size].push(element);
  
              });
            },
          });
        },
      };
      setAjaxObject.init();
  
    /* ------------------------------------------------------------------------ *
     * View Sample : Dynamic rendering
     * ------------------------------------------------------------------------ */
  
    $("body").on("click", ".live-preview > a", function (e) {
     
      e.preventDefault();
  
      $(".size-300x250__banner-section").css("display", "none");
      $(".size-728x90__banner").css("display", "none");
      $(".size-970x250__banner").css("display", "none");
  
      $.each(adDetails, function (dimension, elements) {
        $(".size-" + dimension).empty();
      });
  
      // empty appending blocks
      $(".swiper  > .swiper-wrapper").empty();
      $(".swiper2 > .swiper-wrapper").empty();
      $(".swiper3 > .swiper-wrapper").empty();
  
      var filePathBits, fileName, fileFolder, filePathBits2, bannerPath;
      var bannerData   = $(this).attr("href");
      var post_ID      = bannerData.split("-").pop();
      var showBanner   = new Array();
      var slideBanners = new Array();
  
      // prepare data set for rendering
      $.each( adDetails, function ( size, elements ) {
        $.each( elements, function ( index, element ) {
          if (element.post_id.includes(post_ID)) {
            showBanner.push(element);
          } else {
            slideBanners.push(element);
          }
        });
      });
  
      // render template with banner content
      $.each(showBanner, function (index, element) {

        detailTitle.innerText    = element.html_creative_name;
        detailIndustry.innerText = element.html_industry;
        detailFormat.innerText   = element.html_ad_format;
        detailTools.innerText    = element.html_production_tools;
        detailFeatures.innerText = element.html_features;
        detailSize.innerText     = element.html_ad_dimension;
  
        filePathBits  = element.html_banner_image[0].split("/");
        fileName      = filePathBits[filePathBits.length - 1];
        fileFolder    = fileName.split(".").shift();
        filePathBits2 = element.html_banner_image[0].split("uploads");
        bannerPath    = filePathBits2[0] + "uploads/Rich_Media_Archive/" + fileFolder + "/index.html";
  
        if ( element.html_ad_dimension == "300x250" ||  element.html_ad_dimension == "160x600" || element.html_ad_dimension == "300x600" ) {
          $(".size-300x250__banner-section").css("display", "flex");
          $(".size-" + element.html_ad_dimension).append(
            '<iframe src="' + bannerPath + '" scrolling="auto" ></iframe>'
          );
        } else if (element.html_ad_dimension == "728x90") {
          $(".size-728x90__banner").css("display", "flex");
          $(".size-728x90__banner")
            .find(".size-" + element.html_ad_dimension)
            .append(
              '<iframe src="' + bannerPath + '" scrolling="auto" ></iframe>'
            );
        } else if (element.html_ad_dimension == "970x250") {
          $(".size-970x250__banner").css("display", "flex");
          $(".size-970x250__banner")
            .find(".size-" + element.html_ad_dimension)
            .append(
              '<iframe src="' + bannerPath + '" scrolling="auto" ></iframe>'
            );
        }  

        /**
         * Scroll to top upon view sample click 
         */
        $("html, body").animate(
          {
            scrollTop: $(".size-" + element.html_ad_dimension).offset().top - 200,
          },
          100
        );

      });
  
      /**
       * Render sliders in takeover section
       */
      $.each(slideBanners, function (index, element) {
        size = element.html_ad_dimension[0];
  
        // Takeover section on page load
        $(".swiper > .swiper-wrapper").append(
          '<div class="campaign-title swiper-slide"><h1 class="campaign-title__heading">' +
            element.html_creative_name +
            "</h1></div>"
        );
        $(".swiper2 > .swiper-wrapper").append(
          '<div class="swiper-slide"><img src=' + element.html_slider_image +' alt="" /></div>'
        );
        $(".swiper3 > .swiper-wrapper").append(
          '<div class="swiper-slide ad-details-content">' +
            '<div class="ad-text">' +
            '<p class="ad-description">' +
            element.html_slider_description +
            "</p>" +
            "</div>" +
            '<div class="live-preview">' +
            '<a href="' +
            size +
            "-" +
            element.post_id +
            '">VIEW SAMPLE <i aria-hidden="true" class="fas fa-caret-right"></i></a>' +
            "</div>" +
            "</div>"
        );
      });
    });
  
    /* ------------------------------------------------------------------------ *
     * Sliders in Takeover Section
     * ------------------------------------------------------------------------ */
  
    /**
     * Campaign Title Slider
     * Fade Transition
     */
    const campaignTitle = new Swiper(".swiper", {
      speed: 500,
      loop: false,
      fadeEffect: { crossFade: true },
      slidesPerView: 1,
      simulateTouch: false,
      effect: "fade",
      observer: true,
      allowTouchMove: false,
    });
  
    /**
     * Takeover Ad Image Slider
     * Default Transition | Swipe Next & Prev Transition
     */
    const adImage = new Swiper(".swiper2", {
      loop: false,
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
      slidesPerView: 1,
      simulateTouch: false,
      allowTouchMove: false,
      observer: true,
    });
  
    /**
     * Takeover Ad Description Slider
     * Default Transition | Swipe Next & Prev Transition
     */
    const adDescription = new Swiper(".swiper3", {
      loop: false,
      slidesPerView: 1,
      simulateTouch: false,
      allowTouchMove: false,
      observer: true,
    });
  
    /**
     * Slide To Next All Campaign Title , Ad Image & Ad Description At Same Time
     */
    adImage.on("slideNextTransitionStart", () => {
      campaignTitle.slideNext();
      adDescription.slideNext();
    });
  
    /**
     * Slide To Prev All Campaign Title , Ad Image & Ad Description At Same Time
     */
    adImage.on("slidePrevTransitionStart", () => {
      campaignTitle.slidePrev();
      adDescription.slidePrev();
    });
  
    
  });

} 

/* ------------------------------------------------------------------------ *
 * Dynamic rendering : Mobile Device
 * ------------------------------------------------------------------------ */

else {

  jQuery(document).ready(function ($) {
    
    $("#mobile-container").show();  

    var fileName, fileFolder;

    var mobileAjaxObject = {
      init: function () {
        mobileAjaxObject.callAjaxMethod();
      },
      callAjaxMethod: function () {
        var data = {
          action: "sep_get_dimension_posts",
          postID: postID,            
          device: device
        };

        jQuery.ajax({
          url: ajaxObject.ajaxurl,
          type: "POST",
          dataType: "json",
          data: data,
          async: false,
          success: function (response) {

            jQuery.each(response, function (index, element) {  
             
              filePathBits = element.html_banner_image[0].split("/");
              fileName = filePathBits[filePathBits.length - 1];
              fileFolder = fileName.split(".").shift();
              filePathBits2 = element.html_banner_image[0].split("uploads");
              bannerPath = filePathBits2[0] + "uploads/Rich_Media_Archive/" + fileFolder + "/index.html";

              // Slider populate : Rich media
              $(".swiperMobile > .swiper-wrapper").append(
                '<div class="swiper-slide">'+
                '<div class=size-' + element.html_ad_dimension + '>'+
                '<iframe src="' + bannerPath + '" scrolling="auto" ></iframe>'+
                '</div></div>'                  
              );

              // Slider populate : Ad details
              $(".swiperDetails > .swiper-wrapper").append( 
                '<div class="swiper-slide">'+  
                '<div class="info-content-container">'+                 
                '<div class="left-info">'+
                '<p><span class="bold">Creative Name: </span><span id="detailTitle">'+ element.html_creative_name +'</span></p>'+
                '<p><span class="bold">Industry: </span><span id="detailIndustry">'+ element.html_industry +'</span></p>'+
                '<p><span class="bold">Ad Dimension: </span><span id="detailSize">'+ element.html_ad_format +'</span></p>'+
                '</div>'+
                '<div class="right-info">'+
                '<p><span class="bold">Ad Format: </span><span id="detailFormat">'+ element.html_production_tools +'</span></p>'+
                '<p><span class="bold">Creative Production Tools: </span><span id="detailTools">'+ element.html_features +'</span></p>'+
                '<p><span class="bold">Features: </span><span id="detailFeatures">'+ element.html_ad_dimension +'</span></p>'+
                '</div>'+
                '</div>'               
              );

            });
          },
        });
      },
    };
    mobileAjaxObject.init();


    /**
     * Rich media / banner Slider
     * Default Transition | Swipe Next & Prev Transition
     */
    const adImage = new Swiper(".swiperMobile", {
      loop: false,
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
      slidesPerView: 1,
      simulateTouch: false,
      allowTouchMove: false,
      observer: true,
    });

    /**
     * Slider for ad-details
     */
    const adDescription = new Swiper(".swiperDetails", {
      loop: false,
      slidesPerView: 1,
      simulateTouch: false,
      allowTouchMove: false,
      observer: true,
    });
  
    /**
     * Slide to next banner wise detials info simultaneous to banner slider
     */
    adImage.on("slideNextTransitionStart", () => {
      adDescription.slideNext();
    });
  
    /**
     * Slide To Prev banner wise detials info simultaneous to banner slider
     */
    adImage.on("slidePrevTransitionStart", () => {
      adDescription.slidePrev();
    });
  
    
  });
}