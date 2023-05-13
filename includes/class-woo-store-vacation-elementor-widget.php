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
		 * Whether the reload preview is required or not.
		 *
		 * Used to determine whether the reload preview is required.
		 *
		 * @since 1.8.1
		 *
		 * @return bool Whether the reload preview is required.
		 */
		public function is_reload_preview_required() {

			return false;
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
		 * Register divider widget controls.
		 *
		 * Adds different input fields to allow the user to change and customize the widget settings.
		 *
		 * @since 1.8.1
		 *
		 * @return void
		 */
		protected function register_controls() {

			$this->start_controls_section(
				'section_' . Woo_Store_Vacation::SHORTCODE,
				array(
					'label' => $this->get_title(),
				)
			);

			$this->end_controls_section();
		}

		/**
		 * Remove the widget stack of controls.
		 *
		 * @since 1.8.1
		 *
		 * @param bool $with_common_controls Optional. Whether to include the common controls. Default is true.
		 *
		 * @return array Empty widget stack of controls.
		 */
		public function get_stack( $with_common_controls = true ) {

			return parent::get_stack( false );
		}

		/**
		 * Render shortcode widget as plain content.
		 *
		 * Override the default behavior by printing the shortcode instead of rendering it.
		 *
		 * @since 1.8.1
		 *
		 * @return void
		 */
		public function render_plain_content() {

			// In plain mode, render the shortcode tag.
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '[' . Woo_Store_Vacation::SHORTCODE . ']';
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

			echo do_shortcode( shortcode_unautop( '[' . Woo_Store_Vacation::SHORTCODE . ']' ) );
		}

		/**
		 * Render shortcode widget output in the editor.
		 *
		 * Written as a Backbone JavaScript template and used to generate the live preview.
		 *
		 * @since 1.8.1
		 *
		 * @return void
		 */
		protected function content_template() {}
	}
endif;
