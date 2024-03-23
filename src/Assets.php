<?php
/**
 * The plugin assets (static resources).
 *
 * @since 1.0.0
 *
 * @package woo-store-vacation
 */

namespace Woo_Store_Vacation;

use Woo_Store_Vacation\Enhancements\Notices;

/**
 * Load plugin static resources (CSS and JS files).
 */
abstract class Assets {

	/**
	 * Enqueue editor scripts and styles.
	 *
	 * @since 1.7.0
	 *
	 * @return void
	 */
	public static function enqueue_editor() {

		wp_enqueue_script(
			'woo-store-vacation-block',
			woo_store_vacation()->service( 'file' )->asset_path( 'block.js' ),
			array( 'react', 'wp-components', 'wp-element', 'wp-i18n' ),
			woo_store_vacation()->get_version(),
			true
		);
	}

	/**
	 * Enqueue admin scripts and styles.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function enqueue_admin() {

		$version = woo_store_vacation()->get_version();

		wp_register_style(
			'woo-store-vacation-admin',
			woo_store_vacation()->service( 'file' )->asset_path( 'admin.css' ),
			array( 'woocommerce_admin_styles' ),
			$version,
			'screen'
		);
		wp_register_script(
			'woo-store-vacation-admin',
			woo_store_vacation()->service( 'file' )->asset_path( 'admin.js' ),
			array( 'jquery', 'jquery-ui-datepicker', 'wp-i18n' ),
			$version,
			true
		);

		wp_register_style(
			'woo-store-vacation-rate',
			woo_store_vacation()->service( 'file' )->asset_path( 'rate.css' ),
			array(),
			$version,
			'screen'
		);

		wp_register_script(
			'woo-store-vacation-dismiss',
			woo_store_vacation()->service( 'file' )->asset_path( 'dismiss.js' ),
			array( 'jquery' ),
			$version,
			true
		);
		wp_localize_script(
			'woo-store-vacation-dismiss',
			'woo_store_vacation_params',
			array(
				'dismiss_nonce' => wp_create_nonce( Notices::DISMISS_NONCE_NAME ),
			)
		);
	}
}
