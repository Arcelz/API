<?php
/**
 * Created by PhpStorm.
 * User: Brunno
 * Date: 12/09/2018
 * Time: 17:43
 */

namespace CalfManager\Controller;


use CalfManager\Model\DoseAplicada;
use CalfManager\Utils\Validate\DoseAplicadaValidate;
use CalfManager\View\View;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Exception;

class DoseAplicadaController implements IController
{
    public function post(Request $request, Response $response): Response
    {
         $dose = new DoseAplicada();
         $data = json_decode($request->getBody()->getContents());
         $valida = (new DoseAplicadaValidate())->validatePost((array) $data);
         try{
             if($valida) {
                 $dose->setDose($data->dose);
                 $dose->getMedicamento()->setId($data->medicamento->id);
                 $dose->setDataAplicacao($data->data_aplicacao);
                 if ($dose->cadastrar()) {
                     return View::renderMessage(
                         $response,
                         "success",
                         "Dose aplicada cadastrada com sucesso!",
                         201,
                         "Sucesso ao cadastrar"
                     );
                 } else {
                     return View::renderMessage(
                         $response,
                         "error",
                         "Erro ao cadastrar dose aplicada",
                         500,
                         "Erro ao cadastrar");
                 }
             } else {
                 return View::renderMessage($response, "warning", $valida, 400);
             }
         }catch (Exception $e){
             return View::renderException($response, $e);
         }
    }

    public function get(Request $request, Response $response, array $args): Response
    {
        $dose = new DoseAplicada();
        $page = (int) $request->getQueryParam('pagina');
        try{
            if($request->getAttribute('id')){
                $dose->setId($request->getAttribute('id'));
            }
            if($request->getQueryParam('medicamento')){
                $dose->getMedicamento()->setId($request->getQueryParam('medicamento'));
            }
            $search = $dose->pesquisar($page);
            return View::render($response, $search);

        }catch (Exception $e){
            return View::renderException($response, $e);
        }
    }

    public function put(Request $request, Response $response): Response
    {
        $dose = new DoseAplicada();
        $data = json_decode($request->getBody()->getContents());
        $valida = (new DoseAplicadaValidate())->validatePost((array) $data);
        try{
            if($valida) {
                $dose->setId($request->getAttribute('id'));
                if(!is_null($data->dose)) {
                    $dose->setDose($data->dose);
                }
                if(!is_null($data->medicamento->id)) {
                    $dose->getMedicamento()->setId($data->medicamento->id);
                }
                if(!is_null($data->data_aplicacao)) {
                    $dose->setDataAplicacao($data->data_aplicacao);
                }
                if ($dose->cadastrar()) {
                    return View::renderMessage(
                        $response,
                        "success",
                        "Dose aplicada alterada com sucesso!",
                        201,
                        "sucesso ao alterar"
                    );
                } else {
                    return View::renderMessage(
                        $response,
                        "error",
                        "Erro ao alterar dose aplicada",
                        500,
                        "erro ao alterar"
                    );
                }
            } else {
                return View::renderMessage(
                    $response,
                    "warning",
                    $valida,
                    400
                );
            }
        }catch (Exception $e){
            return View::renderException($response, $e);
        }
    }

    public function delete(Request $request, Response $response): Response
    {
         $dose = new DoseAplicada();
         try {
             if ($request->getAttribute('id')) {
                 $dose->setId($request->getAttribute('id'));

                 if ($dose->deletar()) {
                     return View::renderMessage(
                         $response,
                         "success",
                         "Dose aplicada excluída com sucesso!",
                         201,
                         "Sucesso ao excluir"
                     );
                 } else {
                     return View::renderMessage(
                         $response,
                         "error",
                         "Erro ao excluir dose aplicada",
                         400,
                         "Erro ao excluir"
                     );
                 }
             }
         }catch (Exception $e){
             return View::renderException($response, $e);
         }
    }

}