<?php


namespace App\Controller;


use App\Service\Interfaces\RoleServiceInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;

/**
 * 角色管理相关接口
 * Class RoleController
 * @package App\Controller
 * @AutoController()
 */
class RoleController extends AbstractController
{
    protected $useDefault = true;

    /**
     * @Inject()
     * @var RoleServiceInterface
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