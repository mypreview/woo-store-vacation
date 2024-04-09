<?php
/**
 * The plugin settings sections.
 *
 * @since 1.9.0
 *
 * @package woo-store-vacation
 */

namespace Woo_Store_Vacation\Settings\Sections;

/**
 * Class Settings sections.
 */
abstract class Section {

	/**
	 * Retrieve the settings fields for the section.
	 *
	 * @since 1.9.0
	 *
	 * @return array
	 */
	abstract public function get_fields();

	/**
	 * Compile a list of the available field keys.
	 *
	 * @since 1.9.0
	 *
	 * @return array
	 */
	protected function get_field_keys() {

		static $keys = array();

		if ( empty( $keys ) ) {
			$keys = array_filter(
				array_keys( $this->get_fields() ),
				static fn( $key) => mb_strpos( $key, 'section_' ) !== 0
			);
		}

		return $keys;
	}
}
