<?php

class Ev_Pizza {

	protected static $_instance = null;
	public function __construct() {
		add_filter( 'product_type_options', array( $this, 'woo_type_options' ) );
		add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_tab' ), 50 );
		add_action( 'woocommerce_settings_ev_pizza', array( $this, 'settings_page' ) );
		add_action( 'woocommerce_update_options_ev_pizza', array( $this, 'update_woo_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_product_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_order_scripts' ) );
		add_action( 'admin_head', array( $this, 'for_correct_react' ) );
		// product admin page
		add_filter( 'woocommerce_product_data_tabs', array( $this, 'woo_set_pizza_tabs' ) );
		add_action( 'woocommerce_product_data_panels', array( $this, 'woo_add_product_pizza_fields' ) );
		add_action( 'woocommerce_process_product_meta', array( $this, 'woo_save_data' ), 10, 2 );
		// product front page
		add_action( 'woocommerce_before_add_to_cart_button', array( $this, 'output_pizza_components' ) );

		add_filter( 'product_type_selector', array( $this, 'add_product_type' ) );
		add_filter( 'woocommerce_product_class', array( $this, 'set_product_class' ), 10, 2 );
		add_action( 'wp', array( $this, 'maybe_crate_product' ) );
	}

	/**
	 * Add "PBW-Builder" product type.
	 *
	 * @param array $types Product Types.
	 * @since 1.1
	 */
	public function add_product_type( $types ) {
		$types['pbw_product'] = esc_html__( 'PBW-Builder', 'pizza-builder-for-woocommerce' );
		return $types;
	}

	public function set_product_class( $classname, $product_type ) {
		if ( 'pbw_product' === $product_type ) {
			return 'Ev_Pizza_Product_Builder';
		}
		return $classname;
	}
	public function maybe_crate_product() {

		$pizza_s_data = json_decode( wp_unslash( get_option( 'pizza_settings_data' ) ), true );
		if ( isset( $pizza_s_data['shortcode_disable'] ) && $pizza_s_data['shortcode_disable'] ) {
			return;
		}

		$pbw_builder_product = wc_get_products(
			array(
				'type' => 'pbw_product',
			)
		);
		if ( count( $pbw_builder_product ) > 0 ) {
			return;
		}
		$new_pbw_builder = new Ev_Pizza_Product_Builder();
		$new_pbw_builder->set_name( 'PBW-Builder' );
		$new_pbw_builder->set_regular_price( 0 );
		$new_pbw_builder->set_description( 'Pizza Builder for WooCooomerce: Product must be for working Shortcode Builder' );
		$new_pbw_builder->set_catalog_visibility( 'hidden' );
		$new_pbw_builder->set_status( 'private' );

		$new_pbw_builder->save();

	}

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	public function woo_type_options( $options ) {
		$options['ev_pizza'] = array(
			'id'            => '_ev_pizza',
			'wrapper_class' => 'show_if_simple show_if_variable',
			'label'         => esc_html__( 'Pizza', 'pizza-builder-for-woocommerce' ),
			'default'       => 'no',
		);
		return $options;
	}


	public function add_settings_tab( $settings_tabs ) {
		$settings_tabs['ev_pizza'] = esc_html__( 'Pizza', 'pizza-builder-for-woocommerce' );

		return $settings_tabs;
	}
	public function settings_page() {
		wp_nonce_field( 'ev_pizza_woo_settings', '_pizzanonce' );
		?>
		<div id="admin-pizza-app"></div>
		<?php
	}
	public function update_woo_settings() {
		if ( empty( $_POST['_pizzanonce'] ) || ! wp_verify_nonce( $_POST['_pizzanonce'], 'ev_pizza_woo_settings' ) ) {
			return;
		}

		update_option( 'pizza_components_data', sanitize_text_field( $_POST['pizza_components_data'] ) );
		update_option( 'pizza_settings_data', sanitize_text_field( $_POST['pizza_settings_data'] ) );
		if ( isset( $_POST['ev_pizza_shortcodes'] ) ) {
			$pizza_shortcodes = json_decode( wp_unslash( $_POST['ev_pizza_shortcodes'] ), true );
			if ( json_last_error() === JSON_ERROR_NONE ) {
				foreach ( $pizza_shortcodes as  $shortcode ) {

					update_option( 'pizza_shortcodes_data_' . $shortcode['id'], wc_clean( $shortcode ) );
				}
			}
			update_option( 'pizza_shortcodes_data', sanitize_text_field( $_POST['ev_pizza_shortcodes'] ) );
		}

	}

