<?php


namespace App\Service;


use App\Com\Page;
use App\Com\ResponseCode;
use App\Event\ResetRolePermissionEvent;
use App\Exception\BusinessException;
use App\Model\AdminRole;
use App\Model\Role;
use App\Model\RolePermission;
use App\Service\Interfaces\RoleServiceInterface;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use Hyperf\RpcServer\Annotation\RpcService;
use phpDocumentor\Reflection\DocBlock\Tags\Throws;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * Class RoleService
 * @package App\Service
 * @RpcService(name="RoleService",protocol="jsonprc",server="jsonrpc")
 */
class RoleService implements RoleServiceInterface
{

    /**
     * @Inject()
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * 角色列表
     * @param array $params
     * @return mixed|void
     */
    public function lists(array $params)
    {
        $qb = Role::query();

        $qb = $qb->where(function ($qb) use ($params) {
            if (!empty($params['role_name'])) {
                $qb->where("role_name", "like", "%{$params['role_name']}%");
            }

            if (isset($params['status']) && $params['status'] !== "" && $params['status'] != -1) {
                $qb->where("status", $params['status']);
            }

        });

        return Page::pageByParams($qb, $params);
    }

    /**
     * 批量删除角色
     * @param array $ids
     * @return bool|mixed
     */
    public function mulDel(array $ids)
    {
        try {
            Db::beginTransaction();

            // 删除角色
            Role::destroy($ids);

            // 删除角色、员工关联
            $adminRoleIds = AdminRole::query()->whereIn("role_id", $ids)->get("id")->pluck("id")->toArray();
            if (!empty($adminRoleIds)) {
                AdminRole::destroy($adminRoleIds);
            }

            // 删除角色、权限关联
            $rolePermissionIds = RolePermission::query()->whereIn("role_id", $ids)->get("id")->pluck("id")->toArray();
            if (!empty($rolePermissionIds)) {
                RolePermission::destroy($rolePermissionIds);
            }

            $this->eventDispatcher->dispatch(new ResetRolePermissionEvent($ids, 'del'));

            Db::commit();

            return true;

        } catch (\Throwable $e) {
            Db::rollBack();

            throw new BusinessException($e->getCode(), $e->getMessage());
        }
    }

    /**
     * 更新角色
     * @param array $params
     * @return bool|mixed
     */
    public function update(array $params)
    {
        $id = $params['id'];
        unset($params['id']);

        if (empty($params)) {
            throw new BusinessException(ResponseCode::PARAMS_ERROR);
        }

        if (!empty($params['role_name'])) {
            if (Role::existsUpdateData("role_name", $params['role_name'], $id)) {
                throw new BusinessException(ResponseCode::NAME_EXISTS);
            }
        }

        try {

            Db::beginTransaction();

            if (isset($params['status'])) {
                $status = Role::query()->where("id", $id)->value('status');
            }

            Role::query()->where("id", $id)->update($params);

            if (isset($status) && $status != $params['status']) {
                if ($params['status'] == 0) {
                    $this->eventDispatcher->dispatch(new ResetRolePermissionEvent([$id], 'del'));
                } else {
                    $this->eventDispatcher->dispatch(new ResetRolePermissionEvent([$id]));
                }
            }

            Db::commit();

            return true;
        } catch (\Throwable $e) {
            Db::rollBack();
            throw new BusinessException($e->getCode(), $e->getMessage());
        }

    }

    /**
     * 新增角色
     * @param array $params
     * @return mixed|void
     */
    public function insert(array $params)
    {
        if (Role::existsInsertData("role_name", $params['role_name'])) {
            throw new BusinessException(ResponseCode::NAME_EXISTS);
        }

        Role::query()->insert($params);

        return true;

    }

    /**
     * 获取所有角色并标识该角色与对应员工是否有关联
     * @param $adminId
     * @return array|Throws
     */
    public function getRolesWithAdmin(int $adminId)
    {
        $roleData = Role::all(["id","role_name","status"])->toArray();

        if (empty($roleData)) {
            return [];
        }

        $roleIds = array_column($roleData, "id");

        $roleIds = AdminRole::query()->whereIn("role_id", $roleIds)->where("admin_id", $adminId)->get(["role_id"])->pluck("role_id")->toArray();

        foreach ($roleData as &$v) {
            if (in_array($v['id'], $roleIds)) {
                $v['checked'] = true;
            } else {
                $v['checked'] = false;
            }
        }

        return $roleData;
    }

    /**
     * 保存角色权限关系
     * @param array $params
     * @return mixed|void
     */
    public function savePermissions(array $params)
    {
        try {
            Db::beginTransaction();

            $ids = RolePermission::query()->where("role_id", $params['id'])->get("id")->pluck("id")->toArray();

            if (!empty($ids)) {
                RolePermission::destroy($ids);
            }

            $insertData = [];
            foreach ($params['permissions'] as $permissionId) {
                $insertData []= [
                    'role_id' => $params['id'],
                    'permission_id' => $permissionId
                ];
            }

            RolePermission::query()->insert($insertData);

            $this->eventDispatcher->dispatch(new ResetRolePermissionEvent([$params['id']]));

            Db::commit();

            return true;

        } catch (\Throwable $e) {
            Db::rollBack();
            throw new BusinessException($e->getCode(), $e->getMessage());
        }

    }
}