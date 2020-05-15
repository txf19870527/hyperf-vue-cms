<?php


namespace App\Service\Va;


use App\Com\RedisKeyMap;
use App\Model\Type;
use App\Service\Interfaces\Va\TypeServiceInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Redis\RedisFactory;
use Hyperf\RpcServer\Annotation\RpcService;
use Hyperf\Utils\Codec\Json;

/**
 * Class TypeService
 * @package App\Service\Va
 * @RpcService(name="TypeServiceVa", protocol="jsonrpc", server="jsonrpc")
 */
class TypeService implements TypeServiceInterface
{
    /**
     * @Inject()
     * @var RedisFactory
     */
    private $redisFactory;


    /**
     * 获取收入类型
     * @return mixed
     */
    public function getIncome()
    {
        $redisKey = RedisKeyMap::build(RedisKeyMap::TYPE_INCOME);
        $redis = $this->redisFactory->get("default");
        if ($redis->exists($redisKey)) {
            return json_decode_with_out_error($redis->get($redisKey));
        }

        $typeData = Type::query()->where("type", 2)->orderBy("sort", "desc")->orderBy("id", "asc")->get()->toArray();

        if (empty($typeData)) {
            return [];
        }

        $redis->set($redisKey, Json::encode($typeData));

        return $typeData;

    }

    /**
     * 获取支出类型
     * @return mixed
     */
    public function getExpense()
    {
        $redisKey = RedisKeyMap::build(RedisKeyMap::TYPE_EXPENSE);
        $redis = $this->redisFactory->get("default");
        if ($redis->exists($redisKey)) {
            return json_decode_with_out_error($redis->get($redisKey));
        }

        $typeData = Type::query()->where("type", 1)->orderBy("sort", "desc")->orderBy("id", "asc")->get()->toArray();

        if (empty($typeData)) {
            return [];
        }

        $redis->set($redisKey, Json::encode($typeData));

        return $typeData;
    }

    /**
     * 获取所有类型
     * @param $userId
     * @return mixed|void
     */
    public function getAllType($userId)
    {
        $income = $this->getIncome();
        $expense = $this->getExpense();

    }
}