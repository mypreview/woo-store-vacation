<?php
/**
 * The plugin settings.
 *
 * @author MyPreview (Github: @mahdiyazdani, @gooklani, @mypreview)
 *
 * @since 1.8.0
 *
 * @package woo-store-vacation
 */

namespace Woo_Store_Vacation\Settings;

use WC_Settings_Page;
use Woo_Store_Vacation\Helper;

/**
 * Class Settings.
 */
class Settings extends WC_Settings_Page {

	/**
	 * The settings slug.
	 *
	 * @since 1.8.0
	 *
	 * @var string
	 */
	private $slug;

	/**
	 * Setup settings class.
	 *
	 * @since 1.8.0
	 *
	 * @return void
	 */
	public function __construct() {

		$this->assign();
		$this->enqueue();

		parent::__construct();
	}

	/**
	 * Assign the settings properties.
	 *
	 * @since 1.8.0
	 *
	 * @return void
	 */
	private function assign() {

		$this->id    = sanitize_key( woo_store_vacation()->get_slug() );
		$this->slug  = preg_replace( '/-/', '_', $this->id );
		$this->label = _x( 'Store Vacation', 'settings tab label', 'woo-store-vacation' );
	}

	/**
	 * Enqueue the settings assets.
	 *
	 * @since 1.8.0
	 *
	 * @return void
	 */
	private function enqueue() {

		// Bail early if the current page is not the settings page.
		if ( ! Helper\Settings::is_page() ) {
			return;
		}

		wp_enqueue_style( 'woo-store-vacation-admin' );
		wp_enqueue_script( 'woo-store-vacation-admin' );
	}

	/**
	 * Get settings array.
	 *
	 * @since 1.8.0
	 *
	 * @return array
	 */
	protected function get_settings_for_default_section() {

		return apply_filters(
			// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
			'woocommerce_' . $this->id . '_settings',
			woo_store_vacation()->service( 'settings_general' )->get_fields( $this->slug )
		);
	}
}
