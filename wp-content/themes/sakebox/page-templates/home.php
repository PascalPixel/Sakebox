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
  <div class="container">
  	<div class="row">
  		<div class="col-sm-12">
  		  <div class="heroback">
    		  <h1>A Monthly Box of<br class="hidden-xs"> Delicious Saké Samples</h1>
    		  <h3>Subscribe and get notified when we launch,<br class="hidden-xs"> or shop for full-size saké now!</h3>
    		  <br>
    		  <button class="btn btn-lg btn-danger" data-toggle="modal" data-target="#subscribe">Subscribe</button>
    		  <a class="btn btn-lg btn-success" href="/shop">Shop now</a>
  		  </div>
  		</div>
  	</div>
  </div>
</div>

<!-- <div class="newsbar"><div class="container"><div class="row"><div class="col-sm-12"><span style="float:left;margin-right:5px;font-weight:bold;">News | </span> </div></div></div></div> -->

<div class="container">
  <div class="row">
		<div class="col-sm-12">
		  <div class="divider"><hr><span>How it works</span></div>
		</div>
  </div>
	<div class="row">
		<div class="col-sm-4">
		  <div class="stepsblock steps1">
		    <div class="stepsnumber">1</div>
		    <div class="stepstext">
  		    <div class="stepstop">Subscribe to Sakébox</div>
  		    <div class="stepsbottom">through the signup</div>
		    </div>
		  </div>
		</div>
		<div class="col-sm-4">
		  <div class="stepsblock steps2">
		    <div class="stepsnumber">2</div>
		    <div class="stepstext">
  		    <div class="stepstop">Receive your box</div>
  		    <div class="stepsbottom">every month at home</div>
		    </div>
		  </div>
		</div>
		<div class="col-sm-4">
		  <div class="stepsblock steps3">
		    <div class="stepsnumber">3</div>
		    <div class="stepstext">
  		    <div class="stepstop">Order full-size bottles</div>
  		    <div class="stepsbottom">of your favorites</div>
		    </div>
		  </div>
		</div>
	</div>
  <div class="row">
		<div class="col-sm-12">
		  <div class="divider"><hr><span>Extra info of sorts</span></div>
		</div>
  </div>
	<div class="row">
		<div class="col-sm-6">
		  <?php the_content(); ?>
		</div>
		<div class="col-sm-6">
		  <img class="imgwrap" src="<?php bloginfo('template_directory'); ?>/img/barrels.jpg"/>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
		  <div class="divider"><hr><span>Featured Saké</span></div>
		</div>
  </div>
	<div class="row">
		<div class="col-sm-4 col-md-3 col-lg-2">
  		<div class="shop-item imgwrap">
  		  <div class="shop-item-hover">
    		  <h4 class="price">¥923</h4>
    		  <div class="shop-item-name">
    		    <h5>Hishimasamune</h5>
    		    <h5 class="thin">超辛口純米酒</h5>
    		  </div>
  		  </div>
  		  <img src="<?php bloginfo('template_directory'); ?>/img/bottles/bottle1.png"/>
  		</div>
		</div>
		<div class="col-sm-4 col-md-3 col-lg-2">
		  <div class="shop-item imgwrap">
		    <div class="shop-item-hover">
    		  <h4 class="price">¥923</h4>
    		  <div class="shop-item-name">
      		  <h5>Tea Umeshu</h5>
      		  <h5 class="thin">お茶入り梅酒</h5>
    		  </div>
		    </div>
  		  <img src="<?php bloginfo('template_directory'); ?>/img/bottles/bottle2.png"/>
		  </div>
		</div>
		<div class="col-sm-4 col-md-3 col-lg-2">
		  <div class="shop-item imgwrap">
		    <div class="shop-item-hover">
    		  <h4 class="price">¥923</h4>
    		  <div class="shop-item-name">
      		  <h5>Yamahai Daiginjyo</h5>
      		  <h5 class="thin">山廃大吟醸</h5>
    		  </div>
		    </div>
  		  <img src="<?php bloginfo('template_directory'); ?>/img/bottles/bottle3.png"/>
		  </div>
		</div>
		<div class="col-sm-4 col-md-3 col-lg-2">
		  <div class="shop-item imgwrap">
		    <div class="shop-item-hover">
    		  <h4 class="price">¥923</h4>
    		  <div class="shop-item-name">
      		  <h5>Nansen</h5>
      		  <h5 class="thin">南泉</h5>
    		  </div>
		    </div>
  		  <img src="<?php bloginfo('template_directory'); ?>/img/bottles/bottle4.png"/>
		  </div>
		</div>
		<div class="col-sm-4 col-md-3 col-lg-2">
		  <div class="shop-item imgwrap">
		    <div class="shop-item-hover">
    		  <h4 class="price">¥923</h4>
    		  <div class="shop-item-name">
      		  <h5>Hishimasamune</h5>
      		  <h5 class="thin">超辛口純米酒</h5>
    		  </div>
		    </div>
  		  <img src="<?php bloginfo('template_directory'); ?>/img/bottles/bottle5.png"/>
		  </div>
		</div>
		<div class="col-sm-4 col-md-3 col-lg-2">
		  <div class="shop-item imgwrap">
		    <div class="shop-item-hover">
    		  <h4 class="price">¥923</h4>
    		  <div class="shop-item-name">
      		  <h5>Tea Umeshu</h5>
      		  <h5 class="thin">お茶入り梅酒</h5>
    		  </div>
		    </div>
  		  <img src="<?php bloginfo('template_directory'); ?>/img/bottles/bottle6.png"/>
		  </div>
		</div>
		<div class="col-sm-4 col-md-3 col-lg-2">
		  <div class="shop-item imgwrap">
		    <div class="shop-item-hover">
    		  <h4 class="price">¥646</h4>
    		  <div class="shop-item-name">
      		  <h5>Yamahai Daiginjyo</h5>
      		  <h5 class="thin">山廃大吟醸</h5>
    		  </div>
		    </div>
  		  <img src="<?php bloginfo('template_directory'); ?>/img/bottles/bottle7.png"/>
		  </div>
		</div>
		<div class="col-sm-4 col-md-3 col-lg-2">
		  <div class="shop-item imgwrap">
		    <div class="shop-item-hover">
    		  <h4 class="price">¥1233</h4>
    		  <div class="shop-item-name">
      		  <h5>Nansen</h5>
      		  <h5 class="thin">南泉</h5>
    		  </div>
		    </div>
  		  <img src="<?php bloginfo('template_directory'); ?>/img/bottles/bottle8.png"/>
		  </div>
		</div>
	</div>
</div>

<?php get_footer(); ?>