<?php
/**
 * Created by PhpStorm.
 * User: Brunno
 * Date: 02/09/2018
 * Time: 21:44
 */

namespace CalfManager\Controller;

use CalfManager\Model\Grupo;
use CalfManager\Model\Permissao;
use CalfManager\Utils\Validate\PermissaoValidade;
use CalfManager\View\View;
use Exception;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class PermissaoController implements IController
{
    public function post(Request $request, Response $response): Response
    {
        try {
            $permissao = new Permissao();
            $data = json_decode($request->getBody()->getContents());
            $valida = (new PermissaoValidade())->validatePost((array)$data);
            if ($valida) {
                $permissao->setNomeModulo($data->nome_modulo);
                $permissao->setCreate($data->create);
                $permissao->setUpdate($data->update);
                $permissao->setRead($data->read);
                $permissao->setDelete($data->delete);
                $permissao->setGrupo(new Grupo());
                $permissao->getGrupo()->setId($data->grupo_id);

                if ($permissao->cadastrar()) {
                    return View::renderMessage(
                        $response,
                        "success",
                        "Permissão cadastrada com sucesso!",
                        201, "Sucesso ao cadastrar"
                    );
                } else {
                    return View::renderMessage($response,
                        "error",
                        "Pesagem não cadastrada",
                        500);
                }

            }
        }catch (Exception $e){
            return View::renderMessage($response, 'warning', $valida, 400);
        }
    }

    public function get(Request $request, Response $response, array $args): Response
    {
        try{
            $permissao = new Permissao();
            $page = (int) $request->getQueryParam('pagina');
            if($request->getAttribute('id')){
                $permissao->setId($request->getAttribute('id'));
            }
            if($request->getQueryParam('nome_modulo')){
                $permissao->setNomeModulo($request->getQueryParam('nome_modulo'));
            }
            if($request->getQueryParam('grupo')){
                $permissao->getGrupo()->setId($request->getQueryParam('grupo'));
            }
            return View::renderMessage($response, $permissao->pesquisar($page));
        }catch (Exception $e){
            return View::renderException($response, $e);
        }
    }

    public function put(Request $request, Response $response): Response
    {
        try {
            $permissao = new Permissao();
            $data = json_decode($request->getBody()->getContents());
            $valida = (new PermissaoValidade())->validatePost((array)$data);
            if ($valida) {
                $permissao->setId($request->getAttribute('id'));
                if($data->nome_modulo){
                    $permissao->setNomeModulo($data->nome_modulo);
                }
                if($data->create){
                    $permissao->setCreate($data->create);
                }
                if($data->update){
                    $permissao->setUpdate($data->update);
                }
                if($data->read){
                    $permissao->setRead($data->read);
                }
                if($data->delete){
                    $permissao->setDelete($data->delete);
                }
                if($data->grupo_id) {
                    $permissao->setGrupo(new Grupo());
                    $permissao->getGrupo()->setId($data->grupo_id);
                }

                if ($permissao->alterar()) {
                    return View::renderMessage(
                        $response,
                        "success",
                        "Permissão alterada com sucesso!",
                        201, "Sucesso ao alterar"
                    );
                } else {
                    return View::renderMessage($response,
                        "error",
                        "Pesagem não alterada",
                        500);
                }

            }
        }catch (Exception $e){
            return View::renderMessage($response, 'warning', $valida, 400);
        }
    }

    public function delete(Request $request, Response $response): Response
    {
        try {
            $permissao = new Permissao();
            if ($request->getAttribute('id')) {
                $permissao->setId($request->getAttribute('id'));
            }
            if ($permissao->deletar()) {
                return View::renderMessage(
                    $response,
                    "success",
                    "Permissão excluída com sucesso!",
                    201,
                    "Sucesso ao excluir");
            }
        }catch (Exception $e){
            return View::renderException($response, $e);
        }
    }

}