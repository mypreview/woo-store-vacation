<?php
/**
 * The Template for displaying the docs sidebar.
 *
 * @since 1.0.0
 *
 * @package woo-store-vacation
 */

defined( 'ABSPATH' ) || exit;
defined( 'WC_VERSION' ) || exit;

?>
<div class="woo-store-vacation-docs">
	<h2 class="woo-store-vacation-docs-title">
		<?php echo esc_html_x( 'Learn Settings', 'upsell', 'woo-store-vacation' ); ?>
	</h2>
	<p>
		<?php echo esc_html_x( 'Click on the link below to explore a wealth of information about the plugin’s settings, including step-by-step tutorials, configuration options, and troubleshooting guides. Everything you need to harness the full potential of the plugin is just a click away!', 'upsell', 'woo-store-vacation' ); ?>
	</p>
	<p class="woo-store-vacation-docs-cta">
		<a href="<?php echo esc_url( $args['uri'] ); ?>" target="_blank" rel="noopener noreferrer nofollow">
			<?php echo esc_html_x( 'Visit the documentation', 'upsell', 'woo-store-vacation' ); ?>
		</a>
	</p>
</div>

<?php
/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
