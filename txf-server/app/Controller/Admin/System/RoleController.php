<?php


namespace App\Controller\Admin\System;

use App\Controller\Admin\AbstractController;
use App\Services\RoleService;
use Hyperf\Di\Annotation\Inject;

/**
 * 角色管理相关接口
 * Class RoleController
 * @package App\Controller
 */
class RoleController extends AbstractController
{
    protected $useDefault = true;

    /**
     * @Inject()
     * @var RoleService
     */
    protected $service;


    /**
     * 获取员工绑定的角色信息
     * @return mixed
     */
    public function getRolesWithAdmin()
    {
        $body = $this->request->getAttribute("body");

        return $this->service->getRolesWithAdmin($body['admin_id']);
    }

    /**
     * 保存角色权限关系
     */
    public function savePermissions()
    {
        $body = $this->request->getAttribute("body");
        return $this->service->savePermissions($body);
    }
}