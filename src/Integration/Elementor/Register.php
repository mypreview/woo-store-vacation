<?php
/**
 * Plugin Elementor widget registerer.
 *
 * @since 1.8.1
 *
 * @package woo-store-vacation
 */

namespace Woo_Store_Vacation\Integration\Elementor;

/**
 * Class Register.
 */
class Register {

	/**
	 * Setup hooks and filters.
	 *
	 * @since 1.8.1
	 *
	 * @return void
	 */
	public function setup() {

		add_action( 'elementor/widgets/widgets_registered', array( $this, 'widget' ) );
	}

	/**
	 * Register the widget.
	 *
	 * @since 1.8.1
	 *
	 * @param object $widget_manager The widget manager object.
	 *
	 * @return void
	 */
	public function widget( $widget_manager ) {

		// Register the widget.
		$widget_manager->register_widget_type( woo_store_vacation()->service( 'elementor' ) );
	}
}
