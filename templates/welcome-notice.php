<?php
/**
 * The Template for displaying welcome admin notice.
 *
 * @since 1.0.0
 *
 * @package woo-store-vacation
 */

defined( 'ABSPATH' ) || exit;
defined( 'WC_VERSION' ) || exit;

?>

<div class="notice notice-info is-dismissible">
	<?php echo wp_kses_post( wpautop( wptexturize( $args['content'] ) ) ); ?>
</div>
