<?php

declare(strict_types=1);

namespace App\Command;

use App\Model\Permission;
use App\Service\PermissionService;
use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Command\Annotation\Command;
use Hyperf\Di\Annotation\Inject;
use Psr\Container\ContainerInterface;

/**
 * @Command
 */
class TestCommand extends HyperfCommand
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @Inject()
     * @var PermissionService
     */
    protected $permissionService;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        parent::__construct('test:command');
    }

    public function configure()
    {
        parent::configure();
        $this->setDescription('Test Command');
    }

    public function handle()
    {

        return true;
    }
}