	public function admin_scripts() {
		if ( isset( $_GET['tab'] ) && $_GET['tab'] === 'ev_pizza' ) {

			$pizza_data       = json_decode( wp_unslash( get_option( 'pizza_components_data' ) ), true );
			$pizza_components = ! empty( $pizza_data ) ? $pizza_data : false;

			$pizza_s_data   = json_decode( wp_unslash( get_option( 'pizza_settings_data' ) ), true );
			$pizza_settings = ! empty( $pizza_s_data ) ? $pizza_s_data : false;

			$pizza_shortcodes_data = json_decode( wp_unslash( get_option( 'pizza_shortcodes_data' ) ), true );
			$pizza_shortcodes      = ! empty( $pizza_shortcodes_data ) ? $pizza_shortcodes_data : false;
			wp_enqueue_media();
			wp_enqueue_style( 'pizza-style-code', plugins_url( 'assets/css/adminPizza.css', EV_PIZZA_DIR ), array(), '1.2.3', 'all' );

			wp_enqueue_script( 'pizza-admin-react', plugins_url( 'assets/js/adminPizza.js', EV_PIZZA_DIR ), array( 'wp-element', 'wp-components', 'wp-data', 'lodash', 'wp-i18n' ), '1.2.3', true );
			wp_localize_script(
				'pizza-admin-react',
				'EV_PIZZA_DATA',
				array(

					'url'              => plugins_url( '/assets/', EV_PIZZA_DIR ),
					'pizza_components' => $pizza_components,
					'pizza_settings'   => $pizza_settings,
					'pizza_shortcodes' => $pizza_shortcodes,
					'wc_symbol'        => get_woocommerce_currency_symbol(),
				)
			);
		}
		if ( get_post_type() === 'product' ) {
			global $post;
			$product_ev_components_data = json_decode( wp_unslash( get_post_meta( $post->ID, 'product_ev_components_data', true ) ), true );
			$product_ev_components_data = ! empty( $product_ev_components_data ) ? $product_ev_components_data : false;
			$product_ev_components      = json_decode( wp_unslash( get_post_meta( $post->ID, 'product_ev_components', true ) ), true );
			$product_ev_components      = ! empty( $product_ev_components ) ? $product_ev_components : false;
			$product_pizza_data         = json_decode( wp_unslash( get_post_meta( $post->ID, 'product_ev_pizza_full', true ) ), true );
			$product_ev_pizza_full      = ! empty( $product_pizza_data ) ? $product_pizza_data : false;
			$pizza_data                 = json_decode( wp_unslash( get_option( 'pizza_components_data' ) ), true );
			$pizza_components           = ! empty( $pizza_data ) ? $pizza_data : false;

			$wc_products   = wc_get_products(
				array(
					'limit'   => -1,
					'type'    => array( 'simple', 'variation' ),
					'exclude' => array( $post->ID ),
				)
			);
			$produts_pizza = array();

			foreach ( $wc_products as $product ) {
				$produts_pizza[] = array(
					'name' => $product->get_name(),
					'id'   => $product->get_id(),
				);
			}

			wp_enqueue_media();
			wp_enqueue_script( 'pizza-product-react', plugins_url( 'assets/js/adminProductPizza.js', EV_PIZZA_DIR ), array( 'wp-element', 'wp-components', 'wp-data', 'lodash', 'wp-i18n' ), '1.2.3', true );
			wp_localize_script(
				'pizza-product-react',
				'EV_IL_DATA',
				array(

					'url'                        => plugins_url( 'assets/', EV_PIZZA_DIR ),
					'product_ev_components_data' => $product_ev_components_data,
					'product_ev_pizza_full'      => $product_ev_pizza_full,
					'product_ev_components'      => $product_ev_components,
					'pizza_components'           => $pizza_components,
					'products'                   => $produts_pizza,
					'wc_symbol'                  => get_woocommerce_currency_symbol(),
				)
			);
		}
	}

