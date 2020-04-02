<?php
/**
 * Class Woocommerce Cart
 *
 * Main Plugin class
 * @since 1.2.0
 */

namespace Boostify_Header_Footer\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Woocommerce {

	public function __construct() {
		add_filter( 'add_to_cart_fragments', array( $this, 'add_to_cart_fragment' ) );
		add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'maybe_init_cart' ) );
	}

	public static function render_cart_empty() {
		?>
		<div class="woocommerce-mini-cart__empty-message"><?php esc_attr_e( 'No products in the cart.', 'boostify' ); ?></div>
		<?php
	}

	public function maybe_init_cart() {
		$has_cart = is_a( WC()->cart, 'WC_Cart' );

		if ( ! $has_cart ) {
			$session_class = apply_filters( 'woocommerce_session_handler', 'WC_Session_Handler' );
			WC()->session  = new $session_class();
			WC()->session->init();
			WC()->cart     = new \WC_Cart();
			WC()->customer = new \WC_Customer( get_current_user_id(), true );
		}
	}

	public function add_to_cart_fragment( $fragments ) {
		global $woocommerce;
		ob_start();
		?>
			<span class="boostify-count-product"><?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?></span>

		<?php
		$fragments['span.boostify-count-product'] = ob_get_clean();//a.cartplus-contents,a.cart-button
		ob_end_clean();
		return $fragments;

	}

}