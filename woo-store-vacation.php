<?php
/**
 * The `Woo Store Vacation` bootstrap file.
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * You can redistribute this plugin/software and/or modify it under
 * the terms of the GNU General Public License as published by the
 * Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * @link https://www.mypreview.one
 *
 * @since 1.0.0
 *
 * @package woo-store-vacation
 *
 * @author MyPreview (Github: @mahdiyazdani, @gooklani, @mypreview)
 *
 * @copyright © 2015 - 2023 MyPreview. All Rights Reserved.
 *
 * @wordpress-plugin
 * Plugin Name:          Woo Store Vacation
 * Plugin URI:           https://mypreview.one/woo-store-vacation
 * Description:          Pause your store during vacations by scheduling specific dates and display a customizable notice to visitors.
 * Version:              1.7.1
 * Author:               MyPreview
 * Author URI:           https://mypreview.one/woo-store-vacation
 * Requires at least:    5.3
 * Requires PHP:         7.4
 * License:              GPL-3.0
 * License URI:          http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:          woo-store-vacation
 * Domain Path:          /languages
 * WC requires at least: 4.0
 * WC tested up to:      7.6
 */

use Automattic\WooCommerce\Utilities\FeaturesUtil;

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

/**
 * Gets the path to a plugin file or directory.
 *
 * @see https://codex.wordpress.org/Function_Reference/plugin_basename
 * @see http://php.net/manual/en/language.constants.predefined.php
 */
$woo_store_vacation_plugin_data = get_file_data(
	__FILE__,
	array(
		'name'       => 'Plugin Name',
		'plugin_uri' => 'Plugin URI',
		'version'    => 'Version',
	),
	'plugin'
);
define( 'WOO_STORE_VACATION_NAME', $woo_store_vacation_plugin_data['name'] );
define( 'WOO_STORE_VACATION_URI', $woo_store_vacation_plugin_data['plugin_uri'] );
define( 'WOO_STORE_VACATION_VERSION', $woo_store_vacation_plugin_data['version'] );
define( 'WOO_STORE_VACATION_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'WOO_STORE_VACATION_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'WOO_STORE_VACATION_IS_PRO', defined( 'WSVPRO_META' ) && WSVPRO_META );
define( 'WOO_STORE_VACATION_MIN_DIR', defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : trailingslashit( 'minified' ) );

