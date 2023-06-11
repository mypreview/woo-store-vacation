<?php
/**
 * The Template for displaying onboarding (welcome) admin notice.
 *
 * @since 1.0.0
 *
 * @package woo-store-vacation
 */

defined( 'ABSPATH' ) || exit;
defined( 'WC_VERSION' ) || exit;

?>

<div id="woo-store-vacation-dismiss-onboarding" class="notice notice-info is-dismissible" data-action="onboarding">
	<p>
		<i class="dashicons dashicons-admin-settings"></i>
		<?php
		printf(
			/* translators: 1: Plugin name, 2: Open anchor tag, 3: Close anchor tag. */
			esc_html_x( 'Thanks for installing %1$s plugin! To get started, visit the %2$splugin’s settings page%3$s.', 'admin notice', 'woo-store-vacation' ),
			sprintf(
				'<strong>%s</strong>',
				esc_html_x( 'Woo Store Vacation', 'plugin name', 'woo-store-vacation' )
			),
			sprintf(
				'<a href="%s" class="notice-dismiss-later" target="_self">',
				esc_url( $args['uri'] )
			),
			'</a>'
		);
		?>
	</p>
</div>

<?php
/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
