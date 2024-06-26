<?php
/**
 * Dismiss rate the plugin admin notice.
 *
 * @since 1.6.1
 *
 * @package woo-store-vacation
 */

namespace Woo_Store_Vacation\Ajax;

use Woo_Store_Vacation\Enhancements;

/**
 * Rate admin notice class.
 */
class Rate extends Ajax {

	/**
	 * Setup hooks and filters.
	 *
	 * @since 1.6.1
	 *
	 * @return void
	 */
	public function __construct() {

		// Parent constructor.
		parent::__construct(
			'dismiss_rate',
			Enhancements\Notices::DISMISS_NONCE_NAME
		);
	}

	/**
	 * Setup hooks and filters.
	 *
	 * @since 1.6.1
	 *
	 * @return void
	 */
	public function setup() {

		// Register the AJAX action.
		$this->register_admin();
	}

	/**
	 * AJAX dismiss the admin notice.
	 *
	 * @since 1.6.1
	 *
	 * @return void
	 */
	public function ajax_callback() {

		// Bail early if the nonce is invalid.
		$this->verify_nonce();

		// Set the rate transient.
		set_transient( Enhancements\Rate::TRANSIENT_NAME, true, 3 * MONTH_IN_SECONDS );

		exit();
	}
}
