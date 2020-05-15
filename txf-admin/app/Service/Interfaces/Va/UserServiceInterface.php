<?php


namespace App\Service\Interfaces\Va;


interface UserServiceInterface
{
    /**
     * 小程序登录
     * @param array $params
     * @return mixed
     */
    public function login(array $params);
}