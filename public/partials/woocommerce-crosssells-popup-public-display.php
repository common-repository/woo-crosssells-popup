<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://ahmadshyk.com
 * @since      1.0.0
 *
 * @package    Woocommerce_Crosssells_Popup
 * @subpackage Woocommerce_Crosssells_Popup/public/partials
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<?php 
$crosssellProductIds   =   get_post_meta( get_the_ID(), '_crosssell_ids' );
$crosssellProductIds    =   $crosssellProductIds[0];
global $woocommerce;
?>

<?php if( $crosssellProductIds ) : ?>

	<div class="modal fade crosssells-modal" id="CrossSellsModal-<?php the_ID(); ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header crosssells-header">
					<div class="success-message">
						<?php the_title(); ?> <?php _e('added to cart successfully.', $this->plugin_name) ?>
						<?php $continue_shopping = __('Continue Shopping', $this->plugin_name); ?>
						<?php echo '<a class="crosssells-continue-shopping" href="'. get_permalink( wc_get_page_id( 'shop' ) ) .'">'. $continue_shopping .'</a>'; ?> 
					</div><!--success-message-->
					<!--<div class="header-cart-count"><?php // echo WC()->cart->get_cart_contents_count(); ?></div>-->
				</div>
				<div class="modal-body">
					<h1 class="crosssells-heading"><?php _e('Products that you may like...', $this->plugin_name); ?></h1>
					<div class="crosssells-crousel-<?php the_ID(); ?>">
							<?php foreach( $crosssellProductIds as $crosssellProductId ) : ?>
								<?php $product = wc_get_product( $crosssellProductId ); ?>
								<?php $product_image = get_the_post_thumbnail( $crosssellProductId, 'woocommerce_thumbnail' ) ?>
								<div>
									<div class="crosssell-product-img">
										<?php if( $product_image ) : echo $product_image; endif; ?>
									</div><!--crosssell-product-img-->  
									<div class="crosssell-product-info">
										<a class="crosssell-product-title" href="<?php the_permalink( $crosssellProductId ); ?>"><h2><?php echo get_the_title( $crosssellProductId ); ?></h2></a>
										<span class="price"><?php echo $product->get_price_html(); ?></span>
									</div>
									<div class="crosssell-add-to-cart">
										<?php
										echo apply_filters( 'woocommerce_loop_add_to_cart_link',
											sprintf( '<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" data-quantity="%s" class="button %s product_type_%s ajax_add_to_cart crosssells-atc">%s</a>',
												esc_url( $product->add_to_cart_url() ),
												esc_attr( $product->get_id() ),
												esc_attr( $product->get_sku() ),
												esc_attr( isset( $quantity ) ? $quantity : 1 ),
												$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
												esc_attr( $product->get_type() ),
												esc_html( $product->add_to_cart_text() )
											),
											$product );
											?>
										</div><!--crosssell-add-to-cart-->
									</div>
								<?php endforeach; ?>
						</div><!--crosssells-crousel-->

						<div class="crosssell-prev crosssell-nav">
							<span>&larr;</span>
						</div><!--crosssell-prev-->

						<div class="crosssell-next crosssell-nav">
							<span>&rarr;</span>
						</div><!--crosssell-next-->
					</div>
					<div class="modal-footer">
						<a class="cart-contents" href="<?php echo $woocommerce->cart->get_cart_url(); ?>" title="<?php _e('View your shopping cart', 'woothemes'); ?>">
							<?php echo sprintf(_n('%d item', '%d items', $woocommerce->cart->cart_contents_count, 'woothemes'), $woocommerce->cart->cart_contents_count);?> - <?php echo $woocommerce->cart->get_cart_total(); ?>

						</a>
						<?php do_action( 'woocommerce_proceed_to_checkout' ); ?>
					</div>
				</div>
			</div>
		</div>

		<?php endif; ?>