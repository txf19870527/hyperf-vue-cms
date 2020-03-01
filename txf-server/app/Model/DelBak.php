<?php

declare (strict_types=1);
namespace App\Model;

/**
 * @property int $id 
 * @property string $tb_name 
 * @property string $data 
 * @property string $created_at 
 */
class DelBak extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'del_bak';
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
    protected $casts = ['id' => 'integer'];
    public $timestamps = false;
}