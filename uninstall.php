<?php
/**
 * Unistall Woo Store Vacation.
 * Fired when the plugin is uninstalled.
 *
 * @author      Mahdi Yazdani
 * @package     Woo Store Vacation
 * @since       1.3.0
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
} // End If Statement

$option_name = 'woo_store_vacation_options';

delete_option( $option_name );
// For site options in Multisite
delete_site_option( $option_name );