<?php
/**
 * The Template for displaying settings page sidebar.
 *
 * @since 1.0.0
 *
 * @package woo-store-vacation
 */

defined( 'ABSPATH' ) || exit;
defined( 'WC_VERSION' ) || exit;

?>
<div class="woo-store-vacation-page-sidebar">
	<?php
	/**
	 * Fires inside the sidebar.
	 *
	 * @since 1.0.0
	 */
	do_action( 'woo_store_vacation_settings_sidebar' );
	?>
</div>

<?php
/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
