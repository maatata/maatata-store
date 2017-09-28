<?php
/*
* OrderProductOptions.php - Model file
*
* This file is part of the order component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\ShoppingCart\Models;

use App\Yantrana\Core\BaseModel;

class OrderProductOptions extends BaseModel
{
    /**
     * @var string - The database table used by the model.
     */
    protected $table = 'ordered_product_options';

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
        'ordered_products__id' => 'integer',
        'addon_price' => 'float',
    ];

    /**
     * @var array - The attributes that are mass assignable.
     */
    protected $fillable = [];
}
