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
	 * The dismiss nonce name.
	 *
	 * @since 1.3.8
	 *
	 * @var string
	 */
	const DISMISS_NONCE_NAME = 'woo-store-vacation-dismiss';

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
			woo_store_vacation()->service( 'template_manager' )->echo_template(
				'welcome-notice.php',
				array( 'content' => $welcome_notice )
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
