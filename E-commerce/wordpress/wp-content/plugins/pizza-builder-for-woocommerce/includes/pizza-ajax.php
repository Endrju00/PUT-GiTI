<?php
class Ev_Pizza_Ajax {
	public function __construct() {
		add_action( 'wp_ajax_ev_builder_product', array( $this, 'ev_builder_product' ) );
		add_action( 'wp_ajax_nopriv_ev_builder_product', array( $this, 'ev_builder_product' ) );
	}

	public function ev_builder_product() {
		if ( ! wp_verify_nonce( $_POST['_wpnonce'] ) ) {
			wp_send_json_error( 'You are not allowed' );
		}
		
		$pizza_s_data = json_decode( wp_unslash( get_option( 'pizza_settings_data' ) ), true );
		if ( isset( $pizza_s_data['shortcode_disable'] ) && $pizza_s_data['shortcode_disable'] ) {
			wp_send_json_error( 'This feature is disabled' );
		}

		$pbw_builder_product = wc_get_products(
			array(
				'type' => 'pbw_product',
			)
		);
		if ( count( $pbw_builder_product ) < 1 ) {
			wp_send_json_error( 'Can\'t add product' );
		}

		$components_ids = json_decode( wp_unslash( $_POST['pbw_components'] ), true );
		$shortcode_id   = intval( $_POST['pbw-shortcode-id'] );
		$data           = get_option( 'pizza_shortcodes_data_' . $shortcode_id );
		if ( ! $data ) {
			wp_send_json_error( 'No data' );
		}
		$meta_data = array();
		foreach ( $data['components'] as $step ) {

			foreach ( $step['components'] as $component ) {
				if ( in_array( $component['id'], $components_ids ) ) {
					$meta_data['pbw_components'][] = $component;
				}
			}
		}
		$meta_data['pbw_product_name'] = $data['title'];
		$quantity          = 1;
		$product_id        = $pbw_builder_product[0]->get_id();
		$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );

		if ( $passed_validation && false !== WC()->cart->add_to_cart( $product_id, $quantity, '', '', $meta_data ) ) {

			do_action( 'woocommerce_ajax_added_to_cart', $product_id );

			if ( 'yes' === get_option( 'woocommerce_cart_redirect_after_add' ) ) {
				wc_add_to_cart_message( array( $product_id => $quantity ), true );
			}

			WC_AJAX::get_refreshed_fragments();
		} else {
			wp_send_json_error( 'Something went wrong' );
		}
		wp_send_json_success();

	}
}
