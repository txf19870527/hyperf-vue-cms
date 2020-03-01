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

        // 设置了白名单或黑名单才走过滤逻辑
        if (!empty($white) || !empty($black)) {
            $body = $request->getAttribute("body");

            // 只保留白名单的key
            if (!empty($body) && !empty($white)) {
                $body = array_only($body, $white);
            }

            // 过滤掉黑名单的key
            if (!empty($body) && !empty($black)) {
                $body = array_forget($body, $black);
            }

            $request = $request->withAttribute("body", $body);

        }

        return $request;

    }
}