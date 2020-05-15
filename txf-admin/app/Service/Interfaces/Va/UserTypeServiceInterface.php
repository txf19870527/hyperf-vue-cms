<?php


namespace App\Service\Interfaces\Va;


interface UserTypeServiceInterface
{
    /**
     * 获取用户自定义收入类型
     * @param int $userId
     * @return mixed
     */
    public function getIncome(int $userId);

    /**
     * 获取用户自定义支出类型
     * @param int $userId
     * @return mixed
     */
    public function getExpense(int $userId);

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

    /**
     * 删除
     * @param $id
     * @param $userId
     * @return mixed
     */
    public function delete($id, $userId);
}