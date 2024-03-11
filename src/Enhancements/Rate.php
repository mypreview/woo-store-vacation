<?php
/**
 * The plugin rate class.
 *
 * @since 1.3.8
 *
 * @package woo-store-vacation
 */

namespace Woo_Store_Vacation\Enhancements;

use WP_Footer_Rate;
use Woo_Store_Vacation\Helper;

/**
 * Class Rate.
 */
class Rate {

	/**
	 * Rated (already) option name.
	 *
	 * @since 1.3.8
	 *
	 * @var string
	 */
	const OPTION_NAME = 'woo_store_vacation_rated';

	/**
	 * Rate transient name.
	 *
	 * @since 1.3.8
	 *
	 * @var string
	 */
	const TRANSIENT_NAME = 'woo_store_vacation_rate';


	/**
	 * Setup hooks and filters.
	 *
	 * @since 1.8.0
	 *
	 * @return void
	 */
	public function setup() {

		add_action( 'woo_store_vacation_admin_notices', array( $this, 'admin_notice' ) );
		add_action( 'woocommerce_settings_start', array( $this, 'wp_footer' ) );
	}

	/**
	 * Display the rate the plugin notice.
	 *
	 * @since 1.3.8
	 *
	 * @return void
	 */
	public function admin_notice() {

		// Bail early if the rate notice has been dismissed.
		if (
			get_option( self::OPTION_NAME )
			|| get_transient( self::TRANSIENT_NAME )
		) {
			return;
		}

		$usage_timestamp = woo_store_vacation()->service( 'options' )->get_usage_timestamp();

		// If the usage timestamp is empty, add it and bail.
		if ( empty( $usage_timestamp ) ) {
			// Add the activation timestamp.
			woo_store_vacation()->service( 'options' )->add_usage_timestamp();

			return;
		}

		// Bail early if the plugin recently installed.
		if ( time() < ( $usage_timestamp + WEEK_IN_SECONDS ) ) {
			return;
		}

		// Enqueue the assets.
		wp_enqueue_style( 'woo-store-vacation-rate' );
		wp_enqueue_script( 'woo-store-vacation-dismiss' );

		// Display the notice.
		woo_store_vacation()->service( 'template_manager' )->echo_template(
			'notices/rate.php',
			array(
				'usage_timestamp' => human_time_diff( $usage_timestamp ),
			)
		);
	}

	/**
	 * Ask for a review in the footer of the settings page.
	 *
	 * @since 1.8.0
	 *
	 * @return void
	 */
	public function wp_footer() {

		new WP_Footer_Rate\Rate(
			woo_store_vacation()->service( 'file' )->plugin_basename(),
			woo_store_vacation()->get_slug(),
			_x( 'Woo Store Vacation', 'plugin name', 'woo-store-vacation' ),
			Helper\Settings::is_page()
		);
	}
}
