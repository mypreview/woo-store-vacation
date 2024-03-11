<?php
/**
 * The plugin settings.
 *
 * @since 1.8.0
 *
 * @package woo-store-vacation
 */

namespace Woo_Store_Vacation\Settings;

use WC_Settings_Page;
use WC_Admin_Settings;
use Woo_Store_Vacation\Helper;

/**
 * Class Settings.
 */
class Settings extends WC_Settings_Page {

	/**
	 * Setup settings class.
	 *
	 * @since 1.8.0
	 *
	 * @return void
	 */
	public function __construct() {

		$this->assign();
		$this->setup();
		$this->enqueue();

		parent::__construct();
	}

	/**
	 * Assign the settings properties.
	 *
	 * @since 1.8.0
	 *
	 * @return void
	 */
	private function assign() {

		$this->id    = sanitize_key( woo_store_vacation()->get_slug() );
		$this->label = _x( 'Store Vacation', 'settings tab label', 'woo-store-vacation' );
	}

	/**
	 * Setup hooks and filters.
	 *
	 * @since 1.8.0
	 *
	 * @return void
	 */
	public function setup() {

		add_filter( 'admin_body_class', array( $this, 'add_body_class' ) );
		add_action( "woocommerce_settings_{$this->id}", array( $this, 'sidebar' ) );
	}

	/**
	 * Add plugin specific class to body.
	 *
	 * @since 1.8.0
	 *
	 * @param string $classes Classes to be added to the body element.
	 *
	 * @return string
	 */
	public function add_body_class( $classes ) {

		// Bail early if the current page is not the settings page.
		if ( ! Helper\Settings::is_page() ) {
			return $classes;
		}

		$classes .= sprintf( ' %s-page', sanitize_html_class( $this->id ) );

		return $classes;
	}

	/**
	 * Display the sidebar.
	 *
	 * @since 1.8.0
	 *
	 * @return void
	 */
	public function sidebar() {

		woo_store_vacation()->service( 'template_manager' )->echo_template(
			'sidebar/sidebar.php'
		);
	}

	/**
	 * Enqueue the settings assets.
	 *
	 * @since 1.8.0
	 *
	 * @return void
	 */
	private function enqueue() {

		// Bail early if the current page is not the settings page.
		if ( ! Helper\Settings::is_page() ) {
			return;
		}

		// Enqueue the settings assets.
		wp_enqueue_style( 'woo-store-vacation-admin' );
		wp_enqueue_script( 'woo-store-vacation-admin' );
	}

	/**
	 * Get own sections.
	 *
	 * @since 1.9.0
	 *
	 * @return array
	 */
	protected function get_own_sections() {

		return array(
			''           => _x( 'General', 'settings tab', 'woo-store-vacation' ),
			'conditions' => _x( 'Conditions', 'settings tab', 'woo-store-vacation' ),
		);
	}

	/**
	 * Get settings array.
	 *
	 * @since 1.8.0
	 *
	 * @return array
	 */
	protected function get_settings_for_default_section() {

		return woo_store_vacation()->service( 'settings_general' )->get_fields();
	}

	/**
	 * Get settings for the conditional logic section.
	 *
	 * @since 1.9.0
	 *
	 * @return array
	 */
	protected function get_settings_for_conditions_section() {

		return woo_store_vacation()->service( 'settings_conditions' )->get_fields();
	}

	/**
	 * Save settings.
	 *
	 * @since 1.8.0
	 *
	 * @return void
	 */
	public function save() {

		// Ensures intent by verifying that a user was referred from another admin page with the correct security nonce.
		check_admin_referer( 'woocommerce-settings' );

		// phpcs:disable WordPress.Security.NonceVerification.Missing
		if ( isset( $_POST['woo_store_vacation_options']['start_date'] ) && isset( $_POST['woo_store_vacation_options']['end_date'] ) ) {
			$start_date = sanitize_text_field( wp_unslash( $_POST['woo_store_vacation_options']['start_date'] ) );
			$end_date   = sanitize_text_field( wp_unslash( $_POST['woo_store_vacation_options']['end_date'] ) );

			$timezone       = wp_timezone();
			$start_date_obj = date_create( $start_date, $timezone );
			$end_date_obj   = date_create( $end_date, $timezone );

			if ( $start_date_obj >= $end_date_obj ) {
				WC_Admin_Settings::add_error(
					_x( 'The start date must be less than the end date. Please ensure that the selected start date is earlier than the end date to avoid any conflicts with the vacation scheduling.', 'error message', 'woo-store-vacation' )
				);
				return;
			}
		}
		// phpcs:enable WordPress.Security.NonceVerification.Missing

		parent::save();
	}
}
