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
class Customize {

	// Get our default values
	/**
	 * @var array|mixed
	 */
	private static $panels       = [];
	private static $sections     = [];
	private static $fields       = [];
	private static $fields_group = [];
	protected $defaults;
	protected static $instance = null;

	public function __construct() {
		$this->includes_custom_controls();
		// Register Panels
		add_action( 'customize_register', [ $this, 'add_customizer_panels' ] );
		// Register sections
		add_action( 'customize_register', [ $this, 'add_customizer_sections' ] );
		// Register Settings / Controls
		add_action( 'customize_register', [ $this, 'add_customizer_controls' ] );
	}

	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * @return void
	 */
	public function includes_custom_controls() {
		require_once RT_FRAMEWORK_BASE_DIR . 'inc/customize/custom-controls/sanitization.php';
		require_once RT_FRAMEWORK_BASE_DIR . 'inc/customize/custom-controls/headings.php';
		require_once RT_FRAMEWORK_BASE_DIR . 'inc/customize/custom-controls/switch-control.php';
		require_once RT_FRAMEWORK_BASE_DIR . 'inc/customize/custom-controls/image-radio-control.php';
		require_once RT_FRAMEWORK_BASE_DIR . 'inc/customize/custom-controls/separator-control.php';
		require_once RT_FRAMEWORK_BASE_DIR . 'inc/customize/custom-controls/gallery-control.php';
		require_once RT_FRAMEWORK_BASE_DIR . 'inc/customize/custom-controls/select2-control.php';
		require_once RT_FRAMEWORK_BASE_DIR . 'inc/customize/custom-controls/alfa-color.php';
		require_once RT_FRAMEWORK_BASE_DIR . 'inc/customize/custom-controls/repeater-control.php';
		require_once RT_FRAMEWORK_BASE_DIR . 'inc/customize/custom-controls/bg-attributes.php';
		require_once RT_FRAMEWORK_BASE_DIR . 'inc/customize/custom-controls/tinymce-control.php';
		require_once RT_FRAMEWORK_BASE_DIR . 'inc/customize/custom-controls/typography/typography-controls.php';
		// require_once RT_FRAMEWORK_BASE_DIR . 'inc/customize/custom-controls/test.php';
		// require_once RT_FRAMEWORK_BASE_DIR . 'inc/customize/custom-controls/typography/typography-customizer.php';
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

	/**
	 * Add customizer Settings
	 *
	 * @param $wp_customize
	 *
	 * @return void
	 */
	public function add_customizer_controls( $wp_customize ) {
		new FieldManager( $wp_customize, self::$fields, self::$fields_group );
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

	/**
	 * Add Panels
	 *
	 * @param $panels
	 *
	 * @return void
	 */
	public static function add_panels( $panels ) {
		foreach ( $panels as $panel ) {
			self::$panels[] = $panel;
		}
	}

	/**
	 * Add sections
	 *
	 * @param $section
	 *
	 * @return void
	 */
	public static function add_section( $section ) {
		self::$sections[] = $section;
	}

	/**
	 * Add Controls
	 *
	 * @param $field
	 *
	 * @return void
	 */
	public static function add_control( $field ) {
		self::$fields[] = $field;
	}


	/**
	 * Add Controls
	 *
	 * @param $field
	 *
	 * @return void
	 */
	public static function add_controls( $section, $fields ) {
		self::$fields_group[ $section ] = $fields;
	}
}

new Customize();
