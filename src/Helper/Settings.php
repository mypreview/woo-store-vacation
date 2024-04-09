<?php
/**
 * Settings helpers.
 *
 * @since 1.8.0
 *
 * @package woo-store-vacation
 */

namespace Woo_Store_Vacation\Helper;

/**
 * Helpers for the plugin settings.
 */
abstract class Settings {

	/**
	 * Check if the current page is the settings page.
	 *
	 * @since 1.8.0
	 *
	 * @return bool
	 */
	public static function is_page() {

		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		return (
			isset( $_GET['page'] )
			&& 'wc-settings' === $_GET['page']
			&& isset( $_GET['tab'] )
			&& woo_store_vacation()->get_slug() === $_GET['tab']
		);
		// phpcs:enable WordPress.Security.NonceVerification.Recommended
	}

	/**
	 * Get the plugin settings URI.
	 *
	 * @since 1.9.0
	 *
	 * @return string
	 */
	public static function page_uri() {

		// e.g, "http://example.com/wp-admin/admin.php?page=wc-settings&tab=woo-store-vacation".
		return add_query_arg(
			array(
				'page' => 'wc-settings',
				'tab'  => woo_store_vacation()->get_slug(),
			),
			admin_url( 'admin.php' )
		);
	}
}
