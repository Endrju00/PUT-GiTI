<?php

/**
 * Custom quantity input for different input_names.
 */
function ev_pizza_woo_quantity_input( $args = array(), $product = null, $echo = true ) {
	if ( is_null( $product ) ) {
		$product = $GLOBALS['product'];
	}

	$defaults = array(
		'input_id'     => uniqid( 'quantity_' ),
		'input_name'   => 'quantity',
		'input_value'  => '1',
		'classes'      => apply_filters( 'woocommerce_quantity_input_classes', array( 'input-text', 'qty', 'text' ), $product ),
		'max_value'    => apply_filters( 'woocommerce_quantity_input_max', -1, $product ),
		'min_value'    => apply_filters( 'woocommerce_quantity_input_min', 0, $product ),
		'step'         => apply_filters( 'woocommerce_quantity_input_step', 1, $product ),
		'pattern'      => apply_filters( 'woocommerce_quantity_input_pattern', has_filter( 'woocommerce_stock_amount', 'intval' ) ? '[0-9]*' : '' ),
		'inputmode'    => apply_filters( 'woocommerce_quantity_input_inputmode', has_filter( 'woocommerce_stock_amount', 'intval' ) ? 'numeric' : '' ),
		'product_name' => $product ? $product->get_title() : '',
		'placeholder'  => apply_filters( 'woocommerce_quantity_input_placeholder', '', $product ),
	);

	$args = apply_filters( 'woocommerce_quantity_input_args', wp_parse_args( $args, $defaults ), $product );

	// Apply sanity to min/max args - min cannot be lower than 0.
	$args['min_value'] = max( $args['min_value'], 0 );
	$args['max_value'] = 0 < $args['max_value'] ? $args['max_value'] : '';

	// Max cannot be lower than min if defined.
	if ( '' !== $args['max_value'] && $args['max_value'] < $args['min_value'] ) {
		$args['max_value'] = $args['min_value'];
	}

	ob_start();

	wc_get_template( 'global/component-quantity-input.php', $args, '', EV_PIZZA_PATH . 'templates/' );

	if ( $echo ) {

		echo ob_get_clean();
	} else {
		return ob_get_clean();
	}
}

/**
 * Check whether product is Pizza product.
 */
function ev_is_pizza_product( $product_id ) {
	return get_post_meta( $product_id, '_ev_pizza', true ) === 'yes' ? true : false;
}

/**
 * Check if Tipps enabled in Settings page.
 */
function ev_pizza_tipps_enabled() {
	 $pizza_s_data  = json_decode( wp_unslash( get_option( 'pizza_settings_data' ) ), true );
	$pizza_settings = ! empty( $pizza_s_data ) ? $pizza_s_data : false;
	if ( $pizza_settings ) {
		return $pizza_settings['tipps']['enabled'];
	}
	return false;
}

/**
 * Check if Cart/Order meta popup enabled in Settings page.
 *
 * @since 1.1.3
 */
function ev_pizza_meta_popup_enabled() {
	$pizza_s_data   = json_decode( wp_unslash( get_option( 'pizza_settings_data' ) ), true );
	$pizza_settings = ! empty( $pizza_s_data ) ? $pizza_s_data : false;
	if ( ! $pizza_settings ) {
		return true;
	} elseif ( $pizza_settings && isset( $pizza_settings['meta_popup'] ) && $pizza_settings['meta_popup'] ) {
		return true;
	} elseif ( $pizza_settings && ! isset( $pizza_settings['meta_popup'] ) ) {
		return true;
	}
	return false;
}

/**
 * Get image placeholders from Settings page.
 */
function ev_pizza_get_image_placeholder( string $image ) {
	$pizza_s_data   = json_decode( wp_unslash( get_option( 'pizza_settings_data' ) ), true );
	$pizza_settings = ! empty( $pizza_s_data ) ? $pizza_s_data : false;
	if ( $pizza_settings ) {
		if ( $pizza_settings[ $image ] ) {
			if ( isset( $pizza_settings[ $image ]['image_ID'] ) ) {
				return wp_get_attachment_image_url( $pizza_settings[ $image ]['image_ID'], 'medium' );
			}
			return $pizza_settings[ $image ]['image'];
		}
	}
}

/**
 * Get cart url from Settings page.
 */
function ev_pizza_redirect_cart() {
	$pizza_s_data = json_decode( wp_unslash( get_option( 'pizza_settings_data' ) ), true );
	if ( isset( $pizza_s_data['redirect_cart'] ) && $pizza_s_data['redirect_cart'] ) {
		return wc_get_cart_url();
	}

	return false;
}

/**
 * Display list of cart/order meta.
 */
function ev_pizza_display_meta( $item_data, $popup_enabled = true ) {
	ob_start();
	?>
	<ul class="pizza-meta-list">
		<?php if ( isset( $item_data['consists_of']['consists'] ) ) : ?>
			<li>
				<strong><?php echo esc_html( $item_data['consists_of']['consists_text'] ); ?></strong>
				<?php foreach ( $item_data['consists_of']['consists'] as $component ) : ?>
					<p><span><?php echo wp_kses_post( $component['key'] ); ?></span>
					<?php if ( ! $popup_enabled ) : ?>
						<span> - </span>
						<?php endif; ?>
					<span><?php echo wp_kses_post( $component['value'] ); ?></span></p>
				<?php endforeach; ?>
			</li>
		<?php endif; ?>
		<?php if ( isset( $item_data['consists_of']['to_add'] ) ) : ?>
			<li>
				<strong><?php echo esc_html( $item_data['consists_of']['to_add_text'] ); ?></strong>
				<?php foreach ( $item_data['consists_of']['to_add'] as $component ) : ?>
					<p><span><?php echo wp_kses_post( $component['key'] ); ?></span>
					<?php if ( ! $popup_enabled ) : ?>
						<span> - </span>
						<?php endif; ?>
					<span><?php echo wp_kses_post( $component['value'] ); ?></span></p>
				<?php endforeach; ?>
			</li>
		<?php endif; ?>

		<?php if ( isset( $item_data['layers']['components'] ) ) : ?>
			<li>
				<strong><?php echo esc_html( $item_data['layers']['layers_text'] ); ?></strong>
				<?php foreach ( $item_data['layers']['components'] as $component ) : ?>
					<p><span><?php echo wp_kses_post( $component['key'] ); ?></span>
					<?php if ( ! $popup_enabled ) : ?>
						<span> - </span>
						<?php endif; ?>
					<span><?php echo wp_kses_post( $component['value'] ); ?></span></p>
				<?php endforeach; ?>
			</li>
		<?php endif; ?>
		<?php if ( isset( $item_data['bortik']['components'] ) ) : ?>
			<li>
				<strong><?php echo esc_html( $item_data['bortik']['bortik_text'] ); ?></strong>
				<?php foreach ( $item_data['bortik']['components'] as $component ) : ?>
					<p><span><?php echo wp_kses_post( $component['key'] ); ?></span>
					<?php if ( ! $popup_enabled ) : ?>
						<span> - </span>
						<?php endif; ?>
					<span><?php echo wp_kses_post( $component['value'] ); ?></span></p>
				<?php endforeach; ?>
			</li>
		<?php endif; ?>
	</ul>
	<?php
	return ob_get_clean();
}
