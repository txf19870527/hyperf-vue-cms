<?php

declare(strict_types=1);

namespace App\Controller;

use App\Com\ResponseCode;
use App\Exception\BusinessException;
use App\Service\Interfaces\AbstractInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;

use Psr\Container\ContainerInterface;

abstract class AbstractController
{
    /**
     * @Inject
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @Inject
     * @var RequestInterface
     */
    protected $request;

    /**
     * @Inject
     * @var ResponseInterface
     */
    protected $response;
    
    /**
     * 子类中打开 $useDefault 开关 并且注入 $service 就能使用通用 增删改查的 服务
     * @var bool
     */
    protected $useDefault = false;

    /**
     * 由子类来注入一个具有通用 增删改查的 服务
     * @var AbstractInterface
     */
    protected $service;

    /**
     * 列表通用方法
     */
    public function lists()
    {
        if (!$this->useDefault && empty($this->service)) {
            throw new BusinessException(ResponseCode::UNKNOWN_ERROR);
        }

        $body = $this->request->getAttribute('body');

        return $this->service->lists($body);
    }

    /**
     * 删除/批量通用方法
     */
    public function mulDel()
    {
        if (!$this->useDefault && empty($this->service)) {
            throw new BusinessException(ResponseCode::UNKNOWN_ERROR);
        }

        $body = $this->request->getAttribute('body');
        return $this->service->mulDel($body['ids']);
    }

    /**
     * 更新通用方法
     */
    public function update()
    {
        if (!$this->useDefault && empty($this->service)) {
            throw new BusinessException(ResponseCode::UNKNOWN_ERROR);
        }

        $body = $this->request->getAttribute('body');
        return $this->service->update($body);
    }

    /**
     * 新增通用方法
     */
    public function insert()
    {
        if (!$this->useDefault && empty($this->service)) {
            throw new BusinessException(ResponseCode::UNKNOWN_ERROR);
        }

        $body = $this->request->getAttribute('body');

        return $this->service->insert($body);
    }

}
