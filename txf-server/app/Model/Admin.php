<?php

declare (strict_types=1);
namespace App\Model;

/**
 * @property int $id 
 * @property string $name 
 * @property string $mobile 
 * @property string $password 
 * @property string $salt 
 * @property int $status 
 * @property string $last_login_time 
 * @property int $login_error_times 
 * @property string $created_at 
 * @property string $updated_at 
 */
class Admin extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin';
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
    protected $casts = ['id' => 'integer', 'status' => 'integer', 'login_error_times' => 'integer'];
    public function __construct(array $attributes = [])
    {
        $attributes['last_login_time'] = date_time_now();
        parent::__construct($attributes);
    }
    /**
     * @var array
     * 黑名单
     */
    protected $guarded = ['id'];
    public $timestamps = false;
    public function roles()
    {
        return $this->belongsToMany(Role::class, "admin_role", "admin_id", "role_id");
    }
}