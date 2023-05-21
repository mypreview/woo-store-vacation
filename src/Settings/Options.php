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
	 * Get the plugin settings URI.
	 *
	 * @since 1.9.0
	 *
	 * @return string
	 */
	public function get_uri() {

		return add_query_arg(
			array(
				'page' => 'wc-settings',
				'tab'  => woo_store_vacation()->get_slug(),
			),
			admin_url( 'admin.php' )
		);
	}
}
