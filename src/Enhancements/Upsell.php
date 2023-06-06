<?php
/**
 * Plugin upsell content.
 *
 * @author MyPreview (Github: @mahdiyazdani, @gooklani, @mypreview)
 *
 * @since 1.3.8
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
	 * @since 1.3.8
	 *
	 * @var string
	 */
	const PRO_URI = 'https://mypreview.one/woo-store-vacation/';

	/**
	 * Setup hooks and filters.
	 *
	 * @since 1.3.8
	 *
	 * @param string $slug The plugin slug.
	 *
	 * @return void
	 */
	public function setup( $slug ) {

		add_action( "woocommerce_settings_{$slug}", array( $this, 'content_block' ) );
	}

	/**
	 * Display the upsell block.
	 *
	 * @since 1.8.0
	 *
	 * @return void
	 */
	public function content_block() {

		woo_store_vacation()->service( 'template_manager' )->echo_template(
			'upsell-sidebar.php',
			array( 'uri' => self::PRO_URI )
		);
	}
}
