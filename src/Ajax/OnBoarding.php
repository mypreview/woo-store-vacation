<?php
/**
 * Dismiss onboarding (welcome) admin notice.
 *
 * @since 1.8.0
 *
 * @package woo-store-vacation
 */

namespace Woo_Store_Vacation\Ajax;

use Woo_Store_Vacation\Enhancements;

/**
 * Onboarding admin notice class.
 */
class OnBoarding extends Ajax {

	/**
	 * Setup hooks and filters.
	 *
	 * @since 1.8.0
	 *
	 * @return void
	 */
	public function __construct() {

		// Parent constructor.
		parent::__construct(
			'dismiss_onboarding',
			Enhancements\Notices::DISMISS_NONCE_NAME
		);
	}

	/**
	 * Setup hooks and filters.
	 *
	 * @since 1.8.0
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
	 * @since 1.8.0
	 *
	 * @return void
	 */
	public function ajax_callback() {

		// Bail early if the nonce is invalid.
		$this->verify_nonce();

		// Set the preference to dismiss the notice.
		add_site_option( Enhancements\OnBoarding::OPTION_NAME, true );

		exit();
	}
}
