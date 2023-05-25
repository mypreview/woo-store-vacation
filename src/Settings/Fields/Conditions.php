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
				'name' => _x( 'Set Conditions', 'settings section name', 'woo-store-vacation' ),
				'desc' => _x( 'If you have enabled the “Disable Purchase” option in the General settings, you can further customize the availability of your shop using the settings provided here. These options allow you to select specific products that will remain available for purchase while your shop is in vacation mode.', 'settings field description', 'woo-store-vacation' ),
			),
			'products' => array(
				'name'     => _x( 'Products', 'settings field name', 'woo-store-vacation' ),
				'desc'     => _x( 'Choose which products you want to keep available for purchase while your shop is closed for vacation.', 'settings field description', 'woo-store-vacation' ),
				'type'     => 'multiselect',
				'class'    => 'wc-enhanced-select',
				'id'       => 'woo_store_vacation_options[products]',
				'options'  => woo_store_vacation()->service( 'choices' )->products(),
				'autoload' => false,
				'desc_tip' => true,
			),
			'categories' => array(
				'name'     => _x( 'Categories', 'settings field name', 'woo-store-vacation' ),
				'desc'     => _x( 'Choose the categories that you want to make available for purchase while your shop is closed for vacation.', 'settings field description', 'woo-store-vacation' ),
				'type'     => 'multiselect',
				'class'    => 'wc-enhanced-select',
				'id'       => 'woo_store_vacation_options[categories]',
				'options'  => woo_store_vacation()->service( 'choices' )->categories(),
				'autoload' => false,
				'desc_tip' => true,
			),
			'tags' => array(
				'name'     => _x( 'Tags', 'settings field name', 'woo-store-vacation' ),
				'desc'     => _x( 'Choose the tags that you want to make available for purchase while your shop is closed for vacation.', 'settings field description', 'woo-store-vacation' ),
				'type'     => 'multiselect',
				'class'    => 'wc-enhanced-select',
				'id'       => 'woo_store_vacation_options[tags]',
				'options'  => woo_store_vacation()->service( 'choices' )->tags(),
				'autoload' => false,
				'desc_tip' => true,
			),
			'types' => array(
				'name'     => _x( 'Types', 'settings field name', 'woo-store-vacation' ),
				'desc'     => _x( 'Choose the product types you want to keep available for purchase while your shop is closed for vacation.', 'settings field description', 'woo-store-vacation' ),
				'type'     => 'multiselect',
				'class'    => 'wc-enhanced-select',
				'id'       => 'woo_store_vacation_options[types]',
				'options'  => woo_store_vacation()->service( 'choices' )->types(),
				'autoload' => false,
				'desc_tip' => true,
			),
			'shipping_classes' => array(
				'name'     => _x( 'Shipping Classes', 'settings field name', 'woo-store-vacation' ),
				'desc'     => _x( 'Choose the shipping classes you want to keep available for purchase while your shop is closed for vacation.', 'settings field description', 'woo-store-vacation' ),
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
	 * Retrun the active conditions to exclude.
	 *
	 * @since 1.9.0
	 *
	 * @return array
	 */
	public function get_active() {

		// If the conditions are empty, return an empty array.
		if ( empty( $this->get() ) ) {
			return array();
		}

		$active_conditions = array();

		// Loop through the conditions.
		foreach ( $this->get() as $condition ) {

			$option_value = woo_store_vacation()->service( 'options' )->get( $condition );

			// If the condition is empty, skip it.
			if ( empty( $option_value ) ) {
				continue;
			}

			// Add the condition to the active conditions array.
			$active_conditions[ $condition ] = $option_value;
		}

		return $active_conditions;
	}

	/**
	 * Compile a list of the available condition keys.
	 *
	 * @since 1.9.0
	 *
	 * @return array
	 */
	private function get() {

		static $conditions = array();

		if ( empty( $conditions ) ) {
			$conditions = array_filter(
				array_keys( $this->get_fields( '' ) ),
				static fn( $key) => mb_strpos( $key, 'section_' ) !== 0
			);
		}

		return $conditions;

	}
}
