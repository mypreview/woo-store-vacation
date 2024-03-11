<?php
/**
 * The plugin template manager class.
 *
 * @since 1.9.0
 *
 * @package woo-store-vacation
 */

namespace Woo_Store_Vacation;

/**
 * Class TemplateManager.
 */
class TemplateManager {

	/**
	 * Render the template.
	 *
	 * @since 1.9.0
	 *
	 * @param string $template_name The template name.
	 * @param array  $args          The template arguments.
	 *
	 * @return void
	 */
	public function echo_template( $template_name, $args = array() ) {

		// Supports internal WooCommerce caching.
		wc_get_template(
			$template_name,
			$args,
			'',
			trailingslashit( woo_store_vacation()->service( 'file' )->plugin_path( 'templates' ) )
		);
	}
}
