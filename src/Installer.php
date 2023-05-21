<?php
/**
 * The plugin installer class.
 *
 * @author MyPreview (Github: @mahdiyazdani, @gooklani, @mypreview)
 *
 * @since 1.0.0
 *
 * @package woo-store-vacation
 */

namespace Woo_Store_Vacation;

/**
 * The plugin installer class.
 */
class Installer {

	/**
	 * The welcome notice transient name.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const WELCOME_NOTICE_TRANSIENT_NAME = 'woo_store_vacation_welcome_notice';

	/**
	 * The activation timestamp option name.
	 *
	 * @since 1.6.1
	 *
	 * @var string
	 */
	const TIMESTAMP_OPTION_NAME = 'woo_store_vacation_activation_timestamp';

	/**
	 * Activate the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function activate(): void {

		self::store_timestamp();
		self::store_nux_notice();
	}

	/**
	 * Deactivate the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function deactivate(): void {

		delete_transient( 'woo_store_vacation_rate' );
		delete_transient( 'woo_store_vacation_upsell' );
		delete_transient( 'woo_store_vacation_welcome_notice' );
	}

	/**
	 * Store a timestamp option on plugin activation.
	 *
	 * @since 1.6.1
	 *
	 * @return void
	 */
	private static function store_timestamp() {

		$activation_timestamp = get_site_option( self::TIMESTAMP_OPTION_NAME );

		if ( ! $activation_timestamp ) {
			add_site_option( self::TIMESTAMP_OPTION_NAME, time() );
		}
	}

	/**
	 * Store a welcome notice transient on plugin activation.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private static function store_nux_notice() {

		$welcome_notice = sprintf(
			/* translators: 1: Dashicon, 2: Plugin name, 3: Open anchor tag, 4: Close anchor tag. */
			esc_html_x( '%1$s Thanks for installing %2$s plugin! To get started, visit the %3$splugin’s settings page%4$s.', 'admin notice', 'woo-store-vacation' ),
			'<i class="dashicons dashicons-admin-settings"></i>',
			sprintf(
				'<strong>%s</strong>',
				_x( 'Woo Store Vacation', 'plugin name', 'woo-store-vacation' )
			),
			sprintf(
				'<a href="%s" target="_self">',
				esc_url( woo_store_vacation()->service( 'options' )->get_uri() )
			),
			'</a>'
		);

		set_transient( self::WELCOME_NOTICE_TRANSIENT_NAME, $welcome_notice, MINUTE_IN_SECONDS );
	}
}
