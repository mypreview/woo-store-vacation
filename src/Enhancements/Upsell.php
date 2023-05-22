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
	 *
	 * @var string
	 */
	const PRO_URI = 'https://mypreview.one/woo-store-vacation/';

	/**
	 * Upsell transient name.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const TRANSIENT_NAME = 'woo_store_vacation_upsell';

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

		add_action( 'woo_store_vacation_admin_notices', array( $this, 'admin_notice' ) );
		add_action( "woocommerce_after_settings_{$this->slug}", array( $this, 'content_block' ) );
	}

	/**
	 * Display the upsell notice.
	 *
	 * @since 1.3.8
	 *
	 * @return void
	 */
	public function admin_notice() {

		// Bail early if the transient is set and the usage timestamp is less than a day.
		if (
			get_transient( self::TRANSIENT_NAME )
			&& ( time() - woo_store_vacation()->service( 'options' )->get_usage_timestamp() ) > DAY_IN_SECONDS
		) {
			return;
		}

		// Enqueue the upsell notice assets.
		wp_enqueue_style( 'woo-store-vacation-upsell' );
		wp_enqueue_script( 'woo-store-vacation-upsell' );

		// Display the upsell notice.
		wc_get_template(
			'upsell-notice.php',
			array(
				'slug' => $this->slug,
				'uri'  => self::PRO_URI,
			),
			'',
			trailingslashit( woo_store_vacation()->service( 'file' )->plugin_path( 'templates' ) )
		);
	}

	/**
	 * Display the upsell block.
	 *
	 * @since 1.8.0
	 *
	 * @return void
	 */
	public function content_block() {

		wc_get_template(
			'upsell-block.php',
			array( 'uri' => self::PRO_URI ),
			'',
			trailingslashit( woo_store_vacation()->service( 'file' )->plugin_path( 'templates' ) )
		);
	}
}
