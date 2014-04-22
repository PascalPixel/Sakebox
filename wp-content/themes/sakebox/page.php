<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Sakebox
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
		
      <div class="hero" style="background:url('<?php echo $thumbnail ?>');background-size:cover;background-repeat:no-repeat;background-position: center center;">
        <div class="home_header">
          <a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><img src="<?php bloginfo('template_directory'); ?>/img/logo_w.png" width="270px" /></a>
          <ul class="menu pull-right">
            <?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav-menu' ) ); ?>
          </ul>
        </div>
        <div class="container">
        	<div class="row">
        		<div class="col-sm-12">
        		  <div class="heroback">
          		  <h1>A Monthly Box of<br> Delicious Saké Samples</h1>
          		  <h3>Subscribe and get notified when we launch,<br> or shop for full-size saké now!</h3>
          		  <br>
          		  <button class="btn btn-lg btn-danger" data-toggle="modal" data-target="#subscribe">Subscribe</button>
          		  <a class="btn btn-lg btn-success" href="/shop">Shop now</a>
        		  </div>
        		</div>
        	</div>
        </div>
      </div>

      <div class="container">
      	<div class="row">
      		<div class="col-sm-8">

      			<?php while ( have_posts() ) : the_post(); ?>
      
      				<?php get_template_part( 'content', 'page' ); ?>
      
      				<?php
      					// If comments are open or we have at least one comment, load up the comment template
      					if ( comments_open() || '0' != get_comments_number() ) :
      						comments_template();
      					endif;
      				?>
      
      			<?php endwhile; // end of the loop. ?>

      		</div>
      		<div class="col-sm-8">
      		
        		<?php get_sidebar(); ?>
      		
      		</div>
      	</div>
      </div>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>