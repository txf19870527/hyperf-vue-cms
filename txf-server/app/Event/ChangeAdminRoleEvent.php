<?php


namespace App\Event;

/**
 * 员工角色变更
 * Class ChangeAdminRoleEvent
 * @package App\Event
 */
class ChangeAdminRoleEvent
{
    /**
     * @var array
     * [$adminId => $roles...]
     */
    public $adminRoles;


    /**
     * ChangeAdminRoleEvent constructor.
     * @param array $adminRoles
     */
    public function __construct(array $adminRoles)
    {
        // php bin/hyperf.php gen:listener ChangeAdminRoleListener

        $this->adminRoles = $adminRoles;

    }
}