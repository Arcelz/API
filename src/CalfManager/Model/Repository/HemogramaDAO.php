<?php
/**
 * Created by PhpStorm.
 * User: Brunno
 * Date: 29/08/2018
 * Time: 17:12
 */

namespace CalfManager\Model\Repository;


use CalfManager\Model\Hemograma;
use CalfManager\Model\Repository\Entity\HemogramaEntity;
use CalfManager\Utils\Config;
use Carbon\Carbon;
use InvalidArgumentException;
use Exception;

class HemogramaDAO implements IDAO
{
    /**
     * @param Hemograma $obj
     * @return int|null
     * @throws Exception
     */
    public function create($obj): ?int
    {
        $entity = new HemogramaEntity();
        $entity->data = $obj->getData();
        $entity->ppt = $obj->getPpt();
        $entity->hematocrito = $obj->getHematocrito();
        $entity->animal_id = $obj->getAnimal()->getId();
        $entity->funcionario_id = $obj->getFuncionario()->getId();

        $entity->data_cadastro = $obj->getDataCriacao();
        $entity->usuario_cadastro = $obj->getUsuarioCadastro()->getId();
        $entity->status = 1;
        try {
            if ($entity->save()) {
                return $entity->getKey();
            }
        } catch (Exception $e) {
            throw new Exception("Erro ao cadastrar hemograma. Mensagem: " . $e->getMessage());
        }
    }

    /**
     * @param Hemograma $obj
     * @return bool
     * @throws Exception
     */
    public function update($obj): bool
    {
        $entity = HemogramaEntity::find($obj->getId());
        $entity->data = $obj->getData();

        $entity->data_alteracao = $obj->getDataAlteracao();
        $entity->usuario_alteracao = $obj->getUsuarioAlteracao()->getId();
        if (!is_null($obj->getPpt())) {
            $entity->ppt = $obj->getPpt();
        }
        if (!is_null($obj->getHematocrito())) {
            $entity->hematocrito = $obj->getHematocrito();
        }
        if (!is_null($obj->getFuncionario()->getId())) {
            $entity->funcionario_id = $obj->getFuncionario()->getId();
        }
        try {
            if ($entity->update()) {
                return $entity->id;
            }
        } catch (Exception $e) {
            throw new Exception("Erro ao alterar hemograma. Mensagem: " . $e->getMessage());
        }
    }

    /**
     * @param int $page
     * @return array
     * @throws Exception
     */
    public function retreaveAll(int $page): array
    {

        try {
            $entity = HemogramaEntity::ativo()->paginate(
                Config::QUANTIDADE_ITENS_POR_PAGINA,
                ['*'],
                'pagina',
                $page
            );
            return ["hemogramas" => $entity];
        } catch (Exception $e) {
            throw new Exception("Erro ao pesquisar todos os hemogramas. Mensagem: " . $e->getMessage());
        }
    }

    /**
     * @param int $id
     * @return array
     * @throws Exception
     */
    public function retreaveById(int $id): array
    {

        try {
            $entity = HemogramaEntity::ativo()->where('id', $id)->first()->toArray();
            return ["hemogramas" => $entity];
        } catch (Exception $e) {
            throw new Exception("Erro ao pesquisar hemograma pelo ID " . $id . ". Mensagem: " . $e->getMessage());
        }
    }

    public function retreaveByAnimalId(int $animalId, $page){
        try{
            $entity = HemogramaEntity::ativo()->where('animal_id', $animalId)->paginate(
                Config::QUANTIDADE_ITENS_POR_PAGINA,
                ['*'],
                'pagina',
                $page
            );
            return ['hemogramas' => $entity];
        }catch (Exception $e){
            throw new Exception("Erro ao pesquisar hemograma por animal id " . $animalId . ". Mensagem: " . $e->getMessage());
        }
    }
    public function retreaveByFuncionarioId(int $funcionarioId, $page){
        try{
            $entity = HemogramaEntity::ativo()->where('funcionario_id', $funcionarioId)->paginate(
                Config::QUANTIDADE_ITENS_POR_PAGINA,
                ['*'],
                'pagina',
                $page
            );
            return ['hemogramas' => $entity];
        }
        catch (Exception $e){
            throw new Exception("Erro ao pesquisar hemograma por funcionario id " . $funcionarioId . ". Mensagem: " . $e->getMessage());
        }
    }
    /**
     * @param array $params
     * @return array
     */
    public function graphMonitorDeSaude($params = []): array
    {
        if (!isset($params['animal'])) {
            throw new InvalidArgumentException('Argumento animal é requerido, adicione o id do animal pela requisição (?animal={id})');
        }
        return [
            HemogramaEntity::ativo()
                ->where('animal_id', $params['animal'])
                ->whereDate('data', '<=', Carbon::now())
                ->get(['ppt', 'hematocrito', 'data'])
        ];
    }

    /**
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function delete(int $id): bool
    {
        try {
            $entity = HemogramaEntity::find($id);
            $entity->status = 0;
            if ($entity->save()) {
                return true;
            }
        } catch (Exception $e) {
            throw new Exception("Erro ao excluir hemograma. Mensagem: " . $e->getMessage());
        }
    }

}