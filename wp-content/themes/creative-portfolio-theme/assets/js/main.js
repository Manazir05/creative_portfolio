// Show Menu
const navMenu = document.getElementById("nav-menu"),
  navToggle = document.getElementById("nav-toggle"),
  navClose = document.getElementById("nav-close");

/* MENU SHOW */
/* Validate if constant exists */
if (navToggle) {
  navToggle.addEventListener("click", () => {
    navMenu.classList.add("show-menu");
  });
}

/* MENU HIDDEN */
/* Validate if constant exists */
if (navClose) {
  navClose.addEventListener("click", () => {
    navMenu.classList.remove("show-menu");
  });
}

/* REMOVE MENU MOBILE */
const navLink = document.querySelectorAll(".nav__link");

function linkAction() {
  const navMenu = document.getElementById("nav-menu");
  // When we click on each nav__link, we remove the show-menu class
  navMenu.classList.remove("show-menu");
}
navLink.forEach((n) => n.addEventListener("click", linkAction));

/*
 * Static and Animated GIFs Containers
 * Display Ad By Sizes
 */
window.onload = function () {};

document.addEventListener("DOMContentLoaded", function () {
  document.getElementById("staticBtn").click();
  document.getElementById("sizeBtn").click();
});

function showStaticAndGifContainer(container) {
  let x, i;
  staticGifContainer = document.querySelectorAll(".static-gif-container");
  if (container == "all") container = "";
  for (i = 0; i < staticGifContainer.length; i++) {
    removeClassFromAdContainer(staticGifContainer[i], "showContainer");
    if (staticGifContainer[i].className.indexOf(container) > -1)
      addClassToAdContainer(staticGifContainer[i], "showContainer");
  }
}
showStaticAndGifContainer("all");

/*
 * Display All Ads
 */
function showAdsBySize(container) {
  let allAdsBySize, i;
  allAdsBySize = document.querySelectorAll(".allads");
  if (container == "all") container = "";
  for (i = 0; i < allAdsBySize.length; i++) {
    removeClassFromAdContainer(allAdsBySize[i], "show");
    if (allAdsBySize[i].className.indexOf(container) > -1) {
      addClassToAdContainer(allAdsBySize[i], "show");
    }
  }
}

showAdsBySize("all");

function addClassToAdContainer(element, name) {
  let i, elementArr, nameArr;
  elementArr = element.className.split(" ");
  nameArr = name.split(" ");
  for (i = 0; i < nameArr.length; i++) {
    if (elementArr.indexOf(nameArr[i]) == -1) {
      element.className += " " + nameArr[i];
    }
  }
}

function removeClassFromAdContainer(element, name) {
  let i, elementArr, nameArr;
  elementArr = element.className.split(" ");
  nameArr = name.split(" ");
  for (i = 0; i < nameArr.length; i++) {
    while (elementArr.indexOf(nameArr[i]) > -1) {
      elementArr.splice(elementArr.indexOf(nameArr[i]), 1);
    }
  }
  element.className = elementArr.join(" ");
}

/*
 * Navigate From Static To GIF and different Size Ad Size
 */

/*
 * Add active class to the Static and GIF Button
 */
const staticAndGifBtn = document.querySelectorAll(".containerButton");
for (let i = 0; i < staticAndGifBtn.length; i++) {
  staticAndGifBtn[i].addEventListener("click", function () {
    let current = document.getElementsByClassName("activeCon");
    //  console.log(current);
    current[0].className = current[0].className.replace("activeCon", "");
    this.className += " activeCon";
  });
}

/*
 * Add active class to the Ad Size button On Click
 */
const adSizeNavbarContainer = document.getElementById(
  "staticAnimatedGifContainerBtn"
);
const sizeBtn = adSizeNavbarContainer.querySelectorAll(".other-size-btn");
for (let i = 0; i < sizeBtn.length; i++) {
  sizeBtn[i].addEventListener("click", function () {
    let current = document.getElementsByClassName("active");
    current[0].className = current[0].className.replace("active", "");
    this.className += " active";
  });
}

