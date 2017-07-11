<?php
/**
 * FM submenu page with setting for overriding plugin js
 */

/**
 * Class for dev mode settings
 */
class Simplechart_Dev_Mode_Settings {
	/**
	 * @var instance
	 */
	private static $instance;

	private function __construct() {
		/* Don't do anything, needs to be initialized via instance() method */
	}

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new Simplechart_Dev_Mode_Settings;
			self::$instance->setup();
		}
		return self::$instance;
	}

	public function setup() {
		add_action( 'fm_user', array( $this, 'options_init' ) );
		add_action( 'fm_element_markup_start', array( $this, 'prepend_static_content' ), 10, 2 );
	}

	public function options_init() {
		$js_source = new Fieldmanager_Radios( array(
			'name' => 'simplechart_dev_mode',
			'options' => simplechart_dev_mode_get_options(),
			'default_value' => 'plugin',
		) );
		$js_source->add_user_form( 'Simplechart Dev Mode' );

		$subtitle_args = array(
			'label' => __( 'Enable subtitle (Metadata)', 'simplechart-dev-mode' ),
		);

		if ( apply_filters( 'simplechart_enable_subtitle_field', false ) ) {
			$subtitle_args = array_merge( $subtitle_args, array(
				'attributes' => array(
					'disabled' => true,
					'checked' => true,
				),
				'description' => __( 'Subtitle field enabled by simplechart_enable_subtitle_field filter in theme', 'simplechart-dev-mode' ),
			) );
		} else {
			$subtitle_args['description'] = __( 'simplechart_enable_subtitle_field will apply only when you are the logged in user', 'simplechart-dev-mode' );
		}

		$optional_fields = new Fieldmanager_Group( array(
			'name' => 'simplechart_optional_fields',
			'children' => array(
				'subtitle' => new Fieldmanager_Checkbox( $subtitle_args ),
			),
		) );

		$optional_fields->add_user_form( __( 'Simplechart Optional Fields', 'simplechart-dev-mode' ) );
	}

	public function prepend_static_content( $out, $field ) {
		if ( 'simplechart_dev_mode' === $field->name ) {
			require( SC_DEV_MODE_PATH . '/inc/settings-page-prepend.php' );
		}
		return $out;
	}
}