	public function enqueue_product_scripts() {

		if ( ev_pizza_tipps_enabled() ) {

			wp_enqueue_script( 'pizza-popper', plugins_url( 'assets/js/tippy/popper.min.js', EV_PIZZA_DIR ), array(), '2.11.0', true );
			wp_enqueue_script( 'pizza-tipps', plugins_url( 'assets/js/tippy/tippy.min.js', EV_PIZZA_DIR ), array(), '6.3.7', true );

		}
		if ( is_product() ) {
			wp_enqueue_script( 'pizza-slimscroller', plugins_url( 'assets/js/slimscroll/jquery.slimscroll.min.js', EV_PIZZA_DIR ), array( 'jquery' ), '1.3.8', true );

		}
		wp_enqueue_style( 'pizza-slick', plugins_url( 'assets/js/slick/slick.css', EV_PIZZA_DIR ), array(), '1.8.1', 'all' );
		wp_enqueue_script( 'pizza-slick', plugins_url( 'assets/js/slick/slick.min.js', EV_PIZZA_DIR ), array( 'jquery' ), '1.8.1', true );

		wp_enqueue_style( 'pizza-fancybox', plugins_url( 'assets/js/fancyBox/jquery.fancybox.min.css', EV_PIZZA_DIR ) );

		wp_enqueue_style( 'pizza-front', plugins_url( 'assets/css/main.css', EV_PIZZA_DIR ), array(), '1.1.1', 'all' );
		wp_enqueue_script( 'pizza-fancybox', plugins_url( 'assets/js/fancyBox/jquery.fancybox.min.js', EV_PIZZA_DIR ), array( 'jquery' ), '3.5.7', true );

		wp_enqueue_script( 'pizza-front', plugins_url( 'assets/js/pizza.js', EV_PIZZA_DIR ), array( 'jquery', 'wp-util' ), '1.2.4', true );
		wp_localize_script(
			'pizza-front',
			'FOOD_FRONT_DATA',
			array(
				'ajax_url'            => admin_url( 'admin-ajax.php' ),
				'wc_symbol'           => get_woocommerce_currency_symbol(),
				'price_position'      => get_option( 'woocommerce_currency_pos' ),
				'decimals'            => wc_get_price_decimals(),
				'layer_default_text'  => apply_filters( 'ev_pizza_template_empty_layer_text', __( 'Choose %s flour pizza', 'pizza-builder-for-woocommerce' ) ),
				'layer_default_image' => ev_pizza_get_image_placeholder( 'empty_layer' ),
				'side_default_text'   => apply_filters( 'ev_pizza_empty_side_text', __( 'Choose cheese', 'pizza-builder-for-woocommerce' ) ),
				'side_default_image'  => ev_pizza_get_image_placeholder( 'empty_side' ),
				'tippy_enabled'       => ev_pizza_tipps_enabled(),
				'redirect_cart'       => ev_pizza_redirect_cart(),
			)
		);
	}
	public function enqueue_order_scripts() {
		global $current_screen;
		if ( $current_screen->id === 'shop_order' ) {
			wp_enqueue_style( 'pizza-fancybox', plugins_url( 'assets/js/fancyBox/jquery.fancybox.min.css', EV_PIZZA_DIR ) );
			wp_enqueue_style( 'pizza-admin', plugins_url( 'assets/css/admin.css', EV_PIZZA_DIR ), array(), '1.1', 'all' );
			wp_enqueue_script( 'pizza-fancybox', plugins_url( 'assets/js/fancyBox/jquery.fancybox.min.js', EV_PIZZA_DIR ), array( 'jquery' ), '3.5.7', true );
			wp_enqueue_script( 'pizza-front', plugins_url( 'assets/js/pizza.js', EV_PIZZA_DIR ), array( 'jquery', 'wp-util' ), '1.2.4', true );
		}
	}
	public function for_correct_react() {
		?>
		<style>
			.MuiFormControl-root .MuiFormLabel-root {
				float: none;
				width: auto;
				margin: 0;
			}

			.MuiFormControl-root input[type=color],
			.MuiFormControl-root input[type=date],

			.MuiFormControl-root input[type=number],
			.MuiFormControl-root input[type=text],
			.MuiFormControl-root input[type=tel],
			.MuiFormControl-root select,
			.MuiFormControl-root textarea {
				background-color: transparent;
				border: 0;
				width: 100%;
				padding: 16.5px 14px;
				height: 55px;
				box-sizing: border-box;
			}

			.MuiFormControl-root input[type=checkbox]:focus,
			.MuiFormControl-root input[type=color]:focus,

			.MuiFormControl-root input[type=number]:focus,
			.MuiFormControl-root input[type=password]:focus,
			.MuiFormControl-root input[type=radio]:focus,
			.MuiFormControl-root input[type=text]:focus,
			.MuiFormControl-root input[type=tel]:focus,
			.MuiFormControl-root select:focus,
			.MuiFormControl-root textarea:focus {
				border-color: transparent;
				box-shadow: none;
				outline: 0;
				border-radius: 0;
			}

			.MuiFormControl-root .MuiSwitch-input {
				height: 100%;
			}

			#ev_pizza_product_data label {
				float: none;
				width: auto;
				margin-left: -11px;
				margin-right: 16px;
			}

