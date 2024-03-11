<?php
/**
 * The plugin menu class.
 *
 * @since 1.0.0
 *
 * @package woo-store-vacation
 */

namespace Woo_Store_Vacation\Admin;

use Woo_Store_Vacation\Helper;

/**
 * Menu class.
 */
class Menu {

	/**
	 * Setup hooks and filters.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function setup() {

		add_action( 'admin_menu', array( $this, 'add_submenu_page' ), 999 );
	}

	/**
	 * Create plugin options page.
	 * Backward compatibility for old location of the plugin’s settings page.
	 *
	 * @since 1.0.0
	 * @deprecated 1.8.0
	 *
	 * @return void
	 */
	public function add_submenu_page() {

		// Add the submenu page.
		add_submenu_page(
			'woocommerce',
			esc_html_x( 'Woo Store Vacation', 'plugin name', 'woo-store-vacation' ),
			esc_html_x( 'Store Vacation', 'menu title', 'woo-store-vacation' ),
			'manage_woocommerce',
			woo_store_vacation()->get_slug(),
			fn() => wp_safe_redirect( Helper\Settings::page_uri() )
		);
	}
}
