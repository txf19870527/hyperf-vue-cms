<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Com\Log;
use Hyperf\Utils\ApplicationContext;
use Hyperf\Utils\Context;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class InitMiddleware implements MiddlewareInterface
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
        // 初始化请求信息
        $request = $this->initRequest($request);

        $response = $handler->handle($request);

        // 记录请求日志
        $this->requestLog($request, $response);

        return $response;

    }

    protected function initRequest(ServerRequestInterface $request): ServerRequestInterface
    {

        $beginTime = microtime(true);
        $requestId = $request->getParsedBody()['request_uuid'];

        Context::set('request_uuid', $requestId);

        Context::set($requestId, [
            'request_data' => $request->getParsedBody(),
            'begin_time' => $beginTime,
            'request_time' => date_time_now(),
            'uri' => $request->getUri()->getPath(),
        ]);

        return $request;
    }

    protected function requestLog(ServerRequestInterface $request, ResponseInterface $response)
    {
        $requestId = (string)Context::get('request_uuid');

        // 是否记录请求日志开关
        if (!config('request_log') || empty($requestId)) {
            return;
        }

        $requestData = Context::get($requestId);

        $beginTime = $requestData['begin_time'];
        $endTime = microtime(true);

        $packer = ApplicationContext::getContainer()->get(\Hyperf\JsonRpc\Packer\JsonLengthPacker::class);

        $logResponse = config("request_log_response");

        if ($logResponse || env("APP_ENV") != 'prod') {
            $body = $packer->unpack($response->getBody()->getContents());
        } else {
            $body = '';
        }

        Log::info([
            'request_uuid' => $requestId,
            'request_uri' => $requestData['uri'],
            'request_time' => $requestData['request_time'],
            'request_data' => $requestData['request_data'],
            'response_data' => $body,
            'use_time' => number_format(($endTime - $beginTime), 5),
            'append_data' => Log::destroyAppend(),
        ], 'request_log');
    }
}