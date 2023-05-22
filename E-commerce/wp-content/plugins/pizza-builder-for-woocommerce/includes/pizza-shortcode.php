<?php
class Ev_Pizza_Shortcode {
	public function __construct() {
		add_action( 'init', array( $this, 'add_shortcode' ) );
	}

	public function add_shortcode() {
		add_shortcode( 'pbw-builder', array( $this, 'display_shortcode' ) );
	}

	public function display_shortcode( $atts ) {
		$atts        = shortcode_atts(
			array(
				'id'    => 0,
				'steps' => 1,
				'title' => '',
			),
			$atts
		);
		$option_name = 'pizza_shortcodes_data_' . $atts['id'];
		$steps_data  = get_option( $option_name );
		if ( ! $steps_data ) {
			return '<div>No data for given shortcode</div>';
		}
		$atts['data'] = $steps_data;
		wp_localize_script(
			'pizza-front',
			'pbw_builder_' . $atts['id'],
			array(
				'data' => $steps_data,
			)
		);
		ob_start();
		wc_get_template( 'builder/steps.php', $atts, '', EV_PIZZA_PATH . 'templates/' );
		?>
		<script type="text/html" id="tmpl-pizza-builder-choosen" >
		<div class="pbw-builder-step__component active" data-choosen="{{{data.id}}}">
						<div class="pbw-builder-step__inner">
						
						<div class="pbw-builder-step__name">{{{data.name}}}</div>
						<div class="pbw-builder-step__image">
							<img src="{{{data.image}}}" />
							
						</div>
						<div class="pbw-builder-step__price">{{{data.price}}}</div>
						</div>
					</div>
		</script>
		<?php
		return ob_get_clean();

	}
}
