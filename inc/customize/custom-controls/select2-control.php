<?php

namespace RTFramework\CustomControl;

use WP_Customize_Control;

if ( class_exists( 'WP_Customize_Control' ) ) {
	/**
	 * Dropdown Select2 Custom Control
	 */
	class Customizer_Dropdown_Select2_Control extends WP_Customize_Control {

		/**
		 * The type of control being rendered
		 */
		public $type = 'dropdown_select2';
		/**
		 * The type of Select2 Dropwdown to display. Can be either a single select dropdown or a multi-select dropdown. Either false for true. Default = false
		 */
		private $multiselect = false;
		/**
		 * The Placeholder value to display. Select2 requires a Placeholder value to be set when using the clearall option. Default = 'Please select...'
		 */
		private $placeholder = 'Please select...';

		/**
		 * Constructor
		 */
		public function __construct( $manager, $id, $args = [], $options = [] ) {
			parent::__construct( $manager, $id, $args );
			// Check if this is a multi-select field
			if ( isset( $this->input_attrs['multiselect'] ) && $this->input_attrs['multiselect'] ) {
				$this->multiselect = true;
			}
			// Check if a placeholder string has been specified
			if ( isset( $this->input_attrs['placeholder'] ) && $this->input_attrs['placeholder'] ) {
				$this->placeholder = $this->input_attrs['placeholder'];
			}
		}

		/**
		 * Enqueue our scripts and styles
		 */
		public function enqueue() {
			wp_enqueue_script( 'rttheme-select2-js', RT_FRAMEWORK_DIR_URL . '/assets/js/select2.min.js', [ 'jquery' ], '4.0.6', true );
			wp_enqueue_script( 'rttheme-custom-controls-js', RT_FRAMEWORK_DIR_URL . '/assets/js/customizer.js', [ 'rttheme-select2-js' ], '1.2', true );
			wp_enqueue_style( 'rttheme-custom-controls-css', RT_FRAMEWORK_DIR_URL . '/assets/css/customizer.css', [], '1.1', 'all' );
			wp_enqueue_style( 'rttheme-select2-css', RT_FRAMEWORK_DIR_URL . '/assets/css/select2.min.css', [], '4.0.6', 'all' );
		}

		/**
		 * Render the control in the customizer
		 */
		public function render_content() {
			$defaultValue = $this->value();
			$choices      = $this->choices;
			if ( $this->multiselect ) {
				$defaultValue = explode( ',', $this->value() );


				if ( ! empty( $defaultValue ) ) {
					$new_choices = [];
					foreach ( $defaultValue as $item ) {
						if ( ! $item || ! in_array($item, array_keys($choices)) ) {
							continue;
						}
						$new_choices[ $item ] = $this->choices[ $item ];
					}
					if ( ! empty( $new_choices ) ) {
						foreach ( $this->choices as $item => $value ) {
							if ( in_array( $value, $new_choices ) ) {
								continue;
							}
							$new_choices[ $item ] = $value;
						}
						$choices = $new_choices;
					}
				}
			}

			?>
            <div class="dropdown_select2_control">
				<?php if ( ! empty( $this->label ) ) { ?>
                    <label for="<?php echo esc_attr( $this->id ); ?>" class="customize-control-title">
						<?php echo esc_html( $this->label ); ?>
                    </label>
				<?php } ?>
				<?php if ( ! empty( $this->description ) ) { ?>
                    <span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
				<?php } ?>
                <input type="hidden" id="<?php echo esc_attr( $this->id ); ?>" class="customize-control-dropdown-select2" value="<?php echo esc_attr( $this->value() ); ?>"
                       name="<?php echo esc_attr( $this->id ); ?>" <?php $this->link(); ?> />
                <select name="select2-list-<?php echo esc_attr( $this->multiselect ? 'multi[]' : 'single' ); ?>" class="customize-control-select2"
                        data-placeholder="<?php echo esc_attr( $this->placeholder ); ?>" <?php echo esc_attr( $this->multiselect ? 'multiple="multiple" ' : '' ); ?>>
					<?php
					if ( ! $this->multiselect ) {
						// When using Select2 for single selection, the Placeholder needs an empty <option> at the top of the list for it to work (multi-selects dont need this)
						echo '<option></option>';
					}

					foreach ( $choices as $key => $value ) {
						if ( is_array( $value ) ) {
							echo '<optgroup label="' . esc_attr( $key ) . '">';
							foreach ( $value as $optgroupkey => $optgroupvalue ) {
								echo '<option value="' . esc_attr( $optgroupkey ) . '" ' . ( in_array( esc_attr( $optgroupkey ), $defaultValue ) ? 'selected="selected"' : '' )
								     . '>' . esc_attr( $optgroupvalue ) . '</option>';
							}
							echo '</optgroup>';
						} else {
							$selected = '';
							if ( in_array( $key, $defaultValue ) ) {
								$selected = 'selected';
							}
							//selected( esc_attr( $key ), $defaultValue, false )
							echo '<option value="' . esc_attr( $key ) . '" ' . $selected . '>' . esc_attr( $value ) . '</option>';
						}
					}
					?>
                </select>
            </div>
			<?php
		}

	}
}