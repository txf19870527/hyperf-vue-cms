<?php

declare (strict_types=1);

namespace App\Model;

/**
 * @property int $id
 * @property int $admin_id
 * @property int $role_id
 * @property string $created_at
 * @property string $updated_at
 */
class AdminRole extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_role';
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
}