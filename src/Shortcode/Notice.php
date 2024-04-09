<?php
/**
 * Vacation notice shortcode class.
 *
 * @since 1.0.0
 *
 * @package woo-store-vacation
 */

namespace Woo_Store_Vacation\Shortcode;

/**
 * Notice class.
 */
class Notice extends Shortcode {

	/**
	 * Shortcode name.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const SHORTCODE_NAME = 'woo_store_vacation';

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {

		// Parent constructor.
		parent::__construct( self::SHORTCODE_NAME, true );
	}

	/**
	 * Setup hooks and filters.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function setup() {

		add_action( 'woo_store_vacation_vacation_mode', array( $this, 'init' ) );
	}

	/**
	 * Initialize the shortcode, and register the hooks.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function init() {

		// Unregister the shortcode.
		$this->unregister();

		add_action( 'woocommerce_before_shop_loop', array( $this, 'print' ), 5 );
		add_action( 'woocommerce_before_single_product', array( $this, 'print' ) );
		add_action( 'woocommerce_before_cart', array( $this, 'print' ), 5 );
		add_action( 'woocommerce_before_checkout_form', array( $this, 'print' ), 5 );
		add_action( 'wp_print_styles', array( $this, 'inline_css' ), 99 );

		// Register the shortcode with the new callback.
		$this->register( array( $this, 'retrieve' ) );
	}

	/**
	 * Print the vacation notice.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function print() {

		$notice = woo_store_vacation()->service( 'options' )->get( 'vacation_notice' );

		// Bail early, if the notice is empty.
		if ( empty( $notice ) || ! function_exists( 'wc_print_notice' ) ) {
			return;
		}

		$btn_txt = woo_store_vacation()->service( 'options' )->get( 'btn_txt' );
		$btn_url = woo_store_vacation()->service( 'options' )->get( 'btn_url' );
		$notice  = $this->process_smart_tags( $notice );

		printf( '<div id="%s">', esc_attr( woo_store_vacation()->get_slug() ) );

		// If the button text or URL is empty, or the URL is set to `#`, then don't print the button.
		if ( empty( $btn_txt ) || empty( $btn_url ) || '#' === $btn_url ) {
			$message = wp_kses_post( nl2br( $notice ) );
		} else {
			$message = sprintf(
				'<a href="%1$s" class="%2$s__btn" target="_self">%3$s</a> <span class="%2$s__msg">%4$s</span>',
				esc_url( $btn_url ),
				sanitize_html_class( woo_store_vacation()->get_slug() ),
				esc_html( $btn_txt ),
				wp_kses_post( wptexturize( nl2br( $notice ) ) )
			);
		}

		/**
		 * Print the notice.
		 *
		 * Filters the notice type.
		 * Possible values: `notice`, `error`, `success`.
		 *
		 * @since 1.7.0
		 *
		 * @param string $notice_type Notice type.
		 */
		wc_print_notice( $message, apply_filters( 'woo_store_vacation_notice_type', 'notice' ) );

		echo '</div>';
	}

	/**
	 * Return notice element when shortcode is found among the post-content.
	 *
	 * @since 1.7.0
	 *
	 * @return string
	 */
	public function retrieve() {

		// Flush (erase) the output buffer.
		if ( ob_get_length() ) {
			ob_flush();
		}

		// Start remembering everything that would normally be outputted,
		// but don't quite do anything with it yet.
		ob_start();

		// Output an arbitrary notice content if exists any.
		$this->print();

		// Get current buffer contents and delete current output buffer.
		$output_string = ob_get_contents();
		ob_end_clean(); // Turn off output buffering.

		return $output_string;
	}

	/**
	 * Print inline stylesheet before closing </head> tag.
	 * Specific to `Store vacation` notice message.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function inline_css() {

		global $post;

		// Bailout, if any of the pages below are not displaying at the moment.
		if (
			! is_cart()
			&& ! is_checkout()
			&& ! is_product()
			&& ! is_woocommerce()
			// Check if the vacation notice shortcode exists in the (raw) page content.
			&& false === strpos( get_post_field( 'post_content', $post ), '[' . $this->get_tag() . ']' )
		) {
			return;
		}

		$slug             = woo_store_vacation()->get_slug();
		$text_color       = woo_store_vacation()->service( 'options' )->get( 'text_color' );
		$background_color = woo_store_vacation()->service( 'options' )->get( 'background_color' );
		$inline_css[]     = sprintf(
			'
			#%1$s {
				padding: 0;
			}
			#%1$s .woocommerce-info {
				text-align:left;
				list-style:none;
				border:none;
				border-left:.6180469716em solid rgba(0,0,0,.15);
				border-radius:2px;
				padding:1em 1.618em;
				margin:1.617924em 0 2.617924em 0;
			}
			#%1$s .woocommerce-info::before {
				content:none;
			}
			.%1$s__btn {
				float:right;
				padding:0 0 0 1em;
				background:0 0;
				line-height:1.618;
				margin-left:2em;
				border:none;
				border-left:1px solid rgba(255,255,255,.25)!important;
				border-radius:0;
				box-shadow:none!important;
				text-decoration:none;
			}',
			esc_attr( $slug )
		);

		// If text color is specified, then add it to the inline CSS.
		if ( ! empty( $text_color ) ) {
			$inline_css[] = sprintf(
				'
				#%1$s,
				#%1$s .wc-block-components-notice-banner {
					color:%2$s !important;
				}
				#%1$s * {
					color:inherit !important;
				}',
				esc_attr( $slug ),
				sanitize_hex_color( $text_color )
			);
		}

		// If background color is specified, then add it to the inline CSS.
		if ( ! empty( $background_color ) ) {
			$inline_css[] = sprintf(
				'
				#%1$s,
				#%1$s .wc-block-components-notice-banner {
					background-color:%2$s !important;
				}
				#%1$s svg {
					background-color:%3$s !important;
				}
				#%1$s .wc-block-components-notice-banner {
					border-color:%3$s !important;
				}
				#%1$s * {
					background-color:inherit !important;
				}',
				esc_attr( $slug ),
				sanitize_hex_color( $background_color ),
				sanitize_hex_color( wc_hex_darker( $background_color ) )
			);
		}

		printf(
			'<style id="%s-inline-css">%s</style>',
			esc_attr( $slug ),
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			implode( '', $inline_css )
		);
	}

	/**
	 * Process smart tags.
	 *
	 * @since 1.8.1
	 *
	 * @param string $notice Vacation notice text.
	 *
	 * @return string
	 */
	private function process_smart_tags( $notice ) {

		// Bail-out, if there is no smart tag.
		if ( ! preg_match( '/{{(.*?)}}/', $notice ) ) {
			return $notice;
		}

		$timezone    = wp_timezone();
		$date_format = get_option( 'date_format' );
		$start_date  = date_create( woo_store_vacation()->service( 'options' )->get( 'start_date' ), $timezone );
		$end_date    = date_create( woo_store_vacation()->service( 'options' )->get( 'end_date' ), $timezone );

		// List of smart tags.
		$smart_tags = array(
			'start_date' => $start_date->format( $date_format ),
			'end_date'   => $end_date->format( $date_format ),
		);

		// Loop through each tag and replace it in the string.
		foreach ( $smart_tags as $tag => $value ) {
			$notice = str_replace( '{{' . $tag . '}}', $value, $notice );
		}

		return $notice;
	}
}
