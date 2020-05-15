<?php


namespace App\Service\Interfaces;


interface AbstractInterface
{
    /**
     * 列表
     * @param array $params
     * @return mixed
     */
    public function lists(array $params);

    /**
     * 删除/批量删除
     * @param array $ids
     * @return mixed
     */
    public function batchDelete(array $ids);

    /**
     * 更新
     * @param array $params
     * @return mixed
     */
    public function update(array $params);

    /**
     * 新增
     * @param array $params
     * @return mixed
     */
    public function insert(array $params);
}