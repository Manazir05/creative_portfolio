<?php
/* Template Name: dcms */

get_header();

$title = get_option('homepage_cms_title');

?>

<main>

    <!---------Start Development & CMS Title----------->
    <section class="development-cms-title">
        <div class="title-text">
            <h1><?php echo strtoupper($title); ?></h1>
        </div>
    </section>
    <!---------End Development & CMS Title----------->

    <!------------Start Web Development Project Container---------->
    <section class="web-development-project">

        <?php
        global $wp_query;

        $args = array(
            'post_type' => 'dcms',
            'meta_query' => array(
                array(
                    'key'     => 'dcms_post_visibility',
                    'value'   => 'Active',
                    'compare' => '=',
                ),
            ),
            //'posts_per_page' => 3,
            'post_status' => 'publish',
            'orderby' => array('post_date' => 'DESC')
        );

        $post_query = new WP_Query($args);

        if ($post_query->have_posts()) {

            while ($post_query->have_posts()) {

                $post_query->the_post();
                $post_id = get_the_ID();
                $post_type = get_post_type();
                $post_image = get_post_meta(get_the_ID(), 'dcms_image', true);
                $post_category = get_post_meta(get_the_ID(), 'dcms_category', true);
                $post_title = get_post_meta(get_the_ID(), 'dcms_title', true);
                $post_excerpt = get_post_meta(get_the_ID(), 'dcms_description', true);
                $post_live_url = get_post_meta(get_the_ID(), 'dcms_preview_link', true);

        ?>

                <div class="web-dev__project-img-left dcms">
                    <div class="left">
                        <a href="<?php echo $post_live_url; ?>" target="_blank">
                            <img src="<?php echo $post_image; ?>" alt="" />
                        </a>
                    </div>
                    <div class="right">
                        <h4 class="project-category"><?php echo $post_category; ?></h4>
                        <h2 class="project-title"><?php echo $post_title; ?></h2>
                        <p class="project-description"><?php echo $post_excerpt; ?></p>
                        <a href="<?php echo $post_live_url; ?>" target="_blank" class="view-project__btn">VIEW PROJECT <i aria-hidden="true" class="fas fa-caret-right"></i></a>
                    </div>
                </div>

        <?php
            }
        }
        ?>

    </section>
    <!------------End Web Development Project Container---------->

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
            <a href="<?php echo get_home_url(); ?>/video" class="block">
                <div class="service-link__title_view">
                    <h2 class="page-title">Video Production</h2>
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
    jQuery(function($) {

        /**
         * Swap image and info-card contents of odd positioned elements in the page
         */
        function swapSides() {

            var devElements = $(".dcms:odd");

            $.each(devElements, function(index, element) {

                var className = $(this).attr("class");

                if (className === 'web-dev__project-img-left dcms') {

                    // [left,right]box elements
                    var left_box  = $(this).children()[0];
                    var right_box = $(this).children()[1];

                    // box contents
                    var left_box_contents  = $(left_box).html();
                    var right_box_contents = $(right_box).html();

                    $(left_box).empty();
                    $(right_box).empty();

                    // swap contents
                    $(left_box).append(right_box_contents);
                    $(right_box).append(left_box_contents);

                    // change class
                    $(element).removeClass('web-dev__project-img-left dcms');
                    $(element).addClass('web-dev__project-img-right dcms');
                }

            });
        }

        swapSides();


        //     var current_page = "<?php //echo $ajaxArray['current_page']; 
                                    ?>";
        //     var max_page = "<?php //echo $ajaxArray['max_page']; 
                                ?>";

        //     $('.load-more-other').click(function() {  

        //       var per_page = "<?php //echo $ajaxArray['posts_per_page']; 
                                    ?>";
        //       var ajaxURL = "<?php //echo $ajaxArray['ajaxURL']; 
                                ?>";

        //       var button = $(this),          
        //       data = {
        //         'action': 'loadmore_dcms',
        //         'page' : current_page,
        //         'per_page_initial' : per_page,
        //       };

        //     $.ajax({ 
        //         url : ajaxURL, 
        //         data : data,
        //         dataType: "json",
        //         type : 'POST',
        //         // beforeSend : function ( xhr ) {
        //         // 	button.text('More...');
        //         // },
        //         success : function( data ){

        //           if( data ) {

        //             var looper = data.content;

        //             $.each( looper , function( index, element ) {

        //                 $(".web-development-project").append('<div class="web-dev__project-img-left dcms">'+
        //                     '<div class="left">'+
        //                         '<a href="'+element.dcms_preview_link[0]+'" target="_blank">'+
        //                         '<img src="'+element.dcms_image[0]+'" alt=""/>'+
        //                         '</a>'+
        //                     '</div>'+
        //                     '<div class="right">'+
        //                         '<h4 class="project-category">'+element.dcms_category[0]+'</h4>'+
        //                         '<h2 class="project-title">'+element.dcms_title[0]+'</h2>'+
        //                         '<p class="project-description">'+element.dcms_description[0]+'</p>'+
        //                         '<a href="'+element.dcms_preview_link[0]+'" target="_blank" class="view-project__btn">VIEW PROJECT <i aria-hidden="true" class="fas fa-caret-right"></i></a>'+
        //                     '</div>'+
        //                 '</div>');               

        //             });

        //             current_page = data.page_update;           

        //             if ( current_page == data.max_page )  {

        //                 button.remove(); // if last page, remove the button
        //             }           

        //           } else {
        //             button.remove(); // if no data, remove the button as well
        //           }          


        //           swapSides(); // swap sides of alternate elements with each ajax call
        //         }
        //     });
        //     });

    });
</script>