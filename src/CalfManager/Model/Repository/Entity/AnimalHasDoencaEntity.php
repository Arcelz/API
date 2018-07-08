<?php
/**
 * Created by PhpStorm.
 * User: marci
 * Date: 02/07/2018
 * Time: 23:07
 */

namespace CalfManager\Model\Repository\entities;


class AnimalHasDoencaEntity extends CalfEntity
{
    protected $table = 'animais_has_doencas';
    protected $fillable = [
        'animais_id',
        'doencas_id',
        'situacao'
    ];


}