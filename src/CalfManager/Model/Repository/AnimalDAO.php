<?php

/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 13/09/2017
 * Time: 00:01
 */

namespace CalfManager\Model\Repository;


use Exception;
use CalfManager\Model\Animal;
use CalfManager\Model\Repository\Entity\AnimalEntity;
use CalfManager\Model\Repository\Entity\DoencaEntity;
use CalfManager\Utils\Config;

/**
 * Class AnimalDAO
 * @package CalfManager\Model\Repository
 */
class AnimalDAO implements IDAO
{
    private $vivo;
    private $sexo;
    private $ativo;

    public function create($obj): ?int
    {
        $entity = new AnimalEntity();
        $entity->nome = $obj->getNome();
        $entity->sexo = $obj->getSexo();
        $entity->data_nascimento = $obj->getDataNascimento();
        $entity->codigo_brinco = $obj->getCodigoBrinco();
        $entity->codigo_raca = $obj->getCodigoRaca();
        $entity->data_cadastro = $obj->getDataCriacao();
        $entity->data_alteracao = $obj->getDataAlteracao();
        $entity->usuario_cadastro = $obj->getUsuarioCadastro()->getId();
        $entity->usuario_alteracao = $obj->getUsuarioAlteracao()->getId();
        $entity->fase_vida = $obj->getFaseDaVida();
        $entity->lotes_id = $obj->getLote()->getId();
        $entity->is_vivo = $obj->isVivo();
        $entity->fazendas_id = $obj->getFazenda()->getId();
        $entity->data_morte = $obj->getDataMorte();
        $entity->nascido_morto = $obj->getNascidoMorto();
        try {
            if ($entity->save()) {
                return $entity->id;
            }

        } catch (Exception $e) {
            throw new Exception("Erro ao tentar salvar um novo Animal. " . $e->getMessage());
        }
        return false;
    }

    /**
     * @param int $idAnimal
     * @param int $idDoenca
     * @param string $situacao
     */
    public function createAdoecimento(int $idAnimal, int $idDoenca, string $situacao = 'CURADO')
    {
        $animal = AnimalEntity::find($idAnimal);
        DoencaEntity::find($idDoenca)->animais()->attach($animal, ['situacao' => $situacao]);
    }

    /**
     * @param Animal $obj
     * @return bool
     */
    public function update($obj): bool
    {
        $entity = AnimalEntity::find($obj->getId());
        $entity->data_alteracao = $obj->getDataAlteracao();
        $entity->usuario_alteracao = $obj->getUsuarioAlteracao()->getId();
        if (!is_null($obj->getNome())) {
            $entity->nome = $obj->getNome();
        }
        if(!is_null($obj->getSexo())){
            $entity->sexo = $obj->getSexo();
        }
        if (!is_null($obj->getDataNascimento())) {
            $entity->data_nascimento = $obj->getDataNascimento();
        }
        if (!is_null($obj->getPrimogenito())) {
            $entity->primogenito = $obj->getPrimogenito();
        }
        if (!is_null($obj->getFaseDaVida())) {
            $entity->fase_vida = $obj->getFaseDaVida();
        }
        if (!is_null($obj->getCodigoBrinco())) {
            $entity->codigo_brinco = $obj->getCodigoBrinco();
        }
        if (!is_null($obj->getCodigoRaca())) {
            $entity->codigo_raca = $obj->getCodigoRaca();
        }
        if (!is_null($obj->getDataAlteracao())) {
            $entity->data_alteracao = $obj->getDataAlteracao();
        }
        if (!is_null($obj->getLote()->getId())) {
            $entity->lotes_id = $obj->getLote()->getId();
        }
        if (!is_null($obj->getFazenda()->getId())) {
            $entity->fazendas_id = $obj->getLote()->getId();
        }
        if($obj->isVivo() == false){
            $entity->is_vivo = 0;
            $entity->data_morte = $obj->getDataMorte();
        }else{
            $entity->is_vivo = 1;
        }
        if ($entity->update()) {
            return $entity->getKey();
        };
        return false;
    }

