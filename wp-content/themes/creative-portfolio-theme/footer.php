<?php
  get_option( 'copyright_text' ) ? $copyRight = get_option( 'copyright_text' ) : $copyRight = '©2022.';
  $image_id  = get_option( 'uploaded_logo' );
  $image_id  ? $image_src = wp_get_attachment_image_src( $image_id, 'full' ) : $image_src = false;
  $image_src ? $render = $image_src[0] : $render = ''; 
?>


<!----------Start Footer Section------------->
<footer class="footer">
  <!--------Start Footer Cotainer-------->
  <div class="footer-container">
    <!-----Start Copyright----> 
    <div class="footer-item copyright">
      <p><?php echo $copyRight; ?></p>
    </div>
    <!-----End Copyright---->

    <!-----Start Bottom Navbar---->
    <div class="footer-item bottom-nav">
      <nav>
        <a href="">Contact Us</a>
        <a href="">Privacy Policy</a>
        <a href="">Terms & Conditions</a>
      </nav>
    </div>
    <!-----End Bottom Navbar------>

    <!------Start Bottom Logo------->
    <div class="footer-item footer-logo">
      <img src="<?php echo $render; ?>" alt="Creative" />
    </div>
    <!------End Bottom Logo------->
  </div>
  <!-------End Footer Container-------->
</footer>

  <?php wp_footer(); ?>

  </body>
</html>

