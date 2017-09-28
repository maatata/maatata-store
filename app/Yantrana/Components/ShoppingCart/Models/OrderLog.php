<?php
/*
* OrderLog.php - Model file
*
* This file is part of the ShoppingCart component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\ShoppingCart\Models;

use App\Yantrana\Core\BaseModel;

class OrderLog extends BaseModel
{
    /**
     * @var string - The database table used by the model.
     */
    protected $table = 'order_logs';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The generate UID or not.
     *
     * @var string
     *----------------------------------------------------------------------- */
    protected $isGenerateUID = false;

    /**
     * @var array - The attributes that should be casted to native types..
     */
    protected $casts = [
        '_id' => 'integer',
        'users_id' => 'integer',
        'ordered_products__id' => 'integer',
        'orders__id' => 'integer',
    ];

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = '_id';

    /**
     * @var array - The attributes that are mass assignable.
     */
    protected $fillable = ['created_at', 'updated_at', 'orders__id', 'description', 'users_id', 'ip_address'];
}
