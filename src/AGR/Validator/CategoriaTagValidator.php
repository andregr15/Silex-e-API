<?php
namespace AGR\Validator;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class CategoriaTagValidator extends Validator{

    function __construct($validator){
        parent::__construct($validator);
    }
    
    function validateUpdateData(Request $request){
        $this->dados = array();
        $this->dados['nome'] = $request->request->get('nome');

        $constraint = new Assert\Collection(
            array(
                      'nome' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 3)))
                 )
        );     
                             
        $this->setErrors($this->validator->validate($this->dados, $constraint));
        return $this->errors;
    }
}

?>