<?php
/**
 * The Template for displaying rate (review on wp.org) admin notice.
 *
 * @since 1.3.8
 *
 * @package woo-store-vacation
 */

defined( 'ABSPATH' ) || exit;
defined( 'WC_VERSION' ) || exit;

?>
<div id="woo-store-vacation-dismiss-rate" class="notice notice-alt is-dismissible" data-action="rate">
	<p>
		<i class="dashicons dashicons-palmtree"></i>
		<strong>
			<?php
			printf(
				/* translators: 1: Activation duration, 2: Plugin name */
				esc_html_x( '%1$s have passed since you started using %2$s.', 'admin notice', 'woo-store-vacation' ),
				esc_html( $args['usage_timestamp'] ),
				esc_html_x( 'Woo Store Vacation', 'plugin name', 'woo-store-vacation' )
			);
			?>
		</strong>
	</p>
	<p>
		<?php echo esc_html_x( ' Would you kindly consider leaving a review and letting us know how the plugin has helped your business? Your feedback is greatly appreciated!', 'admin notice', 'woo-store-vacation' ); ?>
	</p>
	<p>
		<a href="https://wordpress.org/support/plugin/woo-store-vacation/reviews?filter=5#new-post" class="button-primary notice-dismiss-later" target="_blank" rel="noopener noreferrer nofollow">
			&#9733;
			<?php echo esc_html_x( 'Give 5 Stars', 'admin notice', 'woo-store-vacation' ); ?> &#8594;
		</a>
		<button class="button-link notice-dismiss-later">
			<?php echo esc_html_x( 'Maybe later', 'admin notice', 'woo-store-vacation' ); ?>
		</button>
		<button class="button-link already-rated">
			<?php echo esc_html_x( 'I already did!', 'admin notice', 'woo-store-vacation' ); ?>
		</button>
	</p>
</div>

<?php
/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
