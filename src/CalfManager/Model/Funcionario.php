<?php
/**
 * Created by PhpStorm.
 * User: Brunno
 * Date: 25/08/2018
 * Time: 15:19
 */

namespace CalfManager\Model;


use CalfManager\Model\Repository\FuncionarioDAO;
use CalfManager\Utils\Config;
use Exception;

class Funcionario extends Modelo
{
    private $salario;

    private $cargo;
    private $fazenda;
    private $pessoa;


    /**
     * Funcionario constructor.
     */
    public function __construct()
    {
         $this->cargo = new Cargo();
         $this->fazenda = new Fazenda();
         $this->pessoa = new Pessoa();
    }


    public function cadastrar(): ?int
    {
        $this->dataCriacao = date(Config::PADRAO_DATA_HORA);

        try{
            if($this->getUsuarioCadastro()->getId() == null){
                $this->getUsuarioCadastro()->setId(1);
            }

            $idFuncionario = (new FuncionarioDAO())->create($this);
            return $idFuncionario;
        }catch (Exception $e){
            throw new Exception($e->getMessage());
        }

    }

    public function alterar(): bool
    {
        $this->dataAlteracao = date(Config::PADRAO_DATA_HORA);

        try{
            if($this->getUsuarioAlteracao()->getId() == null){
                $this->getUsuarioAlteracao()->setId(1);
            }

            return (new FuncionarioDAO())->update($this);
        }catch (Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function pesquisar(int $page): array
    {
        $dao = new FuncionarioDAO();
        try{
            if($this->id and !$this->getCargo()->getId()  and !$this->getFazenda()->getId() and !$this->getPessoa()->getId()){
                return $dao->retreaveById($this->id);
            }
            if(!$this->id and $this->getCargo()->getId()  and !$this->getFazenda()->getId() and !$this->getPessoa()->getId()){
                return $dao->retreaveByIdCargo($this->getCargo()->getId(), $page);
            }
            if(!$this->id and !$this->getCargo()->getId()  and $this->getFazenda()->getId() and !$this->getPessoa()->getId()){
                return $dao->retreaveByIdFazenda($this->getFazenda()->getId(), $page);
            }
            if(!$this->id and !$this->getCargo()->getId()  and !$this->getFazenda()->getId() and $this->getPessoa()->getId()){
                return $dao->retreaveByIdPessoa($this->getFazenda()->getId());
            }
            if($this->getPessoa()->getNome()){
                return $dao->retreaveByNomePessoa($this->getPessoa()->getNome(), $page);
            }
                return $dao->retreaveAll($page);
        }catch (Exception $e){
            throw new Exception($e->getMessage());
        }


    }

    public function deletar(): bool
    {
        try{
            return (new FuncionarioDAO())->delete($this->id);
        }catch (Exception $e){
            throw new Exception($e->getMessage());
        }
    }
    public function antesDeSalvar(){
        $this->cadastrarUsuario();
    }

    public function cadastrarUsuario(){
        $this->getUsuario()->cadastrar();
    }

    /**
     * @return mixed
     */
    public function getCargo()
    {
        return $this->cargo;
    }

    /**
     * @param mixed $cargo
     */
    public function setCargo($cargo)
    {
        $this->cargo = $cargo;
    }

    /**
     * @return mixed
     */
    public function getSalario()
    {
        return $this->salario;
    }

    /**
     * @param mixed $salario
     */
    public function setSalario($salario)
    {
        $this->salario = $salario;
    }

    /**
     * @return Fazenda
     */
    public function getFazenda(): Fazenda
    {
        return $this->fazenda;
    }

    /**
     * @param Fazenda $fazenda
     */
    public function setFazenda(Fazenda $fazenda)
    {
        $this->fazenda = $fazenda;
    }

    /**
     * @return Pessoa
     */
    public function getPessoa(): Pessoa
    {
        return $this->pessoa;
    }

    /**
     * @param Pessoa $pessoa
     */
    public function setPessoa(Pessoa $pessoa)
    {
        $this->pessoa = $pessoa;
    }



}