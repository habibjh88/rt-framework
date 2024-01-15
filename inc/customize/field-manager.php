<?php

namespace RTFramework;


use RTFramework\CustomControl\Customizer_Alfa_Color;
use RTFramework\CustomControl\Customizer_Custom_Heading;
use RTFramework\CustomControl\Customizer_Dropdown_Select2_Control;
use RTFramework\CustomControl\Customizer_Gallery_Control;
use RTFramework\CustomControl\Customizer_Image_Radio_Control;
use RTFramework\CustomControl\Customizer_Sortable_Repeater_Control;
use RTFramework\CustomControl\Customizer_Separator_Control;
use RTFramework\CustomControl\Customizer_Switch_Control;
use RTFramework\CustomControl\Customizer_BG_Attributes_Control;
use RTFramework\CustomControl\Customizer_TinyMCE_control;
use RTFramework\CustomControl\Customizer_Test;
use RTFramework\CustomControl\Typography\Customizer_Google_Fonts_Controls;

use WP_Customize_Control;
use WP_Customize_Date_Time_Control;
use WP_Customize_Media_Control;

class FieldManager {

	public static $conditions = [];

	public function __construct( $wp_customize, $fields, $fields_group ) {
		self::add_customizer_fields( $wp_customize, $fields );
		self::add_customizer_fields_group( $wp_customize, $fields_group );
		//add_action( 'customize_preview_init', [ $this, 'newsfit_customizer_live_preview' ] );
		add_action( 'customize_controls_enqueue_scripts', [ $this, 'customizer_switch_select_control_script' ] );
	}


	public static function customizer_switch_select_control_script() {
		wp_enqueue_script( 'newsfit-customizer-condition', RT_FRAMEWORK_DIR_URL . '/assets/js/customizer-condition.js', [ 'jquery', 'customize-controls' ], '1.0', true );
		wp_localize_script( 'newsfit-customizer-condition', 'rtCustomizeObj', self::$conditions );
	}

	public static function add_customizer_fields( $wp_customize, $fields ) {
		if ( empty( $fields ) ) {
			return;
		}
		foreach ( $fields as $id => $field ) {
			$field['id'] = $id;
			if ( method_exists( __CLASS__, $field['type'] ) ) {
				self::{$field['type']}( $wp_customize, $field );
			}
		}
	}

	/**
	 * Add Customizer Fields group
	 *
	 * @param $wp_customize
	 * @param $fields_group
	 *
	 * @return void
	 */
	public static function add_customizer_fields_group( $wp_customize, $fields_group ) {
		if ( empty( $fields_group ) ) {
			return;
		}
		foreach ( $fields_group as $section_id => $fields ) {
			foreach ( $fields as $id => $field ) {
				if ( ! empty( $field['condition'] ) ) {
					self::$conditions[ $id ] = $field['condition'];
				}
				$field['section'] = $section_id;
				$field['id']      = $id;
				if ( method_exists( __CLASS__, $field['type'] ) ) {
					self::{$field['type']}( $wp_customize, $field );
				}
			}
		}
	}

	/**
	 * Check condition and return actual array
	 *
	 * @param $control_args
	 * @param $field
	 *
	 * @return mixed
	 */
	public static function cehck_condition( $control_args, $field ): mixed {

		if ( ! empty( $field['condition'] ) ) {
			$control_args['condition'] = $field['condition'];
		}

		return $control_args;
	}

	/**
	 * Heading control
	 *
	 * @param $wp_customize
	 * @param $field
	 *
	 * @return void
	 */
	public static function heading( $wp_customize, $field ): void {
		$settings_args = [
			'default'           => $field['default'] ?? '',
			'sanitize_callback' => 'esc_html',
		];
		$control_args  = [
			'label'    => $field['label'] ?? '',
			'settings' => $field['id'],
			'section'  => $field['section'] ?? '',
		];
		if ( ! empty( $field['reset'] ) ) {
			$control_args['reset'] = '1';
		}
		$control_args = self::cehck_condition( $control_args, $field );
		$wp_customize->add_setting( $field['id'], $settings_args );
		$wp_customize->add_control( new Customizer_Custom_Heading( $wp_customize, $field['id'], $control_args ) );
	}

