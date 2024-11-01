<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://ahmadshyk.com
 * @since      1.0.0
 *
 * @package    Woocommerce_Crosssells_Popup
 * @subpackage Woocommerce_Crosssells_Popup/public
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Woocommerce_Crosssells_Popup_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woocommerce-crosssells-popup-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		$options = get_option($this->plugin_name);
		$crosssells_popup_single_product = $options['single-enable-crosssells-popup'];

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woocommerce-crosssells-popup-public.js', array( 'jquery' ), $this->version, false );
		if($crosssells_popup_single_product==1){
			wp_enqueue_script( 'single-add-to-cart', plugin_dir_url( __FILE__ ) . 'js/single-add-to-cart-ajax.js', array( 'jquery' ), $this->version, false );
		}

	}

	/**
	 * Remove Default Add to Cart Button on Shop/Archive Pages
	 * We will add new class in custom button in the below function that will be used to trigger popup.
	 *
	 * @since    1.0.0
	 */
	public function shop_archive_remove_default_add_to_cart(){

		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);

	}

	/**
	 * Function to add custom Add to Cart button on Shop/Archive Pages
	 * It will add an extra class to button to trigger Popup
	 * It will only enable if user enables CrossSells Popup on Shop/Archive pages from Plugin Options
	 *
	 * @since    1.0.0
	 */
	public function shop_archive_custom_add_to_cart(){

		global $product;
		if( $product->is_type( 'simple' ) ){
			$ajax_class = 'ajax_add_to_cart open-crosssells-modal';
		}
		else{
			$ajax_class = 'not_ajax_add_to_cart';
		}
		echo apply_filters( 'woocommerce_loop_add_to_cart_link',
			sprintf( '<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" data-quantity="%s" class="button %s product_type_%s %s">%s</a>',
				esc_url( $product->add_to_cart_url() ),
				esc_attr( $product->get_id() ),
				esc_attr( $product->get_sku() ),
				esc_attr( isset( $quantity ) ? $quantity : 1 ),
				$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
				esc_attr( $product->get_type() ),
				esc_attr( $ajax_class ),
				esc_html( $product->add_to_cart_text() )
			),
			$product );

	}

	/**
	 * Function to include Popup Content
	 *
	 * @since    1.0.0
	 */
	public function modal(){
		include( 'partials/woocommerce-crosssells-popup-public-display.php' );
	}

	/**
	 * Function to enable ajax Add to Cart on Single Product page
	 * Only work if user selected CrossSells Popups on Single Product Page
	 *
	 * @since    1.0.0
	 */
	function single_product_ajax_add_to_cart() {

		$product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
		$quantity = empty($_POST['quantity']) ? 1 : wc_stock_amount($_POST['quantity']);
		$variation_id = absint($_POST['variation_id']);
		$passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
		$product_status = get_post_status($product_id); 

		if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity, $variation_id) && 'publish' === $product_status) {

			do_action('woocommerce_ajax_added_to_cart', $product_id);

			if ('yes' === get_option('woocommerce_cart_redirect_after_add')) {
				wc_add_to_cart_message(array($product_id => $quantity), true);
			}

			WC_AJAX :: get_refreshed_fragments();
		} else {

			$data = array(
				'error' => true,
				'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id));

			echo wp_send_json($data);
		}

		wp_die();
	}

    /**
	 * Function to update cart content and total with ajax
	 * Visible on CrossSells Popup Footer
	 *
	 * @since    1.0.0
	 */
    function modal_footer_cart_content_fragment( $fragments ) {

    	global $woocommerce; 

    	ob_start(); 

    	?>

    	<a class="cart-contents" href="<?php echo $woocommerce->cart->get_cart_url(); ?>" title="<?php _e('View your shopping cart', 'woothemes'); ?>"><?php echo sprintf(_n('%d item', '%d items', $woocommerce->cart->cart_contents_count, 'woothemes'), $woocommerce->cart->cart_contents_count);?> - <?php echo $woocommerce->cart->get_cart_total(); ?></a>

    	<?php 

    	$fragments['a.cart-contents'] = ob_get_clean();

    	return $fragments; 

    }    

    function slider_in_modal_fix(){ ?>
    	<style type="text/css">
    	.crosssells-modal {
    		display: block !important;
    		visibility: hidden !important;
    		overflow-y: hidden !important;
    	}
    	.crosssells-modal.in {
    		visibility: visible !important;
    	}
    	.crosssells-modal .modal-dialog{
    		z-index: 99999 !important;
    	}
    </style>
<?php }

}