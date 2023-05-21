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
	const UPSELL_TRANSIENT_NAME = 'woo_store_vacation_upsell';

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

		add_action( 'woo_store_vacation_admin_notices', array( $this, 'upsell_notice' ) );
		add_action( "woocommerce_after_settings_{$this->slug}", array( $this, 'upsell_block' ) );
		add_action( 'wp_ajax_woo_store_vacation_dismiss_upsell', array( $this, 'dismiss_upsell' ) );
	}

	/**
	 * Display the upsell notice.
	 *
	 * @since 1.3.8
	 *
	 * @return void
	 */
	public function upsell_notice() {

		// Bail early if the transient is set and the usage timestamp is less than a day.
		if (
			get_transient( self::UPSELL_TRANSIENT_NAME )
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
	public function upsell_block() {

		wc_get_template(
			'upsell-block.php',
			array( 'uri' => self::PRO_URI ),
			'',
			trailingslashit( woo_store_vacation()->service( 'file' )->plugin_path( 'templates' ) )
		);
	}

	/**
	 * AJAX dismiss up-sell admin notice.
	 *
	 * @since 1.3.8
	 *
	 * @return void
	 */
	public function dismiss_upsell() {

		// Bail early if the nonce is invalid.
		check_ajax_referer( Notices::DISMISS_NONCE_NAME );

		// Set the upsell transient.
		set_transient( self::UPSELL_TRANSIENT_NAME, true, MONTH_IN_SECONDS );

		exit();
	}
}
