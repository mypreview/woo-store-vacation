<?php
/**
 * Plugin upsell content.
 *
 * @author MyPreview (Github: @mahdiyazdani, @gooklani, @mypreview)
 *
 * @since 1.0.0
 *
 * @package woo-store-vacation
 */

namespace Woo_Store_Vacation\Enhancements;

/**
 * Class Upsell.
 */
class Upsell {

	/**
	 * The PRO version upsell URI.
	 *
	 * @since 1.0.0
	 */
	const PRO_URI = 'https://mypreview.one/woo-store-vacation/';

	/**
	 * Setup hooks and filters.
	 *
	 * @since 1.0.0
	 *
	 * @param string $slug The plugin slug.
	 *
	 * @return void
	 */
	public function setup( $slug ) {

		$this->slug = $slug;

		add_action( "woocommerce_after_settings_{$this->slug}", array( $this, 'upsell_block' ) );
	}

	/**
	 * Display the upsell block.
	 *
	 * @since 1.8.0
	 *
	 * @return void
	 */
	public function upsell_block() {

		wc_get_template(
			'upsell-block.php',
			array( 'uri' => self::PRO_URI ),
			'',
			trailingslashit( woo_store_vacation()->service( 'file' )->plugin_path( 'templates' ) )
		);
	}
}
