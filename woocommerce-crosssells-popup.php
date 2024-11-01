<?php

/**
 * @link              https://ahmadshyk.com
 * @since             1.0.0
 * @package           Woocommerce_CrossSells_Popup
 *
 * @wordpress-plugin
 * Plugin Name:       Woocommerce CrossSells Popup
 * Plugin URI:        http://wpassets.com
 * Description:       Sell more with CrossSells. Show a similar products popup whenever your customer adds a product in cart.
 * Version:           1.0.0
 * Author:            Ahmad Shyk
 * Author URI:        https://ahmadshyk.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woocommerce-crosssells-popup
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'WCSP_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 */
function activate_woocommerce_crosssells_popup() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-crosssells-popup-activator.php';
	Woocommerce_Crosssells_Popup_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_woocommerce_crosssells_popup() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-crosssells-popup-deactivator.php';
	Woocommerce_Crosssells_Popup_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_woocommerce_crosssells_popup' );
register_deactivation_hook( __FILE__, 'deactivate_woocommerce_crosssells_popup' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-crosssells-popup.php';

/**
 * Begins execution of the plugin.
 * @since    1.0.0
 */

function admin_notice_woocommerce_crosssells_popup(){ ?>
		<div class="error">
				<p><?php _e( 'WooCommerce CrossSells Popup Plugin is activated but not effective. It requires WooCommerce in order to work.' ); ?></p>
			</div>
<?php	
}

function run_woocommerce_crosssells_popup() {

	if ( ! function_exists( 'WC' ) ) {
		add_action( 'admin_notices', 'admin_notice_woocommerce_crosssells_popup' );
	}
	else{
	$plugin = new Woocommerce_Crosssells_Popup();
	$plugin->run();
}
}
add_action( 'plugins_loaded', 'run_woocommerce_crosssells_popup', 11 );

//Add settings link on plugin page
function wcsp_settings_link($links) { 
  $settings_link = '<a href="admin.php?page=woocommerce-crosssells-popup">Settings</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}
 
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'wcsp_settings_link' );