<?php

declare (strict_types=1);
namespace App\Model;

/**
 * @property int $id 
 * @property int $user_id 
 * @property string $icon 
 * @property string $title 
 * @property int $type 
 */
class UserType extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_type';
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
    protected $casts = ['id' => 'integer', 'user_id' => 'integer', 'type' => 'integer'];
}