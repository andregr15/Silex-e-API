<?php
namespace AGR\Validator;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class ClienteValidator extends Validator{

    function __construct($validator){
        parent::__construct($validator);
    }
    
    function validateInsertData(Request $request, $id){
        $this->validateId($id);
        $this->validateUpdateData($request);
        $this->dados['id'] = $id;
        return $this->errors;
    }

    function validateUpdateData(Request $request){
        $this->dados = array();
        $this->dados['nome'] = $request->request->get('nome');
        $this->dados['documento'] = $request->request->get('documento');
        $this->dados['email'] = $request->request->get('email');  

        $constraint = new Assert\Collection(
            array(
                      'nome' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 3))),
                      'documento' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 3))),
                      'email' => array(new Assert\NotBlank(), new Assert\Email(array('message' => 'The email "{{ value }}" is not a valid email.', 'checkMX' => false)))
                 )
        );     
                             
        $this->setErrors($this->validator->validate($this->dados, $constraint));
        return $this->errors;
    }
}

?>