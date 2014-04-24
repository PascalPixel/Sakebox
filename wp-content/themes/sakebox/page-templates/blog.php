<?php
/**
 * Template Name: Blog
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
      		<div class="col-sm-8">

        		<?php if ( have_posts() ) : ?>
        
        			<?php /* Start the Loop */ ?>
        			<?php while ( have_posts() ) : the_post(); ?>
        
        				<?php
        					/* Include the Post-Format-specific template for the content.
        					 * If you want to override this in a child theme, then include a file
        					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
        					 */
        					get_template_part( 'content', get_post_format() );
        				?>
        
        			<?php endwhile; ?>
        
        			<?php sakebox_paging_nav(); ?>
        
        		<?php else : ?>
        
        			<?php get_template_part( 'content', 'none' ); ?>
        
        		<?php endif; ?>

      		</div>
      		<div class="col-sm-4">
      		
        		<?php get_sidebar(); ?>
      		
      		</div>
      	</div>
      </div>

		</main><!-- #main -->
	</div><!-- #primary -->

  <?php get_footer(); ?>
	
	</div>
</div>