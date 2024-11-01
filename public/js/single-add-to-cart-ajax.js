 (function ($) {
 	
 	jQuery(document).on('click', '.single_add_to_cart_button', function (e) {
 		e.preventDefault();
 		
 		var $thisbutton = jQuery(this),
 		$form = $thisbutton.closest('form.cart'),
 		id = $thisbutton.val(),
 		product_qty = $form.find('input[name=quantity]').val() || 1,
 		product_id = $form.find('input[name=product_id]').val() || id,
 		variation_id = $form.find('input[name=variation_id]').val() || 0;
 		
 		var data = {
 			action: 'woocommerce_ajax_add_to_cart',
 			product_id: product_id,
 			product_sku: '',
 			quantity: product_qty,
 			variation_id: variation_id,
 		};
 		
 		jQuery(document.body).trigger('adding_to_cart', [$thisbutton, data]);
 		
 		jQuery.ajax({
 			type: 'post',
 			url: wc_add_to_cart_params.ajax_url,
 			data: data,
 			beforeSend: function (response) {
 				$thisbutton.removeClass('added').addClass('loading');
 			},
 			complete: function (response) {
 				$thisbutton.addClass('added product-added').removeClass('loading');
 			},
 			success: function (response) {
 				
 				if (response.error & response.product_url) {
 					window.location = response.product_url;
 					return;
 				} else {
 					jQuery(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $thisbutton]);
 				}
 				after_add_to_cart_single();
 			},
 		});
 		
 		return false;
 	});

	function after_add_to_cart_single(){
		if( jQuery( '.woocommerce-variation-add-to-cart' ).length && !jQuery('.single_add_to_cart_button').hasClass('product-added') ){
 					product_id = jQuery('input[name=add-to-cart]').attr('value');
 					jQuery("#CrossSellsModal-" + product_id).modal('show');
 					jQuery('.crosssells-crousel-' + product_id).slick({
 						infinite: true,
 						slidesToShow: 4,
 						slidesToScroll: 1,
 						prevArrow: $('.crosssell-prev'),
 						nextArrow: $('.crosssell-next'),
 						responsive: [
 						{
 							breakpoint: 1024,
 							settings: {
 								slidesToShow: 3,
 								slidesToScroll: 1,
 							}
 						},
 						{
 							breakpoint: 600,
 							settings: {
 								slidesToShow: 2,
 								slidesToScroll: 1
 							}
 						},
 						{
 							breakpoint: 480,
 							settings: {
 								slidesToShow: 1,
 								slidesToScroll: 1
 							}
 						}
 						]
 					});

 				}
 				else if( !jQuery('.single_add_to_cart_button').hasClass('product-added') ) {
 					product_id = jQuery('.single_add_to_cart_button').attr('value');
 					jQuery("#CrossSellsModal-" + product_id).modal('show');
 					jQuery('.crosssells-crousel-' + product_id).slick({
 						infinite: true,
 						slidesToShow: 4,
 						slidesToScroll: 1,
 						prevArrow: $('.crosssell-prev'),
 						nextArrow: $('.crosssell-next'),
 						responsive: [
 						{
 							breakpoint: 1024,
 							settings: {
 								slidesToShow: 3,
 								slidesToScroll: 1,
 							}
 						},
 						{
 							breakpoint: 600,
 							settings: {
 								slidesToShow: 2,
 								slidesToScroll: 1
 							}
 						},
 						{
 							breakpoint: 480,
 							settings: {
 								slidesToShow: 1,
 								slidesToScroll: 1
 							}
 						}
 						]
 					});
 				}
	}

 })(jQuery);