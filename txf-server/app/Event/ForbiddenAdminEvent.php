<?php


namespace App\Event;


/**
 * 员工删除/禁用
 * Class ForbiddenAdminEvent
 * @package App\Event
 */
class ForbiddenAdminEvent
{
    /**
     * @var array
     */
    public $adminIds;


    /**
     * ForbiddenAdminEvent constructor.
     * @param array $adminIds
     */
    public function __construct(array $adminIds)
    {
        // php bin/hyperf.php gen:listener ForbiddenAdminListener

        $this->adminIds = $adminIds;
    }

}