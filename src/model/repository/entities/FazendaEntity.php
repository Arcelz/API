<?php
/**
 * Created by PhpStorm.
 * User: marci
 * Date: 11/02/2018
 * Time: 17:47
 */

namespace src\model\repository\entities;
use src\model\Usuario;


/**
 * @property string data_alteracao
 * @property string nome
 * @property string data_cadastro
 * @property Usuario usuario_cadastro
 * @property int id
 */
class FazendaEntity extends CalfEntity
{
    protected $table = 'fazendas';
    protected $fillable = [
        'nome',
        'data_alteracao',
        'data_cadastro',
        'usuario_cadastro',
        'usuario_alteracao',
        'status',
    ];


    public function animais() {
        return $this->hasMany('\src\model\repository\entities\AnimalEntity');
    }
}