<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 13/09/2017
 * Time: 12:04
 */

namespace model\dao;


use bd\Banco;
use Exception;
use PDO;

class FazendaDAO implements IDAO
{

    public function create($obj)
    {
        $messagem = "";
        try {
            $db = Banco::conexao();
            $queryVal = "";
            $queryNam = "";
            foreach ($obj as $key => $value) {
                $queryVal .= ":" . $key . ",";
                $queryNam .= $key . ",";
            }
            $queryVal = substr_replace($queryVal, '', -1);
            $queryNam = substr_replace($queryNam, '', -1);
            $query = "INSERT INTO fazendas($queryNam) VALUES ($queryVal)";
            $stmt = $db->prepare($query);
            foreach ($obj as $key => &$val) {
                $stmt->bindParam($key, $val);
            }
            $stmt->execute();
            $messagem = "Fazenda adicionada com sucesso";
        } catch (Exception $e) {
            $messagem = $e->getMessage();
        }
        return $messagem;
    }

    public function update($obj)
    {
        // TODO: Implement update() method.
    }

    public function retrave($obj)
    {
        try {
            $db = Banco::conexao();
            $query = "SELECT * FROM fazendas WHERE status = 'ATIVO'";
            if ($obj['idFazenda'] !== 0) {
                $query .= " AND idFazenda = :idFazenda";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':idFazenda', $obj['idFazenda'], PDO::PARAM_INT);
            } else {
                $stmt = $db->prepare($query);
            }
            $stmt->execute();
            if(!empty($stmt->rowCount())){
                $messagem = ($stmt->fetchAll(PDO::FETCH_ASSOC));
            }
            else{
                $messagem = "Não foi possivel realizar a busca";
            }
        } catch (Exception $e) {
            $messagem = $e->getMessage();
        }
        return $messagem;
    }

    public function delete($obj)
    {
        // TODO: Implement delete() method.
    }
}