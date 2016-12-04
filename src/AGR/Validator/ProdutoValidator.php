<?php
namespace AGR\Validator;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class ProdutoValidator extends Validator{
    
    function __construct($validator){
        parent::__construct($validator);
    }
    
    function validateUpdateData(Request $request){
        $this->dados = array();
        $this->dados['nome'] = $request->request->get('nome');
        $this->dados['descricao'] = $request->request->get('descricao');
        $this->dados['valor'] = $request->request->get('valor'); 
        $this->dados['categoria'] = $request->request->get('categoria');
        $this->dados['tags'] = $request->request->get('tags');

        $constraint = new Assert\Collection(
            array(
                      'nome' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 3))),
                      'descricao' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 3))),
                      'valor' => array(new Assert\NotBlank(), new Assert\Type(array('type'=>'numeric'))),
                      'categoria' => array(new Assert\NotBlank(), new Assert\Regex(array('pattern'=>'/^[0-9]+$/', 'message' => 'This value should be of type integer'))),
                      'tags' => new Assert\NotBlank()
                 )
        );     
                             
        $this->setErrors($this->validator->validate($this->dados, $constraint));
        return $this->errors;
    }
}

?>