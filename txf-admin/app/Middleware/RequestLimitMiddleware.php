<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Com\RedisKeyMap;
use App\Com\ResponseCode;
use App\Exception\BusinessException;
use Hyperf\Redis\RedisFactory;
use Hyperf\Utils\Codec\Json;
use Hyperf\Utils\Context;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RequestLimitMiddleware implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->requestLimit();
        return $handler->handle($request);
    }

    protected function requestLimit()
    {
        $requestId = (string)Context::get('request_uuid');

        if (empty($requestId)) {
            return false;
        }

        $requestData = Context::get($requestId);

        $uri = strtolower($requestData['uri']);

        $requestLimit = config("requestlimit.{$uri}");

        if (empty($requestLimit)) {
            return false;
        }

        $time = $requestLimit['time'] ?? 3;
        $token = !empty($requestLimit['use_token']) ? $requestData['token'] : '_notoken_';
        $bodyKeys = [];
        if (!empty($requestLimit['body_keys']) && is_array($requestLimit['body_keys'])) {
            foreach ($requestData['request_data'] as $k => $v) {
                if (in_array($k, $requestLimit['body_keys'])) {
                    $bodyKeys []= $v;
                }
            }
            $bodyKeys = md5(Json::encode($bodyKeys));
        } elseif (isset($requestLimit['body_keys']) && $requestLimit['body_keys'] === false) {
            $bodyKeys = '_nokey_';
        }

        $redisKey = RedisKeyMap::build(RedisKeyMap::REQUEST_LIMIT, [$token, $bodyKeys]);

        $redis = $this->container->get(RedisFactory::class)->get("default");

        if (!$redis->set($redisKey, 1, ['EX' => $time, 'NX'])) {
            throw new BusinessException(ResponseCode::REQUEST_LIMIT);
        }

        return true;
    }
}