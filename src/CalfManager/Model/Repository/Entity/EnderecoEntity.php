<?php
/**
 * Created by PhpStorm.
 * User: Brunno
 * Date: 27/08/2018
 * Time: 21:17
 */

namespace CalfManager\Model\Repository\Entity;


class EnderecoEntity extends CalfEntity
{
    protected $table = "enderecos";
    protected $fillable = [
        'id',
        'logradouro',
        'numero',
        'bairro',
        'cidade',
        'cidade',
        'estado',
        'pais',
        'cep',
        'data_alteracao',
        'data_cadastro',
        'usuario_cadastro',
        'usuario_alteracao',
        'status'
    ];
    public function pessoas() {
        return $this->hasMany("\CalfManager\Model\Repository\Entity\PessoaEntity");
    }
}