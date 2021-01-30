<?php


namespace App\Controller\Admin\System;


use App\Controller\Admin\AbstractController;
use App\Services\AdminService;
use Hyperf\Di\Annotation\Inject;

/**
 * 后台员工相关接口
 * Class AdminController
 * @package App\Controller\Admin
 */
class AdminController extends AbstractController
{

    protected $useDefault = true;

    /**
     * @var AdminService
     * @Inject()
     */
    protected $service;

    public function saveRoles()
    {
        $body = $this->request->getAttribute("body");
        return $this->service->saveRoles($body);
    }

    public function updatePassword()
    {
        $body = $this->request->getAttribute('body');
        $userData = $this->request->getAttribute('user_data');
        return $this->service->updatePassword($body, $userData['id']);
    }
}