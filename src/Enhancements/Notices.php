<?php
/**
 * The plugin admin notices class.
 *
 * @since 1.0.0
 *
 * @package woo-store-vacation
 */

namespace Woo_Store_Vacation\Enhancements;

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

		/**
		 * Fires after the welcome admin notice.
		 *
		 * @since 1.0.0
		 */
		do_action( 'woo_store_vacation_admin_notices' );
	}
}
