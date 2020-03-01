<?php


namespace App\Aspect;


use Hyperf\Di\Annotation\Aspect;
use Hyperf\Di\Aop\AbstractAspect;
use Hyperf\Di\Aop\ProceedingJoinPoint;
use Hyperf\RpcClient\ServiceClient;
use Hyperf\Utils\Context;

/**
 * Class RpcClientAspect
 * @package App\Aspect
 * @Aspect()
 */
class RpcClientAspect extends AbstractAspect
{

    public $classes = [
        ServiceClient::class . '::' . '__call',
    ];

    public function process(ProceedingJoinPoint $proceedingJoinPoint)
    {
        // 切入一个请求ID，便于 追踪 rpc_client的日志 和 rpc_server的日志
        $requestId = Context::get('request_uuid');
        $proceedingJoinPoint->arguments['keys']['params']['request_uuid'] = $requestId;
        return $proceedingJoinPoint->process();
    }



}