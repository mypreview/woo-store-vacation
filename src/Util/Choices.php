<?php
/**
 * The util choices for the plugin.
 *
 * @since 1.9.0
 *
 * @package woo-store-vacation
 */

namespace Woo_Store_Vacation\Util;

use WC_Data_Store;

/**
 * Class Choices.
 */
class Choices {

	/**
	 * Get the internal function.
	 *
	 * @since 1.9.0
	 *
	 * @param string $name The function name.
	 *
	 * @return array|bool
	 */
	public function get( $name ) {

		// Call the internal function if exists.
		if ( method_exists( $this, "get_{$name}" ) ) {
			return call_user_func( array( $this, "get_{$name}" ) );
		}

		return false;
	}

	/**
	 * Get the products.
	 *
	 * @since 1.9.0
	 *
	 * @return array
	 */
	private function get_products() {

		// Bail early if no products found.
		if ( ! post_type_exists( 'product' ) ) {
			return array();
		}

		$choices    = array();
		$data_store = WC_Data_Store::load( 'product' );
		$ids        = $data_store->get_products(
			array(
				'status' => 'publish',
				'limit'  => -1,
			)
		);

		// Iterate through the products.
		foreach ( $ids as $id ) {
			$product_object = wc_get_product( $id );

			// Skip if the product is not readable.
			if ( ! wc_products_array_filter_readable( $product_object ) ) {
				continue;
			}

			$formatted_name = $product_object->get_formatted_name();
			$managing_stock = $product_object->managing_stock();

			// Append the stock amount to the product name.
			if ( $managing_stock ) {
				$stock_amount    = $product_object->get_stock_quantity();
				$formatted_name .= ' &ndash; ' . sprintf( /* Translators: %d stock amount */
					__( 'Stock: %d', 'woo-store-vacation' ),
					wc_format_stock_quantity_for_display( $stock_amount, $product_object )
				);
			}

			$choices[ $product_object->get_id() ] = rawurldecode( wp_strip_all_tags( $formatted_name ) );
		}

		return $choices;
	}

	/**
	 * Get the product categories.
	 *
	 * @since 1.9.0
	 *
	 * @return array
	 */
	private function get_categories() {

		// Bail early if no products found.
		if (
			! taxonomy_exists( 'product_cat' )
			|| ! is_taxonomy_viewable( 'product_cat' )
		) {
			return array();
		}

		$choices = array();
		$args    = array(
			'taxonomy'   => array( 'product_cat' ),
			'orderby'    => 'id',
			'order'      => 'ASC',
			'hide_empty' => false,
			'fields'     => 'all',
		);

		$terms = get_terms( $args );

		// Bail early if no terms found.
		if ( ! $terms ) {
			return array();
		}

		// Iterate through the terms.
		foreach ( $terms as $term ) {
			$term->formatted_name = '';

			// Get the ancestors.
			if ( $term->parent ) {
				$ancestors = array_reverse( get_ancestors( $term->term_id, 'product_cat' ) );

				foreach ( $ancestors as $ancestor ) {
					$ancestor_term = get_term( $ancestor, 'product_cat' );
					if ( $ancestor_term ) {
						$term->formatted_name .= $ancestor_term->name . ' > ';
					}
				}
			}

			$term->formatted_name     .= $term->name . ' (' . $term->count . ')';
			$choices[ $term->term_id ] = $term->formatted_name;
		}

		return $choices;
	}

	/**
	 * Get the product tags.
	 *
	 * @since 1.9.0
	 *
	 * @return array
	 */
	private function get_tags() {

		// Bail early if no product tags found.
		if (
			! taxonomy_exists( 'product_tag' )
			|| ! is_taxonomy_viewable( 'product_tag' )
		) {
			return array();
		}

		$choices = array();
		$args    = array(
			'taxonomy'   => array( 'product_tag' ),
			'orderby'    => 'id',
			'order'      => 'ASC',
			'hide_empty' => false,
			'fields'     => 'all',
		);

		$terms = get_terms( $args );

		// Bail early if no terms found.
		if ( ! $terms ) {
			return array();
		}

		// Iterate through the terms.
		foreach ( $terms as $term ) {
			$choices[ $term->term_id ] = $term->name . ' (' . $term->count . ')';
		}

		return $choices;
	}

	/**
	 * Get the product types.
	 * e.g. simple, variable, grouped, external.
	 *
	 * @since 1.9.0
	 *
	 * @return array
	 */
	private function get_types() {

		// Bail early if no product types found.
		if ( ! taxonomy_exists( 'product_type' ) ) {
			return array();
		}

		$choices = array();
		$types   = wc_get_product_types();
		$args    = array(
			'taxonomy'   => array( 'product_type' ),
			'hide_empty' => false,
			'fields'     => 'all',
		);

		$terms = get_terms( $args );

		// Bail early if no terms found.
		if ( ! $terms ) {
			return array();
		}

		// Iterate through the terms.
		foreach ( $terms as $term ) {
			if ( ! isset( $types[ $term->name ] ) ) {
				continue;
			}

			$choices[ $term->term_id ] = $types[ $term->name ] . ' (' . $term->count . ')';
		}

		return $choices;
	}

	/**
	 * Get the shipping_classes.
	 *
	 * @since 1.9.0
	 *
	 * @return array
	 */
	private function get_shipping_classes() {

		// Bail early if no product shipping classes found.
		if ( ! taxonomy_exists( 'product_shipping_class' ) ) {
			return array();
		}

		$choices = array();
		$args    = array(
			'taxonomy'   => array( 'product_shipping_class' ),
			'hide_empty' => false,
			'fields'     => 'all',
		);

		$terms = get_terms( $args );

		// Bail early if no terms found.
		if ( ! $terms ) {
			return array();
		}

		// Iterate through the terms.
		foreach ( $terms as $term ) {
			$choices[ $term->term_id ] = $term->name . ' (' . $term->count . ')';
		}

		return $choices;
	}
}
