<?php

namespace RTFramework;
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

	public static function text( $wp_customize, $field ) {
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

	public static function textarea( $wp_customize, $field ) {
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
				'type'            => 'textarea',
				'active_callback' => $field['callback'] ?? '',
			]
		);
	}
}