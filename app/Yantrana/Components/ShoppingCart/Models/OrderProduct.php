<?php
/*
* order.php - Model file
*
* This file is part of the order component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\ShoppingCart\Models;

use App\Yantrana\Core\BaseModel;

use App\Yantrana\Components\Product\Models\Product;

class OrderProduct extends BaseModel
{
    /**
     * @var string - The database table used by the model.
     */
    protected $table = 'ordered_products';

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
        'products_id' => 'integer',
        'orders__id' => 'integer',
        'price' => 'float',
        'quantity' => 'integer',
        'status' => 'integer',
    ];

    /**
     * @var array - The attributes that are mass assignable.
     */
    protected $fillable = ['_id', 'created_at', 'updated_at', 'products_id', 'orders__id', 'name', 'status', 'quantity'];

    /**
     * Get the orderProductOption record associated with the order_product.
     */
    public function productOption()
    {
        return $this->hasMany(OrderProductOptions::class, 'ordered_products__id')
                            ->select(
                                'ordered_products__id',
                                '_id',
                                'name',
                                'value_name',
                                'addon_price'
                            );
    }

    /**
     * Get the product record associated with the order_product.
     */
    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'products_id')
                            ->select(
                                'id',
                                'thumbnail'
                            );
    }
}
