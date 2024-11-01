<?php

/**
* Provide a admin area view for the plugin
*
* This file is used to markup the admin-facing aspects of the plugin.
*
* @link       https://ahmadshyk.com
* @since      1.0.0
*
* @package    Woocommerce_Crosssells_Popup
* @subpackage Woocommerce_Crosssells_Popup/admin/partials
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<?php if( isset($_GET['settings-updated']) ) { ?>
	<div class="notice notice-success is-dismissible">
		<h3><strong><?php _e('Settings saved.', $this->plugin_name); ?></strong></h3>
	</div>
<?php } ?>

<div class="wrap">
	<h1 class="woocommerce-crosssells-admin-title"><?php echo esc_html(get_admin_page_title()); ?></h1>
	<form method="post" name="woocommerce-crosssells-popup-options-form" action="options.php">
		<?php

        //Grab all options
		$options = get_option($this->plugin_name);

		if( $options['enable-crosssells-popup']==1 || $options['enable-crosssells-popup']==0 )
			$enable_crosssells_popup        = $options['enable-crosssells-popup'];
		else
			$enable_crosssells_popup        = 1;

		if( $options['shop-enable-crosssells-popup']==1 || $options['shop-enable-crosssells-popup']==0 )
			$shop_enable_crosssells_popup   = $options['shop-enable-crosssells-popup'];
		else
			$shop_enable_crosssells_popup   = 1;

		if( $options['single-enable-crosssells-popup']==1 || $options['single-enable-crosssells-popup']==0 )
			$single_enable_crosssells_popup = $options['single-enable-crosssells-popup'];
		else 
			$single_enable_crosssells_popup = 1;

		settings_fields($this->plugin_name);
		do_settings_sections($this->plugin_name);
		?>
		<fieldset>
			<legend class="screen-reader-text"><span>Enable CrossSells Popup</span></legend>
			<label for="<?php echo $this->plugin_name; ?>-enable-crosssells-popup">
				<input type="checkbox" id="<?php echo $this->plugin_name; ?>-enable-crosssells-popup" name="<?php echo $this->plugin_name; ?>[enable-crosssells-popup]" value="1" <?php checked($enable_crosssells_popup, 1); ?>/>
				<span><?php esc_attr_e('Enable CrossSells Popup', $this->plugin_name); ?></span>
			</label>
		</fieldset>

		<fieldset>
			<legend class="screen-reader-text"><span>Enable CrossSells Popup on Shop/Archive Pages</span></legend>
			<label for="<?php echo $this->plugin_name; ?>-shop-enable-crosssells-popup">
				<input type="checkbox" id="<?php echo $this->plugin_name; ?>-shop-enable-crosssells-popup" name="<?php echo $this->plugin_name; ?>[shop-enable-crosssells-popup]" value="1" <?php checked($shop_enable_crosssells_popup, 1); ?>/>
				<span><?php esc_attr_e('Enable CrossSells Popup on Shop/Archive Pages', $this->plugin_name); ?></span>
			</label>
		</fieldset>

		<fieldset>
			<legend class="screen-reader-text"><span>Enable CrossSells Popup on Single Product Pages</span></legend>
			<label for="<?php echo $this->plugin_name; ?>-single-enable-crosssells-popup">
				<input type="checkbox" id="<?php echo $this->plugin_name; ?>-single-enable-crosssells-popup" name="<?php echo $this->plugin_name; ?>[single-enable-crosssells-popup]" value="1" <?php checked($single_enable_crosssells_popup, 1); ?>/>
				<span><?php esc_attr_e('Enable CrossSells Popup on Single Product Pages', $this->plugin_name); ?></span>
			</label>
		</fieldset>
		
		<?php submit_button(); ?>
	</form>


	
	<div class="crosssellspopup-instructions">
		<h2><?php _e('How it Works', $this->plugin_name); ?></h2>
		<?php 
		$plugin_instructions = __('To make this plugin working, you need to <strong>Enable</strong> CrossSells Popup from this settings page. Then you need to add Crosssell products for each product, those crosssell products will appear in the popup after specific product added to cart. To <strong>Add Crossell Products</strong>,<br>1: Go to WooCommerce > Products and select the product on which you’d like to show an cross-sell.<br>2: Scroll down to the Product Data panel.<br>3: Select the Linked Products tab in the left menu.<br>4: Add the product you wish to link to by searching for it.<br>5: <strong>Update</strong>.', $this->plugin_name);
		?>
		<p>
			<?php echo $plugin_instructions; ?>
		</p>
	</div>

</div>