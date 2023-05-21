<?php
/**
 * Plugin options class.
 *
 * @author MyPreview (Github: @mahdiyazdani, @gooklani, @mypreview)
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

		$options = (array) get_option( 'woo_store_vacation_options', array() );

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
	public function update( $value ): array {

		// Bail early if the value is not an array or empty.
		if ( ! is_array( $value ) || empty( $value ) ) {
			return array();
		}

		update_option( 'woo_store_vacation_options', $value );

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

		return (int) get_site_option( Installer::TIMESTAMP_OPTION_NAME, 0 );
	}
}
