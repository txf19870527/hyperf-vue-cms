<?php

declare (strict_types=1);

namespace App\Model;

/**
 * @property int $id
 * @property string $role_name
 * @property string $description
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 */
class Role extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'role';
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
    protected $casts = [];
    /**
     * @var array
     * 黑名单
     */
    protected $guarded = ['id'];
    public $timestamps = false;

    public function admins()
    {
        return $this->belongsToMany(Admin::class, "admin_role", "role_id", "admin_id");
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, "role_permission", "role_id", "permission_id");
    }
}