<?php

namespace RTFramework;

use RTFramework\CustomControl\Customizer_Alfa_Color;
use RTFramework\CustomControl\Customizer_Custom_Heading;
use RTFramework\CustomControl\Customizer_Dropdown_Select2_Control;
use RTFramework\CustomControl\Customizer_Image_Radio_Control;
use RTFramework\CustomControl\Customizer_Sortable_Repeater_Control;
use RTFramework\CustomControl\Customizer_Switch_Control;
use WP_Customize_Control;
use WP_Customize_Date_Time_Control;
use WP_Customize_Media_Control;

class FieldManager {

	public static function add_customizer_fields( $wp_customize, $fields ) {
		if ( empty( $fields ) ) {
			return;
		}
		foreach ( $fields as $field ) {
			if ( method_exists( __CLASS__, $field['type'] ) ) {
				self::{$field['type']}( $wp_customize, $field );
			}
		}
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
		$wp_customize->add_setting( $field['id'], [
			'default'           => $field['default'] ?? '',
			'sanitize_callback' => 'esc_html',
		] );

		$wp_customize->add_control( new Customizer_Custom_Heading( $wp_customize, $field['id'], [
			'label'    => $field['label'] ?? 'Heading Title',
			'settings' => $field['id'],
			'section'  => $field['section'] ?? '',
		] ) );
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
		$wp_customize->add_setting( $field['id'],
			[
				'default'           => $field['default'] ?? '',
				'transport'         => $field['transport'] ?? 'refresh',
				'sanitize_callback' => 'sanitize_textarea_field',
			]
		);
		$wp_customize->add_control( $field['id'],
			[
				'label'           => $field['label'] ?? '',
				'section'         => $field['section'] ?? '',
				'type'            => 'text',
				'active_callback' => $field['callback'] ?? '',
			]
		);
	}

	public static function url( $wp_customize, $field ): void {
		$wp_customize->add_setting( $field['id'],
			[
				'default'           => $field['default'] ?? '',
				'transport'         => $field['transport'] ?? 'refresh',
				'sanitize_callback' => 'esc_url_raw',
			]
		);
		$wp_customize->add_control( $field['id'],
			[
				'label'           => $field['label'] ?? '',
				'section'         => $field['section'] ?? '',
				'type'            => 'url',
				'active_callback' => $field['callback'] ?? '',
			]
		);
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
		$wp_customize->add_setting( 'header_btn_order',
			[
				'default'           => $field['default'] ?? '',
				'transport'         => $field['transport'] ?? 'refresh',
				'sanitize_callback' => 'sanitize_textarea_field',
			]
		);
		$wp_customize->add_control( 'header_btn_order',
			[
				'label'           => $field['label'] ?? '',
				'section'         => $field['section'] ?? '',
				'type'            => 'number',
				'active_callback' => $field['callback'] ?? '',
			]
		);
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
		$wp_customize->add_setting( $field['id'],
			[
				'default'           => $field['default'] ?? '',
				'transport'         => $field['transport'] ?? 'refresh',
				'sanitize_callback' => 'sanitize_textarea_field',
			]
		);
		$wp_customize->add_control( $field['id'],
			[
				'type'            => 'textarea',
				'label'           => $field['label'] ?? '',
				'description'     => $field['description'] ?? '',
				'section'         => $field['section'] ?? '',
				'active_callback' => $field['callback'] ?? '',
			]
		);
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
		$wp_customize->add_setting( $field['id'], [
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'rttheme_text_sanitization',
			'default'           => $field['default'] ?? '',
		] );

		$wp_customize->add_control( $field['id'], [
			'type'        => 'select',
			'label'       => $field['label'] ?? '',
			'description' => $field['description'] ?? '',
			'section'     => $field['section'] ?? '',
			'choices'     => $field['choices'] ?? [],
		] );
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
		$wp_customize->add_setting( $field['id'],
			[
				'default'           => $field['default'] ?? '',
				'transport'         => $field['transport'] ?? 'refresh',
				'sanitize_callback' => 'rttheme_radio_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Image_Radio_Control( $wp_customize, $field['id'],
			[
				'type'        => 'image_select',
				'label'       => $field['label'] ?? '',
				'description' => $field['description'] ?? '',
				'section'     => $field['section'] ?? '',
				'choices'     => $field['choices'] ?? [],
			]
		) );
	}

