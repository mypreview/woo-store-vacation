<?php
/**
 * Plugin options class.
 *
 * @since 1.9.0
 *
 * @package woo-store-vacation
 */

namespace Woo_Store_Vacation\Settings;

use Woo_Store_Vacation\Installer;

/**
 * Options class.
 */
class Options {

	/**
	 * The plugin options name.
	 *
	 * @since 1.9.0
	 *
	 * @var string
	 */
	const OPTION_NAME = 'woo_store_vacation_options';

	/**
	 * The activation timestamp option name.
	 *
	 * @since 1.6.1
	 *
	 * @var string
	 */
	const TIMESTAMP_OPTION_NAME = 'woo_store_vacation_activation_timestamp';

	/**
	 * Get the plugin options.
	 *
	 * @since 1.9.0
	 *
	 * @param string $key     The option key.
	 * @param mixed  $default The default value.
	 *
	 * @return string|array
	 */
	public function get( $key = '', $default = null ) {

		$options = (array) get_option( self::OPTION_NAME, array() );

		if ( empty( $key ) ) {
			return $options;
		}

		return isset( $options[ $key ] ) ? $options[ $key ] : $default;
	}

	/**
	 * Update the plugin options.
	 *
	 * @since 1.9.0
	 *
	 * @param array $value The new options value.
	 *
	 * @return array
	 */
	public function update( $value ) {

		// Bail early if the value is not an array or empty.
		if ( ! is_array( $value ) || empty( $value ) ) {
			return array();
		}

		update_option( self::OPTION_NAME, $value );

		return $value;
	}

	/**
	 * Get the plugin activation timestamp.
	 *
	 * @since 1.9.0
	 *
	 * @return int
	 */
	public function get_usage_timestamp() {

		return (int) get_site_option( self::TIMESTAMP_OPTION_NAME, 0 );
	}

	/**
	 * Store a timestamp option on plugin activation.
	 *
	 * @since 1.6.1
	 *
	 * @return void
	 */
	public function add_usage_timestamp() {

		$activation_timestamp = get_site_option( self::TIMESTAMP_OPTION_NAME );

		// Store the activation timestamp if it doesn't exist.
		if ( ! $activation_timestamp ) {
			add_site_option( self::TIMESTAMP_OPTION_NAME, time() );
		}
	}
}
