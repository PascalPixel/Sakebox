<?php
/**
 * Mini-cart
 *
 * Contains the markup for the mini-cart, used by the cart widget
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce;
?>

<?php do_action( 'woocommerce_before_mini_cart' ); ?>

<ul class="cart_list product_list_widget <?php echo $args['list_class']; ?>">

	<?php if ( sizeof( WC()->cart->get_cart() ) > 0 ) : ?>

		<?php
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {

					$product_name  = apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key );
					$thumbnail     = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
					$product_price = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );

					?>
					<li>
					  <div class="row">
  					  <div class="col-xs-3">
    						<a href="<?php echo get_permalink( $product_id ); ?>">
    							<?php echo str_replace( array( 'http:', 'https:' ), '', $thumbnail ); ?>
    						</a>
  					  </div>
              <div class="col-xs-9">
    						<a href="<?php echo get_permalink( $product_id ); ?>"><?php echo $product_name; ?></a>
    						<?php echo WC()->cart->get_item_data( $cart_item ); ?>
    						<?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<br><span class="quantity">' . sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ) . '</span>', $cart_item, $cart_item_key ); ?>
              </div>
					  </div>
					</li>
					<?php
				}
			}
		?>

	<?php else : ?>

		<li class="empty"><?php _e( 'No products in the cart.', 'woocommerce' ); ?></li>

	<?php endif; ?>

</ul><!-- end product list -->

<?php if ( sizeof( WC()->cart->get_cart() ) > 0 ) : ?>

	<p class="total"><?php _e( 'Subtotal', 'woocommerce' ); ?>:</p> <h3 style="margin-top:0;"><?php echo WC()->cart->get_cart_subtotal(); ?></h3>

	<?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>
  
  <div class="foot">
  	<div class="row">
  	  <div class="col-xs-6">
    		<a href="<?php echo WC()->cart->get_cart_url(); ?>" class="btn btn-default btn-block wc-forward"><?php _e( 'View Cart', 'woocommerce' ); ?></a>
  	  </div>
  	  <div class="col-xs-6">
    		<a href="<?php echo WC()->cart->get_checkout_url(); ?>" class="btn btn-success btn-block checkout wc-forward"><?php _e( 'Checkout', 'woocommerce' ); ?></a>
  	  </div>
  	</div>
  </div>

<?php endif; ?>

<?php do_action( 'woocommerce_after_mini_cart' ); ?>