<?php

namespace RTFramework\CustomControl;

use WP_Customize_Control;

/**
 * Toggle Switch Custom Control
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	class Customizer_Custom_Heading extends WP_Customize_Control {

		public $type = 'custom_heading';

        public $reset;

		public function render_content() {
			?>
            <div class="rt-framework-custom-headding">
				<?php
				if ( isset( $this->label ) && '' !== $this->label ) {
					echo '<span class="customize-control-heading">' . sanitize_text_field( $this->label ) . '</span>';
				}

				if ( isset( $this->reset ) ) {
					?>
                    <br>
                    <a class="button" href="<?php echo site_url('/wp-admin/customize.php') ?>?reset_theme_mod=1">
                        Reset Customize
                    </a>
				<?php } ?>
            </div>
            <!--            <input class="wp-editor-area" id="--><?php //echo esc_attr( $this->id ); ?><!--" type="text" --><?php //$this->link(); ?><!-- value="--><?php //echo esc_attr( $this->value() ); ?><!--">-->

			<?php
		}


	}
}