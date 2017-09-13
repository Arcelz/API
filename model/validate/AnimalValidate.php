<?php

/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 13/09/2017
 * Time: 00:10
 */
namespace model\validate;


use Valitron\Validator;

class AnimalValidate implements IValidate
{

    public function validatePost($params)
    {
        $v = new Validator($params);
        $v->rule('required', ['dataNascimento', 'codigoRaca', 'codigoBrinco', 'fkPesagem', 'fkMae', 'fkPai', 'fkLote', 'fkFazenda']);
        $v->rule('integer',['codigoRaca','codigoBrinco','fkPesagem','fkMae','fkPai','fkLote','fkFazenda']);
        $v->rule('date','dataNascimento');
        if ($v->validate()) {
            return true;
        } else {
            // Errors
            return $v->errors();
        }
    }

    public function validateGet($params)
    {
        // TODO: Implement validateGet() method.
    }

    public function validatePut($params)
    {
        // TODO: Implement validatePut() method.
    }

    public function validateDelete($params)
    {
        // TODO: Implement validateDelete() method.
    }
}