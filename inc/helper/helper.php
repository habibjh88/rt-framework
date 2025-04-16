<?php

// Register the setting
add_action( 'admin_init', 'rt_framework_general_settings' );
function rt_framework_general_settings() {
	// Register the setting so WordPress knows about it
	register_setting( 'general', 'rt_framework_option', [
		'type'              => 'string',
		'sanitize_callback' => 'sanitize_text_field',
		'default'           => 'no',
	] );

	// Add the field to the General Settings page
	add_settings_field(
		'rt_framework_option', // ID
		'RT Google Fonts',   // Title
		'rt_framework_option_callback', // Callback function to output the field
		'general'           // Page (General Settings)
	);
}

// Output the select field
function rt_framework_option_callback() {
	$value = get_option( 'rt_framework_option', 'no' );
	?>
    <select name="rt_framework_option">
        <option value="no" <?php selected( $value, 'no' ); ?>><?php echo esc_html__( 'Load all fonts', 'rt-framework' ) ?></option>
        <option value="yes" <?php selected( $value, 'yes' ); ?>><?php echo esc_html__( 'Load popular fonts only', 'rt-framework' ) ?></option>
    </select>
    <p class="description">
		<?php echo esc_html__( "If customizer not loading properly load popular google fonts from here.", "rt-framework" ) ?>
    </p>
	<?php
}


