<?php
/**
 * The implementation of the Pimple service provider interface.
 *
 * @since 1.9.0
 *
 * @package woo-store-vacation
 */

namespace Woo_Store_Vacation;

/**
 * Class PluginServiceProvider.
 */
class PluginServiceProvider implements Vendor\Pimple\ServiceProviderInterface {

	/**
	 * Registers services on the given container.
	 *
	 * This method should only be used to configure services and parameters.
	 * It should not get services.
	 *
	 * @since 1.9.0
	 *
	 * @param Vendor\Pimple\Container $pimple Container instance.
	 */
	public function register( $pimple ) {

		// Plugin core.
		$pimple['template_manager'] = fn() => new TemplateManager();

		// Plugin integrations.
		$pimple['elementor'] = fn() => new Integration\Elementor\Widget();

		// Plugin settings.
		$pimple['settings']            = fn() => new Settings\Settings();
		$pimple['settings_general']    = fn() => new Settings\Sections\General();
		$pimple['settings_conditions'] = fn() => new Settings\Sections\Conditions();
		$pimple['options']             = fn() => new Settings\Options();

		// Shortcodes.
		$pimple['notice'] = fn() => new Shortcode\Notice();

		// Plugin utilities.
		$pimple['choices']     = fn() => new Util\Choices();
		$pimple['datetime']    = fn() => new Util\DateTime();
		$pimple['resolutions'] = fn() => new Util\Resolutions();
	}
}
