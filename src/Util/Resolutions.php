<?php
/**
 * The util resolutions for the plugin.
 *
 * @since 1.9.0
 *
 * @package woo-store-vacation
 */

namespace Woo_Store_Vacation\Util;

/**
 * Class Resolutions.
 */
class Resolutions {

	/**
	 * Get the internal function.
	 *
	 * @since 1.7.0
	 *
	 * @param string $name The function name.
	 * @param array  $args The function arguments.
	 *
	 * @return mixed
	 */
	public function validate( $name, $args = array() ) {

		return call_user_func( array( $this, "validate_{$name}" ), ...$args );
	}

	/**
	 * Check if the given product id is in the array of excluded products.
	 *
	 * @since 1.9.0
	 *
	 * @param array      $products   Array of excluded products.
	 * @param int|string $product_id Product id.
	 *
	 * @return bool
	 */
	private function validate_products( $products, $product_id ) {

		// phpcs:ignore WordPress.PHP.StrictInArray.FoundNonStrictFalse
		return ! in_array( $product_id, $products, false );
	}

	/**
	 * Check if the given product id is in the array of excluded categories.
	 *
	 * @since 1.9.0
	 *
	 * @param array      $categories Array of excluded categories.
	 * @param int|string $product_id Product id.
	 *
	 * @return bool
	 */
	private function validate_categories( $categories, $product_id ) {

		return ! has_term( $categories, 'product_cat', $product_id );
	}

	/**
	 * Check if the given product id is in the array of excluded tags.
	 *
	 * @since 1.9.0
	 *
	 * @param array      $tags       Array of excluded tags.
	 * @param int|string $product_id Product id.
	 *
	 * @return bool
	 */
	private function validate_tags( $tags, $product_id ) {

		return ! has_term( $tags, 'product_tag', $product_id );
	}

	/**
	 * Check if the given product id is in the array of excluded product types.
	 *
	 * @since 1.9.0
	 *
	 * @param array      $types      Array of excluded types.
	 * @param int|string $product_id Product id.
	 *
	 * @return bool
	 */
	private function validate_types( $types, $product_id ) {

		return ! has_term( $types, 'product_type', $product_id );
	}

	/**
	 * Check if the given product id is in the array of excluded product shipping classes.
	 *
	 * @since 1.9.0
	 *
	 * @param array      $shipping_classes Array of excluded shipping classes.
	 * @param int|string $product_id       Product id.
	 *
	 * @return bool
	 */
	private function validate_shipping_classes( $shipping_classes, $product_id ) {

		return ! has_term( $shipping_classes, 'product_shipping_class', $product_id );
	}
}