			.ev_pizza_label {
				font-size: 22px;
			}
			#ev_pizza_product_data.woocommerce_options_panel label, #ev_pizza_product_data.woocommerce_options_panel legend {
				float: unset;
				width: auto;
				padding: 0;
				margin: 0;
			}
			
			#pizza-tabpanel-2 .MuiAccordionSummary-root, #pizza-tabpanel-1 .MuiAccordionSummary-root {
				background-color: aliceblue;
			}
		</style>
		<?php
	}

	public function woo_set_pizza_tabs( $tabs ) {
		$tabs['ev_pizza'] = array(
			'label'    => esc_html__( 'Pizza data', 'pizza-builder-for-woocommerce' ),
			'target'   => 'ev_pizza_product_data',
			'class'    => 'show_if_ev_pizza',
			'priority' => 75,

		);
		return $tabs;
	}

	public function woo_add_product_pizza_fields() {
		?>
		<div id="ev_pizza_product_data" class="panel woocommerce_options_panel hidden wc-metaboxes-wrapper">
			<div id="ilfood_data"></div>
		</div>
		<script>
			if (jQuery('#_ev_pizza').is(':checked')) {
				jQuery('.show_if_ev_pizza').show();
			} else {
				jQuery('.show_if_ev_pizza').hide();
			}
			jQuery('#_ev_pizza').on('change', function() {
				if (jQuery(this).is(':checked')) {
					jQuery('.show_if_ev_pizza').show();
				} else {
					jQuery('.show_if_ev_pizza').hide();
				}
			})
		</script>
		<?php
	}

	public function woo_save_data( $post_id, $post ) {
		update_post_meta( $post_id, '_ev_pizza', isset( $_POST['_ev_pizza'] ) ? 'yes' : 'no' );

		if ( isset( $_POST['_ev_pizza'] ) ) {

			update_post_meta( $post_id, 'product_ev_pizza_full', sanitize_text_field( $_POST['product_ev_pizza_full'] ) );
			update_post_meta( $post_id, 'ev_pizza_price_inc', isset( $_POST['ev_pizza_price_inc'] ) ? 1 : 0 );
		}
	}
	/**
	 * Output components
	 */
	public function output_pizza_components() {
		 global $post, $product;
		if ( ! ev_is_pizza_product( $post->ID ) ) {
			return;
		}
		$food_components_data = json_decode( wp_unslash( get_post_meta( $post->ID, 'product_ev_pizza_full', true ) ), true );
		$food_components_full = ! empty( $food_components_data ) ? $food_components_data : false;
		if ( $food_components_full ) {

			wc_get_template(
				'pizza/components.php',
				array(
					'data'    => apply_filters( 'ev_pizza_components_data', $food_components_full ),
					'product' => $product,
				),
				'',
				EV_PIZZA_PATH . 'templates/'
			);
		}
	}
}
