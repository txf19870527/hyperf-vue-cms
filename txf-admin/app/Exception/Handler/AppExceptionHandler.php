<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

namespace App\Exception\Handler;

use App\Com\Json;
use App\Com\Log;
use App\Com\ResponseCode;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Utils\Context;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class AppExceptionHandler extends ExceptionHandler
{
    /**
     * @var StdoutLoggerInterface
     */
    protected $logger;

    public function __construct(StdoutLoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        $data = Json::encode(ResponseCode::error($throwable->getCode(), $throwable->getMessage()));

        $this->requestLog($throwable);

        return $response->withAddedHeader('content-type', 'application/json; charset=utf-8')->withBody(new SwooleStream($data));

    }

    public function isValid(Throwable $throwable): bool
    {
        return true;
    }

    protected function requestLog(Throwable $throwable)
    {
        $requestId = (string)Context::get('request_uuid');

        // 是否记录请求错误日志开关
        if (!config('request_error_log') || empty($requestId)) {
            return;
        }

        $requestData = Context::get($requestId);

        $beginTime = $requestData['begin_time'];
        $endTime = microtime(true);

        Log::error([
            'request_uuid' => $requestId,
            'request_uri' => $requestData['uri'],
            'request_time' => $requestData['request_time'],
            'request_data' => $requestData['request_data'],
            'response_data' => Log::parseException($throwable),
            'use_time' => number_format(($endTime - $beginTime), 5),
            'append_data' => Log::destroyAppend()
        ], 'error_log');

    }

}
