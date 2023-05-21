<?php
/**
 * Close the store for vacation.
 *
 * @author MyPreview (Github: @mahdiyazdani, @gooklani, @mypreview)
 *
 * @since 1.0.0
 *
 * @package woo-store-vacation
 */

namespace Woo_Store_Vacation\WooCommerce;

/**
 * Close class.
 */
class Close {

	/**
	 * Date time format.
	 *
	 * @since 1.6.4
	 *
	 * @var string
	 */
	const DATETIME_FORMAT = 'Y-m-d H:i:s';

	/**
	 * Setup hooks and filters.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function setup() {

		add_action( 'before_woocommerce_init', array( $this, 'should_close' ) );
		add_action( 'woo_store_vacation_disable_purchase', array( $this, 'disable_purchase' ) );
	}

	/**
	 * Determine if the store should be closed for vacation.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function should_close() {

		$vacation_mode = woo_store_vacation()->service( 'options' )->get( 'vacation_mode', 'no' );

		if ( ! wc_string_to_bool( $vacation_mode ) ) {
			return;
		}

		$start_date = woo_store_vacation()->service( 'options' )->get( 'start_date' );
		$end_date   = woo_store_vacation()->service( 'options' )->get( 'end_date' );

		if ( empty( $start_date ) || empty( $end_date ) ) {
			return;
		}

		// Parses a time string according to WP timezone format.
		$timezone   = wp_timezone();
		$start_date = date_create( $start_date, $timezone );
		$end_date   = date_create( $end_date, $timezone );

		if ( ! $start_date || ! $end_date ) {
			return;
		}

		$start_date->setTime( 0, 0 );
		$end_date->setTime( 0, 0 );

		// The current time as an object using the site’s timezone.
		$today = current_datetime();

		if ( $today < $start_date || $today > $end_date ) {
			return;
		}

		$disable_purchase = woo_store_vacation()->service( 'options' )->get( 'disable_purchase', 'no' );

		if ( wc_string_to_bool( $disable_purchase ) ) {
			/**
			 * Disable purchase.
			 *
			 * @since 1.6.2
			 */
			do_action( 'woo_store_vacation_disable_purchase' );
		}

		/**
		 * Vacation mode.
		 *
		 * @since 1.6.2
		 */
		do_action( 'woo_store_vacation_vacation_mode' );
	}

	/**
	 * Disable purchase.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function disable_purchase() {

		add_filter( 'woocommerce_is_purchasable', '__return_false', PHP_INT_MAX );
		add_filter( 'body_class', array( $this, 'body_classes' ) );
	}

	/**
	 * Adds custom classes to the array of body classes.
	 *
	 * @since 1.6.4
	 *
	 * @param  array $classes Classes for the body element.
	 * @return array
	 */
	public function body_classes( $classes ) {

		/**
		 * Append a class to the body element when the shop is closed.
		 */
		$classes[] = sanitize_html_class( woo_store_vacation()->get_slug() . '-shop-closed' );

		return $classes;
	}
}
