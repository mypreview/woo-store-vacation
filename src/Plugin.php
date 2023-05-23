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

		foreach ( $this->get_classes() as $class => $args ) {

			// Check if the class is has condition.
			if ( ! isset( $args['condition'] ) || ! $args['condition'] ) {
				continue;
			}

			if ( isset( $args['params'] ) ) {
				( new $class() )->setup( ...$args['params'] );
				continue;
			}

			( new $class() )->setup();

		}

		add_action( 'before_woocommerce_init', array( 'Woo_Store_Vacation\\I18n', 'textdomain' ) );
		add_action( 'enqueue_block_editor_assets', array( 'Woo_Store_Vacation\\Assets', 'enqueue_editor' ) );
		add_action( 'admin_enqueue_scripts', array( 'Woo_Store_Vacation\\Assets', 'enqueue_admin' ) );
	}

	/**
	 * Get the classes to load.
	 *
	 * @since 1.9.0
	 *
	 * @return array
	 */
	private function get_classes() {

		$is_ajax     = wp_doing_ajax();
		$is_admin    = is_admin();
		$is_frontend = ! $is_admin;

		$classes = array(
			'Ajax\\Rate' => array(
				'condition' => $is_ajax,
			),
			'Ajax\\Rated' => array(
				'condition' => $is_ajax,
			),
			'Ajax\\Upsell' => array(
				'condition' => $is_ajax,
			),
			'Admin\\Menu' => array(
				'condition' => $is_admin,
			),
			'Compatibility\\WooCommerce' => array(
				'condition' => $is_admin,
			),
			'Enhancements\\Meta' => array(
				'condition' => $is_admin,
				'params'    => array(
					$this['file']->plugin_basename(),
				),
			),
			'Enhancements\\Notices' => array(
				'condition' => $is_admin,
			),
			'Enhancements\\Rate' => array(
				'condition' => $is_admin,
			),
			'Enhancements\\Upsell' => array(
				'condition' => $is_admin,
				'params'    => array(
					$this->get_slug(),
				),
			),
			'Settings\\Register' => array(
				'condition' => $is_admin,
			),
			'Shortcode\\Notice' => array(
				'condition' => $is_frontend,
			),
			'WooCommerce\Vacation' => array(
				'condition' => $is_frontend,
			),
		);

		return array_combine(
			array_map(
				fn ( $key ) => __NAMESPACE__ . '\\' . $key,
				array_keys( $classes )
			),
			$classes
		);
	}
}
