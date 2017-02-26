<?php
/**
 * Woo Store Vacation Front Class.
 *
 * @author      Mahdi Yazdani
 * @package     Woo Store Vacation
 * @since       1.1.0
 */
// Prevent direct file access
if ( ! defined( 'ABSPATH' ) ) exit;
if ( !class_exists( 'WooStoreVacationFront' ) ) :
    class WooStoreVacationFront {
        private $file;
        private $dir;
        public function __construct($file) {
            $this->file = $file;
            $this->dir = dirname( $this->file );
            $this->front_assets_url = esc_url( trailingslashit( plugins_url( 'front/', $this->file ) ) );

            add_action('init', array( $this, 'woo_store_vacation_mode' ), 10);

        }

        public function woo_store_vacation_custom_notice() {
            // Retrieve plugin option value(s)
            $woo_store_vacation = get_option( 'woo_store_vacation_option_name' );
            $vacation_notice = ( isset($woo_store_vacation['vacation_notice']) ) ? esc_attr( $woo_store_vacation['vacation_notice'] ) : '';
            $woo_store_bgcolor = ( isset($woo_store_vacation['notice_bgcolor']) ) ? esc_attr( $woo_store_vacation['notice_bgcolor'] ) : '#000000';
            $woo_store_textcolor = ( isset($woo_store_vacation['notice_textcolor']) ) ? esc_attr( $woo_store_vacation['notice_textcolor'] ) : '#FFFFFF';
            $woo_store_customcss = ( isset($woo_store_vacation['notice_customcss']) ) ? esc_attr( $woo_store_vacation['notice_customcss'] ) : '';
            ?>
            <style type="text/css">
                #woo-store-vacation-alert{
                    background-color: <?php echo $woo_store_bgcolor ?>;
                    color: <?php echo $woo_store_textcolor ?>;
                    <?php echo $woo_store_customcss ?>
                }
            </style>
            <script type="text/javascript">
                jQuery(document).ready(function() {
                    var $vacationAlert = jQuery('<div id="woo-store-vacation-alert"></div>');
                    $vacationAlert.html(<?php echo json_encode(nl2br($vacation_notice)) ?>)
                    jQuery("body").prepend($vacationAlert);
                });
            </script>
            <?php
        }
        public function woo_store_front_vacation_scripts() {
            wp_enqueue_style('woo-store-vacation-jquery-stickyalert', $this->front_assets_url . 'css/custom.css', false, '1.0', false);
        }
        function woo_store_vacation_mode() {
            // Retrieve plugin option value(s)
            $woo_store_vacation = get_option( 'woo_store_vacation_option_name' );
            $enable_vacation_mode = ( isset($woo_store_vacation['enable_vacation_mode']) ) ? esc_attr( $woo_store_vacation['enable_vacation_mode'] ) : '';
            $disable_purchase = ( isset($woo_store_vacation['disable_purchase']) ) ? esc_attr( $woo_store_vacation['disable_purchase'] ) : '';
            $end_date = ( isset($woo_store_vacation['end_date']) ) ? esc_attr( strtotime($woo_store_vacation['end_date']) ) : '';
            $today = strtotime(current_time( 'd-m-Y', $gmt = 0 ));
            if(isset($enable_vacation_mode, $end_date) && !empty($enable_vacation_mode) && !empty($end_date) && $today < $end_date):
                if(isset($disable_purchase) && !empty($disable_purchase)):
                    remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
                    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
                    remove_action( 'woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20 );
                    remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );
                endif;
                add_action('wp_enqueue_scripts', array( $this, 'woo_store_front_vacation_scripts' ), 10);
                add_action('wp_head', array( $this, 'woo_store_vacation_custom_notice' ), 10);
            endif;
        }
    }
endif;