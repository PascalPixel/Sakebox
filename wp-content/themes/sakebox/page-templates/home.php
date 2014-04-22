<?php
/**
 * Template Name: Home
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
    		<div class="col-sm-12">
    		  <?php the_content(); ?>
    		</div>
    	</div>
    </div>
    
    <div class="footer">
      <div class="container">
      	<div class="row">
      		<div class="col-sm-12">
        		© Copyright 2014 Rinkya, LLC
      		</div>
      	</div>
      </div>
    </div>
    
    <div class="modal fade" id="subscribe" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel">Modal title</h4>
          </div>
          <div class="modal-body">
            <!-- Begin MailChimp Signup Form -->
            <link href="//cdn-images.mailchimp.com/embedcode/classic-081711.css" rel="stylesheet" type="text/css">
            <div id="mc_embed_signup">
            <form action="http://superpencil.us7.list-manage.com/subscribe/post?u=43a0823be5d7c532fa4f7f239&amp;id=65b3ce1c66" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
            	
            <div class="mc-field-group">
            	<label for="mce-EMAIL">Email Address </label>
            	<input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL">
            </div>
            	<div id="mce-responses" class="clear">
            		<div class="response" id="mce-error-response" style="display:none"></div>
            		<div class="response" id="mce-success-response" style="display:none"></div>
            	</div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                <div style="position: absolute; left: -5000px;"><input type="text" name="b_43a0823be5d7c532fa4f7f239_65b3ce1c66" value=""></div>
            	<div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
            </form>
            </div>
            <!--End mc_embed_signup-->
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php wp_footer(); ?>