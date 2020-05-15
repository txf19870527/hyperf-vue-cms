<?php


namespace App\Service\Va;


use App\Com\RedisKeyMap;
use App\Model\User;
use App\Service\Interfaces\Va\UserServiceInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Redis\RedisFactory;
use Hyperf\RpcServer\Annotation\RpcService;

/**
 * Class UserService
 * @package App\Service\Va
 * @RpcService(name="UserServiceVa", protocol="jsonrpc", server="jsonrpc")
 */
class UserService implements UserServiceInterface
{
    /**
     * @var RedisFactory
     * @Inject()
     */
    private $redisFactory;

    public function login(array $params)
    {
        $data = User::query()->where("open_id", $params['openid'])->first();

        if (empty($data)) {
            $data = [
                'open_id' => $params['openid']
            ];
            User::query()->insert($data);
        } else {
            $data = $data->toArray();
        }

        return $this->setToken($params, $data);
    }

    private function setToken($params, $data)
    {
        $redis = $this->redisFactory->get("default");

        $tokenCacheKey = RedisKeyMap::build(RedisKeyMap::API_TOKEN_CACHE, [$data['open_id']]);

        $originTokenKey = $redis->get($tokenCacheKey);
        $redis->del($originTokenKey);

        $tokenKey = RedisKeyMap::build(RedisKeyMap::API_TOKEN, [$params['token']]);

        $data['session_key'] = $params['session_key'];
        $redis->hMset($tokenKey, $data);

        $redis->set($tokenCacheKey, $tokenKey);

        return $params['token'];
    }

}