	/**
	 * Text control
	 *
	 * @param $wp_customize
	 * @param $field
	 *
	 * @return void
	 */
	public static function text( $wp_customize, $field ): void {

		$settings_args = [
			'default'           => $field['default'] ?? '',
			'transport'         => $field['transport'] ?? 'refresh',
			'sanitize_callback' => 'sanitize_textarea_field',
			'condition'         => 'condition should come ',
		];
		$control_args  = [
			'label'           => $field['label'] ?? '',
			'description'     => $field['description'] ?? '',
			'section'         => $field['section'] ?? '',
			'type'            => 'text',
			'active_callback' => $field['callback'] ?? '',
			'condition'       => 'condition should come ',
		];

		$wp_customize->add_setting( $field['id'], $settings_args );
		$wp_customize->add_control( $field['id'], $control_args );
	}

	public static function url( $wp_customize, $field ): void {
		$settings_args = [
			'default'           => $field['default'] ?? '',
			'transport'         => $field['transport'] ?? 'refresh',
			'sanitize_callback' => 'esc_url_raw',
		];
		$control_args  = [
			'label'           => $field['label'] ?? '',
			'section'         => $field['section'] ?? '',
			'type'            => 'url',
			'active_callback' => $field['callback'] ?? '',
		];
		$wp_customize->add_setting( $field['id'], $settings_args );
		$wp_customize->add_control( $field['id'], $control_args );
	}

	/**
	 * Number control
	 *
	 * @param $wp_customize
	 * @param $field
	 *
	 * @return void
	 */
	public static function number( $wp_customize, $field ): void {
		$settings_args = [
			'default'           => $field['default'] ?? '',
			'transport'         => $field['transport'] ?? 'refresh',
			'sanitize_callback' => 'sanitize_textarea_field',
		];
		$control_args  = [
			'label'           => $field['label'] ?? '',
			'description'     => $field['description'] ?? '',
			'section'         => $field['section'] ?? '',
			'type'            => 'number',
			'active_callback' => $field['callback'] ?? '',
		];
		$wp_customize->add_setting( $field['id'], $settings_args );
		$wp_customize->add_control( $field['id'], $control_args );
	}

	/**
	 * Text area Control
	 *
	 * @param $wp_customize
	 * @param $field
	 *
	 * @return void
	 */
	public static function textarea( $wp_customize, $field ): void {
		$settings_args = [
			'default'           => $field['default'] ?? '',
			'transport'         => $field['transport'] ?? 'refresh',
			'sanitize_callback' => 'sanitize_textarea_field',
		];
		$control_args  = [
			'type'            => 'textarea',
			'label'           => $field['label'] ?? '',
			'description'     => $field['description'] ?? '',
			'section'         => $field['section'] ?? '',
			'active_callback' => $field['callback'] ?? '',
		];
		$wp_customize->add_setting( $field['id'], $settings_args );
		$wp_customize->add_control( $field['id'], $control_args );
	}

	/**
	 * Select Control
	 *
	 * @param $wp_customize
	 * @param $field
	 *
	 * @return void
	 */
	public static function select( $wp_customize, $field ): void {
		$settings_args = [
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'rttheme_text_sanitization',
			'default'           => $field['default'] ?? '',
		];
		$control_args  = [
			'type'        => 'select',
			'label'       => $field['label'] ?? '',
			'description' => $field['description'] ?? '',
			'section'     => $field['section'] ?? '',
			'choices'     => $field['choices'] ?? [],
		];
		$wp_customize->add_setting( $field['id'], $settings_args );
		$wp_customize->add_control( $field['id'], $control_args );
	}

	/**
	 * Image Select control
	 *
	 * @param $wp_customize
	 * @param $field
	 *
	 * @return void
	 */
	public static function image_select( $wp_customize, $field ): void {
		$settings_args = [
			'default'           => $field['default'] ?? '',
			'transport'         => $field['transport'] ?? 'refresh',
			'sanitize_callback' => 'rttheme_radio_sanitization',
		];
		$control_args  = [
			'type'        => 'image_select',
			'label'       => $field['label'] ?? '',
			'description' => $field['description'] ?? '',
			'section'     => $field['section'] ?? '',
			'choices'     => $field['choices'] ?? [],
		];
		$wp_customize->add_setting( $field['id'], $settings_args );
		$wp_customize->add_control( new Customizer_Image_Radio_Control( $wp_customize, $field['id'], $control_args ) );
	}