	/**
	 *
	 * @param $wp_customize
	 * @param $field
	 *
	 * @return void
	 */
	public static function image( $wp_customize, $field ): void {
		$button_label = $field['button_label'] ?? __( 'Image', 'homlisti' );
		$wp_customize->add_setting( $field['id'],
			[
				'default'           => $field['default'] ?? '',
				'transport'         => $field['transport'] ?? 'refresh',
				'sanitize_callback' => 'absint',
			]
		);
		$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, $field['id'],
			[
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
			]
		) );
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
		$wp_customize->add_setting( $field['id'], [
			'default'           => $field['default'] ?? '',
			'transport'         => $field['transport'] ?? 'refresh',
			'sanitize_callback' => 'rttheme_text_sanitization',
		] );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, $field['id'],
			[
				'type'        => 'checkbox',
				'label'       => $field['label'] ?? '',
				'description' => $field['description'] ?? '',
				'section'     => $field['section'] ?? '',
			]
		) );
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
		$wp_customize->add_setting( $field['id'],
			array(
				'default'           => $field['default'] ?? '',
				'transport'         => $field['transport'] ?? 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization'
			)
		);
		$wp_customize->add_control( $field['id'],
			array(
				'type'        => 'radio',
				'label'       => $field['label'] ?? '',
				'description' => $field['description'] ?? '',
				'section'     => $field['section'] ?? '',
				'choices'     => $field['choices'] ?? [],
			)
		);
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
		$wp_customize->add_setting( $field['id'],
			array(
				'default'           => $field['default'] ?? '',
				'transport'         => $field['transport'] ?? 'refresh',
				'sanitize_callback' => 'absint'
			)
		);
		$wp_customize->add_control( $field['id'],
			array(
				'label'       => $field['label'] ?? '',
				'description' => $field['description'] ?? '',
				'section'     => $field['section'] ?? '',
				'type'        => 'dropdown-pages'
			)
		);
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

		$wp_customize->add_setting( $field['id'],
			array(
				'default'           => $field['default'] ?? '',
				'transport'         => $field['transport'] ?? 'refresh',
				'sanitize_callback' => 'sanitize_hex_color'
			)
		);
		$wp_customize->add_control( $field['id'],
			array(
				'label'       => $field['label'] ?? '',
				'description' => $field['description'] ?? '',
				'section'     => $field['section'] ?? '',
				'type'        => 'color'
			)
		);
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

		$wp_customize->add_setting( $field['id'],
			[
				'default'           => $field['default'] ?? '',
				'transport'         => $field['transport'] ?? 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Alfa_Color( $wp_customize, $field['id'],
			[
				'label'       => $field['label'] ?? '',
				'description' => $field['description'] ?? '',
				'section'     => $field['section'] ?? '',
			]
		) );
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

		$wp_customize->add_setting( $field['id'],
			array(
				'default'           => $field['default'] ?? '',
				'transport'         => $field['transport'] ?? 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			)
		);
		$wp_customize->add_control( new WP_Customize_Date_Time_Control( $wp_customize, $field['id'],
			array(
				'label'              => $field['label'] ?? '',
				'description'        => $field['description'] ?? '',
				'section'            => $field['section'] ?? '',
				'include_time'       => false,
				'allow_past_date'    => true,
				'twelve_hour_format' => true,
				'min_year'           => '2016',
				'max_year'           => '2025',
			)
		) );
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

		$wp_customize->add_setting( $field['id'],
			array(
				'default'           => $field['default'] ?? '',
				'transport'         => $field['transport'] ?? 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization'
			)
		);
		$wp_customize->add_control( new Customizer_Dropdown_Select2_Control( $wp_customize, $field['id'],
			array(
				'label'       => $field['label'] ?? '',
				'description' => $field['description'] ?? '',
				'section'     => $field['section'] ?? '',
				'input_attrs' => array(
					'placeholder' => $field['placeholder'] ?? __( 'Please select...', 'skyrocket' ),
					'multiselect' => false,
				),
				'choices'     => array(
					'nsw' => __( 'New South Wales', 'skyrocket' ),
					'vic' => __( 'Victoria', 'skyrocket' ),
					'qld' => __( 'Queensland', 'skyrocket' ),
					'wa'  => __( 'Western Australia', 'skyrocket' ),
					'sa'  => __( 'South Australia', 'skyrocket' ),
					'tas' => __( 'Tasmania', 'skyrocket' ),
					'act' => __( 'Australian Capital Territory', 'skyrocket' ),
					'nt'  => __( 'Northern Territory', 'skyrocket' ),
				)
			)
		) );
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
		$wp_customize->add_setting( $field['id'], [
			'default'           => $field['default'] ?? '',
			'transport'         => $field['transport'] ?? 'refresh',
			'sanitize_callback' => 'rttheme_switch_sanitization',
		] );
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, $field['id'], [
			'label'       => $field['label'] ?? '',
			'description' => $field['description'] ?? '',
			'section'     => $field['section'] ?? '',
		] ) );

	}

	/**
	 * select2 Control
	 *
	 * @param $wp_customize
	 * @param $field
	 *
	 * @return void
	 */
	public static function repeater( $wp_customize, $field ): void {
		$wp_customize->add_setting( $field['id'], [
			'default'           => $field['default'] ?? '',
			'transport'         => $field['transport'] ?? 'refresh',
			'sanitize_callback' => 'rttheme_switch_sanitization',
		] );
		$wp_customize->add_control( new Customizer_Sortable_Repeater_Control( $wp_customize, $field['id'], [
			'label'       => $field['label'] ?? '',
			'description' => $field['description'] ?? '',
			'section'     => $field['section'] ?? '',
			'button_labels' => array(
				'add' => __( 'Add Row', 'skyrocket' ),
			)
		] ) );

	}


}