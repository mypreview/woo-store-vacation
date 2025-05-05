<?php
/**
 * The Template for displaying upsell sidebar.
 *
 * @since 1.0.0
 *
 * @package woo-store-vacation
 */

defined( 'ABSPATH' ) || exit;
defined( 'WC_VERSION' ) || exit;

?>
<div class="woo-store-vacation-get-pro">
	<div class="woo-store-vacation-get-pro-logo"></div>
	<h2 class="woo-store-vacation-get-pro-title">
		<?php echo esc_html_x( 'Get Store Vacation Pro', 'upsell', 'woo-store-vacation' ); ?>
	</h2>
	<ul class="woo-store-vacation-get-pro-features">
		<li>
			<?php echo esc_html_x( 'Automate your schedules', 'upsell', 'woo-store-vacation' ); ?>
		</li>
		<li>
			<?php echo esc_html_x( 'Repeat weekday closings', 'upsell', 'woo-store-vacation' ); ?>
		</li>
		<li>
			<?php echo esc_html_x( 'Define smart conditions', 'upsell', 'woo-store-vacation' ); ?>
		</li>
		<li>
			<?php echo esc_html_x( 'Unlimited store notices', 'upsell', 'woo-store-vacation' ); ?>
		</li>
		<li>
			<?php echo esc_html_x( '24/7 priority support', 'upsell', 'woo-store-vacation' ); ?>
		</li>
	</ul>
	<p class="woo-store-vacation-get-pro-cta">
		<a href="<?php echo esc_url( $args['uri'] ); ?>" target="_blank" rel="noopener noreferrer nofollow">
			<?php echo esc_html_x( 'Go PRO for More Options', 'upsell', 'woo-store-vacation' ); ?>
		</a>
	</p>
	<div class="woo-store-vacation-get-pro-rate">
		<a href="https://wordpress.org/support/plugin/woo-store-vacation/reviews?filter=5" target="_blank" rel="noopener noreferrer nofollow">
			<strong>
				<?php echo esc_html_x( 'Read reviews from real users', 'upsell', 'woo-store-vacation' ); ?>
			</strong>
			<div>
				<span class="dashicons dashicons-wordpress"></span>
				<span class="dashicons dashicons-star-filled"></span>
				<span class="dashicons dashicons-star-filled"></span>
				<span class="dashicons dashicons-star-filled"></span>
				<span class="dashicons dashicons-star-filled"></span>
				<span class="dashicons dashicons-star-half"></span>
				<span class="woo-store-vacation-get-pro-rating-text">4.5 / 5</span>
			</div>
		</a>
	</div>
</div>

<?php
/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
