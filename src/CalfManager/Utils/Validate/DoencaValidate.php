<?php
/**
 * Created by PhpStorm.
 * User: marci
 * Date: 26/02/2018
 * Time: 12:18
 */

namespace CalfManager\Utils\Validate;


use Valitron\Validator;

class DoencaValidate extends Validate
{

    public function validatePost($params)
    {
        $rules = [];
        $v = new Validator($params);
        $v->rules($rules);
        if ($v->validate()) {
            return true;
        } else {
            $toReturn = $this->filtrarValidacao($v);
            return $toReturn;
        }
    }

    public function validateGet($params)
    {
        // TODO: Implement validateGet() method.
    }

    public function validatePut($params)
    {
        $rules = [];
        $v = new Validator($params);
        $v->rules($rules);
        if ($v->validate()) {
            return true;
        } else {
            $toReturn = $this->filtrarValidacao($v);
            return $toReturn;
        }
    }

    public function validateDelete($params)
    {
        // TODO: Implement validateDelete() method.
    }
}