<?php
/*
* OrderTax.php - Model file
*
* This file is part of the order component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\ShoppingCart\Models;

use App\Yantrana\Core\BaseModel;
use App\Yantrana\Components\Tax\Models\Tax;

class OrderTax extends BaseModel
{
    /**
     * @var string - The database table used by the model.
     */
    protected $table = 'order_taxes';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = '_id';

    /**
     * @var array - The attributes that should be casted to native types..
     */
    protected $casts = [
        '_id' => 'integer',
        'tax__id' => 'integer',
        'orders__id' => 'integer',
        'amount' => 'float',
    ];

    /**
     * @var array - The attributes that are mass assignable.
     */
    protected $fillable = [];

    /**
     * Get the order taxes.
     */
    public function taxes()
    {
        return $this->hasOne(Tax::class, '_id', 'tax__id')
                            ->where('status', 1)
                            ->select(
                                '_id',
                                'label',
                                'notes',
                                'type'
                            );
    }
}
