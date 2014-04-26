<?php
/**
 * Template Name: Home
 *
 * @package _
 * @subpackage _
 * @since _
 */
 
get_header();

?>

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
		<div class="col-sm-12">
		  <div class="howitworks">
    	  <!-- Begin MailChimp Signup Form -->
        <form action="http://superpencil.us7.list-manage1.com/subscribe/post?u=43a0823be5d7c532fa4f7f239&amp;id=65b3ce1c66" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate="">
          <div id="mc_embed_signup" class="row">
            <div          class="col-sm-5">
            	<h4>Get notified when we launch</h4>
            </div>
            <div class="col-xs-8 col-sm-4 col-md-5">
            	<input type="email" value="" name="EMAIL" class="email form-control col-sm-8" id="mce-EMAIL" placeholder="Your e-mail" required="">
              <div style="position: absolute; left: -5000px;"><input type="text" name="b_43a0823be5d7c532fa4f7f239_65b3ce1c66" value=""></div>
            </div>
            <div class="col-xs-4 col-sm-3 col-md-2">
            	<input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="btn btn-success btn-block">
            </div>
          </div>
        </form>
        <!--End mc_embed_signup-->
		  </div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
		  <div class="divider"><hr><span><?php _e( 'Featured Products', 'woothemes' ); ?></span></div>
		</div>
  </div>
	<div class="row featured-products">
    <?php
    $args = array( 'post_type' => 'product', 'posts_per_page' => get_option( 'woo_featured_product_limit' ), 'meta_key' => '_featured', 'meta_value' => 'yes' );
    $loop = new WP_Query( $args );
    while ( $loop->have_posts() ) : $loop->the_post(); $_product;
    if ( function_exists( 'get_product' ) ) {
    	$_product = get_product( $loop->post->ID );
    } else {
    	$_product = new WC_Product( $loop->post->ID );
    }
    ?>
      <div class="col-sm-4 col-md-3 col-lg-2">
        <?php woocommerce_show_product_sale_flash( $post, $_product ); ?>
      	<a href="<?php echo get_permalink( $loop->post->ID ) ?>" title="<?php echo esc_attr($loop->post->post_title ? $loop->post->post_title : $loop->post->ID); ?>">
          <div class="shop-item imgwrap">
            <div class="shop-item-hover">
          	  <h4><span class="price"><?php echo $_product->get_price_html(); ?></span></h4>
          	  <div class="shop-item-name">
          	    <h5><?php the_title(); ?></h5>
          	    <h5 class="thin"><?php do_action( 'woocommerce_after_shop_loop_item' ); ?></h5>
          	  </div>
            </div>
              <?php if ( has_post_thumbnail( $loop->post->ID ) ) echo get_the_post_thumbnail( $loop->post->ID, 'shop_thumbnail' ); else echo '<img src="' . $woocommerce->plugin_url() . '/assets/images/placeholder.png" alt="Placeholder" width="' . wc_get_image_size( 'shop_thumbnail_image_width' ) . 'px" height="' . wc_get_image_size( 'shop_thumbnail_image_height' ) . 'px" />'; ?>
          </div>
        </a>
      </div>
		<?php endwhile; ?>
  </div><!--.row-->
  <div class="row">
		<div class="col-sm-12">
		  <div class="divider"><hr><span><?php _e( 'Recent Products', 'woothemes' ); ?></span></div>
		</div>
  </div>
  <div id="content" class="col-full">
    <div id="main" class="col-left">
      <div class="product-gallery">
      	<?php echo do_shortcode( '[recent_products per_page="12" columns="3"]' ); ?>
      </div><!--/.product-gallery-->
    </div><!-- /#main -->
  </div><!-- /#content -->
</div>

<?php get_footer(); ?>
