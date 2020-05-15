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

use App\Com\Log;
use App\Com\ResponseCode;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Utils\ApplicationContext;
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
        try {

            $packer = ApplicationContext::getContainer()->get(\Hyperf\JsonRpc\Packer\JsonLengthPacker::class);

            $body = $packer->unpack($response->getBody()->getContents());

            // 使用 ResponseCode 重新获取一遍错误信息。在 ResponseCode 获取不到的消息会赋值为 UNKNOWN_ERROR（防止一些敏感消息返回出去）
            // 如果 code 正好撞上的话 会覆盖为 ResponseCode 的错误消息，需要调整下 code 的值
            // 如果 code 为0（正确的code）用 UNKNOWN_ERROR 覆盖掉
            $errorInfo = ResponseCode::error($throwable->getCode(), $throwable->getMessage());

            $body['error']['code'] = $errorInfo['code'];
            $body['error']['message'] = $errorInfo['message'];
            $body['error']['data'] = [];
            $body['context'] = [];

            $this->requestLog($throwable);

            $body = new SwooleStream($packer->pack($body));

            return $response->withAddedHeader('content-type', 'application/json')->withBody($body);

        } catch (\Throwable $e) {

            return $response;

        }


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
            'response_data' => ["code" => $throwable->getCode(), "message" => $throwable->getMessage(), 'file' => $throwable->getFile(), 'line' => $throwable->getLine()],
            'use_time' => number_format(($endTime - $beginTime), 5),
            'append_data' => Log::destroyAppend()
        ], 'error_log');

    }
}
