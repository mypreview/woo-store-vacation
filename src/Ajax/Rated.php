<?php
/**
 * Dismiss rate the plugin admin notice.
 *
 * @author MyPreview (Github: @mahdiyazdani, @gooklani, @mypreview)
 *
 * @since 1.8.0
 *
 * @package woo-store-vacation
 */

namespace Woo_Store_Vacation\Ajax;

use Woo_Store_Vacation\Enhancements;

/**
 * Rate(d) admin notice class.
 */
class Rated extends Ajax {

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
			'woo_store_vacation_dismiss_rated',
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

		$this->register_admin();
	}

	/**
	 * AJAX dismiss rate admin notice.
	 *
	 * @since 1.8.0
	 *
	 * @return void
	 */
	public function ajax_callback() {

		// Bail early if the nonce is invalid.
		$this->verify_nonce();

		// Set the already rated option.
		add_option( Enhancements\Rate::OPTION_NAME, true );

		exit();
	}
}