<?php
/**
 * The Template for displaying upsell block.
 *
 * @since 1.0.0
 *
 * @package woo-store-vacation
 */

defined( 'ABSPATH' ) || exit;
defined( 'WC_VERSION' ) || exit;

?>
<div class="woocommerce-message" style="background:#fff;border:1px solid #dadada;padding:25px 20px;margin-top:20px;position:relative;">
	<h3 style="margin-top:0;">
		<?php echo esc_html_x( 'Upgrade to Woo Store Vacation PRO for Even More Powerful Features', 'upsell', 'woo-store-vacation' ); ?>
	</h3>
	<p class="importer-title">
		<?php echo esc_html_x( 'Looking for more vacation options for your online store? Upgrade to Woo Store Vacation PRO and get access to powerful features and customization options that are not available in the basic version. Say goodbye to limited options and elevate your online store with premium tools.', 'upsell', 'woo-store-vacation' ); ?>
	</p>
	<p class="importer-title">
		<?php echo esc_html_x( 'Here’s a summary of the features you’ll get with Woo Store Vacation PRO:', 'upsell', 'woo-store-vacation' ); ?>
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
		<a href="<?php echo esc_url( $args['uri'] ); ?>" class="button-primary" target="_blank" rel="noopener noreferrer nofollow">
			<?php echo esc_html_x( 'Go PRO for More Options', 'upsell', 'woo-store-vacation' ); ?> &#8594
		</a>
	</p>
</div>
