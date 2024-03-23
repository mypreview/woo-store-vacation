<?php
/**
 * The vacation class for WooCommerce.
 *
 * @since 1.0.0
 *
 * @package woo-store-vacation
 */

namespace Woo_Store_Vacation\WooCommerce;

use WC_Product;
use WP_Admin_Bar;
use Woo_Store_Vacation\Helper;

/**
 * Vacation class.
 */
class Vacation {

	/**
	 * Conditions to check before disabling the purchase.
	 *
	 * @since 1.9.0
	 *
	 * @var array
	 */
	private $conditions = array();

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

		// Bail early, in case vacation mode is not enabled.
		if ( ! wc_string_to_bool( $vacation_mode ) ) {
			return;
		}

		$start_date = woo_store_vacation()->service( 'options' )->get( 'start_date' );
		$end_date   = woo_store_vacation()->service( 'options' )->get( 'end_date' );

		// Bail early, in case start or end date is empty.
		if ( empty( $start_date ) || empty( $end_date ) ) {
			return;
		}

		// Parses a time string according to WP timezone format.
		$timezone   = wp_timezone();
		$start_date = date_create( $start_date, $timezone );
		$end_date   = date_create( $end_date, $timezone );

		// Bail early, in case start or end date is invalid.
		if ( ! $start_date || ! $end_date ) {
			return;
		}

		$start_date->setTime( 0, 0 );
		$end_date->setTime( 0, 0 );

		// The current time as an object using the site’s timezone.
		$today = current_datetime();

		// Bail early, in case today is not between start and end date.
		if ( $today < $start_date || $today > $end_date ) {
			return;
		}

		$disable_purchase = woo_store_vacation()->service( 'options' )->get( 'disable_purchase', 'no' );

		if ( wc_string_to_bool( $disable_purchase ) ) {

			// Get active conditions.
			$this->conditions = woo_store_vacation()->service( 'settings_conditions' )->get_active();

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

		add_action( 'admin_bar_menu', array( $this, 'admin_bar_node' ), 999 );
		add_filter( 'body_class', array( $this, 'body_classes' ) );
		add_filter( 'woocommerce_is_purchasable', array( $this, 'is_purchasable' ), PHP_INT_MAX, 2 );
		add_action( 'woo_store_vacation_disable_purchase_product', array( $this, 'remove_add_to_cart_button' ) );
	}

	/**
	 * Appends a new node to the admin-bar menu items.
	 *
	 * @since 1.9.0
	 *
	 * @param WP_Admin_Bar $admin_bar WordPress admin bar object of nodes.
	 *
	 * @return void
	 */
	public function admin_bar_node( $admin_bar ) {

		// Bail early, in case user can manage shop settings.
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			return;
		}

		// Register a new node.
		$admin_bar->add_menu(
			array(
				'parent' => null,
				'group'  => null,
				'id'     => woo_store_vacation()->service( 'file' )->plugin_basename(),
				/* translators: %s: HTML `Warning` symbol. */
				'title'  => sprintf( esc_html_x( '%s Shop Closed!', 'admin bar', 'woo-store-vacation' ), '&#9888;' ),
				'href'   => esc_url( Helper\Settings::page_uri() ),
				'meta'   => array(
					'title' => esc_html_x( 'Vacation mode is activated! Click here to navigate to the plugin’s settings page.', 'admin bar', 'woo-store-vacation' ),
				),
			)
		);
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

	/**
	 * Determine if the product is purchasable.
	 *
	 * @since 1.0.0
	 *
	 * @param bool       $purchasable Whether the product is purchasable.
	 * @param WC_Product $product     Product data.
	 *
	 * @return bool
	 */
	public function is_purchasable( $purchasable, $product ) {

		// Bail early, in case the conditions are empty.
		if ( empty( $this->conditions ) ) {
			/**
			 * Disable purchase for the product.
			 *
			 * @since 1.9.1
			 */
			do_action( 'woo_store_vacation_disable_purchase_product', $product );

			return false;
		}

		if ( $product->is_type( 'variation' ) ) {
			// Get parent product.
			$product = wc_get_product( $product->get_parent_id() );
		}

		$product_id  = $product->get_id();
		$resolutions = array();

		// Iterate through each condition.
		foreach ( $this->conditions as $condition => $ids ) {
			// Get the resolution.
			$resolutions[] = woo_store_vacation()->service( 'resolutions' )->validate(
				$condition,
				array( $ids, $product_id )
			);
		}

		// Check if any of the resolutions are false.
		if ( in_array( false, array_unique( $resolutions ), true ) ) {
			return true;
		}

		/**
		 * Disable purchase for the product.
		 *
		 * @since 1.9.1
		 */
		do_action( 'woo_store_vacation_disable_purchase_product', $product );

		return false;
	}

	/**
	 * Remove add to cart button from the variation product page.
	 *
	 * @since 1.9.1
	 *
	 * @param WC_Product $product Product data.
	 *
	 * @return void
	 */
	public function remove_add_to_cart_button( $product ) {

		// For variable products, we need to remove the add to cart button from the variation product page.
		// Otherwise, the add to cart button will be removed from the parent product page by default.
		if ( ! $product->is_type( 'variable' ) ) {
			return;
		}

		// We are all set, let’s remove the add to cart button from the variation product page.
		remove_action( 'woocommerce_single_variation', 'woocommerce_single_variation_add_to_cart_button', 20 );
	}
}
