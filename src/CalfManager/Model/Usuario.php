<?php
/**
 * Created by PhpStorm.
 * User: marci
 * Date: 22/02/2018
 * Time: 15:12
 */

namespace CalfManager\Model;

use CalfManager\Model\Repository\GrupoDAO;
use CalfManager\Model\Repository\UsuarioDAO;
use CalfManager\Utils\Config;
use Exception;

/**
 * Class Usuario
 * @package CalfManager\Model
 */
class Usuario extends Modelo
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var string
     */
    private $login;
    /**
     * @var string
     */
    private $senha;

    private $grupo;
    private $funcionario;

    public function __construct()
    {
        $this->funcionario = new Funcionario();
        $this->grupo = new Grupo();
        $this->usuarioCadastro = new Usuario();
        $this->usuarioAlteracao = new Usuario();
    }


    /**
     * @return int|null
     */
    public function cadastrar(): ?int
    {
        $this->dataCriacao = date(Config::PADRAO_DATA_HORA);
        $this->dataAlteracao = date(Config::PADRAO_DATA_HORA);
        $this->usuarioCadastro->setId(1);
        try{
            $idUsuario = (new UsuarioDAO())->create($this);
            $this->depoisDeSalvar($idUsuario);
            return $idUsuario;
        }catch (Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function alterar(): bool
    {
        $this->dataAlteracao = date(Config::PADRAO_DATA_HORA);
        $this->usuarioAlteracao->setId(1);
        try{
            return (new UsuarioDAO())->update($this);
        }catch (Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @param int $page
     * @return array
     * @throws Exception
     */
    public function pesquisar(int $page): array
    {
        $dao = new UsuarioDAO();
        try{
            if($this->id and !$this->login and !$this->senha and !$this->getGrupo()->getId()){
                return $dao->retreaveById($this->id);
            } else if (!$this->id and $this->login and $this->senha and !$this->getGrupo()->getId()){
                return $dao->retreaveByLoginSenha($this->login, $this->senha);
            } else if(!$this->id and !$this->login and !$this->senha and $this->getGrupo()->getId()){
                return $dao->retreaveByGrupo($this->getGrupo()->getId(), $page);
            } else {
                return $dao->retreaveAll($page);
            }
        }catch (Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function deletar(): bool
    {
        try{
            return (new GrupoDAO())->delete($this->id);
        }catch (Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function depoisDeSalvar($idUsuario){
        $this->setId($idUsuario);
    }
    public function cadastrarFuncionario(){
        $this->getFuncionario()->cadastrar();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @param string $login
     */
    public function setLogin(string $login)
    {
        $this->login = $login;
    }

    /**
     * @return string
     */
    public function getSenha(): string
    {
        return $this->senha;
    }

    /**
     * @param string $senha
     */
    public function setSenha(string $senha)
    {
        $this->senha = $senha;
    }

    /**
     * @return Grupo
     */
    public function getGrupo(): Grupo
    {
        return $this->grupo;
    }

    /**
     * @param Grupo $grupo
     */
    public function setGrupo(Grupo $grupo)
    {
        $this->grupo = $grupo;
    }

    /**
     * @return Funcionario
     */
    public function getFuncionario(): Funcionario
    {
        return $this->funcionario;
    }

    /**
     * @param Funcionario $funcionario
     */
    public function setFuncionario(Funcionario $funcionario)
    {
        $this->funcionario = $funcionario;
    }


}
