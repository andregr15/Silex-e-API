<?php
namespace AGR\Validator;

use Symfony\Component\HttpFoundation\Request;

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

    abstract function validateId($id);
}

?>