    /**
     * @param int $page
     * @return array
     */
    public function retreaveAll(int $page): array
    {
        $entity = AnimalEntity::ativo();
        if (!is_null($this->vivo)) {
            $entity->where('is_vivo', $this->vivo);
        }
        if (!is_null($this->sexo)) {
            $entity->where('sexo', $this->sexo);
        }

        $animais = $entity->with('hemogramas')
            ->with('doses')
            ->with('pesagens')
            ->with('doencas')
            ->with('fazenda')
            ->with('lote')
            ->paginate(
                Config::QUANTIDADE_ITENS_POR_PAGINA,
                ['*'],
                'pagina',
                $page
            );
        return ["animais" => $animais];
    }

    /**
     * @param int $id
     * @return array
     * @throws Exception
     */
    public function retreaveById(int $id): array
    {
        try {
            $entity = AnimalEntity::ativo();
            if (!is_null($this->vivo)) {
                $entity->where('is_vivo', $this->vivo);
            }
            if (!is_null($this->sexo)) {
                $entity->where('sexo', $this->sexo);
            }
            $animal = $entity->with('hemogramas')
                ->with('doses')
                ->with('pesagens')
                ->with('doencas')
                ->with('fazenda')
                ->with('lote')
                ->where('id', $id)
                ->first()
                ->toArray();
            return ["animais" => $animal];
        } catch (Exception $e) {
            throw new Exception("Algo de errado aconteceu ao tentar pesquisar por ID" . $e->getMessage());
        }
    }

    public function retreaveQuantidadeAnimais()
    {
        try {
            $entity = AnimalEntity::ativo()->where('is_vivo', 1)->get()->count();
            return ["animais" => $entity];
        }
        catch (Exception $e){
            throw new Exception("Erro ao contar animais".$e->getMessage());
        }
    }
    
    // adicionar método para consultar obter a quantidade de animais por lote.

    public function retreaveQtdAnimaisDoentes()
    {
        try{
            $animais = AnimalEntity::ativo()
                ->with('doencas')
                ->whereHas('doencas', function ($situacao) {
                    $situacao->where('situacao', 'DOENTE');
                })
                ->get()
                ->count();
            return ['animais' => $animais];
        }catch (Exception $e){
            throw new Exception('Erro ao pesquisar por animais doentes! ' . $e);
        }
    }


    /**
     * @param string $nome
     * @param int $page
     * @return array
     * @throws Exception
     */
    public function retreaveByNome(string $nome, int $page): array
    {
        try {
            $entity = AnimalEntity::ativo();
            if (!is_null($this->vivo)) {
                $entity->where('is_vivo', $this->vivo);
            }
            if (!is_null($this->sexo)) {
                $entity->where('sexo', $this->sexo);
            }
            $animais = $entity->with('hemogramas')
                ->with('doses')
                ->with('pesagens')
                ->with('doencas')
                ->with('fazenda')
                ->with('lote')
                ->where('nome', 'like', $nome . "%")
                ->paginate(
                    Config::QUANTIDADE_ITENS_POR_PAGINA,
                    ['*'],
                    'pagina',
                    $page
                );
            return ["animais" => $animais];
        } catch (Exception $e) {
            throw new Exception("Algo de errado aconteceu ao tentar pesquisar por nome" . $e->getMessage());
        }
    }


    /**
     * @param int $idLote
     * @param int $page
     * @return array
     * @throws Exception
     */
    public function retreaveByIdLote(int $idLote, int $page)
    {
        try {
            $entity = AnimalEntity::ativo();
            $animaisLote = $entity->with('hemogramas')
                ->with('doses')
                ->with('pesagens')
                ->with('doencas')
                ->with('fazenda')
                ->with('lote')
                ->where('lotes_id', $idLote)
                ->where('is_vivo', $this->vivo)
                ->paginate(
                    Config::QUANTIDADE_ITENS_POR_PAGINA,
                    ['*'],
                    'pagina',
                    $page
                );
            return ["animais" => $animaisLote];
        } catch (Exception $e) {
            throw new Exception("Algo de errado aconteceu ao tentar pesquisar por ID" . $e->getMessage());
        }
    }

