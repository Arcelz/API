<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 13/09/2017
 * Time: 00:03
 */
namespace src\model\repository;

use src\model\Modelo;


/**
 * Interface IDAO
 * @package src\model\repository
 */
interface IDAO
{
    /**
     * @param $obj
     * @return int|null
     */
    public function create($obj): ?int ;

    /**
     * @param Modelo $obj
     * @return bool
     */
    public function update($obj): bool ;

    /**
     * @param int $page
     * @return array
     */
    public function retreaveAll(int $page): array;

    /**
     * @param int $id
     * @return array
     */
    public function retreaveById(int $id): array;

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}