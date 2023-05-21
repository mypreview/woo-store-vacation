<?php
/**
 * The plugin settings.
 *
 * @author MyPreview (Github: @mahdiyazdani, @gooklani, @mypreview)
 *
 * @since 1.8.0
 *
 * @package woo-store-vacation
 */

namespace Woo_Store_Vacation\Settings;

use WC_Settings_Page;
use Woo_Store_Vacation\WooCommerce\Close;

/**
 * Class Settings.
 */
class Settings extends WC_Settings_Page {

	/**
	 * Setup settings class.
	 *
	 * @since 1.8.0
	 *
	 * @return void
	 */
	public function __construct() {

		$this->assign();
		$this->enqueue();

		parent::__construct();
	}

	/**
	 * Assign the settings properties.
	 *
	 * @since 1.8.0
	 *
	 * @return void
	 */
	private function assign() {

		$this->id    = sanitize_key( woo_store_vacation()->get_slug() );
		$this->slug  = preg_replace( '/-/', '_', $this->id );
		$this->label = _x( 'Store Vacation', 'settings tab label', 'woo-store-vacation' );
	}

	/**
	 * Enqueue the settings assets.
	 *
	 * @since 1.8.0
	 *
	 * @return void
	 */
	private function enqueue() {

		// Bail early if the current page is not the settings page.
		if ( ! Helpers::is_page() ) {
			return;
		}

		wp_enqueue_style( 'woo-store-vacation-admin' );
		wp_enqueue_script( 'woo-store-vacation-admin' );
	}

	/**
	 * Get settings array.
	 *
	 * @since 1.8.0
	 *
	 * @return array
	 */
	protected function get_settings_for_default_section() {

		$invalid_end_date_style = $this->is_invalid_end_date() ? 'border:1px solid red;' : '';
		$settings               = array(
			'section_title'        => array(
				'id'   => $this->slug,
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
				'class'             => "{$this->slug}-start-datepicker",
				'id'                => 'woo_store_vacation_options[start_date]',
				'css'               => 'background:#fff;',
				'autoload'          => false,
				'custom_attributes' => array( 'readonly' => true ),
			),
			'end_date'             => array(
				'name'              => _x( 'End Date', 'settings field name', 'woo-store-vacation' ),
				'desc'              => _x( 'The validity of the date range begins at midnight on the "Start Date" and lasts until the start of the day on the "End Date".', 'settings field description', 'woo-store-vacation' ),
				'type'              => 'text',
				'class'             => "{$this->slug}-end-datepicker",
				'id'                => 'woo_store_vacation_options[end_date]',
				'css'               => "background:#fff;{$invalid_end_date_style}",
				'autoload'          => false,
				'custom_attributes' => array( 'readonly' => true ),
			),
			'timezone'             => array(
				'type' => 'info',
				'text' => '<p class="description">' . implode( '</p><p class="description">', $this->get_timezone_info() ) . '</p>',
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

		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
		return apply_filters( 'woocommerce_' . $this->id . '_settings', $settings );
	}

	/**
	 * Retrieve the timezone info. e,g: Local time is 2021-01-01 00:00:00.
	 *
	 * @since 1.8.0
	 *
	 * @return array
	 */
	private function get_timezone_info() {

		$timezone_info  = array();
		$current_offset = get_option( 'gmt_offset' );

		// Bail early if the timezone is not set or the offset is empty.
		if ( ! get_option( 'timezone_string' ) || empty( $current_offset ) ) {
			return array();
		}

		$timezone_info[] = sprintf( /* translators: 1: Open span tag, 2: Local time, 3: Close span tag. */
			esc_html__( '%1$sLocal time is %2$s.%3$s', 'woo-store-vacation' ),
			'<time> ',
			sprintf( '<code>%s</code>', current_datetime()->format( Close::DATETIME_FORMAT ) ),
			'</time>'
		);

		$timezone_info[] = sprintf( /* translators: 1: The timezone of the site as a string. */
			esc_html__( 'Your timezone is currently in %s time.', 'woo-store-vacation' ),
			sprintf(
				'<code>%s</code>',
				esc_html( wc_timezone_string() )
			)
		);

		return $timezone_info;
	}

	/**
	 * Determine whether the end date has passed.
	 *
	 * @since 1.8.0
	 *
	 * @return bool
	 */
	private static function is_invalid_end_date() {

		$end_date_string = woo_store_vacation()->service( 'options' )->get( 'end_date', false );

		if ( empty( $end_date_string ) ) {
			return false;
		}

		$end_date = date_create( $end_date_string, wp_timezone() );

		if ( ! $end_date ) {
			return false;
		}

		$end_date->setTime( 0, 0 );

		$today = current_datetime();

		return $today > $end_date;
	}
}