    /**
     * @param int $idLote
     * @param string $nome
     * @param int $page
     * @return array
     * @throws Exception
     */
    public function retreaveByIdLoteAndName(int $idLote, 
                                            string $nome, 
                                            int $page)
    {
        try {
            $entity = AnimalEntity::ativo();
            $animais = $entity->with('hemogramas')
                ->with('doses')
                ->with('pesagens')
                ->with('doencas')
                ->with('fazenda')
                ->with('lote')
                ->where('nome', 'like', $nome . "%")
                ->where('lotes_id', $idLote)
                ->where('is_vivo', $this->vivo)
                ->paginate(
                    Config::QUANTIDADE_ITENS_POR_PAGINA,
                    ['*'],
                    'pagina',
                    $page
                );
            return ["animais" => $animais];
        } catch (Exception $e) {
            throw new Exception("Algo de errado aconteceu ao tentar pesquisar por por nome e lote" . $e->getMessage());
        }
    }
    
    public function retreaveAnimalDoente($page)
    {
        try{
            $animais = AnimalEntity::ativo()
                ->with('hemogramas')
                ->with('doses')
                ->with('pesagens')
                ->with('lote')
                ->with('fazenda')
                ->with('doencas')
                ->whereHas('doencas', function ($situacao) {
                    $situacao->where('situacao', 'DOENTE');
                })
                ->paginate(
                    Config::QUANTIDADE_ITENS_POR_PAGINA,
                    ['*'],
                    'pagina',
                    $page
                );
            return ['animais' => $animais];
        }catch (Exception $e){
            throw new Exception('Erro ao pesquisar por animais doentes! ' . $e);
        }
    }

    public function retreaveAnimaisMortosAoNascer($page)
    {
        $animais = AnimalEntity::ativo()
            ->where("is_vivo", false)
            ->paginate(
                Config::QUANTIDADE_ITENS_POR_PAGINA,
                ['*'],
                'pagina',
                $page
            );

    }

    public function retreaveNatimortos($page)
    {
        $animais = AnimalEntity::ativo()
            ->where("nascido_morto", true)
            ->paginate(
                Config::QUANTIDADE_ITENS_POR_PAGINA,
                ['*'],
                'pagina',
                $page
            );
        
        return ["animais" => $animais];
    }
    
    public function retreaveQtdAnimaisMortos()
    {
        $animais = AnimalEntity::ativo()
            ->where("is_vivo", false)
            ->get()
            ->count();
        return ["animais" => $animais];
    }

    /**
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function delete(int $id): bool
    {
        try {
            $entity = AnimalEntity::find($id);
            $entity->status = 0;
            if ($entity->save()) {
                return true;
            };
        } catch (Exception $e) {
            throw new Exception("Algo de errado aconteceu ao tentar desativar um animal" . $e->getMessage());
        }
        return false;
    }

    /**
     * @param bool $vivo
     * @return void
     */
    public function setVivo(?bool $vivo): void
    {
        $this->vivo = $vivo;
    }

    /**
     * @return mixed
     */
    public function getSexo()
    {
        return $this->sexo;
    }

    /**
     * @param mixed $sexo
     */
    public function setSexo($sexo): void
    {
        $this->sexo = $sexo;
    }

    /**
     * @param mixed $ativo
     */
    public function setAtivo($ativo): void
    {
        $this->ativo = $ativo;
    }

    /**
     * @return bool
     */
    public function isVivo(): bool
    {
        return $this->vivo;
    }

    /**
     * @return mixed
     */
    public function getAtivo()
    {
        return $this->ativo;
    }


}