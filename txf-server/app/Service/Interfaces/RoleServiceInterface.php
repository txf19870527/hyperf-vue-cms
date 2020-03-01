<?php

namespace App\Service\Interfaces;

interface RoleServiceInterface /*extends AbstractInterface*/
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
    public function mulDel(array $ids);

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
     * 获取所有角色并标识该角色与对应员工是否有关联
     * @param $adminId
     */
    public function getRolesWithAdmin(int $adminId);

    /**
     * 保存角色权限关系
     * @param array $params
     * @return mixed
     */
    public function savePermissions(array $params);
}