if ( ! class_exists( 'Woo_Store_Vacation' ) ) :

	/**
	 * The Woo Store Vacation - Class
	 */
	final class Woo_Store_Vacation {

		/**
		 * Instance of the class.
		 *
		 * @since 1.0.0
		 *
		 * @var object $instance
		 */
		private static $instance;

		/**
		 * Date time format constant.
		 *
		 * @since 1.6.4
		 */
		const DATE_TIME_FORMAT = 'Y-m-d H:i:s';

		/**
		 * Plugin slug.
		 *
		 * @since 1.7.1
		 */
		const SLUG = 'woo-store-vacation';

		/**
		 * Main `Woo_Store_Vacation` instance.
		 *
		 * Insures that only one instance of Woo_Store_Vacation exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @since 1.0.0
		 *
		 * @return null|Woo_Store_Vacation The one true Woo_Store_Vacation
		 */
		public static function instance() {

			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Woo_Store_Vacation ) ) {
				self::$instance = new Woo_Store_Vacation();
				self::$instance->init();
			}

			return self::$instance;
		}


		/**
		 * Load actions.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		private function init() {

			add_action( 'init', array( self::instance(), 'textdomain' ) );
			add_action( 'admin_init', array( self::instance(), 'check_activation_timestamp' ) );
			add_action( 'admin_notices', array( self::instance(), 'admin_notices' ) );
			add_action( 'wp_ajax_woo_store_vacation_dismiss_upsell', array( self::instance(), 'dismiss_upsell' ) );
			add_action( 'wp_ajax_woo_store_vacation_dismiss_rate', array( self::instance(), 'dismiss_rate' ) );
			add_action( 'before_woocommerce_init', array( self::instance(), 'add_compatibility' ), 99 );
			add_action( 'admin_menu', array( self::instance(), 'add_submenu_page' ), 999 );
			add_filter( 'woocommerce_settings_tabs_array', array( self::instance(), 'add_settings_tab' ), 999 );
			add_action( 'woocommerce_settings_tabs_' . self::SLUG, array( self::instance(), 'render_plugin_page' ) );
			add_action( 'woocommerce_update_options_' . self::SLUG, array( self::instance(), 'update_plugin_page' ) );
			add_action( 'woocommerce_after_settings_' . self::SLUG, array( self::instance(), 'upsell_after_settings' ) );
			add_action( 'admin_enqueue_scripts', array( self::instance(), 'admin_enqueue' ) );
			add_action( 'enqueue_block_editor_assets', array( self::instance(), 'editor_enqueue' ) );
			add_action( 'woocommerce_loaded', array( self::instance(), 'close_the_shop' ) );
			add_filter( 'admin_footer_text', array( self::instance(), 'ask_to_rate' ) );
			add_filter( 'plugin_action_links_' . WOO_STORE_VACATION_PLUGIN_BASENAME, array( self::instance(), 'add_action_links' ) );
			add_filter( 'plugin_row_meta', array( self::instance(), 'add_meta_links' ), 10, 2 );

			add_shortcode( 'woo_store_vacation', '__return_empty_string' );

			register_activation_hook( __FILE__, array( self::instance(), 'activation' ) );
			register_deactivation_hook( __FILE__, array( self::instance(), 'deactivation' ) );
		}

		/**
		 * Cloning instances of this class is forbidden.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		protected function __clone() {

			_doing_it_wrong( __FUNCTION__, esc_html_x( 'Cloning instances of this class is forbidden.', 'clone', 'woo-store-vacation' ), esc_html( WOO_STORE_VACATION_VERSION ) );
		}

		/**
		 * Unserializing instances of this class is forbidden.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function __wakeup() {

			_doing_it_wrong( __FUNCTION__, esc_html_x( 'Unserializing instances of this class is forbidden.', 'wakeup', 'woo-store-vacation' ), esc_html( WOO_STORE_VACATION_VERSION ) );
		}

		/**
		 * Load languages file and text domains.
		 * Define the internationalization functionality.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function textdomain() {

			$domain = 'woo-store-vacation';
			// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
			$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

			load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . "{$domain}/{$domain}-{$locale}.mo" );
			load_plugin_textdomain( $domain, false, dirname( WOO_STORE_VACATION_PLUGIN_BASENAME ) . '/languages/' );
		}

		/**
		 * Check date on admin initiation and add to admin notice if it was more than the time limit.
		 *
		 * @since 1.6.1
		 *
		 * @return void
		 */
		public function check_activation_timestamp() {

			if ( get_transient( 'woo_store_vacation_rate' ) ) {
				return;
			}

			// If not installation date set, then add it.
			$option_name          = 'woo_store_vacation_activation_timestamp';
			$activation_timestamp = get_site_option( $option_name );

			if ( ! $activation_timestamp ) {
				add_site_option( $option_name, time() );
			}
		}

		/**
		 * Query WooCommerce activation.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function admin_notices() {

			// Query WooCommerce activation.
			if ( ! $this->is_woocommerce() ) {
				$message = sprintf(
					/* translators: 1: Dashicon, 2: Open anchor tag, 3: Close anchor tag. */
					esc_html_x( '%1$s requires the following plugin: %2$sWooCommerce%3$s', 'admin notice', 'woo-store-vacation' ),
					sprintf(
						'<i class="dashicons dashicons-admin-plugins"></i> <strong>%s</strong>',
						WOO_STORE_VACATION_NAME
					),
					'<a href="https://wordpress.org/plugins/woocommerce" target="_blank" rel="noopener noreferrer nofollow"><em>',
					'</em></a>'
				);
				?>
				<div class="notice notice-error notice-alt">
					<p><?php echo wp_kses_post( $message ); ?></p>
				</div>
				<?php
				return;
			}

			// Display a friendly admin notice upon plugin activation.
			$welcome_notice_transient = 'woo_store_vacation_welcome_notice';
			$welcome_notice           = get_transient( $welcome_notice_transient );

			if ( $welcome_notice ) {
				?>
				<div class="notice notice-info">
					<p><?php echo wp_kses_post( $welcome_notice ); ?></p>
				</div>
				<?php
				delete_transient( $welcome_notice_transient );
				return;
			}

			if ( ! WOO_STORE_VACATION_IS_PRO && ! get_transient( 'woo_store_vacation_upsell' ) && ( time() - (int) get_site_option( 'woo_store_vacation_activation_timestamp' ) ) > DAY_IN_SECONDS ) {
				?>
				<div id="<?php echo esc_attr( self::SLUG ); ?>-dismiss-upsell" class="notice notice-info woocommerce-message notice-alt is-dismissible">
					<p class="subtitle">
						<i class="dashicons dashicons-palmtree" style="vertical-align:sub"></i>
						<?php
						printf(
							/* translators: 1: Open strong tag, 2: Close strong tag. */
							esc_html_x( 'Do not settle for limited vacation options! Upgrade to %1$sWoo Store Vacation PRO%2$s for powerful features like unlimited scheduled vacations, smart logic, and more.', 'admin notice', 'woo-store-vacation' ),
							'<strong>',
							'</strong>'
						);
						?>
						<br>
						<br>
						<a href="<?php echo esc_url( WOO_STORE_VACATION_URI ); ?>" target="_blank" rel="noopener noreferrer nofollow" class="button-primary">
							<?php echo esc_html_x( 'Go PRO for More Options', 'admin notice', 'woo-store-vacation' ); ?> &#8594;
						</a>
					</p>
				</div>
				<?php
				return;
			}

			if ( ! get_transient( 'woo_store_vacation_rate' ) && ( time() - (int) get_site_option( 'woo_store_vacation_activation_timestamp' ) ) > WEEK_IN_SECONDS ) {
				$message = sprintf(
					/* translators: 1: HTML symbol, 2: Plugin name, 3: Activation duration, 4: HTML symbol, 5: Open anchor tag, 6: Close anchor tag. */
					esc_html_x( '%1$s You have been using the %2$s plugin for %3$s now, do you like it as much as we like you? %4$s %5$sRate 5-Stars%6$s', 'admin notice', 'woo-store-vacation' ),
					'&#9733;',
					esc_html( WOO_STORE_VACATION_NAME ),
					human_time_diff( (int) get_site_option( 'woo_store_vacation_activation_timestamp' ), time() ),
					'&#8594;',
					sprintf(
						'<a href="https://wordpress.org/support/plugin/%s/reviews?filter=5#new-post" class="button-primary" target="_blank" rel="noopener noreferrer nofollow">&#9733; ',
						esc_attr( self::SLUG )
					),
					'</a>'
				);
				?>
				<div id="<?php echo esc_attr( self::SLUG ); ?>-dismiss-rate" class="notice notice-info is-dismissible">
					<p><?php echo wp_kses_post( $message ); ?></p>
				</div>
				<?php
			}
		}

		/**
		 * AJAX dismiss up-sell admin notice.
		 *
		 * @since 1.3.8
		 *
		 * @return void
		 */
		public function dismiss_upsell() {

			check_ajax_referer( self::SLUG . '-dismiss' );

			set_transient( 'woo_store_vacation_upsell', true, MONTH_IN_SECONDS );

			exit();
		}

		/**
		 * AJAX dismiss ask-to-rate admin notice.
		 *
		 * @since 1.6.1
		 *
		 * @return void
		 */
		public function dismiss_rate() {

			check_ajax_referer( self::SLUG . '-dismiss' );

			set_transient( 'woo_store_vacation_rate', true, 3 * MONTH_IN_SECONDS );

			exit();
		}

		/**
		 * Declaring compatibility with HPOS.
		 *
		 * This plugin has nothing to do with "High-Performance Order Storage".
		 * However, the compatibility flag has been added to avoid WooCommerce declaring the plugin as "uncertain".
		 *
		 * @since 1.6.2
		 *
		 * @return void
		 */
		public function add_compatibility() {

			if ( ! class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
				return;
			}

			FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__ );
		}

		/**
		 * Create plugin options page.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function add_submenu_page() {

			add_submenu_page(
				'woocommerce',
				esc_html_x( 'Woo Store Vacation', 'page title', 'woo-store-vacation' ),
				esc_html_x( 'Store Vacation', 'menu title', 'woo-store-vacation' ),
				'manage_woocommerce',
				self::SLUG,
				static function() {

					// Backward compatibility for old location of the plugin’s settings page.
					wp_safe_redirect(
						add_query_arg(
							array(
								'page' => 'wc-settings',
								'tab'  => self::SLUG,
							),
							admin_url( 'admin.php' )
						)
					);
					exit();
				}
			);
		}

		/**
		 * Create plugin options tab (page).
		 * Add a new settings tab to the WooCommerce settings tabs array.
		 *
		 * @since 1.7.1
		 *
		 * @param array $settings_tabs Array of WooCommerce setting tabs & their labels.
		 *
		 * @return array
		 */
		public function add_settings_tab( $settings_tabs ) {

			$settings_tabs[ self::SLUG ] = esc_html_x( 'Store Vacation', 'tab title', 'woo-store-vacation' );

			return $settings_tabs;
		}

		/**
		 * Render and display plugin options page.
		 * Uses the WooCommerce admin fields API to output settings.
		 *
		 * @since 1.7.1
		 *
		 * @return void
		 */
		public function render_plugin_page() {

			woocommerce_admin_fields( self::get_settings() );
		}

		/**
		 * Render and display plugin options page.
		 * Uses the WooCommerce options API to save settings.
		 *
		 * @since 1.7.1
		 *
		 * @return void
		 */
		public function update_plugin_page() {

			woocommerce_update_options( self::get_settings() );
		}

		/**
		 * Promote PRO version adequately!
		 *
		 * @since 1.7.1
		 *
		 * @return void
		 */
		public function upsell_after_settings() {

			// Bail early, in case the PRO version of the plugin is installed.
			if ( WOO_STORE_VACATION_IS_PRO ) {
				return;
			}

			?>
			<div class="woocommerce-message" style="background:#fff;border:1px solid #dadada;padding:25px 20px;margin-top:20px;position:relative;">
				<h3 style="margin-top:0;">
					<?php echo esc_html_x( 'Upgrade to Woo Store Vacation PRO for Even More Powerful Features', 'upsell', 'woo-store-vacation' ); ?>
				</h3>
				<p class="importer-title">
					<?php echo esc_html_x( 'Upgrade to Woo Store Vacation PRO now and unlock a world of possibilities for your online store! With powerful features and customization options that are not available in the basic version, you’ll be able to take your online store management to the next level.', 'upsell', 'woo-store-vacation' ); ?>
				</p>
				<p class="importer-title">
					<?php echo esc_html_x( 'Here’s a summary of the features you’ll get with Woo Store Vacation PRO', 'upsell', 'woo-store-vacation' ); ?>
				</p>
				<ul style="display:grid;gap:5px 10px;grid-template-columns:repeat(auto-fit,minmax(420px,1fr));font-size:14px;margin-block:20px;">
					<li>
						<i class="dashicons dashicons-yes"></i>
						<?php echo esc_html_x( 'Schedule an unlimited number of vacation periods', 'upsell', 'woo-store-vacation' ); ?>
					</li>
					<li>
						<i class="dashicons dashicons-yes"></i>
						<?php echo esc_html_x( 'Schedule specific weekdays to close shop recurring', 'upsell', 'woo-store-vacation' ); ?>
					</li>
					<li>
						<i class="dashicons dashicons-yes"></i>
						<?php echo esc_html_x( 'Close your store immediately without prior notice', 'upsell', 'woo-store-vacation' ); ?>
					</li>
					<li>
						<i class="dashicons dashicons-yes"></i>
						<?php echo esc_html_x( 'Keep your shop open for specific user roles', 'upsell', 'woo-store-vacation' ); ?>
					</li>
					<li>
						<i class="dashicons dashicons-yes"></i>
						<?php echo esc_html_x( 'Create complex conditional logic to alter a vacation mode', 'upsell', 'woo-store-vacation' ); ?>
					</li>
					<li>
						<i class="dashicons dashicons-yes"></i>
						<?php echo esc_html_x( 'Exclude product types from vacation mode', 'upsell', 'woo-store-vacation' ); ?>
					</li>
					<li>
						<i class="dashicons dashicons-yes"></i>
						<?php echo esc_html_x( 'Allow certain Products to be purchased during vacation', 'upsell', 'woo-store-vacation' ); ?>
					</li>
					<li>
						<i class="dashicons dashicons-yes"></i>
						<?php echo esc_html_x( 'Restrict a vacation mode by Categories', 'upsell', 'woo-store-vacation' ); ?>
					</li>
					<li>
						<i class="dashicons dashicons-yes"></i>
						<?php echo esc_html_x( 'Restrict vacation mode by Tags', 'upsell', 'woo-store-vacation' ); ?>
					</li>
					<li>
						<i class="dashicons dashicons-yes"></i>
						<?php echo esc_html_x( 'Restrict vacation mode by Shipping classes', 'upsell', 'woo-store-vacation' ); ?>
					</li>
					<li>
						<i class="dashicons dashicons-yes"></i>
						<?php echo esc_html_x( 'Restrict vacation mode by WooCommerce Brands', 'upsell', 'woo-store-vacation' ); ?>
					</li>
					<li>
						<i class="dashicons dashicons-yes"></i>
						<?php echo esc_html_x( 'Import and export your plugin settings and content', 'upsell', 'woo-store-vacation' ); ?>
					</li>
				</ul>
				<p class="importer-title">
					<?php echo esc_html_x( 'Upgrade now and take advantage of all these features to grow your online store.', 'upsell', 'woo-store-vacation' ); ?>
				</p>
				<p>
					<a href="<?php echo esc_url( WOO_STORE_VACATION_URI ); ?>" class="button-primary" target="_blank" rel="noopener noreferrer nofollow">
						<?php echo esc_html_x( 'Go PRO for More Options', 'upsell', 'woo-store-vacation' ); ?> &#8594
					</a>
				</p>
			</div>
			<?php
		}

		/**
		 * Enqueue scripts and styles for admin pages.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function admin_enqueue() {

			// Enqueue a script.
			wp_register_script( self::SLUG, trailingslashit( WOO_STORE_VACATION_DIR_URL ) . 'assets/js/' . WOO_STORE_VACATION_MIN_DIR . 'admin.js', array( 'jquery', 'jquery-ui-datepicker', 'wp-i18n' ), WOO_STORE_VACATION_VERSION, true );
			wp_register_script( self::SLUG . '-upsell', trailingslashit( WOO_STORE_VACATION_DIR_URL ) . 'assets/js/' . WOO_STORE_VACATION_MIN_DIR . 'upsell.js', array( 'jquery' ), WOO_STORE_VACATION_VERSION, true );
			wp_localize_script( self::SLUG . '-upsell', 'wsvVars', array( 'dismiss_nonce' => wp_create_nonce( self::SLUG . '-dismiss' ) ) );

			if ( ! get_transient( 'woo_store_vacation_rate' ) || ! get_transient( 'woo_store_vacation_upsell' ) ) {
				wp_enqueue_script( self::SLUG . '-upsell' );
			}

			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( ! ( isset( $_GET['page'], $_GET['tab'] ) && 'wc-settings' === $_GET['page'] && self::SLUG === $_GET['tab'] ) ) {
				return;
			}

			wp_enqueue_script( self::SLUG );
		}

		/**
		 * Enqueue static resources for the editor.
		 *
		 * @since 1.7.0
		 *
		 * @return void
		 */
		public function editor_enqueue() {

			wp_enqueue_script( self::SLUG, trailingslashit( WOO_STORE_VACATION_DIR_URL ) . 'assets/js/' . WOO_STORE_VACATION_MIN_DIR . 'block.js', array( 'react', 'wp-components', 'wp-element', 'wp-i18n' ), WOO_STORE_VACATION_VERSION, true );
		}

		/**
		 * Determine whether the shop should be closed or not!
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function close_the_shop() {

			// Bail early, in case the current request is for an administrative interface page.
			if ( is_admin() ) {
				return;
			}

			$options       = get_option( 'woo_store_vacation_options', array() );
			$vacation_mode = $options['vacation_mode'] ?? 'no';

			if ( ! wc_string_to_bool( $vacation_mode ) ) {
				return;
			}

			$start_date = $options['start_date'] ?? null;
			$end_date   = $options['end_date'] ?? null;

			if ( empty( $start_date ) || empty( $end_date ) ) {
				return;
			}

			// Parses a time string according to WP timezone format.
			$timezone   = wp_timezone();
			$start_date = date_create( $start_date, $timezone );
			$end_date   = date_create( $end_date, $timezone );

			if ( ! $start_date || ! $end_date ) {
				return;
			}

			$start_date->setTime( 0, 0 );
			$end_date->setTime( 0, 0 );

			// The current time as an object using the site’s timezone.
			$today = current_datetime();

			if ( $today < $start_date || $today > $end_date ) {
				return;
			}

			$disable_purchase = $options['disable_purchase'] ?? 'no';

			if ( wc_string_to_bool( $disable_purchase ) ) {
				// Make all products not purchasable.
				add_filter( 'woocommerce_is_purchasable', '__return_false', PHP_INT_MAX );
				add_filter( 'body_class', array( $this, 'body_classes' ) );

				/**
				 * Allow third-party plugin(s) to hook into this place and add their own functionality if needed.
				 *
				 * @since 1.6.2
				 */
				do_action( 'woo_store_vacation_shop_closed' );
			}

			remove_shortcode( 'woo_store_vacation' );

			add_action( 'woocommerce_before_shop_loop', array( self::instance(), 'vacation_notice' ), 5 );
			add_action( 'woocommerce_before_single_product', array( self::instance(), 'vacation_notice' ) );
			add_action( 'woocommerce_before_cart', array( self::instance(), 'vacation_notice' ), 5 );
			add_action( 'woocommerce_before_checkout_form', array( self::instance(), 'vacation_notice' ), 5 );
			add_action( 'wp_print_styles', array( self::instance(), 'inline_css' ), 99 );

			add_shortcode( 'woo_store_vacation', array( self::instance(), 'return_vacation_notice' ) );
		}

		/**
		 * Adds and store a notice.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function vacation_notice() {

			$options = get_option( 'woo_store_vacation_options', array() );
			$btn_txt = $options['btn_txt'] ?? null;
			$btn_url = $options['btn_url'] ?? null;
			$notice  = $options['vacation_notice'] ?? null;

			if ( empty( $notice ) || ! function_exists( 'wc_print_notice' ) ) {
				return;
			}

			printf( '<div id="%s">', esc_attr( self::SLUG ) );

			if ( empty( $btn_txt ) || empty( $btn_url ) || '#' === $btn_url ) {
				$message = wp_kses_post( nl2br( $notice ) );
			} else {
				$message = sprintf(
					'<a href="%1$s" class="%2$s__btn" target="_self">%3$s</a> <span class="%2$s__msg">%4$s</span>',
					esc_url( $btn_url ),
					sanitize_html_class( self::SLUG ),
					esc_html( $btn_txt ),
					wp_kses_post( wptexturize( nl2br( $notice ) ) )
				);
			}

			wc_print_notice( $message, apply_filters( 'woo_store_vacation_notice_type', 'notice' ) );

			echo '</div>';
		}

		/**
		 * Returns notice element when shortcode is found among the post content.
		 *
		 * @since 1.7.0
		 *
		 * @return string
		 */
		public function return_vacation_notice() {

			// Flush (erase) the output buffer.
			if ( ob_get_length() ) {
				ob_flush();
			}

			// Start remembering everything that would normally be outputted,
			// but don't quite do anything with it yet.
			ob_start();

			// Output an arbitrary notice content if exists any.
			$this->vacation_notice();

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
			if ( ! is_cart() && ! is_checkout() && ! is_product() && ! is_woocommerce() && ! has_shortcode( get_the_content( null, false, $post ), 'woo_store_vacation' ) ) {
				return;
			}

			$options          = get_option( 'woo_store_vacation_options', array() );
			$text_color       = $options['text_color'] ?? '#ffffff';
			$background_color = $options['background_color'] ?? '#3d9cd2';
			$css              = sprintf(
				'
				#%1$s .woocommerce-info {
					background-color:%2$s !important;
					color:%3$s !important;
					z-index:2;
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
				.%1$s__msg {
					display:table-cell;
				}
				.%1$s__btn {
					color:%3$s !important;
					background-color:%2$s !important;
					display:table-cell;
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
				esc_attr( self::SLUG ),
				sanitize_hex_color( $background_color ),
				sanitize_hex_color( $text_color )
			);

			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			printf( '<style id="%s">%s</style>', esc_attr( self::SLUG ), $css );
		}

		/**
		 * Adds custom classes to the array of body classes.
		 *
		 * @since 1.6.4
		 *
		 * @param  array $classes Classes for the body element.
		 * @return array
		 */
		public function body_classes( $classes ) {

			/**
			 * Append a class to the body element when shop is closed.
			 */
			$classes[] = 'woo-store-vacation-shop-closed';

			return $classes;
		}

		/**
		 * Filters the “Thank you” text displayed in the admin footer.
		 *
		 * @since 1.7.0
		 *
		 * @param string $text The content that will be printed.
		 *
		 * @return string
		 *
		 * @phpcs:disable WordPress.Security.NonceVerification.Recommended
		 */
		public function ask_to_rate( $text ) {

			if ( ! ( isset( $_GET['page'], $_GET['tab'] ) && 'wc-settings' === $_GET['page'] && self::SLUG === $_GET['tab'] ) ) {
				return $text;
			}

			return sprintf(
				/* translators: 1: Open paragraph tag, 2: Plugin name, 3: Five stars, 4: Close paragraph tag. */
				esc_html__( '%1$sIf you like %2$s please leave us a %3$s rating to help us spread the word!%4$s', 'woo-store-vacation' ),
				'<p class="alignleft">',
				sprintf( '<strong>%s</strong>', esc_html( WOO_STORE_VACATION_NAME ) ),
				'<a href="https://wordpress.org/support/plugin/' . esc_html( self::SLUG ) . '/reviews?filter=5#new-post" target="_blank" rel="noopener noreferrer nofollow" aria-label="' . esc_attr__( 'five star', 'woo-store-vacation' ) . '">&#9733;&#9733;&#9733;&#9733;&#9733;</a>',
				'</p><style>#wpfooter{display:inline !important}.has-woocommerce-navigation #wpfooter{padding-left: 260px;}</style>'
			);
		}

		/**
		 * Display additional links in plugins table page.
		 * Filters the list of action links displayed for a specific plugin in the Plugins list table.
		 *
		 * @since 1.0.0
		 *
		 * @param array $links Plugin table/item action links.
		 *
		 * @return array
		 */
		public function add_action_links( $links ) {

			// Bail early, in case the PRO version of the plugin is installed.
			if ( WOO_STORE_VACATION_IS_PRO ) {
				return $links;
			}

			$plugin_links = array();
			/* translators: 1: Open anchor tag, 2: Close anchor tag. */
			$plugin_links[] = sprintf( esc_html_x( '%1$sGet PRO%2$s', 'plugin link', 'woo-store-vacation' ), sprintf( '<a href="%s" target="_blank" rel="noopener noreferrer nofollow" style="color:green;font-weight:bold;">&#127796; ', esc_url( WOO_STORE_VACATION_URI ) ), '</a>' );

			return array_merge( $plugin_links, $links );
		}

		/**
		 * Add additional helpful links to the plugin’s metadata.
		 *
		 * @since 1.0.0
		 *
		 * @param array  $links An array of the plugin’s metadata.
		 * @param string $file  Path to the plugin file relative to the plugins' directory.
		 *
		 * @return array
		 */
		public function add_meta_links( $links, $file ) {

			if ( WOO_STORE_VACATION_PLUGIN_BASENAME !== $file ) {
				return $links;
			}

			$plugin_links = array();
			/* translators: 1: Open anchor tag, 2: Close anchor tag. */
			$plugin_links[] = sprintf( esc_html_x( '%1$sCommunity support%2$s', 'plugin link', 'woo-store-vacation' ), sprintf( '<a href="https://wordpress.org/support/plugin/%s" target="_blank" rel="noopener noreferrer nofollow">', esc_html( self::SLUG ) ), '</a>' );

			if ( $this->is_woocommerce() ) {
				$settings_url = add_query_arg(
					array(
						'page' => 'wc-settings',
						'tab'  => self::SLUG,
					),
					admin_url( 'admin.php' )
				);
				/* translators: 1: Open anchor tag, 2: Close anchor tag. */
				$plugin_links[] = sprintf( esc_html_x( '%1$sSettings%2$s', 'plugin settings page', 'woo-store-vacation' ), sprintf( '<a href="%s" style="font-weight:bold;">&#9881; ', esc_url( $settings_url ) ), '</a>' );
			}

			return array_merge( $links, $plugin_links );
		}

		/**
		 * Set the activation hook for a plugin.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function activation() {

			// Set up the admin notice to be displayed on activation.
			$settings_url   = add_query_arg( 'page', self::SLUG, admin_url( 'admin.php' ) );
			$welcome_notice = sprintf(
			/* translators: 1: Dashicon, 2: Plugin name, 3: Open anchor tag, 4: Close anchor tag. */
				esc_html_x( '%1$s Thanks for installing %2$s plugin! To get started, visit the %3$splugin’s settings page%4$s.', 'admin notice', 'woo-store-vacation' ),
				'<i class="dashicons dashicons-admin-settings"></i>',
				sprintf(
					'<strong>%s</strong>',
					WOO_STORE_VACATION_NAME
				),
				sprintf(
					'<a href="%s" target="_self">',
					esc_url( $settings_url )
				),
				'</a>'
			);

			set_transient( 'woo_store_vacation_welcome_notice', $welcome_notice, MINUTE_IN_SECONDS );
		}

		/**
		 * Set the deactivation hook for a plugin.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function deactivation() {

			delete_transient( 'woo_store_vacation_rate' );
			delete_transient( 'woo_store_vacation_upsell' );
			delete_transient( 'woo_store_vacation_welcome_notice' );
		}

		/**
		 * Get all the settings for this plugin.
		 *
		 * @since 1.7.1
		 *
		 * @return array
		 */
		private static function get_settings() {

			$timezone_info          = array();
			$current_offset         = get_option( 'gmt_offset' );
			$options                = get_option( 'woo_store_vacation_options', array() );
			$invalid_end_date_style = self::is_invalid_end_date( $options['end_date'] ?? '' ) ? 'border:1px solid red;' : '';

			if ( get_option( 'timezone_string' ) || ! empty( $current_offset ) ) {
				/* translators: 1: Open span tag, 2: Local time, 3: Close span tag. */
				$timezone_info[] = sprintf( esc_html__( '%1$sLocal time is %2$s.%3$s', 'woo-store-vacation' ), '<time> ', sprintf( '<code>%s</code>', current_datetime()->format( self::DATE_TIME_FORMAT ) ), '</time>' );
			}

			/* translators: 1: The timezone of the site as a string. */
			$timezone_info[] = sprintf( esc_html__( 'Your timezone is currently in %s time.', 'woo-store-vacation' ), sprintf( '<code>%s</code>', esc_html( wc_timezone_string() ) ) );

			$settings = array(
				'section_title' => array(
					'id'   => self::SLUG,
					'type' => 'title',
					'name' => esc_html_x( 'Woo Store Vacation', 'settings section name', 'woo-store-vacation' ),
					'desc' => esc_html_x( 'Close your store temporarily by scheduling your vacation time. While your shop will remain online and accessible to visitors, new order operations will pause, and your checkout will be disabled.', 'settings field description', 'woo-store-vacation' ),
				),
				'vacation_mode' => array(
					'name'     => esc_html_x( 'Set Vacation Mode', 'settings field name', 'woo-store-vacation' ),
					'desc'     => esc_html_x( 'Turn on vacation mode and close my store publicly.', 'settings field name', 'woo-store-vacation' ),
					'type'     => 'checkbox',
					'id'       => 'woo_store_vacation_options[vacation_mode]',
					'value'    => wc_bool_to_string( $options['vacation_mode'] ?? false ),
					'autoload' => false,
				),
				'disable_purchase' => array(
					'name'     => esc_html_x( 'Disable Purchase', 'settings field name', 'woo-store-vacation' ),
					'desc'     => '&#9888; ' . esc_html_x( 'This will disable eCommerce functionality and takes out the cart, checkout process and add to cart buttons.', 'settings field description', 'woo-store-vacation' ),
					'type'     => 'checkbox',
					'id'       => 'woo_store_vacation_options[disable_purchase]',
					'value'    => wc_bool_to_string( $options['disable_purchase'] ?? false ),
					'autoload' => false,
				),
				'start_date' => array(
					'name'              => esc_html_x( 'Start Date', 'settings field name', 'woo-store-vacation' ),
					'desc'              => esc_html_x( 'The database will store a time of 00:00:00 by default.', 'settings field description', 'woo-store-vacation' ),
					'type'              => 'text',
					'class'             => self::SLUG . '-start-datepicker',
					'id'                => 'woo_store_vacation_options[start_date]',
					'css'               => 'background:#fff;',
					'autoload'          => false,
					'custom_attributes' => array( 'readonly' => true ),
				),
				'end_date' => array(
					'name'              => esc_html_x( 'End Date', 'settings field name', 'woo-store-vacation' ),
					'desc'              => esc_html_x( 'The validity of the date range begins at midnight on the "Start Date" and lasts until the start of the day on the "End Date".', 'settings field description', 'woo-store-vacation' ),
					'type'              => 'text',
					'class'             => self::SLUG . '-end-datepicker',
					'id'                => 'woo_store_vacation_options[end_date]',
					'css'               => "background:#fff;{$invalid_end_date_style}",
					'autoload'          => false,
					'custom_attributes' => array( 'readonly' => true ),
				),
				'timezone' => array(
					'type' => 'info',
					'text' => '<p class="description">' . implode( '</p><p class="description">', $timezone_info ) . '</p>',
				),
				'btn_txt' => array(
					'name'        => esc_html_x( 'Button Text', 'settings field name', 'woo-store-vacation' ),
					'desc'        => esc_html_x( 'If specified, a call to action button will be displayed next to your message.', 'settings field description', 'woo-store-vacation' ),
					'placeholder' => esc_html_x( 'Contact me &#8594;', 'settings field placeholder', 'woo-store-vacation' ),
					'type'        => 'text',
					'id'          => 'woo_store_vacation_options[btn_txt]',
					'autoload'    => false,
					'desc_tip'    => true,
				),
				'btn_url' => array(
					'name'        => esc_html_x( 'Button URL', 'settings field name', 'woo-store-vacation' ),
					'desc'        => esc_html_x( 'If specified, clicking the CTA button will direct your buyers to do something specific, like visit your contact page.', 'settings field description', 'woo-store-vacation' ),
					'placeholder' => esc_url( wp_guess_url() ),
					'type'        => 'url',
					'id'          => 'woo_store_vacation_options[btn_url]',
					'autoload'    => false,
					'desc_tip'    => true,
				),
				'vacation_notice' => array(
					'name'              => esc_html_x( 'Vacation Notice', 'settings field name', 'woo-store-vacation' ),
					'desc'              => esc_html_x( 'If specified, the notice will be displayed on your shop and single product pages if specified.', 'settings field description', 'woo-store-vacation' ),
					'default'           => esc_html_x( 'I am currently on vacation and products from my shop will be unavailable for next few days. Thank you for your patience and apologize for any inconvenience.', 'settings field placeholder', 'woo-store-vacation' ),
					'placeholder'       => esc_html_x( 'I am currently on vacation and products from my shop will be unavailable for next few days. Thank you for your patience and apologize for any inconvenience.', 'settings field placeholder', 'woo-store-vacation' ),
					'type'              => 'textarea',
					'css'               => 'min-width:50%;height:75px;',
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
						esc_html_x( '%1$sThe %2$s shortcode allows you to display the vacation notification on pages and posts at the scheduled times.%3$s', 'settings field text', 'woo-store-vacation' ),
						'<p class="description">',
						'<code>[woo_store_vacation]</code>',
						'</p>',
					),
				),
				'text_color' => array(
					'name'        => esc_html_x( 'Text Color', 'settings field name', 'woo-store-vacation' ),
					'desc'        => esc_html_x( 'It will override the default text color for the WooCommerce info notice if specified.', 'settings field description', 'woo-store-vacation' ),
					'default'     => '#ffffff',
					'placeholder' => '#ffffff',
					'type'        => 'color',
					'css'         => 'width:6em;',
					'id'          => 'woo_store_vacation_options[text_color]',
					'desc_tip'    => true,
					'autoload'    => false,
				),
				'background_color' => array(
					'name'        => esc_html_x( 'Background Color', 'settings field name', 'woo-store-vacation' ),
					'desc'        => esc_html_x( 'It will override the default background color for the WooCommerce info notice if specified.', 'settings field description', 'woo-store-vacation' ),
					'default'     => '#3d9cd2',
					'placeholder' => '#3d9cd2',
					'type'        => 'color',
					'css'         => 'width:6em;',
					'id'          => 'woo_store_vacation_options[background_color]',
					'desc_tip'    => true,
					'autoload'    => false,
				),
				'section_end' => array(
					'type' => 'sectionend',
				),
			);

			return (array) apply_filters( 'woo_store_vacation_settings_args', $settings );
		}

		/**
		 * Determine whether the end date has passed.
		 *
		 * @since 1.7.1
		 *
		 * @param string $end_date_string End date in string. i.e. 2023-01-31.
		 *
		 * @return bool
		 */
		private static function is_invalid_end_date( $end_date_string = '' ) {

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

		/**
		 * Query WooCommerce activation.
		 *
		 * @since 1.3.8
		 *
		 * @return bool
		 */
		private function is_woocommerce() {

			// This statement prevents from producing fatal errors,
			// in case the WooCommerce plugin is not activated on the site.
			$woocommerce_plugin = apply_filters( 'woo_store_vacation_woocommerce_path', 'woocommerce/woocommerce.php' ); // phpcs:ignore WooCommerce.Commenting.CommentHooks.HookCommentWrongStyle
			// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
			$subsite_active_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );
			// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
			$network_active_plugins = apply_filters( 'active_plugins', get_site_option( 'active_sitewide_plugins' ) );

			// Bail early in case the plugin is not activated on the website.
			// phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
			if ( ( empty( $subsite_active_plugins ) || ! in_array( $woocommerce_plugin, $subsite_active_plugins ) ) && ( empty( $network_active_plugins ) || ! array_key_exists( $woocommerce_plugin, $network_active_plugins ) ) ) {
				return false;
			}

			return true;
		}

	}
endif;

if ( ! function_exists( 'woo_store_vacation_init' ) ) :
	/**
	 * Begins execution of the plugin.
	 * The main function responsible for returning the one true Woo_Store_Vacation
	 * Instance to functions everywhere.
	 *
	 * This function is meant to be used like any other global variable,
	 * except without needing to declare the global.
	 *
	 * Since everything within the plugin is registered via hooks,
	 * then kicking off the plugin from this point in the file does
	 * not affect the page life cycle.
	 *
	 * @since 1.0.0
	 *
	 * @return object|Woo_Store_Vacation The one true Woo_Store_Vacation Instance.
	 */
	function woo_store_vacation_init() {

		return Woo_Store_Vacation::instance();
	}

	woo_store_vacation_init();
endif;
