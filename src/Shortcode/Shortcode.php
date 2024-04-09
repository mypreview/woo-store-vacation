<?php
/**
 * The Shortcode class for registering shortcodes.
 *
 * @since 1.9.0
 *
 * @package woo-store-vacation
 */

namespace Woo_Store_Vacation\Shortcode;

/**
 * Shortcode class.
 */
abstract class Shortcode {

	/**
	 * Shortcode name.
	 *
	 * @since 1.9.0
	 *
	 * @var string
	 */
	private $tag;

	/**
	 * Constructor.
	 *
	 * @since 1.9.0
	 *
	 * @param string $tag          Shortcode tag.
	 * @param bool   $with_dry_run Whether to register the shortcode with a dry run.
	 *
	 * @return void
	 */
	public function __construct( $tag, $with_dry_run = false ) {

		$this->tag = sanitize_key( $tag );

		// Register the shortcode with a dry run.
		if ( $with_dry_run ) {
			$this->dry_register();
		}
	}

	/**
	 * Get the shortcode tag name.
	 *
	 * @since 1.9.0
	 *
	 * @return string
	 */
	protected function get_tag() {

		return $this->tag;
	}

	/**
	 * Unregister the shortcode.
	 *
	 * @since 1.9.0
	 *
	 * @return void
	 */
	protected function unregister() {

		remove_shortcode( $this->tag );
	}

	/**
	 * Register the shortcode with a dry run.
	 *
	 * @since 1.9.0
	 *
	 * @return void
	 */
	protected function dry_register() {

		add_shortcode( $this->tag, '__return_empty_string' );
	}

	/**
	 * Register the shortcode with a callback.
	 *
	 * @since 1.9.0
	 *
	 * @param callable $callback Callback function.
	 *
	 * @return void
	 */
	protected function register( $callback ) {

		add_shortcode( $this->tag, $callback );
	}
}
