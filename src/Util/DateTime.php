<?php
/**
 * The utility class for date-time related functions.
 *
 * @since 1.7.0
 *
 * @package woo-store-vacation
 */

namespace Woo_Store_Vacation\Util;

/**
 * Class DateTime.
 */
class DateTime {

	/**
	 * Date time format.
	 *
	 * @since 1.6.4
	 *
	 * @var string
	 */
	const FORMAT = 'Y-m-d H:i:s';

	/**
	 * Get the internal function.
	 *
	 * @since 1.7.0
	 *
	 * @param string $name The function name.
	 *
	 * @return string|bool|array
	 */
	public function get( $name ) {

		// Call the internal function if exists.
		if ( method_exists( $this, "get_{$name}" ) ) {
			return call_user_func( array( $this, "get_{$name}" ) );
		}

		return false;
	}

	/**
	 * Retrieve the current datetime.
	 *
	 * @since 1.9.0
	 *
	 * @return string
	 */
	private function get_current() {

		return current_datetime()->format( self::FORMAT );
	}

	/**
	 * Retrieve the current datetime.
	 *
	 * @since 1.9.0
	 *
	 * @return string
	 */
	private function get_current_utc() {

		return current_datetime()->setTimezone( timezone_open( 'UTC' ) )->format( self::FORMAT );
	}

	/**
	 * Retrieve the timezone info. e,g: Local time is 2021-01-01 00:00:00.
	 *
	 * @since 1.9.0
	 *
	 * @return array
	 */
	private function get_timezone_info() {

		return array(
			sprintf( /* translators: 1: Open span tag, 2: UTC time, 3: Close span tag. */
				esc_html__( '%1$sUniversal time is %2$s.%3$s', 'woo-store-vacation' ),
				'<time id="wsvpro-utc-time">',
				sprintf(
					'<code>%s</code>',
					$this->get_current_utc()
				),
				'</time>'
			),
			sprintf( /* translators: 1: Open span tag, 2: Local time, 3: Close span tag. */
				esc_html__( '%1$sLocal time is %2$s.%3$s', 'woo-store-vacation' ),
				'<time> ',
				sprintf( '<code>%s</code>', $this->get_current() ),
				'</time>'
			),
			sprintf( /* translators: 1: The timezone of the site as a string. */
				esc_html__( 'Your timezone is currently in %s time.', 'woo-store-vacation' ),
				sprintf(
					'<code>%s</code>',
					esc_html( wc_timezone_string() )
				)
			),
		);
	}
}
