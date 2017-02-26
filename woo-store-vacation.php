<?php
/*
Plugin Name: 	Woo Store Vacation
Plugin URI:  	https://www.mypreview.one
Description: 	Put your WooCommerce store in vacation or pause mode with custom notice.
Version:     	1.0.3
Author:      	Mahdi Yazdani
Author URI:  	https://www.mypreview.one
Text Domain: 	woo-store-vacation
Domain Path: 	/languages
License:     	GPL2
License URI: 	https://www.gnu.org/licenses/gpl-2.0.html
 
Woo Store Vacation is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
Woo Store Vacation is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with Woo Store Vacation. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/
// Prevent direct file access
defined( 'ABSPATH' ) or exit;
// Check the requirements of plugin (first step).
require_once dirname( __FILE__ ) . '/includes/requirements.php';
// Woo Store Vacation Class.
require_once dirname( __FILE__ ) . '/includes/WooStoreVacation.php';
// Woo Store Vacation Front Class.
require_once dirname( __FILE__ ) . '/includes/WooStoreVacationFront.php';

$woo_store_vacation_front = new WooStoreVacationFront(__FILE__);

if ( is_admin() ) :
	$woo_store_vacation = new WooStoreVacation(__FILE__);
	load_plugin_textdomain( 'woo-store-vacation', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
endif;