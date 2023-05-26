<?php
/**
 * The plugin installer class.
 *
 * @author MyPreview (Github: @mahdiyazdani, @gooklani, @mypreview)
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
	 * The activation timestamp option name.
	 *
	 * @since 1.6.1
	 *
	 * @var string
	 */
	const TIMESTAMP_OPTION_NAME = 'woo_store_vacation_activation_timestamp';

	/**
	 * Activate the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function activate() {

		self::store_timestamp();
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
		delete_transient( Enhancements\Upsell::TRANSIENT_NAME );
	}

	/**
	 * Store a timestamp option on plugin activation.
	 *
	 * @since 1.6.1
	 *
	 * @return void
	 */
	private static function store_timestamp() {

		$activation_timestamp = get_site_option( self::TIMESTAMP_OPTION_NAME );

		// Store the activation timestamp if it doesn't exist.
		if ( ! $activation_timestamp ) {
			add_site_option( self::TIMESTAMP_OPTION_NAME, time() );
		}
	}
}
