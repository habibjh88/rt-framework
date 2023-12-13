<?php

namespace RTFramework\CustomControl;

use WP_Customize_Control;

/**
 * Gallery Control
 * Reference: https://wordpress.stackexchange.com/questions/265603/extend-wp-customizer-to-make-multiple-image-selection-possible
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	class Customizer_Gallery_Control extends WP_Customize_Control {

		/**
		 * Button labels
		 */
		public $button_labels = [];

		public $type = 'custom_heading';

		/**
		 * Constructor
		 */
		public function __construct( $manager, $id, $args = [] ) {
			parent::__construct( $manager, $id, $args );
			// Merge the passed button labels with our default labels
			$this->button_labels = wp_parse_args( $this->button_labels,
				[
					'add' => esc_html__( 'Add', 'homlisti' ),
				]
			);
		}

		public function enqueue() {
			wp_enqueue_script( 'rttheme-custom-controls-js', RT_FRAMEWORK_DIR_URL . '/assets/js/customizer.js', [ 'jquery' ], '1.0', true );
			wp_enqueue_style( 'rttheme-custom-controls-css', RT_FRAMEWORK_DIR_URL . '/assets/css/customizer.css', [], '1.0', 'all' );
		}

		public function render_content() { ?>
			<?php if ( ! empty( $this->label ) ) { ?>
                <label>
                    <span class='customize-control-title'><?php echo esc_html( $this->label ); ?></span>
                </label>
			<?php } ?>
            <div>
                <ul class='images'></ul>
            </div>
            <div class='actions'>
                <a class="button-secondary upload"><?php echo esc_html( $this->button_labels['add'] ); ?></a>
            </div>

			<?php echo $this->value();
            if($this->id == 'newsfit_gallery1') {
	            error_log( print_r( $this, true ) . "\n\n", 3, __DIR__ . '/log.txt' );
            }
            ?>

            <input class="wp-editor-area"
                   name="<?php echo esc_attr($this->id); ?>"
                   id="<?php echo esc_attr( $this->id ); ?>"
                   type="text" <?php $this->link(); ?>
                   value="<?php echo esc_attr( $this->value() ); ?>">
			<?php
		}

	}
}