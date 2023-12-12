<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace RTFramework;

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class Opt {

	// Get our default values
	/**
	 * @var array|mixed
	 */
	private static $panels = [];
	private static $sections = [];
	private static $fields = [];
	protected $defaults;
	protected static $instance = null;

	public function __construct() {
		// Register Panels
		add_action( 'customize_register', [ $this, 'add_customizer_panels' ] );
		// Register sections
		add_action( 'customize_register', [ $this, 'add_customizer_sections' ] );
		//Register Settings / Fields
		add_action( 'customize_register', [ $this, 'add_customizer_settings' ] );

	}

	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function populated_default_data() {
		$this->defaults = rttheme_generate_defaults();
	}

	/**
	 * Customizer Panels
	 */
	public function add_customizer_panels( $wp_customize ) {
		if ( empty( self::$panels ) ) {
			return;
		}
		// Layout Panel
		foreach ( self::$panels as $panel ) {
			$args = [
				'title'       => $panel['title'] ?? '',
				'description' => $panel['description'] ?? '',
				'priority'    => $panel['priority'] ?? 10,
			];
			$wp_customize->add_panel( $panel['id'], $args );
		}
	}

	/**
	 * Customizer sections
	 */
	public function add_customizer_sections( $wp_customize ) {

		if ( empty( self::$sections ) ) {
			return;
		}
		foreach ( self::$sections as $section ) {
			$args = [
				'title'    => $section['title'] ?? '',
				'priority' => $section['priority'] ?? '10',
			];

			if ( ! empty( $section['panel'] ) ) {
				$args['panel'] = $section['panel'];
			}

			$wp_customize->add_section( $section['id'], $args );
		}
	}

	public function add_customizer_settings( $wp_customize ) {

		error_log( print_r( self::$fields, true ) . "\n\n" , 3, __DIR__ . '/log.txt' );
		FieldManager::add_customizer_fields( $wp_customize, self::$fields );
	}

	/**
	 * Add Panel
	 *
	 * @param $panel
	 *
	 * @return void
	 */
	public static function add_panel( $panel ) {
		self::$panels[] = $panel;
	}

	public static function add_section( $section ) {
		self::$sections[] = $section;
	}

	public static function add_field( $field ) {
		self::$fields[] = $field;
	}


}

new Opt();