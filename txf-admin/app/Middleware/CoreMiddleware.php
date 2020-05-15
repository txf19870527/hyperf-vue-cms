<?php
declare(strict_types=1);

namespace App\Middleware;

use App\Com\ResponseCode;
use Hyperf\HttpMessage\Stream\SwooleStream;
use App\Com\Json;
use Hyperf\Utils\Contracts\Arrayable;
use Hyperf\Utils\Contracts\Jsonable;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CoreMiddleware extends \Hyperf\HttpServer\CoreMiddleware
{

    /**
     * @param array|Arrayable|Jsonable|string $response
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    protected function transferToResponse($response, ServerRequestInterface $request): ResponseInterface
    {
        return $this->response()
            ->withAddedHeader('content-type', 'application/json')
            ->withBody(new SwooleStream(Json::encode(ResponseCode::response($response))));

    }
}
