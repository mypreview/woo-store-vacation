<?php
/**
 * The `Woo Store Vacation` bootstrap file.
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * You can redistribute this plugin/software and/or modify it under
 * the terms of the GNU General Public License as published by the
 * Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * @link https://www.mypreview.one
 *
 * @since 1.0.0
 *
 * @package woo-store-vacation
 *
 * @copyright © 2015 - 2024 MyPreview. All Rights Reserved.
 *
 * @wordpress-plugin
 * Plugin Name: Woo Store Vacation
 * Plugin URI: https://mypreview.one/woo-store-vacation
 * Description: Pause your store during vacations by scheduling specific dates and display a customizable notice to visitors.
 * Version: 1.9.6
 * Author: MyPreview
 * Author URI: https://mypreview.one/woo-store-vacation
 * Requires at least: 5.9
 * Requires PHP: 7.4
 * License: GPL-3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: woo-store-vacation
 * Domain Path: /languages
 *
 * WC requires at least: 5.5
 * WC tested up to: 9.9
 */

use Woo_Store_Vacation\Plugin;
use WC_Install_Notice\Nag;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit;
}

/**
 * Loads the PSR-4 autoloader implementation.
 *
 * @since 1.9.0
 *
 * @return void
 */
require_once untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/vendor/autoload.php';

/**
 * Initialize the plugin.
 *
 * @since 1.9.0
 *
 * @return null|Plugin
 */
function woo_store_vacation() {

	static $instance;

	if ( is_null( $instance ) ) {
		$version  = get_file_data( __FILE__, array( 'Version' => 'Version' ), false );
		$instance = new Plugin( $version['Version'] ?? '1.0.0', __FILE__ );
	}

	return $instance;
}

/**
 * Load the plugin after all plugins are loaded.
 *
 * @since 1.9.0
 *
 * @return void
 */
function woo_store_vacation_load() {

	// Fetch the instance.
	woo_store_vacation();
}

if ( ! (
		( new Nag() )
		->set_file_path( __FILE__ )
		->set_plugin_name( 'Woo Store Vacation' )
		->does_it_requires_nag()
	)
) {

	add_action( 'woocommerce_loaded', 'woo_store_vacation_load', 20 );

	// Register activation and deactivation hooks.
	register_activation_hook( __FILE__, array( 'Woo_Store_Vacation\\Installer', 'activate' ) );
	register_deactivation_hook( __FILE__, array( 'Woo_Store_Vacation\\Installer', 'deactivate' ) );
}
