<?php 

/**
 *
 * Plugin Name: Gravity Forms Validação CPF CNPJ
 * Description: Plugin que habilita a validação de CPF e CNPJ
 * Author: JP Tibério
 *
 */

require 'class-valida-cpf-cnpj.php';


if ( is_plugin_active('gravityforms/gravityforms.php') ) {


	add_filter("gform_validation", "custom_validation");


	function custom_validation( $validation_result){

		$form = $validation_result['form'];

		foreach( $form['fields'] as $field ) {


			if ( $field->cssClass == 'cpf_cnpj' ) {

				$field_value = rgpost( 'input_'.$field->id );


				$cpf_cnpj = new ValidaCPFCNPJ($field_value);

				if (!empty($cpf_cnpj)) {

					$validado = $cpf_cnpj->valida();

					if ( $validado != true ) {

			            $field->validation_message = 'CPF/CNPJ Inválido';

			        	$validation_result['is_valid'] = false;

			            $field->failed_validation = true;

			            break;
					}

				} else {

		            $field->validation_message = 'CPF/CNPJ Inválido';

		        	$validation_result['is_valid'] = false;

		            $field->failed_validation = true;

		            break;

				}
			    
			}

	    }


	    $validation_result['form'] = $form;

		return $validation_result;

	}

}


?>