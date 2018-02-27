<?php
/**
 * Created by PhpStorm.
 * User: marci
 * Date: 16/02/2018
 * Time: 19:12
 */

namespace src\util\validate;


interface IValidate
{
    public function validatePost($params);
    public function validateGet($params);
    public function validatePut($params);
    public function validateDelete($params);
}