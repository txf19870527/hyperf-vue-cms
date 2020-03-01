<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Com\ResponseCode;
use App\Exception\BusinessException;
use Hyperf\Utils\Context;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CrossOriginMiddleware implements MiddlewareInterface
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

        if (!in_array($request->getMethod(), ['OPTIONS', 'POST'])) {
            throw new BusinessException(ResponseCode::METHOD_ERROR);
        }

        $crossOriginConfig = config("crossorigin");

        $origin = $request->getHeaderLine("origin");
        $response = Context::get(ResponseInterface::class);

        if (in_array($origin, $crossOriginConfig['allow_domain_list'])) {
            $response = $response->withHeader('Access-Control-Allow-Origin', $origin)
                ->withHeader('Access-Control-Allow-Credentials', $crossOriginConfig['allow_credentials'])
                ->withHeader('Access-Control-Allow-Methods', $crossOriginConfig['allow_request_type'])
                ->withHeader('Access-Control-Allow-Headers', $crossOriginConfig['allow_headers']);

            Context::set(ResponseInterface::class, $response);
        }

        if ($request->getMethod() == 'OPTIONS') {
            return $response;
        }

        return $handler->handle($request);
    }
}