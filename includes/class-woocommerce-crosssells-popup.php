<?php

/**
 * The core plugin class.
 * @since      1.0.0
 * @package    Woocommerce_Crosssells_Popup
 * @subpackage Woocommerce_Crosssells_Popup/includes
 * @author     Ahmad Shyk <ahmad.hassan.shaykh@gmail.com>
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Woocommerce_Crosssells_Popup {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Woocommerce_Crosssells_Popup_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'WCSP_VERSION' ) ) {
			$this->version = WCSP_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'woocommerce-crosssells-popup';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 * - Woocommerce_Crosssells_Popup_Loader. Orchestrates the hooks of the plugin.
	 * - Woocommerce_Crosssells_Popup_i18n. Defines internationalization functionality.
	 * - Woocommerce_Crosssells_Popup_Admin. Defines all hooks for the admin area.
	 * - Woocommerce_Crosssells_Popup_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woocommerce-crosssells-popup-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woocommerce-crosssells-popup-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-woocommerce-crosssells-popup-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-woocommerce-crosssells-popup-public.php';

		$this->loader = new Woocommerce_Crosssells_Popup_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Woocommerce_Crosssells_Popup_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Woocommerce_Crosssells_Popup_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Woocommerce_Crosssells_Popup_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'admin_menu' );
		$this->loader->add_action('admin_init', $plugin_admin, 'options_update');
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Woocommerce_Crosssells_Popup_Public( $this->get_plugin_name(), $this->get_version() );
		$options = get_option($this->plugin_name);
		$crosssells_popup                = $options['enable-crosssells-popup'];
		$crosssells_popup_shop_archive   = $options['shop-enable-crosssells-popup'];
		$crosssells_popup_single_product = $options['single-enable-crosssells-popup'];

		if($crosssells_popup==1) {

			$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
			$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

			if($crosssells_popup_shop_archive==1){
				$this->loader->add_action( 'woocommerce_init', $plugin_public, 'shop_archive_remove_default_add_to_cart' );
				$this->loader->add_action( 'woocommerce_after_shop_loop_item', $plugin_public, 'shop_archive_custom_add_to_cart' );
				$this->loader->add_action( 'woocommerce_after_shop_loop_item', $plugin_public, 'modal' );
			}
			if($crosssells_popup_single_product==1){
				$this->loader->add_action( 'wp_ajax_woocommerce_ajax_add_to_cart', $plugin_public, 'single_product_ajax_add_to_cart' );
				$this->loader->add_action( 'wp_ajax_nopriv_woocommerce_ajax_add_to_cart', $plugin_public, 'single_product_ajax_add_to_cart' );
				$this->loader->add_action( 'woocommerce_after_add_to_cart_button', $plugin_public, 'modal' );
			}
			$this->loader->add_filter( 'add_to_cart_fragments', $plugin_public, 'modal_footer_cart_content_fragment' );
			$this->loader->add_action( 'wp_head', $plugin_public, 'slider_in_modal_fix' );

		}

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Woocommerce_Crosssells_Popup_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
