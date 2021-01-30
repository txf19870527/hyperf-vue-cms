<?php


namespace App\Services;


interface AbstractServiceInterface
{

    public function lists(array $params);

    public function batchDelete(array $ids);

    public function update(array $params);

    public function insert(array $params);

    public function delete(int $id);
}