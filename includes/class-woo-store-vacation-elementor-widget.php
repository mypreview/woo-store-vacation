<?php
/**
 * The Elementor widget class.
 *
 * @link https://mypreview.one/woo-store-vacation
 *
 * @author MyPreview (Github: @mahdiyazdani, @gooklani, @mypreview)
 *
 * @since 1.8.1
 *
 * @package woo-store-vacation
 *
 * @subpackage woo-store-vacation/includes
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

if (
	! class_exists( 'Woo_Store_Vacation_Elementor_Widget' )
	&& class_exists( 'Elementor\Widget_Base', false )
) :

	/**
	 * The Elementor widget class.
	 */
	class Woo_Store_Vacation_Elementor_Widget extends Elementor\Widget_Base {

		/**
		 * Get widget name.
		 *
		 * @since 1.8.1
		 *
		 * @return string
		 */
		public function get_name() {

			return Woo_Store_Vacation::SLUG;
		}

		/**
		 * Get widget title.
		 *
		 * @since 1.8.1
		 *
		 * @return string
		 */
		public function get_title() {

			return __( 'Store Vacation Notice', 'woo-store-vacation' );
		}

		/**
		 * Get widget icon.
		 *
		 * @since 1.8.1
		 *
		 * @return string
		 */
		public function get_icon() {

			return 'eicon-alert';
		}

		/**
		 * Get the upsell URL.
		 *
		 * @since 1.8.1
		 *
		 * @return string
		 */
		public function get_help_url() {

			return esc_url( WOO_STORE_VACATION_URI );
		}

		/**
		 * Get widget category.
		 *
		 * @since 1.8.1
		 *
		 * @return array|string[]
		 */
		public function get_categories() {

			return array( 'woocommerce-elements' );
		}

		/**
		 * Render the widget output in the editor.
		 *
		 * @since 1.8.1
		 *
		 * @return void
		 */
		protected function render() {

			// If the user is in the editor, display a placeholder message.
			if ( Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				esc_html_e( '⚠ This alert-box is a placeholder that is displayed in place of the actual vacation notice message.', 'woo-store-vacation' );
				return;
			}

			echo do_shortcode( '[' . Woo_Store_Vacation::SHORTCODE . ']' );
		}
	}
endif;
