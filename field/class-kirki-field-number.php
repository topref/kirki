<?php
/**
 * Override field methods
 *
 * @package     Kirki
 * @subpackage  Controls
 * @copyright   Copyright (c) 2017, Aristeides Stathopoulos
 * @license     http://opensource.org/licenses/https://opensource.org/licenses/MIT
 * @since       2.2.7
 */

/**
 * Field overrides.
 */
class Kirki_Field_Number extends Kirki_Field {

	/**
	 * Sets the control type.
	 *
	 * @access protected
	 */
	protected function set_type() {

		$this->type = 'kirki-number';

	}

	/**
	 * Sets the $sanitize_callback
	 *
	 * @access protected
	 */
	protected function set_sanitize_callback() {

		$this->sanitize_callback = array( $this, 'sanitize' );

	}

	/**
	 * Sets the $choices
	 *
	 * @access protected
	 */
	protected function set_choices() {

		if ( ! is_customize_preview() ) {
			return;
		}
		$this->choices = wp_parse_args(
			$this->choices,
			array(
				'min'  => -999999999,
				'max'  => 999999999,
				'step' => 1,
			)
		);
		// Make sure min, max & step are all numeric.
		$this->choices['min']  = filter_var( $this->choices['min'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
		$this->choices['max']  = filter_var( $this->choices['max'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
		$this->choices['step'] = filter_var( $this->choices['step'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
	}

	/**
	 * Sanitizes numeric values.
	 *
	 * @access public
	 * @param integer|string $value The checkbox value.
	 * @return bool
	 */
	public function sanitize( $value = 0 ) {

		$this->set_choices();

		$value = filter_var( $value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );

		// Minimum & maximum value limits.
		if ( $value < $min || $value > $max ) {
			return max( min( $value, $max ), $min );
		}

		// Only multiple of steps.
		$steps = ( $value - $min ) / $step;
		if ( ! is_int( $steps ) ) {
			$value = $min + ( round( $steps ) * $step );
		}
		return $value;
	}
}
