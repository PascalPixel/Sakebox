<?php
/**
 * Template Name: Shop
 *
 * @package _
 * @subpackage _
 * @since _
 */
 
get_header();

?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
    
		  <?php 
		    if(is_page(38)) { 
  		  //Get URL for featured image to apply to inline CSS background
        $post_image_id = get_post_thumbnail_id(38);
        if ($post_image_id) {
          $size = array(2000,1000);
          $thumbnail = wp_get_attachment_image_src( $post_image_id, $size, false);
          if ($thumbnail) (string)$thumbnail = $thumbnail[0];
        }
		  ?>
      <div class="hero" style="background:url('<?php echo $thumbnail ?>');background-size:cover;background-repeat:no-repeat;background-position: center center;">
        <div class="container">
        	<div class="row">
        		<div class="col-sm-12">
        		  <div class="heroback">
          		  <h1>Shop for amazing saké straight from Japan</h1>
        		  </div>
        		</div>
        	</div>
        </div>
      </div>
      <?php } ?>

      <div class="container">
      	<div class="row">
      		<div class="col-sm-7 col-md-8">
      		
        		<?php woocommerce_content(); ?>

      		</div>
      		<div class="col-sm-5 col-md-4">
      		
      		  <?php get_sidebar(); ?>
      		  
      		</div>
      	</div>
      </div>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>