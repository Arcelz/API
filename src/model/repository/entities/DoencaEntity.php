<?php
/**
 * Created by PhpStorm.
 * User: marci
 * Date: 22/02/2018
 * Time: 11:21
 */

namespace src\model\repository\entities;

use src\model\Usuario;


/**
 * @property string nome
 * @property string descricao
 * @property string situacao
 * @property string data_cadastro
 * @property string data_alteracao
 * @property Usuario usuario_cadastro
 * @property Usuario usuario_alteracao
 * @property int id
 */
class DoencaEntity extends CalfEntity
{
    protected $table = 'doencas';
    protected $fillable = [
        'id',
        'nome',
        'descricao',
        'data_criacao',
        'data_alteracao',
        'usuario_cadastro',
        'usuario_alteracao',
        'status'
    ];


    public function animais()
    {
        return $this->belongsToMany('src\model\repository\entities\AnimalEntity',
            'animais_has_doencas', 'doencas_id', 'animais_id');
    }
}