<?php
/**
 * Template Name: Shop
 *
 * @package _
 * @subpackage _
 * @since _
 */
 
get_header();

//Get URL for featured image to apply to inline CSS background
$post_image_id = get_post_thumbnail_id($post_to_use->ID);
if ($post_image_id) {
  $size = array(2000,1000);
  $thumbnail = wp_get_attachment_image_src( $post_image_id, $size, false);
  if ($thumbnail) (string)$thumbnail = $thumbnail[0];
}

?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		
		  <?php 
		    if(has_post_thumbnail()) { 
  		  
  		  //Get URL for featured image to apply to inline CSS background
        $post_image_id = get_post_thumbnail_id($post_to_use->ID);
        if ($post_image_id) {
          $size = array(2000,1000);
          $thumbnail = wp_get_attachment_image_src( $post_image_id, $size, false);
          if ($thumbnail) (string)$thumbnail = $thumbnail[0];
        }
		  ?>
        <div class="hero" style="background:url('<?php echo $thumbnail ?>');background-size:cover;background-repeat:no-repeat;background-position: center center;"></div>
      <?php } ?>

      <div class="container">
      	<div class="row" style="margin: 60px 0;">
      		<div class="col-sm-12">
      		
        		<?php woocommerce_content(); ?>

      		</div>
      	</div>
      </div>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>