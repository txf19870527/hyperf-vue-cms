<?php

declare(strict_types=1);

namespace App\Command;

use App\Com\Log;
use App\Model\Admin;
use App\Service\AdminService;
use App\Service\Interfaces\AdminServiceInterface;
use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Command\Annotation\Command;
use Psr\Container\ContainerInterface;

/**
 * @Command
 */
class ResetSuperAdminCommand extends HyperfCommand
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        parent::__construct('reset_admin:command');
    }

    public function configure()
    {
        parent::configure();
        $this->setDescription('重置超级管理员信息');
    }

    /**
     * 超管信息只能通过脚本重置
     * @return bool
     */
    public function handle()
    {

        /**************配置重置信息****************/

        $resetData = [
            'name' => '1',
            'mobile' => '1',
            'password' => '1',
            'login_error_times' => 0
        ];

        $id = 1;

        /*************配置重置信息**************/


        $adminService = new AdminService();
        $adminService->resetSuperAdmin($resetData, $id);

        $this->line("重置成功");



        return true;
    }
}
