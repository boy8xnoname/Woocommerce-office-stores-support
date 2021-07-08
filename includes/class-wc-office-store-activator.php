<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.wplauncher.com
 * @since      1.0.0
 *
 * @package    Wc_Office_store
 * @subpackage Wc_Office_store/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wc_Office_store
 * @subpackage Wc_Office_store/includes
 * @author     Ben Shadle <benshadle@gmail.com>
 */
class Wc_Office_store_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/functions/function.show-group-product-on-summary-block.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/functions/function.suggest-product-upsell-checkout.php';
	}

}
