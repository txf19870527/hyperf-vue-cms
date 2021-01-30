<?php


namespace App\Controller\Admin\System;

use App\Controller\Admin\AbstractController;
use App\Services\PermissionService;
use Hyperf\Di\Annotation\Inject;

/**
 * 权限管理相关接口
 * Class PermissionController
 * @package App\Controller
 */
class PermissionController extends AbstractController
{

    protected $useDefault = true;

    /**
     * @var PermissionService
     * @Inject()
     */
    protected $service;

    /**
     * 获取权限下拉列表
     */
    public function getPermissionDropDown()
    {
        $body = $this->request->getAttribute("body");
        $depth = $body['depth'] ?? 2;
        $status = $body['status'] ?? -1;

        return $this->service->getPermissionDropDown($depth, $status);
    }

    /**
     * 获取角色绑定的权限信息
     */
    public function getPermissionsWithRole()
    {
        $body = $this->request->getAttribute("body");
        return $this->service->getPermissionsWithRole($body['role_id']);
    }
}