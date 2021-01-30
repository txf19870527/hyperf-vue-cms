<?php


namespace App\Services;


use App\Com\Page;
use App\Com\RedisKeyMap;
use App\Com\ResponseCode;
use App\Event\ChangeAdminRoleEvent;
use App\Event\ForbiddenAdminEvent;
use App\Exception\BusinessException;
use App\Model\Admin;
use App\Model\AdminRole;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Redis\RedisFactory;
use App\Com\Json;
use Psr\EventDispatcher\EventDispatcherInterface;


class AdminService implements AbstractServiceInterface
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
     * @Inject()
     * @var PermissionService
     */
    private $permissionService;

    /**
     * @param string $mobile
     * @param string $password
     * @return array
     * 登录
     */
    public function login(string $mobile, string $password)
    {

        try {
            $userModel = Admin::query()->where("mobile", $mobile)->first();

            if (empty($userModel)) {
                throw new BusinessException(ResponseCode::USER_NOT_EXISTS);
            }

            $userData = $userModel->toArray();

            $userData['roles'] = $userModel->roles()->where("status", 1)->get()->toArray();

            $userData['roles'] = array_column($userData['roles'], 'id');

            if ($userData['login_error_times'] > config("admin.login_error_times")) {
                throw new BusinessException(ResponseCode::USER_CANNOT_LOGIN);
            }
            
            if ($userData['password'] != $this->buildPassword($password, $userData['salt'])) {
                throw new BusinessException(ResponseCode::LOGIN_ERROR);
            }

            if ($userData['status'] != 1) {
                throw new BusinessException(ResponseCode::USER_STATUS_ERROR);
            }

            $userModel->login_error_times = 0;
            $userModel->last_login_time = date_time_now();
            $userModel->save();

            return [
                'token' => $this->buildTokenData($userData),
                'name' => $userData['name'],
                'menus' => $this->permissionService->getPermissionTree($userData['id'], $userData['roles']),
                'routes' => $this->permissionService->getPermissionFromIndex()
            ];

        } catch (\Throwable $e) {
            if (!empty($userModel)) {
                $userModel->login_error_times = $userModel->login_error_times + 1;
                $userModel->save();
            }
            throw new BusinessException($e->getCode(), $e->getMessage());
        }

    }

    protected function buildTokenData($userData)
    {
        $tokenCacheKey = RedisKeyMap::build(RedisKeyMap::TOKEN_CACHE, [$userData["id"]]);

        $redis = $this->redisFactory->get("default");

        $tokenCache = $redis->get($tokenCacheKey);

        $ex = config("admin.session_time_out");

        if (!empty($tokenCache)) {

            $redisKey = RedisKeyMap::build(RedisKeyMap::TOKEN, [$tokenCache]);

            if ($redis->exists($redisKey)) {
                $redis->expire($redisKey, $ex);
                return $tokenCache;
            }

        }

        $token = $this->salt();

        $userData = array_forget($userData, ['password', 'salt']);

        $redisKey = RedisKeyMap::build(RedisKeyMap::TOKEN, [$token]);

        $userData = Json::encode($userData);

        $redis->set($redisKey, $userData, $ex);

        $redis->set($tokenCacheKey, $token);

        return $token;

    }

    protected function buildPassword(string $originPassword, string $salt): string
    {
        return md5($originPassword . $salt);
    }

    protected function salt(): string
    {
        $rand = mt_rand(1, 99999);
        $time = time();
        $salt = "fdsrvccjaj;" . $rand . "fsaceparewkq" . $time . 'ewpqbfxz.';
        return md5($salt);
    }

    /**
     * 命令行脚本调用，重置超管信息
     * @param $resetData
     * @param $id
     * @return bool
     */
    public function resetSuperAdmin($resetData, $id)
    {
        if (!empty($resetData['mobile'])) {
            if (Admin::existsUpdateData("mobile", $resetData['mobile'], $id)) {
                throw new BusinessException(ResponseCode::MOBILE_EXISTS);
            }
        }

        if (!empty($resetData['password'])) {
            $resetData['salt'] = $this->salt();
            $resetData['password'] = $this->buildPassword($resetData['password'], $resetData['salt']);
        }

        Admin::query()->where('id', $id)->update($resetData);

        return true;
    }

    /**
     * 员工列表
     * @param array $params
     * @return array|mixed
     */
    public function lists(array $params)
    {
        $superAdmin = config("admin.super_admin_id");

        if (!empty($superAdmin)) {
            $qb = Admin::query()->whereNotIn("id", $superAdmin);
        } else {
            $qb = Admin::query();
        }

        $qb = $qb->where(function ($qb) use ($params) {
            if (isset($params['status']) && $params['status'] !== "" && $params['status'] != -1) {
                $qb->where("status", $params['status']);
            }

            if (!empty($params['mobile'])) {
                $qb->where("mobile", $params['mobile']);
            }

            if (!empty($params['name'])) {
                $qb->where("name", 'like', "%{$params['name']}%");
            }

            return $qb;
        });


        $res = Page::pageByParams($qb, $params);

        $res['data'] = array_forget($res['data'], ['password', 'salt']);

        return $res;
    }

    /**
     * 批量删除员工
     * @param array $ids
     * @return mixed|void
     */
    public function batchDelete(array $ids)
    {

        try {
            Db::beginTransaction();

            Admin::destroy($ids);

            $roleIds = AdminRole::query()->whereIn("admin_id", $ids)->get("id")->pluck("id")->toArray();

            if (!empty($roleIds)) {
                // 如果需要监听 deleting，使用模型进行删除（通过qb删除监听不到）
                AdminRole::destroy($roleIds);
            }

            $this->eventDispatcher->dispatch(new ForbiddenAdminEvent($ids));

            Db::commit();

            return true;

        } catch (\Throwable $e) {
            Db::rollBack();

            throw new BusinessException($e->getCode(), $e->getMessage());
        }

    }

    /**
     * 更新员工信息
     * @param array $params
     * @return mixed|void
     */
    public function update(array $params)
    {
        $id = $params['id'];
        unset($params['id']);

        if (empty($params)) {
            throw new BusinessException(ResponseCode::PARAMS_ERROR);
        }

        if (in_array($id, config("admin.super_admin_id"))) {
            throw new BusinessException(ResponseCode::PARAMS_ERROR);
        }

        if (!empty($params['mobile'])) {
            if (Admin::existsUpdateData("mobile", $params['mobile'], $id)) {
                throw new BusinessException(ResponseCode::MOBILE_EXISTS);
            }

        }

        Admin::query()->where('id', $id)->update($params);

        if (isset($params["status"]) && $params['status'] == 0) {
            $this->eventDispatcher->dispatch(new ForbiddenAdminEvent([$id]));
        }

        return true;
    }

    /**
     * 新增员工信息
     * @param array $params
     * @return mixed|void
     */
    public function insert(array $params)
    {
        if (Admin::existsInsertData("mobile", $params['mobile'])) {
            throw new BusinessException(ResponseCode::MOBILE_EXISTS);
        }

        $params['salt'] = $this->salt();
        $params['password'] = $this->buildPassword($params['password'], $params['salt']);

        Admin::query()->insert($params);

        return true;
    }

    /**
     * 保存员工角色关系
     * @param array $params
     * @return mixed|void
     */
    public function saveRoles(array $params)
    {
        try {
            Db::beginTransaction();

            $ids = AdminRole::query()->where("admin_id", $params['id'])->get("id")->pluck("id")->toArray();

            if (!empty($ids)) {
                AdminRole::destroy($ids);
            }

            $insertData = [];
            foreach ($params['roles'] as $roleId) {
                $insertData [] = [
                    'admin_id' => $params['id'],
                    'role_id' => $roleId
                ];
            }

            AdminRole::query()->insert($insertData);

            $this->eventDispatcher->dispatch(new ChangeAdminRoleEvent([$params['id'] => array_column($insertData, 'role_id')]));

            Db::commit();

            return true;

        } catch (\Throwable $e) {
            Db::rollBack();
            throw new BusinessException($e->getCode(), $e->getMessage());
        }

    }

    /**
     * 退出登录
     * @param int $adminId
     * @return bool
     */
    public function loginOut(int $adminId)
    {
        $this->eventDispatcher->dispatch(new ForbiddenAdminEvent([$adminId]));
        return true;
    }

    /**
     * 修改密码
     * @param array $params
     * @param int $id
     * @return mixed|void
     */
    public function updatePassword(array $params, int $id)
    {
        $res = Admin::query()->find($id, ["password", "salt"])->toArray();
        if (empty($res)) {
            throw new BusinessException(ResponseCode::USER_NOT_EXISTS);
        }
        $password = $this->buildPassword($params['old_password'], $res['salt']);


        if ($password != $res['password']) {
            throw new BusinessException(ResponseCode::ORIGIN_PASSWORD_ERROR);
        }

        $salt = $this->salt();

        $updateData = [
            'password' => $this->buildPassword($params['new_password'], $salt),
            'salt' => $salt
        ];
        Admin::query()->where('id', $id)->update($updateData);

        return true;
    }

    public function delete(int $id)
    {

    }

}