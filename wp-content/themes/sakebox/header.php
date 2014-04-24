<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Sakebox
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php if(is_front_page() ) { 
  //Get URL for featured image to apply to inline CSS background
  $post_image_id = get_post_thumbnail_id($post_to_use->ID);
  if ($post_image_id) {
    $size = array(2000,1000);
    $thumbnail = wp_get_attachment_image_src( $post_image_id, $size, false);
    if ($thumbnail) (string)$thumbnail = $thumbnail[0];
  }
?>
<div class="hero" style="background:url('<?php echo $thumbnail ?>');background-size:cover;background-repeat:no-repeat;background-position: center center;">
  <div class="container">
  	<div class="row">
  		<div class="col-sm-12" style="height:100%;display:table;">
  		  <div class="heroback">
    		  <h1>A Monthly Box of<br class="hidden-xs"> Delicious Saké Samples</h1>
    		  <h3>Subscribe and get notified when we launch,<br class="hidden-xs"> or shop for full-size saké now!</h3>
    		  <br>
    		  <div class="row">
    		    <div class="col-xs-6">
    		      <button class="btn btn-lg btn-danger pull-right" data-toggle="modal" data-target="#subscribe">Subscribe</button>
    		    </div>
    		    <div class="col-xs-6">
    		      <a class="btn btn-lg btn-success pull-left" href="/shop">Shop now</a>
    		    </div>
    		  </div>
  		  </div>
  		</div>
  	</div>
  </div>
</div>
<?php } ?>

<div id="page" class="hfeed site">

	<header id="masthead" class="site-header" role="banner">
	
    <nav id="main-navigation" class="navbar navbar-default navbar-fixed-top <?php if(is_front_page() ) { ?>homeHeader<?php } ?>" role="navigation">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        
        <a class="navbar-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
          <img class="logoNormal" src="<?php bloginfo('template_directory'); ?>/img/sakebox_logo_b.png" />
          <img class="logoHome"   src="<?php bloginfo('template_directory'); ?>/img/sakebox_logo_w.png" />
        </a>
      </div>
      
      <?php
        wp_nav_menu( array(
          'menu'              => 'primary',
          'theme_location'    => 'primary',
          'depth'             => 2,
          'container'         => 'div',
          'container_class'   => 'collapse navbar-collapse navbar-ex1-collapse',
          'menu_class'        => 'nav navbar-nav navbar-right',
          'fallback_cb'       => 'wp_bootstrap_navwalker::fallback',
          'walker'            => new wp_bootstrap_navwalker())
        );
      ?>
    </nav>
	 
	</header><!-- #masthead -->

	<div id="content" class="site-content">
