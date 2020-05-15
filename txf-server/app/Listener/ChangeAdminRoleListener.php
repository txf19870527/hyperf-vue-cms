<?php

declare(strict_types=1);

namespace App\Listener;

use App\Com\RedisKeyMap;
use App\Event\ChangeAdminRoleEvent;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Redis\RedisFactory;
use App\Com\Json;
use Psr\Container\ContainerInterface;
use Hyperf\Event\Contract\ListenerInterface;

/**
 * @Listener
 */
class ChangeAdminRoleListener implements ListenerInterface
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
            ChangeAdminRoleEvent::class
        ];
    }

    /**
     * @param ChangeAdminRoleEvent $event
     */
    public function process(object $event)
    {
        if ($event->adminRoles) {
            $redis = $this->container->get(RedisFactory::class)->get("default");
            foreach ($event->adminRoles as $adminId => $roles) {
                $redisKey = RedisKeyMap::build(RedisKeyMap::TOKEN_CACHE, [$adminId]);
                $token = $redis->get($redisKey);
                if (!empty($token)) {
                    $redisKey = RedisKeyMap::build(RedisKeyMap::TOKEN, [$token]);
                    $tokenData = $redis->get($redisKey);
                    if (!empty($tokenData)) {
                        $tokenData = Json::decode($tokenData);
                        if (!empty($tokenData)) {
                            $tokenData['roles'] = $roles;
                            $redis->set($redisKey, Json::encode($tokenData));
                        }
                    }
                }
            }
        }

        return true;
    }
}
