<?php
/**
 * Helper links used throughout the plugin.
 *
 * @since 1.7.0
 *
 * @package woo-store-vacation
 */

namespace Woo_Store_Vacation\Helper;

/**
 * Helper links.
 */
abstract class Links {

	/**
	 * Get the url for the documentation with the given path.
	 *
	 * @since 1.9.0
	 *
	 * @param string $path The path to the documentation page.
	 * @param array  $args The query args.
	 *
	 * @return string
	 */
	public static function docs_uri( $path = '', $args = array() ) {

		return path_join(
			'https://mypreview.github.io/woo-store-vacation',
			add_query_arg( $args, $path )
		);
	}

	/**
	 * Get the url for the pro version with the given path.
	 *
	 * @since 1.9.0
	 *
	 * @param string $path The path to the pro version page.
	 * @param array  $args The query args.
	 *
	 * @return string
	 */
	public static function pro_uri( $path = '', $args = array() ) {

		return path_join(
			'https://woocommerce.com/products/store-vacation/',
			add_query_arg( $args, $path )
		);
	}
}
