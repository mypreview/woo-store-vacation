<?php
/**
 * The core plugin class.
 *
 * @author MyPreview (Github: @mahdiyazdani, @gooklani, @mypreview)
 *
 * @since 1.9.0
 *
 * @package woo-store-vacation
 */

namespace Woo_Store_Vacation;

use Pimple\Container;
use Woo_Store_Vacation\Compatibility;
use Woo_Store_Vacation\Enhancements;
use Woo_Store_Vacation\Shortcode;
use Woo_Store_Vacation\Settings;
use Woo_Store_Vacation\WooCommerce;

/**
 * The plugin class.
 */
class Plugin extends Container {

	/**
	 * The plugin version.
	 *
	 * @since 1.9.0
	 *
	 * @var string
	 */
	private string $version;

	/**
	 * Constructor.
	 *
	 * @since 1.9.0
	 *
	 * @param string $version The plugin version.
	 * @param string $file    The plugin file.
	 *
	 * @return void
	 */
	public function __construct( string $version, string $file ) {

		// Set the version.
		$this->version = $version;

		// Pimple Container construct.
		parent::__construct();

		// Register the file service.
		$this['file'] = fn() => new File( $file );

		// Register services early.
		$this->register_services();

		// Load the plugin.
		$this->load();
	}

	/**
	 * Register services.
	 *
	 * @since 1.9.0
	 *
	 * @return void
	 */
	private function register_services(): void {

		$provider = new PluginServiceProvider();
		$provider->register( $this );
	}

	/**
	 * Get a service by given key.
	 *
	 * @since 1.9.0
	 *
	 * @param string $key The service key.
	 *
	 * @return mixed
	 */
	public function service( string $key ) {

		return $this[ $key ];
	}

	/**
	 * Get the plugin slug.
	 *
	 * @since 1.9.0
	 *
	 * @return string
	 */
	public function get_slug(): string {

		return 'woo-store-vacation';
	}

	/**
	 * Get the plugin version.
	 *
	 * @since 1.9.0
	 *
	 * @return string
	 */
	public function get_version(): string {

		return $this->version;
	}

	/**
	 * Start loading classes on `woocommerce_loaded`, priority 20.
	 *
	 * @since 1.9.0
	 *
	 * @return void
	 */
	private function load(): void {

		if ( is_admin() ) {
			// Add compatibility with WooCommerce (core) features.
			$hpos = new Compatibility\WooCommerce();
			$hpos->setup();

			// Add plugin action links.
			$meta = new Enhancements\Meta();
			$meta->setup( $this['file']->plugin_basename() );

			// Add plugin notices.
			$notices = new Enhancements\Notices();
			$notices->setup();

			// Add plugin upsell.
			$upsell = new Enhancements\Upsell();
			$upsell->setup( $this->get_slug() );

			// Register plugin settings.
			$settings = new Settings\Register();
			$settings->setup();
		} else {

			// Register the shortcode.
			$notice = new Shortcode\Notice();
			$notice->setup();

			// WooCommerce close store.
			$wc_close = new WooCommerce\Close();
			$wc_close->setup();
		}

		add_action( 'before_woocommerce_init', array( 'Woo_Store_Vacation\\I18n', 'textdomain' ) );
		add_action( 'enqueue_block_editor_assets', array( 'Woo_Store_Vacation\\Assets', 'enqueue_editor' ) );
		add_action( 'admin_enqueue_scripts', array( 'Woo_Store_Vacation\\Assets', 'enqueue_admin' ) );
	}
}
