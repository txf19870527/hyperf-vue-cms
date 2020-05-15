<?php


namespace App\Service\Interfaces\V1;


interface UserServiceInterface
{
    /**
     * 小程序登录
     * @param array $params
     * @return mixed
     */
    public function login(array $params);
}