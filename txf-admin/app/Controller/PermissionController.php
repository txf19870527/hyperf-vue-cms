<?php


namespace App\Controller;

use App\Service\Interfaces\PermissionServiceInterface;
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
     * @Inject()
     * @var PermissionServiceInterface
     */
    protected $service;

    /**
     * 获取权限下拉列表
     */
    public function getPermissionCascader()
    {
        $body = $this->request->getAttribute("body");
        $depth = $body['depth'] ?? 2;
        $status = $body['status'] ?? -1;

        return $this->service->getPermissionCascader($depth, $status);
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