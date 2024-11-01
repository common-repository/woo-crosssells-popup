<?php

	/**
	 * The admin-specific functionality of the plugin.
	 *
	 * @link       https://ahmadshyk.com
	 * @since      1.0.0
	 *
	 * @package    Woocommerce_Crosssells_Popup
	 * @subpackage Woocommerce_Crosssells_Popup/admin
	 */

// If this file is called directly, abort.
	if ( ! defined( 'WPINC' ) ) {
		die;
	}

	class Woocommerce_Crosssells_Popup_Admin {

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
		 * @param      string    $plugin_name       The name of this plugin.
		 * @param      string    $version    The version of this plugin.
		 */
		public function __construct( $plugin_name, $version ) {

			$this->plugin_name = $plugin_name;
			$this->version = $version;

		}

		/**
		 * Register the stylesheets for the admin area.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_styles() {

			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woocommerce-crosssells-popup-admin.css', array(), $this->version, 'all' );

		}

		/**
		 * Register the JavaScript for the admin area.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_scripts() {

			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woocommerce-crosssells-popup-admin.js', array( 'jquery' ), $this->version, false );

		}

		/**
	     * Register the administration menu for this plugin into the WordPress Dashboard menu under Woocommerce.
	     *
	     * @since    1.0.0
	     */
		public function admin_menu() {

			$page_title = 'Woocommerce CrossSells Popup';
			$menu_title = 'CrossSells Popup';
			$capability = 'manage_options';
			$slug = $this->plugin_name;
			$callback = array($this, 'setup_page');
			add_submenu_page('woocommerce',$page_title, $menu_title, $capability, $slug, $callback);

		}

		/**
	     * Add settings action link to the plugins page.
	     *
	     * @since    1.0.0
	     */
		
	    /**
	     * Render the settings page for this plugin.
	     *
	     * @since    1.0.0
	     */
	    public function setup_page() {
	    	include_once( 'partials/woocommerce-crosssells-popup-admin-display.php' );
	    }

	    public function options_update() {
	    	register_setting($this->plugin_name, $this->plugin_name, array($this, 'validate'));
	    }

	    public function validate($input) {
    // All checkboxes inputs        
	    	$valid = array();
	    	
    //Quote title
	    	$valid['enable-crosssells-popup'] = (isset($input['enable-crosssells-popup']) && !empty($input['enable-crosssells-popup'])) ? 1 : 0;
	    	$valid['shop-enable-crosssells-popup'] = (isset($input['shop-enable-crosssells-popup']) && !empty($input['shop-enable-crosssells-popup'])) ? 1 : 0;
	    	$valid['single-enable-crosssells-popup'] = (isset($input['single-enable-crosssells-popup']) && !empty($input['single-enable-crosssells-popup'])) ? 1 : 0;
    //return 1;
	    	return $valid;
	    }
	}