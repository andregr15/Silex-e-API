<?php
namespace AGR\Validator;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class ProdutoValidator extends Validator{
    
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
        $this->dados['descricao'] = $request->request->get('descricao');
        $this->dados['valor'] = $request->request->get('valor'); 

        $constraint = new Assert\Collection(
            array(
                      'nome' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 3))),
                      'descricao' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 3))),
                      'valor' => array(new Assert\NotBlank(), new Assert\Type(array('type'=>'numeric')))
                 )
        );     
                             
        $this->setErrors($this->validator->validate($this->dados, $constraint));
        return $this->errors;
    }

    function validateId($id){
        $this->dados = array();
        $this->dados['id'] = $id;
        
        $constraint = new Assert\Collection(
            array(
                      'id' => array(new Assert\NotBlank(), new Assert\Regex('/^[0-9]+$/'))
                 )
        );
       
        $this->setErrors($this->validator->validate($this->dados, $constraint));
        return $this->errors;
    }
}

?>