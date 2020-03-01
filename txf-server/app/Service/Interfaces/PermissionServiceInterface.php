<?php

namespace App\Service\Interfaces;


interface PermissionServiceInterface
{

    /**
     * 获取权限下拉列表
     * @param int $depth
     * @param int|null $status
     * @return mixed
     */
    public function getPermissionCascader(int $depth, ?int $status);

    /**
     * 获取权限菜单
     * @param int $userId
     * @param array $roles
     * @return mixed
     */
    public function getPermissionTree(int $userId, array $roles);

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
     * 获取所有权限以及对应角色的关联情况
     * @param int $roleId
     * @return mixed
     */
    public function getPermissionsWithRole(int $roleId);

    /**
     * 获取配置了前端路由的权限信息
     * @return array
     */
    public function getPermissionFromIndex();
}