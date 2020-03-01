<?php

declare (strict_types=1);
namespace App\Model;

/**
 * @property int $id 
 * @property int $pid 
 * @property int $type 
 * @property string $icon 
 * @property string $title 
 * @property string $index 
 * @property string $uri 
 * @property int $status 
 * @property int $sort 
 * @property string $created_at 
 * @property string $updated_at 
 */
class Permission extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'permission';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'pid' => 'integer', 'type' => 'integer', 'status' => 'integer', 'sort' => 'integer'];
    /**
     * @var array
     * 黑名单
     */
    protected $guarded = ['id'];
    public $timestamps = false;
    public function roles()
    {
        return $this->belongsToMany(Role::class, "role_permission", "permission_id", "role_id");
    }
}