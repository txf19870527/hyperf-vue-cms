<?php

namespace App\Service;


use App\Com\Page;
use App\Com\ResponseCode;
use App\Event\ResetRolePermissionEvent;
use App\Exception\BusinessException;
use App\Model\Permission;
use App\Model\RolePermission;
use App\Service\Interfaces\PermissionServiceInterface;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Redis\RedisFactory;
use Hyperf\RpcServer\Annotation\RpcService;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * Class PermissionService
 * @package App\Service
 * @RpcService(name="PermissionService",protocol="jsonrpc",server="jsonrpc")
 */
class PermissionService implements PermissionServiceInterface
{
    /**
     * @Inject()
     * @var RedisFactory
     */
    private $redisFactory;

    /**
     * @Inject()
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * 获取权限下拉数据（默认返回一级、二级）
     * @param int $depth
     * @return array|mixed
     */
    public function getPermissionCascader($depth = 2, $status = -1)
    {
        $type = range(1, $depth);

        if (in_array($status, ['0', '1'])) {
            $qb = Permission::query()->where("status", $status);
        } else {
            $qb = Permission::query();
        }

        $res = $qb->whereIn("type", $type)->orderBy("sort", "desc")
            ->get(['id','pid','type','icon','title','index']);

        if (empty($res)) {
            return [];
        }

        $res = list_to_tree($res->toArray());

        return $res;

    }

    /**
     * 获取菜单权限树
     * @param int $userId
     * @param array $roles
     * @return mixed
     */
    public function getPermissionTree($userId, $roles)
    {
        if (in_array($userId, config("admin.super_admin_id"))) {

            $res = Permission::query()->where("status", 1)->whereIn("type", [1,2,3])->orderBy("sort", "desc")
                    ->get(['id','pid','type','icon','title','index']);
        } else {

            $permissionIds = RolePermission::query()->whereIn("role_id", $roles)->get(["permission_id"]);

            if (empty($permissionIds)) {
                return [];
            }

            $res = Permission::query()->whereIn("id", array_column($permissionIds->toArray(), "permission_id"))
                    ->where("status", 1)->whereIn("type", [1,2,3])->orderBy("sort", "desc")->get(['id','pid','type','icon','title','index']);
        }

        if (empty($res)) {
            return [];
        }

        $res = list_to_tree($res->toArray());

        return $res;
    }

    /**
     * 获取配置了前端路由的权限信息
     * @return array
     */
    public function getPermissionFromIndex()
    {
        $data = Permission::query()->where('status', 1)->whereIn('type', [1,2,3])->where('index', '!=', '')->get(["index","title","icon"])->toArray();
        return array_reset_key($data, 'index');
    }

    /**
     * 权限列表
     */
    public function lists($params)
    {
        $qb = Permission::query();

        $qb = $qb->where(function ($qb) use ($params) {
            if (!empty($params['pid'])) {
                $qb->where("pid", $params['pid']);
            }

            if (!empty($params['title'])) {
                $qb->where("title", "like", "%{$params['title']}%");
            }

            if (isset($params['status']) && $params['status'] !== "" && $params['status'] != -1) {
                $qb->where("status", $params['status']);
            }
        });


        return Page::pageByParams($qb, $params);

    }

    /**
     * 新增权限
     */
    public function insert($params)
    {
        if (Permission::existsInsertData("title", $params['title'])) {
            throw new BusinessException(ResponseCode::NAME_EXISTS);
        }

        Permission::query()->insert($params);

        return true;

    }

    /**
     * 修改权限
     */
    public function update($params)
    {
        $id = $params['id'];
        unset($params['id']);

        if (empty($params)) {
            throw new BusinessException(ResponseCode::PARAMS_ERROR);
        }

        $model = Permission::query()->find($id);

        if (empty($model)) {
            throw new BusinessException(ResponseCode::PARAMS_ERROR);
        }

        // 系统权限，限制更新
        if (in_array($id, config("admin.super_permission_id"))) {

            if (
                (isset($params['status']) && $params['status'] == 0)
            ) {
                throw new BusinessException(ResponseCode::SYSTEM_PERMISSION);
            }

        }

        if (!empty($params['title'])) {
            if (Permission::existsUpdateData("title", $params['title'], $id)) {
                throw new BusinessException(ResponseCode::NAME_EXISTS);
            }
        }

        try {

            Db::beginTransaction();

            Permission::query()->where("id", $id)->update($params);

            if (isset($params['status']) && $params['status'] != $model->status) {
                $roles = RolePermission::query()->where("permission_id", $id)->get("role_id")->pluck("role_id")->toArray();

                if (!empty($roles)) {
                    $this->eventDispatcher->dispatch(new ResetRolePermissionEvent($roles));
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
     * 批量删除
     */
    public function batchDelete($ids)
    {
        try {
            Db::beginTransaction();

            Permission::destroy($ids);

            $rolePermission = RolePermission::query()->whereIn("permission_id", $ids)->get(["id","role_id"])->toArray();

            if (!empty($rolePermission)) {
                $ids = array_column($rolePermission, "id");
                RolePermission::destroy($ids);

                $ids = array_column($rolePermission, "role_id");
                $this->eventDispatcher->dispatch(new ResetRolePermissionEvent($ids));

            }

            Db::commit();
            return true;
        } catch (\Throwable $e) {
            Db::rollBack();
            throw new BusinessException($e->getCode(), $e->getMessage());
        }

    }

    /**
     * 获取所有权限以及对应角色的关联情况
     * @param int $roleId
     * @return mixed
     */
    public function getPermissionsWithRole(int $roleId)
    {
        $returnData = [
            'data' => [],
            'default_checked_keys' => []        // 默认选中的id（当前$roleId关联的）
        ];

        $data = Permission::query()->with("roles")->orderBy("sort", "desc")->get(["id","pid","type","title","status"])->toArray();

        if (empty($data)) {
            return $returnData;
        }

        $pids = array_unique(array_column($data, "pid"));

        foreach ($data as $k => $v) {

            if (!$v['status']) {
                $data[$k]['title'] = "{$v['title']} [禁用]";
            }

            if (!empty($v['roles'])) {
                $v['roles'] = array_column($v['roles'], 'id');
                if (in_array($roleId, $v['roles']) && !in_array($v['id'], $pids)) {
                    $returnData['default_checked_keys'] []= $v['id'];
                }
            }

            unset($data[$k]['roles']);
        }

        $returnData['data'] = list_to_tree($data);


        return $returnData;
    }

}