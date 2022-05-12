<?php 

/**
 *
 * Plugin Name: Gravity Forms Validação CPF CNPJ
 * Description: Plugin que habilita a validação de CPF e CNPJ
 * Author: JP Tibério
 *
 */

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

require 'ValidaCPFCNPJ.php';

if ( is_plugin_active('gravityforms/gravityforms.php') ) {


	add_filter("gform_validation", "custom_validation");


	function custom_validation( $validation_result){

		$form = $validation_result['form'];

		$classes = [
			'valida_cpf' => 'CPF inválido',
			'valida_cnpj' => 'CNPJ inválido',
			'valida_cpf_cnpj' => 'CPF ou CNPJ inválido'
		];

		foreach( $form['fields'] as $field ) {

			if ( array_key_exists($field->cssClass, $classes) !== false ) {

				$field_value = rgpost( 'input_'.$field->id );
				$cpf_cnpj = new ValidaCPFCNPJ($field_value);

				if (!empty($cpf_cnpj)) {

					$validado = $cpf_cnpj->valida();

					if ( $validado != true ) {
			            $field->validation_message = $classes[$field->cssClass];
			        	$validation_result['is_valid'] = false;
			            $field->failed_validation = true;
			            break;
					}

				} else {

		            $field->validation_message = $classes[$field->cssClass];
		        	$validation_result['is_valid'] = false;
		            $field->failed_validation = true;
		            break;

				}
			    
			}

	    }

	    $validation_result['form'] = $form;

		return $validation_result;

	}

} else {

	function pls_activate_gforms() {
	    ?>
	    <div class="error notice">
	        <p><?php _e( 'Por favor, instale ou ative o Gravity Forms!', 'my_plugin_textdomain' ); ?></p>
	    </div>
	    <?php
	}
	
	add_action( 'admin_notices', 'pls_activate_gforms' );
}

?>