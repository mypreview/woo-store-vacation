<?php
/**
 * The plugin meta class.
 *
 * @since 1.0.0
 *
 * @package woo-store-vacation
 */

namespace Woo_Store_Vacation\Enhancements;

use Woo_Store_Vacation\Helper;

/**
 * The plugin meta class.
 */
class Meta {

	/**
	 * Setup hooks and filters.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function setup() {

		add_filter( 'plugin_row_meta', array( $this, 'meta_links' ), 10, 2 );
		add_filter( 'plugin_action_links', array( $this, 'action_links' ), 10, 2 );
	}

	/**
	 * Add additional helpful links to the plugin’s metadata.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $links An array of the plugin’s metadata.
	 * @param string $file  Path to the plugin file relative to the plugins' directory.
	 *
	 * @return array
	 */
	public function meta_links( $links, $file ) {

		// Return early if not on the plugin page.
		if ( ! $this->is_this_plugin( $file ) ) {
			return $links;
		}

		$plugin_links = array(
			sprintf( /* translators: 1: Open anchor tag, 2: Close anchor tag. */
				esc_html_x( '%1$sDocs%2$s', 'plugin link', 'woo-store-vacation' ),
				sprintf(
					'<a href="%s" target="_blank" rel="noopener noreferrer nofollow">',
					esc_url( Helper\Links::docs_uri() )
				),
				'</a>'
			),
			sprintf( /* translators: 1: Open anchor tag, 2: Close anchor tag. */
				esc_html_x( '%1$sCommunity support%2$s', 'plugin link', 'woo-store-vacation' ),
				sprintf(
					'<a href="https://wordpress.org/support/plugin/%s" target="_blank" rel="noopener noreferrer nofollow">',
					esc_attr( woo_store_vacation()->get_slug() )
				),
				'</a>'
			),
		);

		return array_merge( $links, $plugin_links );
	}

	/**
	 * Display additional links in the plugin table page.
	 * Filters the list of action links displayed for a specific plugin in the Plugins list table.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $links Plugin table/item action links.
	 * @param string $file  Path to the plugin file relative to the plugins' directory.
	 *
	 * @return array
	 */
	public function action_links( $links, $file ) {

		// Leave early if the filter is not for this plugin.
		if ( ! $this->is_this_plugin( $file ) ) {
			return $links;
		}

		$plugin_links   = array();
		$plugin_links[] = sprintf( /* translators: 1: Open anchor tag, 2: Close anchor tag. */
			esc_html_x( '%1$sGet PRO%2$s', 'plugin link', 'woo-store-vacation' ),
			sprintf(
				'<a href="%s" target="_blank" rel="noopener noreferrer nofollow" style="color:green;font-weight:bold;">&#127796; ',
				esc_url( Helper\Links::pro_uri() )
			),
			'</a>'
		);
		$plugin_links[] = sprintf( /* translators: 1: Open anchor tag, 2: Close anchor tag. */
			esc_html_x( '%1$sSettings%2$s', 'plugin settings page', 'woo-store-vacation' ),
			sprintf(
				'<a href="%s">',
				esc_url( Helper\Settings::page_uri() )
			),
			'</a>'
		);

		return array_merge( $plugin_links, $links );
	}

	/**
	 * Check if the current plugin is the one we are looking for.
	 *
	 * @since 1.0.0
	 *
	 * @param string $file Path to the plugin file relative to the plugins' directory.
	 *
	 * @return bool
	 */
	private function is_this_plugin( $file ) {

		return woo_store_vacation()->service( 'file' )->plugin_basename() === $file;
	}
}