	/**
	 * Image
	 *
	 * @param $wp_customize
	 * @param $field
	 *
	 * @return void
	 */
	public static function image( $wp_customize, $field ): void {
		$button_label  = $field['button_label'] ?? __( 'Image', 'homlisti' );
		$settings_args = [
			'default'           => $field['default'] ?? '',
			'transport'         => $field['transport'] ?? 'refresh',
			'sanitize_callback' => 'absint',
		];
		$control_args  = [
			'label'         => $field['label'] ?? '',
			'description'   => $field['description'] ?? '',
			'section'       => $field['section'] ?? '',
			'mime_type'     => $field['mime_type'] ?? 'image',
			'button_labels' => [
				'select'       => esc_html__( 'Select', 'homlisti' ) . ' ' . $button_label,
				'change'       => esc_html__( 'Change', 'homlisti' ) . ' ' . $button_label,
				'default'      => esc_html__( 'Default', 'homlisti' ) . ' ' . $button_label,
				'remove'       => esc_html__( 'Remove', 'homlisti' ) . ' ' . $button_label,
				'placeholder'  => esc_html__( "No file selected", 'homlisti' ),
				'frame_title'  => esc_html__( 'Select', 'homlisti' ) . ' ' . $button_label,
				'frame_button' => esc_html__( 'Choose', 'homlisti' ) . ' ' . $button_label,
			],
		];

		$wp_customize->add_setting( $field['id'], $settings_args );
		$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, $field['id'], $control_args ) );
	}

	/**
	 * Checkbox Control
	 *
	 * @param $wp_customize
	 * @param $field
	 *
	 * @return void
	 */
	public static function checkbox( $wp_customize, $field ): void {
		$settings_args = [
			'default'           => $field['default'] ?? '',
			'transport'         => $field['transport'] ?? 'refresh',
			'sanitize_callback' => 'rttheme_text_sanitization',
		];
		$control_args  = [
			'type'        => 'checkbox',
			'label'       => $field['label'] ?? '',
			'description' => $field['description'] ?? '',
			'section'     => $field['section'] ?? '',
		];
		$wp_customize->add_setting( $field['id'], $settings_args );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, $field['id'], $control_args ) );
	}

	/**
	 * Radio Control
	 *
	 * @param $wp_customize
	 * @param $field
	 *
	 * @return void
	 */
	public static function radio( $wp_customize, $field ): void {
		$settings_args = [
			'default'           => $field['default'] ?? '',
			'transport'         => $field['transport'] ?? 'refresh',
			'sanitize_callback' => 'rttheme_text_sanitization'
		];
		$control_args  = [
			'type'        => 'radio',
			'label'       => $field['label'] ?? '',
			'description' => $field['description'] ?? '',
			'section'     => $field['section'] ?? '',
			'choices'     => $field['choices'] ?? [],
		];
		$wp_customize->add_setting( $field['id'], $settings_args );
		$wp_customize->add_control( $field['id'], $control_args );
	}

	/**
	 * Pages Control
	 *
	 * @param $wp_customize
	 * @param $field
	 *
	 * @return void
	 */
	public static function pages( $wp_customize, $field ): void {
		$settings_args = [
			'default'           => $field['default'] ?? '',
			'transport'         => $field['transport'] ?? 'refresh',
			'sanitize_callback' => 'absint'
		];
		$control_args  = [
			'label'       => $field['label'] ?? '',
			'description' => $field['description'] ?? '',
			'section'     => $field['section'] ?? '',
			'type'        => 'dropdown-pages'
		];
		$wp_customize->add_setting( $field['id'], $settings_args );
		$wp_customize->add_control( $field['id'], $control_args );
	}

	/**
	 * Color Control
	 *
	 * @param $wp_customize
	 * @param $field
	 *
	 * @return void
	 */
	public static function color( $wp_customize, $field ): void {
		$settings_args = [
			'default'           => $field['default'] ?? '',
			'transport'         => $field['transport'] ?? 'refresh',
			'sanitize_callback' => 'sanitize_hex_color'
		];
		$control_args  = [
			'label'       => $field['label'] ?? '',
			'description' => $field['description'] ?? '',
			'section'     => $field['section'] ?? '',
			'type'        => 'color'
		];
		$wp_customize->add_setting( $field['id'], $settings_args );
		$wp_customize->add_control( $field['id'], $control_args );
	}


	/**
	 * alfa_color Control
	 *
	 * @param $wp_customize
	 * @param $field
	 *
	 * @return void
	 */
	public static function alfa_color( $wp_customize, $field ): void {
		$settings_args = [
			'default'           => $field['default'] ?? '',
			'transport'         => $field['transport'] ?? 'refresh',
			'sanitize_callback' => 'rttheme_text_sanitization',
		];
		$control_args  = [
			'label'       => $field['label'] ?? '',
			'description' => $field['description'] ?? '',
			'section'     => $field['section'] ?? '',
		];
		$wp_customize->add_setting( $field['id'], $settings_args );
		$wp_customize->add_control( new Customizer_Alfa_Color( $wp_customize, $field['id'], $control_args ) );
	}


	/**
	 * Datetime Control
	 *
	 * @param $wp_customize
	 * @param $field
	 *
	 * @return void
	 */
	public static function datetime( $wp_customize, $field ): void {
		$settings_args = [
			'default'           => $field['default'] ?? '',
			'transport'         => $field['transport'] ?? 'refresh',
			'sanitize_callback' => 'rttheme_text_sanitization',
		];
		$control_args  = [
			'label'              => $field['label'] ?? '',
			'description'        => $field['description'] ?? '',
			'section'            => $field['section'] ?? '',
			'include_time'       => false,
			'allow_past_date'    => true,
			'twelve_hour_format' => true,
			'min_year'           => '2016',
			'max_year'           => '2025',
		];
		$wp_customize->add_setting( $field['id'], $settings_args );
		$wp_customize->add_control( new WP_Customize_Date_Time_Control( $wp_customize, $field['id'], $control_args ) );
	}


	/**
	 * select2 Control
	 *
	 * @param $wp_customize
	 * @param $field
	 *
	 * @return void
	 */
	public static function select2( $wp_customize, $field ): void {
		$settings_args = [
			'default'           => $field['default'] ?? '',
			'transport'         => $field['transport'] ?? 'refresh',
			'sanitize_callback' => 'rttheme_text_sanitization'
		];
		$control_args  = [
			'label'       => $field['label'] ?? '',
			'description' => $field['description'] ?? '',
			'section'     => $field['section'] ?? '',
			'input_attrs' => [
				'placeholder' => $field['placeholder'] ?? __( 'Please select...', 'skyrocket' ),
				'multiselect' => false,
			],
			'choices'     => [
				'nsw' => __( 'New South Wales', 'skyrocket' ),
				'vic' => __( 'Victoria', 'skyrocket' ),
				'qld' => __( 'Queensland', 'skyrocket' ),
				'wa'  => __( 'Western Australia', 'skyrocket' ),
				'sa'  => __( 'South Australia', 'skyrocket' ),
				'tas' => __( 'Tasmania', 'skyrocket' ),
				'act' => __( 'Australian Capital Territory', 'skyrocket' ),
				'nt'  => __( 'Northern Territory', 'skyrocket' ),
			]
		];
		$wp_customize->add_setting( $field['id'], $settings_args );
		$wp_customize->add_control( new Customizer_Dropdown_Select2_Control( $wp_customize, $field['id'], $control_args ) );
	}

	/**
	 * select2 Control
	 *
	 * @param $wp_customize
	 * @param $field
	 *
	 * @return void
	 */
	public static function switch( $wp_customize, $field ): void {
		$settings_args = [
			'default'           => $field['default'] ?? '',
			'transport'         => $field['transport'] ?? 'refresh',
			'sanitize_callback' => 'rttheme_switch_sanitization',
		];
		$control_args  = [
			'label'       => $field['label'] ?? '',
			'description' => $field['description'] ?? '',
			'section'     => $field['section'] ?? '',
			'class'       => $field['class'] ?? '',
		];
		$wp_customize->add_setting( $field['id'], $settings_args );
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, $field['id'], $control_args ) );

	}

	/**
	 * tinymce Control
	 *
	 * @param $wp_customize
	 * @param $field
	 *
	 * @return void
	 */
	public static function tinymce( $wp_customize, $field ): void {
		$settings_args = [
			'default'           => $field['default'] ?? '',
			'transport'         => $field['transport'] ?? 'refresh',
			'sanitize_callback' => 'wp_kses_post',
		];
		$control_args  = [
			'label'       => $field['label'] ?? '',
			'description' => $field['description'] ?? '',
			'section'     => $field['section'] ?? '',
		];
		$wp_customize->add_setting( $field['id'], $settings_args );
		$wp_customize->add_control( new Customizer_TinyMCE_control( $wp_customize, $field['id'], $control_args ) );

	}

	/**
	 * separator Control
	 *
	 * @param $wp_customize
	 * @param $field
	 *
	 * @return void
	 */
	public static function separator( $wp_customize, $field ): void {
		$settings_args = [
			'default'           => $field['default'] ?? '',
			'sanitize_callback' => 'rttheme_switch_sanitization',
		];
		$control_args  = [
			'section' => $field['section'] ?? ''
		];
		$wp_customize->add_setting( $field['id'], $settings_args );
		$wp_customize->add_control( new Customizer_Separator_Control( $wp_customize, $field['id'], $control_args ) );

	}

	/**
	 * Repeater Control
	 *
	 * @param $wp_customize
	 * @param $field
	 *
	 * @return void
	 */
	public static function repeater( $wp_customize, $field ): void {
		$settings_args = [
			'default'           => $field['default'] ?? '',
			'transport'         => $field['transport'] ?? 'refresh',
			'sanitize_callback' => 'rttheme_text_sanitization',
		];
		$control_args  = [
			'label'         => $field['label'] ?? '',
			'description'   => $field['description'] ?? '',
			'section'       => $field['section'] ?? '',
			'use_as'        => $field['use_as'] ?? 'repeater',
			'button_labels' => [
				'add' => __( 'Add Item', 'skyrocket' ),
			]
		];
		$wp_customize->add_setting( $field['id'], $settings_args );
		$wp_customize->add_control( new Customizer_Sortable_Repeater_Control( $wp_customize, $field['id'], $control_args ) );

	}

	/**
	 * Gallery Control
	 *
	 * @param $wp_customize
	 * @param $field
	 *
	 * @return void
	 */
	public static function gallery( $wp_customize, $field ): void {
		$settings_args = [
			'default'           => $field['default'] ?? '',
			'transport'         => $field['transport'] ?? 'refresh',
			'sanitize_callback' => 'rttheme_switch_sanitization',
		];
		$control_args  = [
			'label'         => $field['label'] ?? '',
			'description'   => $field['description'] ?? '',
			'section'       => $field['section'] ?? '',
			'button_labels' => [
				'add' => __( 'Add Gallery', 'skyrocket' ),
			]
		];
		$wp_customize->add_setting( $field['id'], $settings_args );
		$wp_customize->add_control( new Customizer_Gallery_Control( $wp_customize, $field['id'], $control_args ) );

	}

	/**
	 * Typography Control
	 *
	 * @param $wp_customize
	 * @param $field
	 *
	 * @return void
	 */
	public static function typography( $wp_customize, $field ): void {
		$settings_args = [
			'default'           => $field['default'] ?? '',
			'sanitize_callback' => 'rttheme_google_font_sanitization',
		];
		$control_args  = [
			'label'       => $field['label'] ?? '',
			'description' => $field['description'] ?? '',
			'section'     => $field['section'] ?? '',
			'input_attrs' => [
				'font_count' => 'all',
				'orderby'    => 'popular',
			],
		];
		$wp_customize->add_setting( $field['id'], $settings_args );
		$wp_customize->add_control( new Customizer_Google_Fonts_Controls( $wp_customize, $field['id'], $control_args ) );
	}


	/**
	 * Background attribute
	 *
	 * @param $wp_customize
	 * @param $field
	 *
	 * @return void
	 */
	public static function bg_attribute( $wp_customize, $field ): void {
		$settings_args = [
			'default'           => $field['default'] ?? '',
			'sanitize_callback' => 'rttheme_google_font_sanitization',
		];
		$control_args  = [
			'label'       => $field['label'] ?? '',
			'description' => $field['description'] ?? '',
			'section'     => $field['section'] ?? '',
		];
		$wp_customize->add_setting( $field['id'], $settings_args );
		$wp_customize->add_control( new Customizer_BG_Attributes_Control( $wp_customize, $field['id'], $control_args ) );
	}

}