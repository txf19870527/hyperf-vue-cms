<?php


namespace App\Event;

/**
 * 重新生成角色对应的权限缓存
 * Class ResetRolePermissionEvent
 * @package App\Event
 */
class ResetRolePermissionEvent
{
    /**
     * 需要重置的角色ID
     * @var array
     */
    public $roles;

    /**
     * 重置 | 删除
     * @var string in:reset,del
     */
    public $operate = "reset";      // reset：重置 del：删除


    public function __construct(array $roles, $operate = "reset")
    {

        // php bin/hyperf.php gen:listener ResetRolePermissionListener

        $this->roles = $roles;
        $this->operate = $operate;
    }
}