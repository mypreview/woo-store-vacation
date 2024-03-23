<?php
/**
 * The plugin installer class.
 *
 * @since 1.0.0
 *
 * @package woo-store-vacation
 */

namespace Woo_Store_Vacation;

use Woo_Store_Vacation\Enhancements;

/**
 * The plugin installer class.
 */
class Installer {

	/**
	 * Activate the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function activate() {

		// Add the activation timestamp, if not already added.
		woo_store_vacation()->service( 'options' )->add_usage_timestamp();
	}

	/**
	 * Deactivate the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function deactivate() {

		delete_transient( Enhancements\Rate::TRANSIENT_NAME );
	}
}
