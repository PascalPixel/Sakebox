<?php
/**
 * Template Name: Full-Width
 *
 * @package _
 * @subpackage _
 * @since _
 */

get_header(); 

?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

      <div class="container">
      	<div class="row">
      		<div class="col-sm-12">

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
      		
        		<?php get_sidebar(); ?>
      		
      		</div>
      	</div>
      </div>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>