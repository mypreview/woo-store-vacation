<?php
/**
 * The implementation of the Pimple service provider interface.
 *
 * @author MyPreview (Github: @mahdiyazdani, @gooklani, @mypreview)
 *
 * @since 1.9.0
 *
 * @package woo-store-vacation
 */

namespace Woo_Store_Vacation;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Woo_Store_Vacation\Integration;
use Woo_Store_Vacation\Settings;
use Woo_Store_Vacation\Shortcode;
use Woo_Store_Vacation\Util;

/**
 * Class PluginServiceProvider.
 */
class PluginServiceProvider implements ServiceProviderInterface {

	/**
	 * Registers services on the given container.
	 *
	 * This method should only be used to configure services and parameters.
	 * It should not get services.
	 *
	 * @since 1.9.0
	 *
	 * @param Container $pimple Container instance.
	 */
	public function register( Container $pimple ): void {

		// Plugin integrations.
		$pimple['elementor'] = fn() => new Integration\Elementor\Widget();

		// Plugin settings.
		$pimple['settings']            = fn() => new Settings\Settings();
		$pimple['settings_general']    = fn() => new Settings\Fields\General();
		$pimple['settings_conditions'] = fn() => new Settings\Fields\Conditions();
		$pimple['options']             = fn() => new Settings\Options();

		// Shortcodes.
		$pimple['notice'] = fn() => new Shortcode\Notice();

		// Plugin utilities.
		$pimple['choices']     = fn() => new Util\Choices();
		$pimple['resolutions'] = fn() => new Util\Resolutions();
	}
}
