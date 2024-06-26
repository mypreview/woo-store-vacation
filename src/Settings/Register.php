<?php
/**
 * Plugin settings registerer.
 *
 * @since 1.8.0
 *
 * @package woo-store-vacation
 */

namespace Woo_Store_Vacation\Settings;

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

		add_filter( 'woocommerce_get_settings_pages', array( $this, 'settings' ) );
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

		// Add our settings page.
		$settings[] = woo_store_vacation()->service( 'settings' );
		return $settings;
	}
}
