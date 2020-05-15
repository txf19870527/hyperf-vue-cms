<?php


namespace App\Controller;

use App\Service\Interfaces\AdminServiceInterface;
use Hyperf\Di\Annotation\Inject;

/**
 * 后台员工相关接口
 * Class AdminController
 * @package App\Controller
 */
class AdminController extends AbstractController
{

    protected $useDefault = true;

    /**
     * $service自带默认的增删改查
     * @Inject()
     * @var AdminServiceInterface
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