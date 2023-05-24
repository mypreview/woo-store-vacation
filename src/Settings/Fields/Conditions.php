<?php
/**
 * The plugin settings fields.
 *
 * @author MyPreview (Github: @mahdiyazdani, @gooklani, @mypreview)
 *
 * @since 1.8.0
 *
 * @package woo-store-vacation
 */

namespace Woo_Store_Vacation\Settings\Fields;

/**
 * Class Settings fields.
 */
class Conditions {

	/**
	 * Retrieve the settings fields for the general (default) settings tab.
	 *
	 * @since 1.8.0
	 *
	 * @param string $slug The settings tab slug.
	 *
	 * @return array
	 */
	public function get_fields( $slug ) {

		return array(
			'section_title' => array(
				'id'   => $slug,
				'type' => 'title',
				'name' => _x( 'Woo Store Vacation', 'settings section name', 'woo-store-vacation' ),
				'desc' => _x( 'Close your store temporarily by scheduling your vacation time. While your shop will remain online and accessible to visitors, new order operations will pause, and your checkout will be disabled.', 'settings field description', 'woo-store-vacation' ),
			),
			'products' => array(
				'name'     => _x( 'Products', 'settings field name', 'woo-store-vacation' ),
				'desc'     => _x( 'Select the products to exclude during the vacation mode.', 'settings field description', 'woo-store-vacation' ),
				'type'     => 'multiselect',
				'class'    => 'wc-enhanced-select',
				'id'       => 'woo_store_vacation_options[products]',
				'options'  => woo_store_vacation()->service( 'choices' )->products(),
				'autoload' => false,
				'desc_tip' => true,
			),
			'categories' => array(
				'name'     => _x( 'Categories', 'settings field name', 'woo-store-vacation' ),
				'desc'     => _x( 'Select the categories to exclude during the vacation mode.', 'settings field description', 'woo-store-vacation' ),
				'type'     => 'multiselect',
				'class'    => 'wc-enhanced-select',
				'id'       => 'woo_store_vacation_options[categories]',
				'options'  => woo_store_vacation()->service( 'choices' )->categories(),
				'autoload' => false,
				'desc_tip' => true,
			),
			'tags' => array(
				'name'     => _x( 'Tags', 'settings field name', 'woo-store-vacation' ),
				'desc'     => _x( 'Select the tags to exclude during the vacation mode.', 'settings field description', 'woo-store-vacation' ),
				'type'     => 'multiselect',
				'class'    => 'wc-enhanced-select',
				'id'       => 'woo_store_vacation_options[tags]',
				'options'  => woo_store_vacation()->service( 'choices' )->tags(),
				'autoload' => false,
				'desc_tip' => true,
			),
			'types' => array(
				'name'     => _x( 'Types', 'settings field name', 'woo-store-vacation' ),
				'desc'     => _x( 'Select the product types to exclude during the vacation mode.', 'settings field description', 'woo-store-vacation' ),
				'type'     => 'multiselect',
				'class'    => 'wc-enhanced-select',
				'id'       => 'woo_store_vacation_options[types]',
				'options'  => woo_store_vacation()->service( 'choices' )->types(),
				'autoload' => false,
				'desc_tip' => true,
			),
			'shipping_classes' => array(
				'name'     => _x( 'Shipping Classes', 'settings field name', 'woo-store-vacation' ),
				'desc'     => _x( 'Select the shipping classes to exclude during the vacation mode.', 'settings field description', 'woo-store-vacation' ),
				'type'     => 'multiselect',
				'class'    => 'wc-enhanced-select',
				'id'       => 'woo_store_vacation_options[shipping_classes]',
				'options'  => woo_store_vacation()->service( 'choices' )->shipping_classes(),
				'autoload' => false,
				'desc_tip' => true,
			),
			'section_end' => array(
				'type' => 'sectionend',
			),
		);
	}

	/**
	 * Retrun the conditions to exclude.
	 *
	 * @since 1.9.0
	 *
	 * @return array
	 */
	public function get_conditions() {

		return array(
			'products',
			'categories',
			'tags',
			'types',
			'shipping_classes',
		);
	}

	/**
	 * Retrun the active conditions to exclude.
	 *
	 * @since 1.9.0
	 *
	 * @return array
	 */
	public function get_active_conditions() {

		$active_conditions = array();

		// Loop through the conditions.
		foreach ( $this->get_conditions() as $condition ) {

			$option = woo_store_vacation()->service( 'option' )->get( $condition );

			// If the condition is empty, skip it.
			if ( empty( $option ) ) {
				continue;
			}

			// Add the condition to the active conditions array.
			$active_conditions[ $condition ] = $option;
		}

		return $active_conditions;
	}
}
