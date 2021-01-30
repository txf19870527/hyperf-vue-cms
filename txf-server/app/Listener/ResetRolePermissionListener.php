<?php

declare(strict_types=1);

namespace App\Listener;

use App\Com\RedisKeyMap;
use App\Event\ResetRolePermissionEvent;
use App\Model\Role;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Redis\RedisFactory;
use Psr\Container\ContainerInterface;
use Hyperf\Event\Contract\ListenerInterface;

/**
 * @Listener
 */
class ResetRolePermissionListener implements ListenerInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function listen(): array
    {
        return [
            ResetRolePermissionEvent::class
        ];
    }

    /**
     * @param ResetRolePermissionEvent $event
     * @return bool
     */
    public function process($event)
    {

        $redis = $this->container->get(RedisFactory::class)->get("default");

        // 清除缓存
        foreach ($event->roles as $roleId) {
            $redisKey = RedisKeyMap::build(RedisKeyMap::RULE_PERMISSION_CACHE, [$roleId]);
            $redis->del($redisKey);
        }

        if ($event->operate != 'reset') {
            return true;
        }

        $roles = Role::query()->whereIn("id", $event->roles)->where("status", 1)->with("permissions")->get(["id"])->toArray();

        if (empty($roles)) {
            return true;
        }

        foreach ($roles as $v) {
            $redisKey = RedisKeyMap::build(RedisKeyMap::RULE_PERMISSION_CACHE, [$v['id']]);

            $redisData = [];
            foreach ($v['permissions'] as $permission) {
                if ($permission['status'] == 1 && !empty($permission['uri'])) {
                    $redisData[$permission['id']] = $permission['uri'];
                }
            }

            if (!empty($redisData)) {
                $redis->hMset($redisKey, $redisData);
            }

        }

        return true;


    }
}
