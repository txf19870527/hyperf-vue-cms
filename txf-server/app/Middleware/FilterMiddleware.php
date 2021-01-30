<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class FilterMiddleware implements MiddlewareInterface
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
        $request = $this->filter($request);

        return $handler->handle($request);
    }

    protected function filter(ServerRequestInterface $request): ServerRequestInterface
    {

        $uri = strtolower($request->getUri()->getPath());
        $filter = config("filter.{$uri}");

        $white = $filter['white'] ?? [];
        $black = $filter['black'] ?? [];

        $body = $request->getAttribute("body");

        // 分页参数不在validation中配置了，特殊处理下，目前固定只能 page row
        if (isset($body['page'])) {
            $body['page'] = (int)$body['page'];
        }
        if (isset($body['row'])) {
            $body['row'] = (int)$body['row'];
        }

        // 设置了白名单或黑名单才走过滤逻辑
        if (!empty($white) || !empty($black)) {

            // 只保留白名单的key
            if (!empty($body) && !empty($white)) {
                // 分页参数不在filter中配置了，特殊处理下，目前固定只能 page row
                if (isset($body['page'])) {
                    $white[] = 'page';
                }
                if (isset($body['row'])) {
                    $white[] = 'row';
                }
                $body = array_only($body, $white);
            }

            // 过滤掉黑名单的key，黑名单优先级高于白名单
            if (!empty($body) && !empty($black)) {
                $body = array_forget($body, $black);
            }

            $request = $request->withAttribute("body", $body);

        }

        return $request;

    }
}