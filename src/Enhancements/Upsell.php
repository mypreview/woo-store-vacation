<?php
/**
 * Plugin upsell content.
 *
 * @since 1.3.8
 *
 * @package woo-store-vacation
 */

namespace Woo_Store_Vacation\Enhancements;

use Woo_Store_Vacation\Helper;

/**
 * Class Upsell.
 */
class Upsell {

	/**
	 * Setup hooks and filters.
	 *
	 * @since 1.3.8
	 *
	 * @return void
	 */
	public function setup() {

		add_action( 'woo_store_vacation_settings_sidebar', array( $this, 'sidebar' ) );
	}

	/**
	 * Display the upsell sidebar.
	 *
	 * @since 1.8.0
	 *
	 * @return void
	 */
	public function sidebar() {

		woo_store_vacation()->service( 'template_manager' )->echo_template(
			'sidebar/upsell.php',
			array(
				'uri' => Helper\Links::pro_uri(),
			)
		);
	}
}
