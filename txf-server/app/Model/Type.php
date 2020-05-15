<?php

declare (strict_types=1);
namespace App\Model;

/**
 * @property int $id 
 * @property string $icon 
 * @property string $title 
 * @property int $type 
 * @property int $sort 
 */
class Type extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'type';
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
    protected $casts = ['id' => 'integer', 'type' => 'integer', 'sort' => 'integer'];
}