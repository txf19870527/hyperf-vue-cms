<?php

declare(strict_types=1);

namespace App\Middleware\Admin;


use App\Com\Json;
use App\Com\Log;
use App\Com\RedisKeyMap;
use App\Com\ResponseCode;
use App\Exception\BusinessException;
use Hyperf\Redis\RedisFactory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthMiddleware implements MiddlewareInterface
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
        // 权限验证
        $request = $this->validate($request);
        return $handler->handle($request);
    }

    protected function validate(ServerRequestInterface $request):ServerRequestInterface
    {
        $token = $request->getAttribute('token');
        $uri = $request->getUri()->getPath();

        if (
            empty($token) &&
            (
                !in_array_UpLow($uri, config("admin.no_check_uri"))
                || in_array_UpLow($uri, config("admin.must_login_uri"))
            )
        ) {
            throw new BusinessException(ResponseCode::LOGIN_TIME_OUT);
        }

        if (empty($token)) {
            return $request;
        }

        $redis = $this->container->get(RedisFactory::class)->get("default");

        $userData = Json::decode($redis->get(RedisKeyMap::build(RedisKeyMap::TOKEN, [$token])));

        // 登录失败
        if (empty($userData['id'])) {

            // 无需登录的接口，传 token 也会触发登录动作，获取登录信息
            // 无需登录的接口，token错误 视为未登录访问
            if (in_array_UpLow($uri, config("admin.no_check_uri")) && !in_array_UpLow($uri, config("admin.must_login_uri"))) {
                return $request;
            } else {
                throw new BusinessException(ResponseCode::LOGIN_TIME_OUT);
            }
        }

        // 把用户ID追加到日志中
        Log::append('user_id', $userData['id']);

        // 设置用户登录信息
        $request = $request->withAttribute("user_data", $userData);


        // 无需权限验证的接口，绕过权限验证
        if ( in_array_UpLow($uri, config("admin.no_check_uri")) ) {
            return $request;
        }

        // 超级管理员，绕过权限验证
        if (in_array($userData['id'], config("admin.super_admin_id"))) {
            return $request;
        }

        // 权限验证
        $this->checkPermission($uri, $userData['roles']);

        return $request;

    }

    protected function checkPermission($uri, $roles)
    {
        if (empty($roles)) {
            throw new BusinessException(ResponseCode::AUTH_ERROR);
        }

        $redis = $this->container->get(RedisFactory::class)->get("default");

        foreach ($roles as $roleId) {
            $redisKey = RedisKeyMap::build(RedisKeyMap::RULE_PERMISSION_CACHE, [$roleId]);
            $data = $redis->hGetAll($redisKey);

            if (!empty($data) && in_array_UpLow($uri, $data)) {
                return true;
            }
        }

        throw new BusinessException(ResponseCode::AUTH_ERROR);


    }

}