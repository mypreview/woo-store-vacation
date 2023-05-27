<?php
/**
 * Dismiss up-sell admin notice.
 *
 * @author MyPreview (Github: @mahdiyazdani, @gooklani, @mypreview)
 *
 * @since 1.3.8
 *
 * @package woo-store-vacation
 */

namespace Woo_Store_Vacation\Ajax;

use Woo_Store_Vacation\Enhancements;

/**
 * Upsell admin notice class.
 */
class Upsell extends Ajax {

	/**
	 * Setup hooks and filters.
	 *
	 * @since 1.3.8
	 *
	 * @return void
	 */
	public function __construct() {

		// Parent constructor.
		parent::__construct(
			'dismiss_upsell',
			Enhancements\Notices::DISMISS_NONCE_NAME
		);
	}

	/**
	 * Setup hooks and filters.
	 *
	 * @since 1.3.8
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
	 * @since 1.3.8
	 *
	 * @return void
	 */
	public function ajax_callback() {

		// Bail early if the nonce is invalid.
		$this->verify_nonce();

		// Set the upsell transient.
		set_transient( Enhancements\Upsell::TRANSIENT_NAME, true, MONTH_IN_SECONDS );

		exit();
	}
}
