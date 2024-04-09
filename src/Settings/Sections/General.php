<?php
/**
 * The plugin settings fields.
 *
 * @since 1.8.0
 *
 * @package woo-store-vacation
 */

namespace Woo_Store_Vacation\Settings\Sections;

use Woo_Store_Vacation\WooCommerce;

/**
 * Class Settings fields.
 */
class General extends Section {

	/**
	 * Retrieve the settings fields for the general (default) settings tab.
	 *
	 * @since 1.8.0
	 *
	 * @return array
	 */
	public function get_fields() {

		// CSS class name for the invalid end date.
		$invalid_end_date_class = $this->is_invalid_end_date() ? 'end-date-error' : '';

		return array(
			'section_title'        => array(
				'id'   => 'woo-store-vacation-general',
				'type' => 'title',
				'name' => _x( 'Woo Store Vacation', 'settings section name', 'woo-store-vacation' ),
				'desc' => _x( 'Close your store temporarily by scheduling your vacation time. While your shop will remain online and accessible to visitors, new order operations will pause, and your checkout will be disabled.', 'settings field description', 'woo-store-vacation' ),
			),
			'vacation_mode'        => array(
				'name'     => _x( 'Enable Vacation Mode', 'settings field name', 'woo-store-vacation' ),
				'desc'     => _x( 'Check to enable vacation mode and vacation settings. Uncheck to deactivate the vacation functionality.', 'settings field name', 'woo-store-vacation' ),
				'type'     => 'checkbox',
				'id'       => 'woo_store_vacation_options[vacation_mode]',
				'value'    => wc_bool_to_string( woo_store_vacation()->service( 'options' )->get( 'vacation_mode', false ) ),
				'autoload' => false,
			),
			'disable_purchase'     => array(
				'name'     => _x( 'Disable Purchase', 'settings field name', 'woo-store-vacation' ),
				'desc'     => '&#9888; ' . _x( 'This will disable eCommerce functionality and takes out the cart, checkout process and add to cart buttons.', 'settings field description', 'woo-store-vacation' ),
				'type'     => 'checkbox',
				'id'       => 'woo_store_vacation_options[disable_purchase]',
				'value'    => wc_bool_to_string( woo_store_vacation()->service( 'options' )->get( 'disable_purchase', false ) ),
				'autoload' => false,
			),
			'start_date'           => array(
				'name'              => _x( 'Start Date', 'settings field name', 'woo-store-vacation' ),
				'desc'              => _x( 'The database will store a time of 00:00:00 by default.', 'settings field description', 'woo-store-vacation' ),
				'type'              => 'text',
				'class'             => 'woo-store-vacation-start-datepicker',
				'id'                => 'woo_store_vacation_options[start_date]',
				'autoload'          => false,
				'custom_attributes' => array( 'readonly' => true ),
			),
			'end_date'             => array(
				'name'              => _x( 'End Date', 'settings field name', 'woo-store-vacation' ),
				'desc'              => _x( 'The validity of the date range begins at midnight on the "Start Date" and lasts until the start of the day on the "End Date".', 'settings field description', 'woo-store-vacation' ),
				'type'              => 'text',
				'class'             => "woo-store-vacation-end-datepicker {$invalid_end_date_class}",
				'id'                => 'woo_store_vacation_options[end_date]',
				'autoload'          => false,
				'custom_attributes' => array( 'readonly' => true ),
			),
			'timezone'             => array(
				'type' => 'info',
				'text' => '<p class="description">' . implode( '</p><p class="description">', woo_store_vacation()->service( 'datetime' )->get( 'timezone_info' ) ) . '</p>',
			),
			'btn_txt'              => array(
				'name'        => _x( 'Button Text', 'settings field name', 'woo-store-vacation' ),
				'desc'        => _x( 'Use this field to add a call-to-action button alongside your message.', 'settings field description', 'woo-store-vacation' ),
				'placeholder' => _x( 'Contact me &#8594;', 'settings field placeholder', 'woo-store-vacation' ),
				'type'        => 'text',
				'id'          => 'woo_store_vacation_options[btn_txt]',
				'autoload'    => false,
				'desc_tip'    => true,
			),
			'btn_url'              => array(
				'name'        => _x( 'Button URL', 'settings field name', 'woo-store-vacation' ),
				'desc'        => _x( 'If a CTA button text has been added, you can use this field to specify the URL that the button should direct your buyers to, such as contact page.', 'settings field description', 'woo-store-vacation' ),
				'placeholder' => esc_url( wp_guess_url() ),
				'type'        => 'url',
				'id'          => 'woo_store_vacation_options[btn_url]',
				'autoload'    => false,
				'desc_tip'    => true,
			),
			'vacation_notice'      => array(
				'name'              => _x( 'Vacation Notice', 'settings field name', 'woo-store-vacation' ),
				'desc'              => _x( 'If specified, this text will be displayed as a notice on your shop and single product pages during your defined vacation dates.', 'settings field description', 'woo-store-vacation' ),
				'default'           => _x( 'I am currently on vacation and products from my shop will be unavailable for next few days. Thank you for your patience and apologize for any inconvenience.', 'settings field placeholder', 'woo-store-vacation' ),
				'placeholder'       => _x( 'I am currently on vacation and products from my shop will be unavailable for next few days. Thank you for your patience and apologize for any inconvenience.', 'settings field placeholder', 'woo-store-vacation' ),
				'type'              => 'textarea',
				'css'               => 'min-width:50%;height:85px;',
				'id'                => 'woo_store_vacation_options[vacation_notice]',
				'desc_tip'          => true,
				'autoload'          => false,
				'custom_attributes' => array(
					'rows' => '5',
					'cols' => '50',
				),
			),
			'vacation_notice_info' => array(
				'type' => 'info',
				'text' => sprintf(
					/* translators: 1: Open paragraph tag, 2: Shortcode, 3: Close paragraph tag. */
					_x( '%1$sThe %2$s shortcode allows you to display the vacation notification on pages and posts at the scheduled times.%3$s', 'settings field text', 'woo-store-vacation' ),
					'<p class="description">',
					'<code>[woo_store_vacation]</code>',
					'</p>',
				) .
				sprintf(
				/* translators: 1: Open paragraph tag, 2: Start date smart tag, 3: End date smart tag, 4: Close paragraph tag. */
					_x( '%1$sYou may utilize the %2$s and %3$s smart tags to automatically populate the vacation dates in your notice message.%4$s', 'settings field text', 'woo-store-vacation' ),
					'<p class="description">',
					'<code>{{start_date}}</code>',
					'<code>{{end_date}}</code>',
					'</p>',
				),
			),
			'text_color'           => array(
				'name'        => _x( 'Text Color', 'settings field name', 'woo-store-vacation' ),
				'desc'        => _x( 'If specified, it will change the text color of the WooCommerce info notice to a custom color of your choice.', 'settings field description', 'woo-store-vacation' ),
				'default'     => '#ffffff',
				'placeholder' => '#ffffff',
				'type'        => 'color',
				'css'         => 'width:6em;',
				'id'          => 'woo_store_vacation_options[text_color]',
				'desc_tip'    => true,
				'autoload'    => false,
			),
			'background_color'     => array(
				'name'        => _x( 'Background Color', 'settings field name', 'woo-store-vacation' ),
				'desc'        => _x( 'If specified, it will change the background color of the WooCommerce info notice to a custom color of your choice.', 'settings field description', 'woo-store-vacation' ),
				'default'     => '#3d9cd2',
				'placeholder' => '#3d9cd2',
				'type'        => 'color',
				'css'         => 'width:6em;',
				'id'          => 'woo_store_vacation_options[background_color]',
				'desc_tip'    => true,
				'autoload'    => false,
			),
			'section_end'          => array(
				'type' => 'sectionend',
			),
		);
	}

	/**
	 * Determine whether the end date has passed.
	 *
	 * @since 1.8.0
	 *
	 * @return bool
	 */
	private function is_invalid_end_date() {

		$end_date_string = woo_store_vacation()->service( 'options' )->get( 'end_date', false );

		// Bail early if the end date is not set.
		if ( empty( $end_date_string ) ) {
			return false;
		}

		$end_date = date_create( $end_date_string, wp_timezone() );

		// Bail early if the end date is invalid.
		if ( ! $end_date ) {
			return false;
		}

		$end_date->setTime( 0, 0 );

		$today = current_datetime();

		return $today > $end_date;
	}
}
