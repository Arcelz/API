<?php
/**
 * Created by PhpStorm.
 * User: Brunno
 * Date: 27/08/2018
 * Time: 21:12
 */

namespace CalfManager\Model\Repository\Entity;


/**
 * @property mixed id
 * @property int status
 * @property mixed usuario_cadastro
 * @property string data_cadastro
 * @property mixed endereco_id
 * @property mixed data_nascimento
 * @property mixed numero_telefone
 * @property mixed sexo
 * @property mixed cpf
 * @property mixed rg
 * @property mixed nome
 */
class PessoaEntity extends CalfEntity
{
    protected $table = "pessoas";
    protected $fillable = [
        'id',
        'nome',
        'rg',
        'cpf',
        'sexo',
        'numero_telefone',
        'data_nascimento',
        'endereco_id',
        'data_alteracao',
        'data_cadastro',
        'usuario_alteracao',
        'usuario_cadastro',
        'status'
    ];
    protected $casts = [
        'data_nascimento' => 'date:d/m/Y'
    ];
    public function endereco() {
        return $this->belongsTo("\CalfManager\Model\Repository\Entity\EnderecoEntity", "endereco_id");
    }
    public function funcionario(){
        return $this->hasMany("CalfManager\Model\Repository\Entity\FuncionarioEntity");
    }
}