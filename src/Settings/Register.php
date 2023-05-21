<?php
/**
 * Plugin settings registerer.
 *
 * @author MyPreview (Github: @mahdiyazdani, @gooklani, @mypreview)
 *
 * @since 1.8.0
 *
 * @package woo-store-vacation
 */

namespace Woo_Store_Vacation\Settings;

use WP_Footer_Rate\Rate;
use Woo_Store_Vacation\Helper;

/**
 * Register plugin settings fields.
 */
class Register {

	/**
	 * Setup hooks and filters.
	 *
	 * @since 1.8.0
	 *
	 * @return void
	 */
	public function setup() {

		add_action( 'admin_menu', array( $this, 'add_submenu_page' ), 999 );
		add_filter( 'woocommerce_get_settings_pages', array( $this, 'settings' ) );
		add_action( 'woocommerce_settings_start', array( $this, 'rate' ) );
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

		add_submenu_page(
			'woocommerce',
			esc_html_x( 'Woo Store Vacation', 'plugin name', 'woo-store-vacation' ),
			esc_html_x( 'Store Vacation', 'menu title', 'woo-store-vacation' ),
			'manage_woocommerce',
			woo_store_vacation()->get_slug(),
			fn() => wp_safe_redirect( Helper\Settings::page_uri() )
		);
	}

	/**
	 * We will add our settings pages using the following filter, so that the code that
	 * being used to hook into that filter is init by a filter later than `wp_loaded`.
	 *
	 * @since 1.8.0
	 *
	 * @param array $settings Setting pages.
	 *
	 * @return array
	 */
	public function settings( $settings ) {

		$settings[] = woo_store_vacation()->service( 'settings' );
		return $settings;
	}

	/**
	 * Ask for a review in the footer of the settings page.
	 *
	 * @since 1.8.0
	 *
	 * @return void
	 */
	public function rate() {

		new Rate(
			woo_store_vacation()->service( 'file' )->plugin_basename(),
			woo_store_vacation()->get_slug(),
			_x( 'Woo Store Vacation', 'plugin name', 'woo-store-vacation' ),
			Helper\Settings::is_page()
		);
	}
}
