<?php
/**
 * Class Woocommerce Cart
 *
 * Main Plugin class
 *
 * @package Boostify_Header_Footer\Module\Woocommerce;
 *
 * Written by ptp
 */

namespace Boostify_Header_Footer\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Module Woocommerce.
 */
class Woocommerce {

	/**
	 * $icon
	 *
	 * @var icon
	 */
	public static $icon;

	/**
	 * Boostify Header Footer Builder Woocommerce Constructor.
	 */
	public function __construct() {
		add_filter( 'add_to_cart_fragments', array( $this, 'add_to_cart_fragment' ) );
		add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'maybe_init_cart' ) );
		if ( ! empty( $_REQUEST['action'] ) && 'elementor' === $_REQUEST['action'] && is_admin() ) { // phpcs:ignore
			add_action( 'init', array( $this, 'register_wc_hooks' ), 5 );
		}
		add_action( 'wp_ajax_boostify_product_remove', array( $this, 'product_remove' ) );
		add_action( 'wp_ajax_nopriv_boostify_product_remove', array( $this, 'product_remove' ) );
		add_action( 'wp_ajax_boostify_ajax_add_to_cart', array( $this, 'boostify_ajax_add_to_cart' ) );
		add_action( 'wp_ajax_nopriv_boostify_ajax_add_to_cart', array( $this, 'boostify_ajax_add_to_cart' ) );
	}

	/**
	 * Cart empty template.
	 */
	public static function render_cart_empty() {
		?>
		<div class="boostify-mini-cart-empty-message">
			<span><?php echo esc_html__( 'No products in the cart.', 'boostify' ); ?></span>
		</div>
		<?php
	}

	/**
	 * Cart Session.
	 */
	public function maybe_init_cart() {
		global $woocommerce;
		$has_cart = is_a( WC()->cart, 'WC_Cart' );

		if ( ! $has_cart ) {
			$session_class = apply_filters( 'woocommerce_session_handler', 'WC_Session_Handler' );
			WC()->session  = new $session_class();
			WC()->session->init();
			WC()->cart     = new \WC_Cart();
			WC()->customer = new \WC_Customer( get_current_user_id(), true );
		}
	}

	/**
	 * Remove cart item.
	 */
	public function product_remove() {
		// Get mini cart.
		ob_start();
		$carts = WC()->cart->get_cart();

		foreach ( $carts as $cart_item_key => $cart_item ) {
			if( $cart_item['product_id'] === $_POST['product_id'] && $cart_item_key === $_POST['cart_item_key'] ) { //phpcs:ignore
				WC()->cart->remove_cart_item( $cart_item_key );
			}
		}

		WC()->cart->calculate_totals();
		WC()->cart->maybe_set_cart_cookies();

		woocommerce_mini_cart();

		$mini_cart = ob_get_clean();

		// Fragments and mini cart are returned.
		$data = array(
			'fragments' => apply_filters(
				'woocommerce_add_to_cart_fragments',
				array(
					'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>',
				)
			),
			'cart_hash' => apply_filters(
				'woocommerce_add_to_cart_hash',
				WC()->cart->get_cart_for_session() ? md5( wp_json_encode( WC()->cart->get_cart_for_session() ) ) : '',
				WC()->cart->get_cart_for_session()
			),
		);

		\WC_AJAX::get_refreshed_fragments();

		die();
	}

	/**
	 * Remove cart item.
	 *
	 * @param (array) $fragments | Cart fragments.
	 */
	public function add_to_cart_fragment( $fragments ) {
		global $woocommerce;
		ob_start();
		self::render_cart();
		$menu_cart_html = ob_get_clean(); // phpcs:ignore
		$fragments['div.widget-cart-icon--wrapper'] = $menu_cart_html;
		ob_end_clean();

		return $fragments;
	}

	/**
	 * Register woocommrce hooks.
	 */
	public function register_wc_hooks() {
		WC()->frontend_includes();
	}

	/**
	 * Render cart item detail.
	 *
	 * @param (string) $cart_item_key | cart item key.
	 * @param (mixed)  $cart_item | cart item.
	 */
	public static function cart_item_detail( $cart_item_key, $cart_item ) {
		$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
		$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

		if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
			$product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
			$thumbnail         = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
			$product_price     = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
			$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
			?>
			<li class="woocommerce-mini-cart--item boostify-mini-cart-item">
				<div class="mini-cart-item-wrapper">
					<?php
					echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						'woocommerce_cart_item_remove_link',
						sprintf(
							'<a href="%s" class="remove remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">&times;</a>',
							esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
							esc_attr__( 'Remove this item', 'woocommerce' ),
							esc_attr( $product_id ),
							esc_attr( $cart_item_key ),
							esc_attr( $_product->get_sku() )
						),
						$cart_item_key
					);
					?>
					<div class="boostify-cart-item-info">
						<?php
						if ( empty( $product_permalink ) ) :
							?>
							<div class="mini-cart-item-thumbnail">
								<?php echo $thumbnail; //phpcs:ignore ?>
							</div>
							<?php

						else :
							?>
							<div class="mini-cart-item-thumbnail">
								<a href="<?php echo esc_url( $product_permalink ); ?>">
									<?php echo $thumbnail; //phpcs:ignore ?>
								</a>
							</div>
							<?php
						endif;

						if ( empty( $product_permalink ) ) :
							?>
							<div class="mini-cart-item-detail">
								<span class="mini-cart-item-name">
									<?php echo esc_html( $product_name ); ?>
								</span>

								<?php
									echo wc_get_formatted_cart_item_data( $cart_item ); //phpcs:ignore
									echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ) . '</span>', $cart_item, $cart_item_key ); //phpcs:ignore
								?>
							</div>
							<?php
						else :
							?>
							<div class="mini-cart-item-detail">
								<a href="<?php echo esc_url( $product_permalink ); ?>" class="mini-cart-item-name">
									<?php echo esc_html( $product_name ); ?>
								</a>
								<?php
									echo wc_get_formatted_cart_item_data( $cart_item ); //phpcs:ignore
									echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ) . '</span>', $cart_item, $cart_item_key ); //phpcs:ignore
								?>

							</div>
							<?php
						endif;
						?>
					</div>

				</div>
			</li>
			<?php
		}
	}

	/**
	 * Render Mini Cart.
	 */
	public static function render_cart() {
		if ( null === WC()->cart ) {
			return;
		}
		$widget_cart_is_hidden = apply_filters( 'woocommerce_widget_cart_is_hidden', is_cart() || is_checkout() );
		$product_count         = WC()->cart->get_cart_contents_count();
		$sub_total             = WC()->cart->get_cart_subtotal();
		$cart_items            = WC()->cart->get_cart();
		$toggle_button_link    = $widget_cart_is_hidden ? wc_get_cart_url() : '#';
		$url                   = wc_get_cart_url();
		?>
		<div class="widget-cart-icon--wrapper">
			<a href="<?php echo esc_url( $url ); ?>" class="cart-link boostify-btn--cart">
				<span class="icon--wrapper">

						<span class="boostify-subtotal">
							<?php echo $sub_total; //phpcs:ignore ?>
						</span>

					<span class="boostify-icon--cart">
						<span class="icon-cart"></span>
						<span class="boostify-count-product">
							<?php echo WC()->cart->get_cart_contents_count();//phpcs:ignore ?>
						</span>
					</span>

				</span>
			</a>
			<div class="boostify-cart-detail">
				<div class="cart-detail-wrapper">
					<div class="cart-sidebar-head">
						<div class="head-info">
							<h4 class="cart-sidebar-title"><?php echo esc_html__( 'Shopping Cart', 'boostify' ); ?></h4>
							<span class="count"><?php echo WC()->cart->get_cart_contents_count();//phpcs:ignore ?></span>
						</div>

						<button id="boostify-close-cart-sidebar" class="ion-android-close boostify-close-cart-sidebar"></button>
					</div>

					<?php
					do_action( 'woocommerce_before_mini_cart' );
					if ( empty( $cart_items ) ) {
						self::render_cart_empty();
					} else {
						?>
						<ul class="woocommerce--mini-cart boostify-mini-cart boostify-cart_list boostify-product_list_widget">
						<?php

						do_action( 'woocommerce_before_mini_cart_contents' );

						foreach ( $cart_items as $cart_item_key => $cart_item ) {
							self::cart_item_detail( $cart_item_key, $cart_item );
						}

						do_action( 'woocommerce_mini_cart_contents' );
						?>
						</ul>
						<div class="woocommerce-mini-cart-bottom boostify-cart-sidebar-bottom">
							<div class="boostify-mini-cart__total total">
								<?php
								/**
								 * Hook: woocommerce_widget_shopping_cart_total.
								 *
								 * @hooked woocommerce_widget_shopping_cart_subtotal - 10
								 */
								do_action( 'woocommerce_widget_shopping_cart_total' );
								?>
							</div>

							<?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>

							<div class="boostify-mini-cart__buttons buttons"><?php do_action( 'woocommerce_widget_shopping_cart_buttons' ); ?></div>

							<?php do_action( 'woocommerce_widget_shopping_cart_after_buttons' ); ?>
						</div>
						<?php
					}
					?>


				</div>
			</div>
		</div>

		<?php
	}

	/**
	 * Ajax add to cart.
	 */
	public function boostify_ajax_add_to_cart() {
		global $woocommerce;
		check_ajax_referer( 'boostify_nonce' );
		$product_id        = apply_filters( 'woocommerce_add_to_cart_product_id', absint( isset( $_POST['product_id'] ) ) );
		$quantity          = empty( $_POST['quantity'] ) ? 1 : wc_stock_amount( $_POST['quantity'] ); //phpcs:ignore
		$variation_id      = absint( isset( $_POST['variation_id'] ) ? $_POST['variation_id'] : 0 );
		$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );
		$product_status    = get_post_status( $product_id );

		if ( $passed_validation && WC()->cart->add_to_cart( $product_id, $quantity, $variation_id ) && 'publish' === $product_status ) {

			do_action( 'woocommerce_ajax_added_to_cart', $product_id );

			if ( 'yes' === get_option( 'woocommerce_cart_redirect_after_add' ) ) {
				wc_add_to_cart_message( array( $product_id => $quantity ), true );
			}

			\WC_AJAX::get_refreshed_fragments();
		} else {

			$data = array(
				'error'       => true,
				'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id ),
			);

			echo wp_send_json( $data ); //phpcs:ignore
		}

		wp_die();
	}
}
