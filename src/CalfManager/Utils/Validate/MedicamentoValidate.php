<?php
/**
 * Created by PhpStorm.
 * User: Brunno
 * Date: 28/08/2018
 * Time: 21:42
 */

namespace CalfManager\Utils\Validate;

use Valitron\Validator;

class MedicamentoValidate extends Validate {
    public function validatePost($params)
    {
        $valida = new Validator($params);
        $valida->rule('required', ['nome']);
        $valida->rule('required', ['prescricao']);
        if($valida->validate()){
            return true;
        } else{
            $toReturn = $this->filtrarValidacao($valida);
            return $toReturn;
        }
    }

    public function validateGet($params)
    {
        // TODO: Implement validateGet() method.
    }

    public function validatePut($params)
    {
        $valida = new Validator($params);
        $valida->rule('required', [ 'nome', 'prescricao']);
        if($valida->validate()){
            return true;
        } else{
            $toReturn = $this->filtrarValidacao($valida);
            return $toReturn;
        }
    }
    public function validateDelete($params)
    {
        // TODO: Implement validateDelete() method.
    }

}