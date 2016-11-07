<?php
namespace AGR\Validator;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

abstract class Validator{
    protected $dados;
    protected $validator;
    protected $errors;

    function __construct($validator){
        $this->validator = $validator;
        $this->errors = array();
    }

    function getDados(){
        return $this->dados;
    }

    protected function setErrors($errors){
         foreach ($errors as $error) {
           $this->errors[] = array($error->getPropertyPath() => $error->getMessage());
        }
    }

    abstract function validateInsertData(Request $request, $id);

    abstract function validateUpdateData(Request $request);

    function validateId($id){
        $this->dados = array();
        $this->dados['id'] = $id;
        
        $constraint = new Assert\Collection(
            array(
                      'id' => array(new Assert\NotBlank(), new Assert\Regex(array('pattern'=>'/^[0-9]+$/', 'message' => 'This value should be of type integer')))
                 )
        );
       
        $this->setErrors($this->validator->validate($this->dados, $constraint));
        return $this->errors;
    }
}

?>