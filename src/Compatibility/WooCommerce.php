<?php
/**
 * Add compatibility with WooCommerce (core) features.
 *
 * @author MyPreview (Github: @mahdiyazdani, @gooklani, @mypreview)
 *
 * @since 1.6.2
 *
 * @package woo-store-vacation
 */

namespace Woo_Store_Vacation\Compatibility;

use Automattic\WooCommerce\Utilities\FeaturesUtil;

/**
 * WooCommerce Compatibility class.
 */
class WooCommerce {

	/**
	 * Constructor method.
	 *
	 * @since 1.6.2
	 *
	 * @return void
	 */
	public function setup() {

		add_action( 'before_woocommerce_init', array( $this, 'add_hpos_compatibility' ) );
	}

	/**
	 * Declaring compatibility with HPOS.
	 *
	 * This plugin has nothing to do with "High-Performance Order Storage".
	 * However, the compatibility flag has been added to avoid WooCommerce declaring the plugin as "uncertain".
	 *
	 * @since 1.6.2
	 *
	 * @return void
	 */
	public function add_hpos_compatibility() {

		// Declare compatibility with HPOS.
		FeaturesUtil::declare_compatibility( 'custom_order_tables', woo_store_vacation()->service( 'file' )->plugin_file() );
	}
}
