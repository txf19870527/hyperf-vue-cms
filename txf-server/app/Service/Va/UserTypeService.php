<?php


namespace App\Service\Va;


use App\Com\RedisKeyMap;
use App\Com\ResponseCode;
use App\Exception\BusinessException;
use App\Model\Type;
use App\Model\User;
use App\Model\UserType;
use App\Service\Interfaces\Va\UserTypeServiceInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Redis\RedisFactory;
use Hyperf\RpcServer\Annotation\RpcService;
use Hyperf\Utils\Codec\Json;

/**
 * Class UserTypeService
 * @package App\Service\Va
 * @RpcService(name="UserTypeServiceVa", protocol="jsonrpc", server="jsonrpc")
 */
class UserTypeService implements UserTypeServiceInterface
{

    /**
     * @Inject()
     * @var RedisFactory
     */
    private $redisFactory;

    /**
     * 获取用户自定义收入类型
     * @param int $userId
     * @return mixed|void
     */
    public function getIncome(int $userId)
    {
        $redis = $this->redisFactory->get('default');

        $redisKey = RedisKeyMap::build(RedisKeyMap::TYPE_INCOME, [$userId]);

        if ($redis->exists($redisKey)) {
            return json_decode_with_out_error($redis->get($redisKey));
        }

        $userTypeData = UserType::query()->where("user_id", $userId)->where("type", 2)->orderBy("id", "asc")->get()->toArray();

        if (empty($userTypeData)) {
            return [];
        }

        $redis->set($redisKey, Json::encode($userTypeData));

        return $userTypeData;

    }

    /**
     * 获取用户自定义支出类型
     * @param int $userId
     * @return mixed|void
     */
    public function getExpense(int $userId)
    {
        $redis = $this->redisFactory->get('default');

        $redisKey = RedisKeyMap::build(RedisKeyMap::TYPE_EXPENSE, [$userId]);

        if ($redis->exists($redisKey)) {
            return json_decode_with_out_error($redis->get($redisKey));
        }

        $userTypeData = UserType::query()->where("user_id", $userId)->where("type", 1)->orderBy("id", "asc")->get()->toArray();

        if (empty($userTypeData)) {
            return [];
        }

        $redis->set($redisKey, Json::encode($userTypeData));

        return $userTypeData;
    }

    /**
     * 更新
     * @param array $params
     * @return mixed|void
     */
    public function update(array $params)
    {
        $id = $params['id'];
        $userId = $params['user_id'];
        unset($params['id']);
        unset($params['user_id']);

        if (empty($params)) {
            throw new BusinessException(ResponseCode::PARAMS_ERROR);
        }

        $userTypeModel = UserType::query()->where("id", $id)->where("user_id", $userId)->first();

        if (empty($userTypeModel)) {
            throw new BusinessException(ResponseCode::DATA_NOT_EXISTS);
        }

        // 类型模板表是否存在该名称
        if (Type::existsInsertData("title", $params['title'])) {
            throw new BusinessException(ResponseCode::TYPE_TITLE_EXISTS);
        }

        // 当前用户自定义类型是否存在该名称
        if (UserType::existsUpdateDataByArray(['title' => $params['title'], 'type' => $userTypeModel['type'], 'user_id' => $userId], $id)) {
            throw new BusinessException(ResponseCode::TYPE_TITLE_EXISTS);
        }

        $userTypeModel->update($params);

        $this->cache($userTypeModel->type, $userId);

        return true;

    }

    /**
     * 新增
     * @param array $params
     * @return mixed|void
     */
    public function insert(array $params)
    {
        // 类型模板表是否存在该名称
        if (Type::existsInsertData("title", $params['title'])) {
            throw new BusinessException(ResponseCode::TYPE_TITLE_EXISTS);
        }

        if (UserType::existsInsertDataByArray(['title' => $params['title'], 'type' => $params['type'], 'user_id' => $params['user_id']])) {
            throw new BusinessException(ResponseCode::TYPE_TITLE_EXISTS);
        }

        UserType::query()->insert($params);

        $this->cache($params['type'], $params['user_id']);

        return true;
    }

    public function delete($id, $userId)
    {

        $data = UserType::query()->where('id', $id)->where('user_id', $userId)->first();

        if (empty($data)) {
            throw new BusinessException(ResponseCode::PARAMS_ERROR);
        }

        $data = $data->toArray();

        UserType::destroy($id);

        $this->cache($data['type'], $userId);

        return true;

    }

    /**
     * 缓存用户自定义类型
     * @param $type
     * @param $userId
     * @return bool
     */
    protected function cache($type, $userId)
    {
        if ($type == 1) {
            $redisKey = RedisKeyMap::build(RedisKeyMap::TYPE_EXPENSE, [$userId]);
        } else {
            $redisKey = RedisKeyMap::build(RedisKeyMap::TYPE_INCOME, [$userId]);
        }

        $data = UserType::query()->where("user_id", $userId)->where("type", $type)->get()->toArray();

        if (empty($data)) {
            return true;
        }

        $redis = $this->redisFactory->get("default");

        $redis->set($redisKey, Json::encode($data));

        return true;

    }

}