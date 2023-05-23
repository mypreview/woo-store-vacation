<?php
/**
 * The Template for displaying upsell admin notice.
 *
 * @since 1.3.8
 *
 * @package woo-store-vacation
 */

defined( 'ABSPATH' ) || exit;
defined( 'WC_VERSION' ) || exit;

?>
<div id="<?php echo esc_attr( woo_store_vacation()->get_slug() ); ?>-dismiss-upsell" class="notice woocommerce-message notice-alt is-dismissible">
	<p>
		<i class="dashicons dashicons-palmtree"></i>
		<strong>
			<?php echo esc_html_x( 'Upgrade to Woo Store Vacation PRO', 'admin notice', 'woo-store-vacation' ); ?>
		</strong>
	</p>
	<p>
		<?php echo esc_html_x( 'Unlock a whole new world of customization! Enjoy unlimited vacation scheduling, automated weekday closing times, smart conditional logic, and more.', 'admin notice', 'woo-store-vacation' ); ?>
	</p>
	<p>
		<?php echo esc_html_x( 'Upgrade now to experience the full potential of the plugin!', 'admin notice', 'woo-store-vacation' ); ?>
	</p>
	<p>
		<a href="<?php echo esc_url( $args['uri'] ); ?>" class="button-primary" target="_blank" rel="noopener noreferrer nofollow">
			<?php echo esc_html_x( 'Go PRO for More Options', 'admin notice', 'woo-store-vacation' ); ?> &#8594;
		</a>
	</p>
</div>
