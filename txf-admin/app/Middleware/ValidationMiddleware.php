<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Com\ResponseCode;
use App\Exception\BusinessException;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ValidationMiddleware implements MiddlewareInterface
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

        $this->validation($request);

        return $handler->handle($request);
    }

    protected function validation(ServerRequestInterface $request): bool
    {

        $uri = strtolower($request->getUri()->getPath());

        $validation = config("validation.{$uri}");
        $validationMessage = config("validation.{$uri}#message#") ?? [];

        if (!empty($validation)) {
            $validationClass = $this->container->get(ValidatorFactoryInterface::class);

            $validator = $validationClass->make(
                $request->getAttribute("body"),
                $validation,
                $validationMessage
            );

            if ($validator->fails()) {

                $message = $validator->errors()->first();

                throw new BusinessException(ResponseCode::CUSTOM_ERROR, $message);
            }

        }

        return true;
    }
}