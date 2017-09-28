<?php
/*
* OrderPayments.php - Model file
*
* This file is part of the ShoppingCart component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\ShoppingCart\Models;

use App\Yantrana\Components\ShoppingCart\Models\Order as OrderModel;
use App\Yantrana\Core\BaseModel;

class OrderPayments extends BaseModel
{
    /**
     * @var string - The database table used by the model.
     */
    protected $table = 'order_payments';

    /**
     * The custom primary key.
     *
     * @var string
     *----------------------------------------------------------------------- */
    protected $primaryKey = '_id';

    /**
     * @var array - The attributes that should be casted to native types.
     */
    protected $casts = [
        '_id' => 'integer',
        'orders__id' => 'integer',
        'gross_amount' => 'float',
        'fee' => 'float',
        'status' => 'integer',
        'type' => 'integer',
    ];

    /**
     * @var array - The attributes that are mass assignable.
     */
    protected $fillable = [];

    /**
     * Get the order record associated with the order payment.
     */
    public function order()
    {
        return $this->hasOne(OrderModel::class, '_id', 'orders__id')
                    ->select('_id', 'order_uid', 'total_amount');
    }
}
