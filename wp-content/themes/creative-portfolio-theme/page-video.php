<?php

/* Template Name: video */
get_header();
$title = get_option('homepage_video_title');

?>

<main>
    <!-----------Start Video Production Section Title------------>
    <section class="video-production__title">
        <div class="title-text">
            <h1><?php echo strtoupper($title); ?></h1>
        </div>
    </section>
    <!-----------End Video Production Section Title------------>

    <!-----------Start Video Production Section--------------->
    <section class="video-production">
        <!------------Start Video Container----------->

        <?php
        global $wp_query;

        $args = array(
            'post_type' => 'video',
            'meta_query' => array(
                array(
                    'key'     => 'video_post_visibility',
                    'value'   => 'Active',
                    'compare' => '=',
                ),
            ),
            //'posts_per_page' => 5,
            'post_status' => 'publish',
            'orderby' => array('post_date' => 'DESC')
        );

        $post_query = new WP_Query($args);

        if ($post_query->have_posts()) {

            while( $post_query->have_posts() ) {
                
                $post_query->the_post();

                $post_id   = get_the_ID();
                $post_type = get_post_type();
                $post_file = get_post_meta(get_the_ID(), 'video_file', true);
                $post_img  = get_post_meta(get_the_ID(), 'video_thumbnail', true);
                $post_category = get_post_meta(get_the_ID(), 'video_category', true);
                $post_title    = get_post_meta(get_the_ID(), 'video_title', true);
                $post_excerpt  = get_post_meta(get_the_ID(), 'video_description', true);

        ?>

                <div class="video-container">
                    <div class="video-wrapper">
                        <!------------Start Video Frame Container----------->
                        <div class="video-frame">
                            <video poster="<?php echo $post_img; ?>" class="video-production__videos">
                                <source src="<?php echo $post_file; ?>" type="video/mp4" />
                            </video>
                        </div>
                        <!------------Start Video Frame Container----------->

                        <!----------Start Video Details Container----------->
                        <div class="video-details">
                            <div class="title-and-category">
                                <h4 class="video-category"><?php echo $post_category; ?></h4>
                                <h1 class="video-title">
                                    <?php echo $post_title; ?>
                                </h1>
                            </div>
                            <p class="video-description">
                                <?php echo $post_excerpt; ?>
                            </p>
                        </div>
                        <!----------End Video Details Container----------->
                    </div>
                </div>

        <?php
            }
        }
        ?>

        <!------------End Video Container----------->

    </section>
    <!-----------End Video Production Section--------------->


    <?php

    // if ( $post_query->max_num_pages > 1 ) {
    //     echo '<button class="load-more-other">Load More</button>';
    // }

    // $ajaxArray = array(
    //     'ajaxURL' => admin_url( 'admin-ajax.php' ), // WordPress AJAX
    //     //'posts' => json_encode( $post_query->query_vars ), // everything about your loop is here
    //     'current_page' => $post_query->query_vars['paged'] ? $post_query->query_vars['paged'] : 1,
    //     'max_page' => $post_query->max_num_pages,
    //     'posts_per_page' => $post_query->query_vars['posts_per_page'],
    // );

    ?>

    <!----------------Start Spacer-------------->
    <section class="static-page-spacer"></section>
    <!---------------End Spacer-------------->

    <!-------------Start Other Service Page Section------------------>
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

    </section>
    <!-------------End Other Service Page Section------------------>

    <!----------------Start Spacer-------------->
    <section class="static-page-spacer"></section>
    <!---------------End Spacer-------------->
</main>

<?php
get_footer();
?>

<script>
    const videos = document.getElementsByClassName(
        "video-production__videos"
    );
    const videoFrames = document.getElementsByClassName("video-frame");

    for (let i = 0; i < videos.length; i++) {
        videos[i].addEventListener("mouseover", () => {
            videos[i].setAttribute("controls", "controls");
            videoFrames[i].style.cursor = "pointer";
        });
        videos[i].addEventListener("mouseleave", () => {
            videos[i].removeAttribute("controls");
        });
    }


    // jQuery(function($) {

    // var current_page = "<?php //echo $ajaxArray['current_page']; 
                            ?>";
    // var max_page = "<?php //echo $ajaxArray['max_page']; 
                        ?>";

    // $('.load-more-other').click(function() {  

    //   var per_page = "<?php //echo $ajaxArray['posts_per_page']; 
                            ?>";
    //   var ajaxURL = "<?php //echo $ajaxArray['ajaxURL']; 
                        ?>";

    //   var button = $(this),          
    //   data = {
    //     'action': 'loadmore_video',
    //     'page' : current_page,
    //     'per_page_initial' : per_page,
    //   };

    // $.ajax({ 
    //     url : ajaxURL, 
    //     data : data,
    //     dataType: "json",
    //     type : 'POST',
    //     // beforeSend : function ( xhr ) {
    //     // 	button.text('Loading...'); // change the button text, you can also add a preloader image
    //     // },
    //     success : function( data ){

    //       if( data ) {

    //         var looper = data.content;

    //         $.each( looper , function( index, element ) {

    //             $(".video-production").append('<div class="video-container">'+
    //                 '<div class="video-wrapper">'+                        
    //                 '<div class="video-frame">'+
    //                 '<video poster="" class="video-production__videos">'+
    //                 '<source src="'+element.video_file[0]+'" type="video/mp4" />'+
    //                 '</video>'+
    //                 '</div>'+
    //                 '<div class="video-details">'+
    //                 '<div class="title-and-category">'+
    //                 '<h4 class="video-category">'+element.video_category[0]+'</h4>'+
    //                 '<h1 class="video-title">'+element.video_title[0]+'</h1>'+
    //                 '</div>'+
    //                 '<p class="video-description">'+element.video_description[0]+'</p>'+
    //                 '</div>'+
    //                 '</div>'+
    //                 '</div>');               

    //         });

    //         current_page = data.page_update;           

    //         if ( current_page == data.max_page )  {

    //             button.remove(); // if last page, remove the button
    //         }           

    //       } else {
    //         button.remove(); // if no data, remove the button as well
    //       } 

    //     }
    // });
    // });

    // });
</script>