const viewportHeight = document.documentElement.clientHeight;

lightbox.option({
  resizeDuration: 200,
  wrapAround: false,
  positionFromTop: viewportHeight / 4,
  showImageNumberLabel: false,
  disableScrolling: true,
});



// jQuery(window).resize(function($) {});



/* ---------------------------------------- *
 * AJAX : Render Image
 * ---------------------------------------- */

jQuery(document).ready(function($) {
  
  let device, deviceOrientation;
  let template_allowed_images;
 
  // Safari will not set screen.dimension on orientation according to device's angle
  let screenWidth  = screen.width; 
  let screenHeight = screen.height;
  
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
    // device detection
    if ( deviceOrientation == 'portrait' && screenWidth <= 480 
      || deviceOrientation == 'landscape' && screenHeight <= 480 ) { // phone

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

  if ( device == 'phone' ) { // smartphone

    template_allowed_images = new Map([
      ['300x250', 4 ],
      ['160x600', 4 ],
      ['300x600', 2 ],      
      ['320x50' , 4 ],
      ['300x50' , 4 ],
    ]);

    /**
     * phone in landscape orientation
     */
    if ( window.matchMedia('(min-width: 481px)').matches ) {
      template_allowed_images.set('300x250', 3 );
      template_allowed_images.set('160x600', 7 );
      template_allowed_images.set('300x600', 4 );
    }    

    // hide buttons and contents
    jQuery("#728x90").hide();
    jQuery("#970x250").hide();
    jQuery(".size-728x90").hide();
    jQuery(".size-970x250").hide();

    // show buttons and contents
    jQuery("#320x50").show();
    jQuery("#300x50").show(); 
    jQuery(".size-320x50").show();
    jQuery(".size-300x50").show(); 

  } else if( device == 'tab' ) { // tab

    template_allowed_images = new Map([
      ['300x250', 9 ],
      ['160x600', 14],
      ['300x600', 8 ],      
      ['728x90' , 8 ],
      ['970x250', 8 ],
    ]);

    /**
     * tablet device in landscape orientation
     */
    if ( window.matchMedia('(min-width: 1025px)').matches ) {
      template_allowed_images.set('300x250', 8 );
      template_allowed_images.set('160x600', 6 );
    } 

    // show buttons and contents
    jQuery("#320x50").hide();
    jQuery("#300x50").hide(); 
    jQuery(".size-320x50").hide();
    jQuery(".size-300x50").hide();    

    // hide buttons and contents
    jQuery("#728x90").show();
    jQuery("#970x250").show();
    jQuery(".size-728x90").show();
    jQuery(".size-970x250").show();

  } else if( device == 'desktop' ) {  // desktop

    // allowed number of banners on each page (without pagination/ lazyloading )
    template_allowed_images = new Map([
      ['300x250', 16 ],
      ['160x600', 14 ],
      ['300x600', 8  ],
      ['728x90' , 8  ],
      ['970x250', 8  ],
    ]);

    // show buttons and contents
    jQuery("#320x50").hide();
    jQuery("#300x50").hide(); 
    jQuery(".size-320x50").hide();
    jQuery(".size-300x50").hide();    

    // hide buttons and contents
    jQuery("#728x90").show();
    jQuery("#970x250").show();
    jQuery(".size-728x90").show();
    jQuery(".size-970x250").show();
  }  

  $(".load-more-other").hide();

  // resolve lightbox initialized on page load issue
  $("#lightbox").hide(); 

  // spinner image path
  var spinLogo = nObject.tmplate_url+'/assets/images/loading.gif';

  const nonce = $(".adsize-nav").attr("data-nonce");  

  let banner_load_more = new Map([
    ['static-300x250', [] ],
    ['static-160x600', [] ],
    ['static-300x600', [] ],
    ['static-728x90' , [] ],
    ['static-970x250', [] ],
    ['static-320x50' , [] ],
    ['static-300x50' , [] ],
    ['gif-300x250'   , [] ],
    ['gif-160x600'   , [] ],
    ['gif-300x600'   , [] ],
    ['gif-728x90'    , [] ],
    ['gif-970x250'   , [] ],
    ['gif-320x50'    , [] ],
    ['gif-300x50'    , [] ]
  ]);  

  const url     = window.location.pathname;
  var segments  = url.split( '/' );
  var pageFound = jQuery.inArray('static', segments );

  if( pageFound > -1 ) {

      var all_banners = [];

      var setAjaxObject = {
        init: function () {
          setAjaxObject.callAjaxMethod();
        },
        callAjaxMethod:function() {
            var data = {
              'action': 'sep_ajax_render_static',
              'category': 'Static',
              'dimension' : '300x250',
              'nonce' : nonce
            };
  
            jQuery.ajax({
                url: nObject.ajaxurl,
                type: 'POST',
                dataType: "json",
                data: data,
                beforeSend : function ( xhr ) {
                  $("#spin").append('<img src='+spinLogo+' />');
                },
                success: function (response) {

                  // hide no-banner text
                  $( ".no-banner" ).hide();
                  
                  if( response.length > 0 ) {
                    
                    var img_block = '';
                    
                    for ( var i = 0; i < template_allowed_images.get('300x250'); i++ ) {
                  
                      if( !(response[i] === undefined) ) { 
  
                        $('.static-ad').children('.size-300x250').empty();
                      
                        img_block += '<div class="allads show list-300x250">';
                        img_block += '<a href="' +response[i]+ '" data-lightbox="static-300x250" >';
                        img_block += '<img src="' +response[i]+ '" alt=""/>';
                        img_block += '</a></div>';
  
                        $('.static-ad').children('.size-300x250').append( img_block );
                      }
                    }
                    
                    for ( var i = template_allowed_images.get('300x250'); i < response.length; i++ ) {
                      all_banners.push(response[i]);
                    }
                    banner_load_more.set('static-300x250', all_banners);
                    all_banners = [];
  
                    if ( response.length > template_allowed_images.get('300x250') ) {
                      $('#load').append('<button class="load-more-other" data-category="static" data-size="300x250" >Load More</button>');
                    } 
                  } 
                  else {
                    $( ".no-banner" ).show( 'slow' );
                  }
                                   
                },
                complete: function() {
                  $("#spin").empty();
                },
            });
        }
      }
      setAjaxObject.init();
      
      /**
       * Event-1: fire on category button click 
       */
      var category, dimension, containerDiv;

      $( ".containerButton" ).click( function(e) { 
        
        var btn_id = $(this).attr('id');
        
        if ( btn_id == 'staticBtn' ) {
          category     = 'Static';
          containerDiv = '.static-ad';
        } else {
          category     = 'GIF';
          containerDiv = '.gif-ad';
        }

        dimension   = $("li.other-size-btn.active").children('p').html();
        $loadState1 = $( containerDiv + ' > ' +'.size-'+dimension ).children('.allads').length;

        /**
         * Show already loaded content by ajax
         * no need to call ajax if there is child element present in the parent div
         */
        if( $loadState1 > 0 ) {

          $( containerDiv ).children().not('.size-'+dimension).hide();
          $( containerDiv + ' > ' +'.size-'+dimension ).show();
          // Load more button
          $( "#load" ).children( ".load-more-other[data-category="+category.toLowerCase()+"][data-size="+dimension+"]" ).show();
          $( "#load" ).children().not( ".load-more-other[data-category="+category.toLowerCase()+"][data-size="+dimension+"]" ).hide();
          // hide no-banner text
          $( ".no-banner" ).hide();
        } 
        else{

          var more_banners = [];

          var catAjaxObject = {
            init: function () {
              catAjaxObject.callAjaxMethod();
            },
            callAjaxMethod:function() 
            {
              var data = {
                'action': 'sep_ajax_render_static',
                'category': category,
                'dimension' : dimension,
                'nonce' : nonce
              };
    
              jQuery.ajax({
                url: nObject.ajaxurl,
                type: 'POST',
                dataType: "json",
                data: data,
                beforeSend : function ( xhr ) {
                  $("#spin").append('<img src='+spinLogo+' />');
                },
                success: function (response) {
                  
                  if( response.length > 0 ) {

                    // hide text when no banne available
                    $( ".no-banner" ).hide();

                    var img_block = '';
  
                    for ( var j = 0; j < template_allowed_images.get(dimension); j++ ) {
                      
                      if( !(response[j] === undefined) ) {
                        $(containerDiv).children('.size-'+dimension).empty();
                        
                        img_block += '<div class="allads show list-'+dimension+'">';
                        img_block += '<a href="' +response[j]+ '" data-lightbox="'+category.toLowerCase()+'-'+dimension+'" >';
                        img_block += '<img src="' +response[j]+ '" alt=""/>';
                        img_block += '</a></div>';                    
                      
                        $(containerDiv).children('.size-'+dimension).append( img_block );
                      }    
                    }

                    for ( var j = template_allowed_images.get(dimension); j < response.length; j++ ) {
                      more_banners.push(response[j]);
                    }
                    banner_load_more.set(category.toLowerCase()+'-'+dimension , more_banners); // store all banners
                    more_banners = [];

                    if ( response.length > template_allowed_images.get(dimension) ) {
                      $( '#load' ).append('<button class="load-more-other" data-category='+category.toLowerCase()+' data-size='+dimension+' >Load More</button>');
                    }

                    // Load more button
                    $( "#load" ).children( ".load-more-other[data-category="+category.toLowerCase()+"][data-size="+dimension+"]" ).show();
                    $( "#load" ).children().not( ".load-more-other[data-category="+category.toLowerCase()+"][data-size="+dimension+"]" ).hide();      

                    $( containerDiv ).children('.size-'+dimension).show();
                    $( containerDiv ).children().not('.size-'+dimension).hide();    

                  }
                  else {
                    $( ".no-banner" ).show('slow');
                  }
                },
                complete: function() {
                  $("#spin").empty();
                },
              });
            }
          }
          catAjaxObject.init();
        }         
      });

    /**
     * Event-2: fire on size-dimension button click 
     */
    $( ".other-size-btn" ).click( function(e) {

      dimension = $( this ).children('p').html();

      btn_id = $( "li.containerButton.activeCon" ).attr('id');

      if ( btn_id == 'staticBtn' ) {
        category     = 'Static';
        containerDiv = '.static-ad';
      } else {
        category     = 'GIF';
        containerDiv = '.gif-ad';
      }

      $loadState2 = $( containerDiv + ' > ' +'.size-'+dimension ).children('.allads').length;

      /**
       * Show already loaded content by ajax
       * no need to call ajax if there is child element present in the parent div
       */
      if( $loadState2 > 0 ) {

        $( containerDiv ).children().not('.size-'+dimension).hide();
        $( containerDiv + ' > ' +'.size-'+dimension ).show();

        // Load more button
        $( "#load" ).children( ".load-more-other[data-category="+category.toLowerCase()+"][data-size="+dimension+"]" ).show();
        $( "#load" ).children().not( ".load-more-other[data-category="+category.toLowerCase()+"][data-size="+dimension+"]" ).hide(); 
        
        // hide no-banner text
        $( ".no-banner" ).hide();
      } 
      else {

        var load_banners = [];

        var sizeAjaxObject = {
          init: function () {
            sizeAjaxObject.callAjaxMethod();
          },
          callAjaxMethod:function(){
              var data = {
                'action': 'sep_ajax_render_static',
                'category': category,
                'dimension' : dimension,
                'nonce' : nonce
              };             
  
              jQuery.ajax({
                  url: nObject.ajaxurl,
                  type: 'POST',
                  dataType: "json",
                  data: data,
                  beforeSend : function ( xhr ) {
                    $("#spin").append('<img src='+spinLogo+' />');
                  },
                  success: function (response) {
                    
                    if( response.length > 0 ) {

                      $( ".no-banner" ).hide();

                      var img_block = '';
    
                      for ( var k = 0; k < template_allowed_images.get(dimension); k++ ) {
                          
                        if( !(response[k] === undefined) ) {

                          $(containerDiv).children('.size-'+dimension).empty();
                          
                          img_block += '<div class="allads show list-'+dimension+'">';
                          img_block += '<a href="' +response[k]+ '" data-lightbox="'+category.toLowerCase()+'-'+dimension+'" >';
                          img_block += '<img src="' +response[k]+ '" alt=""/>';
                          img_block += '</a></div>';                  
                        
                          $(containerDiv).children('.size-'+dimension).append( img_block );
                        }   
                      }
              
                      for ( var k = template_allowed_images.get(dimension); k < response.length; k++ ) {
                        load_banners.push(response[k]);
                      } 

                      // store all banners
                      banner_load_more.set(category.toLowerCase()+'-'+dimension , load_banners);
                      load_banners = [];

                      if ( response.length > template_allowed_images.get(dimension) ) {
                        $('#load').append('<button class="load-more-other" data-category='+category.toLowerCase()+' data-size='+dimension+' >Load More</button>');
                      }
                      
                    }
                    else {
                      $( ".no-banner" ).show('slow');
                    }

                    // Load more button
                    $( "#load" ).children( ".load-more-other[data-category="+category.toLowerCase()+"][data-size="+dimension+"]" ).show();
                    $( "#load" ).children().not( ".load-more-other[data-category="+category.toLowerCase()+"][data-size="+dimension+"]" ).hide();       
                    
                    $( containerDiv ).children('.size-'+dimension).show();
                    $( containerDiv ).children().not('.size-'+dimension).hide();
                    
                  },
                  complete: function() {
                    $("#spin").empty();
                  },
              });
          }
        }
        sizeAjaxObject.init();
      }      
      
    });

   
    /**
     * Load-More static banners
     */
    $(document).on('click', '.load-more-other', function(event) {

      var pCategory  = $(this).data('category');
      var pDimension = $(this).data('size');
      var loadN;

      // device-screen wise load-more function
      if ( device == "phone" ) {
        loadN  = template_allowed_images.get(pDimension);
      }
      else if( device == "tab" && pDimension == '300x250' ) {
        
       /**
        * tablet device in landscape orientation for 300x250 banners
        */
        if ( window.matchMedia('(min-width: 1025px)').matches ) {
          loadN  = ( template_allowed_images.get(pDimension) / 2 );
        } else {
          loadN  = ( template_allowed_images.get(pDimension) / 3 );
        }        
      } 
      else if( device == "tab" && pDimension == '160x600' ) {
        
       /**
        * tablet device in landscape orientation for 160x600 banners
        */
        if ( window.matchMedia('(min-width: 1025px)').matches ) {
          loadN  = template_allowed_images.get(pDimension);
        } else {
          loadN  = ( template_allowed_images.get(pDimension) / 2 );
        }
      }  
      else {

        loadN  = ( template_allowed_images.get(pDimension) / 2 );
      }

      var loadBanner = banner_load_more.get( pCategory +'-'+ pDimension );
      var looper     = ( loadBanner.length > loadN ) ? loadN : loadBanner.length;
      var img_block  = '';

      ( pCategory.toLowerCase() == 'static' ) ? containerDiv = '.static-ad' : containerDiv = '.gif-ad';
      
      for ( var p = 0; p < looper; p++ ) {

        var aBanner = loadBanner.shift();
        
        img_block += '<div class="allads show list-'+ pDimension +'">';
        img_block += '<a href="' +aBanner+ '" data-lightbox="'+pCategory.toLowerCase()+'-'+pDimension+'" >';
        img_block += '<img src="' +aBanner+ '" alt=""/>';
        img_block += '</a></div>';  
      }

      $(img_block).appendTo( $(containerDiv).children('.size-'+pDimension) );

      if ( loadBanner.length == 0 ) {
        $(this).remove();
      }

    });

    /**
     * Re-load banners on device orientation
     */
    $(window).on("orientationchange", function( e ) {
      location.reload();
    });
  
  } // end post-page condition    

}); // end jquery



