<?php
/**
 * The plugin admin notices class.
 *
 * @author MyPreview (Github: @mahdiyazdani, @gooklani, @mypreview)
 *
 * @since 1.0.0
 *
 * @package woo-store-vacation
 */

namespace Woo_Store_Vacation\Enhancements;

use Woo_Store_Vacation\Installer;

/**
 * The plugin notices class.
 */
class Notices {

	/**
	 * Setup hooks and filters.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function setup() {

		add_filter( 'admin_notices', array( $this, 'print' ) );
	}

	/**
	 * Display the admin notices.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function print() {

		$welcome_notice = get_transient( Installer::WELCOME_NOTICE_TRANSIENT_NAME );

		// Check if the welcome notice transient is set.
		if ( ! empty( $welcome_notice ) ) {

			// Delete the welcome notice transient.
			delete_transient( Installer::WELCOME_NOTICE_TRANSIENT_NAME );

			// Display the welcome notice.
			wc_get_template(
				'welcome-notice.php',
				array( 'content' => $welcome_notice ),
				'',
				trailingslashit( woo_store_vacation()->service( 'file' )->plugin_path( 'templates' ) )
			);

			return;
		}

		/**
		 * Fires after the welcome admin notice.
		 *
		 * @since 1.0.0
		 */
		do_action( 'woo_store_vacation_admin_notices' );
	}
}
