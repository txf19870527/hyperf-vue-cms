<?php

namespace App\Service\Interfaces\Va;

interface TypeServiceInterface
{

    /**
     * 获取收入类型
     * @return mixed
     */
    public function getIncome();

    /**
     * 获取支出类型
     * @return mixed
     */
    public function getExpense();

    /**
     * 获取所有类型
     * @param $userId
     * @return mixed
     */
    public function getAllType($userId);
}