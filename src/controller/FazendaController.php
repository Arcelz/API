<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 13/09/2017
 * Time: 11:46
 */

namespace src\controller;


use src\model\Fazenda;
use src\model\validate\FazendaValidate;
use src\util\DataConversor;
use view\View;

class FazendaController implements IController
{

    public function post()
    {
        $fazenda = new Fazenda();
        $data = (new DataConversor())->converter();
        $valida = (new FazendaValidate())->validatePost($data);
        if ($valida === true) {
            $fazenda->setNomeFazenda($data['nomeFazenda']);
            View::render($fazenda->cadastrar());
        } else {
            View::render($valida);
        }
    }

    public function get($param)
    {
        $fazenda = new Fazenda();
        if (!empty($param)) {
            foreach ($param as $key => $val) {
                $var = "set" . ucfirst($key);
                if (method_exists($fazenda, 'set' . ucfirst($key))) {
                    $fazenda->$var($val);
                } else {
                    View::render([
                        "status" => 401,
                        "message" => "Parametro invalido " . $key
                    ]);
                }
            }
        }
        View::render($fazenda->pesquisar());
    }

    public function put($param)
    {
        $fazenda = new Fazenda();
        if (isset($param['idFazenda'])) {
            $data = (new DataConversor())->converter();
            $fazenda->setIdFazenda($param['idFazenda']);
            if (isset($data['nomeFazenda'])) {
                $fazenda->setNomeFazenda($data['nomeFazenda']);
            }
            View::render($fazenda->alterar());
        }
    }

    public function delete($param)
    {
        $fazenda = new Fazenda();
        if (isset($param['idFazenda'])) {
            $fazenda->setIdFazenda($param['idFazenda']);
            View::render($fazenda->deletar());
        }
    }
}