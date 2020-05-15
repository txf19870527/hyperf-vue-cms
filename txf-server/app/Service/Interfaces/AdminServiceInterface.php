<?php

namespace App\Service\Interfaces;

interface AdminServiceInterface/* extends AbstractInterface*/
{
    /**
     * @param string $mobile
     * @param string $password
     * @return array
     * 登录
     */
    public function login(string $mobile, string $password);

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

    /**
     * 设置员工角色
     * @param array $params
     * @return mixed
     */
    public function saveRoles(array $params);

    /**
     * 退出登录
     * @param int $adminId
     * @return mixed
     */
    public function loginOut(int $adminId);

    /**
     * 更改密码
     * @param array $params
     * @param int $id
     * @return mixed
     */
    public function updatePassword(array $params, int $id);
}