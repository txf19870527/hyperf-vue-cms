<?php

declare(strict_types=1);

namespace App\Listener;

use App\Com\RedisKeyMap;
use App\Event\ForbiddenAdminEvent;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Redis\RedisFactory;
use phpDocumentor\Reflection\DocBlock\Tags\Throws;
use Psr\Container\ContainerInterface;
use Hyperf\Event\Contract\ListenerInterface;

/**
 * @Listener
 */
class ForbiddenAdminListener implements ListenerInterface
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
            ForbiddenAdminEvent::class
        ];
    }

    /**
     * @param ForbiddenAdminEvent $event
     * @return bool|Throws
     */
    public function process($event)
    {
        if ($event->adminIds) {
            $redis = $this->container->get(RedisFactory::class)->get("default");

            foreach ($event->adminIds as $adminId) {
                $redisKey = RedisKeyMap::build(RedisKeyMap::TOKEN_CACHE, [$adminId]);
                $token = $redis->get($redisKey);
                $redis->del($redisKey);
                if (!empty($token)) {
                    $redisKey = RedisKeyMap::build(RedisKeyMap::TOKEN, [$token]);
                    $redis->del($redisKey);
                }
            }
        }

        return true;

    }
}
