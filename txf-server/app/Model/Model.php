<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

namespace App\Model;

use App\Com\ResponseCode;
use App\Exception\BusinessException;
use Hyperf\Database\Model\Events\Deleting;
use Hyperf\DbConnection\Model\Model as BaseModel;

//use Hyperf\ModelCache\Cacheable;
//use Hyperf\ModelCache\CacheableInterface;

abstract class Model extends BaseModel /*implements CacheableInterface*/
{
    //    use Cacheable;

    /**
     * 删除统一用模型操作，不要用 qb 操作
     * @param Deleting $event
     * @return bool
     */
    public function deleting(Deleting $event)
    {
        $model = $event->getModel();

        if ($model instanceof Admin) {

            $data = $model->toArray();

            if (in_array($data['id'], config("admin.super_admin_id"))) {
                throw new BusinessException(ResponseCode::CAN_NOT_DEL);
            }

        } elseif ($model instanceof DelBak) {
            throw new BusinessException(ResponseCode::CAN_NOT_DEL);
        } elseif ($model instanceof Permission) {

            $data = $model->toArray();

            // 存在子权限不允许删除
            if (Permission::query()->where("pid", $data['id'])->count()) {
                throw new BusinessException(ResponseCode::CUSTOM_ERROR, "{$data['title']}存在子权限，不允许删除");
            }

            if (in_array($data['id'], config("admin.super_permission_id"))) {
                throw new BusinessException(ResponseCode::SYSTEM_PERMISSION);
            }
        }

        $table = $model->table;
        $data = $model->toJson();

        DelBak::query()->insert([
            'tb_name' => $table,
            'data' => $data
        ]);


        return true;
    }

    public static function existsInsertData($column, $value)
    {
        // 空值默认可以重复
        if (empty($value)) {
            return true;
        }

        $count = static::query()->where($column, $value)->count();
        return $count ? true : false;
    }

    public static function existsUpdateData($column, $value, $id, $primaryKey = "id")
    {
        if (empty($value)) {
            return true;
        }

        $count = static::query()->where($column, $value)->where($primaryKey, "!=", $id)->count();
        return $count ? true : false;
    }


}
