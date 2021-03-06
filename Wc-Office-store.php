<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.wplauncher.com
 * @since             1.0.0
 * @package           Wc_Office_store
 *
 * @wordpress-plugin
 * Plugin Name:       Woocommerce Office Stores Support
 * Plugin URI:        https://www.wplauncher.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Ben Shadle
 * Author URI:        https://www.wplauncher.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wc-office-store
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'Wc_Office_store_VERSION', '1.0.0' );
define( 'WC_OFFICE_STORE_DIR', plugin_dir_path( __FILE__ ) );

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( is_plugin_active( 'woocommerce/woocommerce.php') ) {
  // Do what you want in case woocommerce is installed
  /**
	 * The code that runs during plugin activation.
	 * This action is documented in includes/class-wc-office-store-activator.php
	 */
	function activate_Wc_Office_store() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-wc-office-store-activator.php';

		Wc_Office_store_Activator::activate();
	}

	/**
	 * The code that runs during plugin deactivation.
	 * This action is documented in includes/class-wc-office-store-deactivator.php
	 */
	function deactivate_Wc_Office_store() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-wc-office-store-deactivator.php';
		Wc_Office_store_Deactivator::deactivate();
	}


	register_activation_hook( __FILE__, 'activate_Wc_Office_store' );

	register_deactivation_hook( __FILE__, 'deactivate_Wc_Office_store' );

	/**
	 * 
	 * The core plugin class that is used to define internationalization,
	 * admin-specific hooks, and public-facing site hooks.
	 */
	require plugin_dir_path( __FILE__ ) . 'includes/class-wc-office-store.php';

		
	/**
	 * Begins execution of the plugin.
	 *
	 * Since everything within the plugin is registered via hooks,
	 * then kicking off the plugin from this point in the file does
	 * not affect the page life cycle.
	 *
	 * @since    1.0.0
	 */
	function run_Wc_Office_store() {
		$plugin = new Wc_Office_store();
		$plugin->run();

	}
	run_Wc_Office_store();
} else {
	// show admin notice if WooCommerce is not activated
	function Wc_Office_store_admin_notice(){ ?>
		<div class="notice notice-error is-dismissible">
			<p><b>WooCommerce</b> is not activated, please activate it to use <b>Woocommerce Office Stores Support Plugin</b></p>
		</div>
	<?php
	}
	add_action('admin_notices', 'Wc_Office_store_admin_notice');
}