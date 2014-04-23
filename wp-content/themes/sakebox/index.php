<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Sakebox
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
      	<div class="row">
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
      		<div class="col-sm-8">
      		
        		<?php get_sidebar(); ?>
      		
      		</div>
      	</div>
      </div>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>