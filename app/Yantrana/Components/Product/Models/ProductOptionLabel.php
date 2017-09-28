<?php
/*
* ProductOptionLabel.php - Model file
*
* This file is part of the Product component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Product\Models;

use App\Yantrana\Core\BaseModel;

class ProductOptionLabel extends BaseModel
{
    /**
     * @var string - The database table used by the model.
     */
    protected $table = 'product_option_labels';

    /**
     * @var array - The attributes that should be casted to native types..
     */
    protected $casts = [
        'id' => 'integer',
        'products_id' => 'integer',
    ];

    /**
     * @var array - The attributes that are mass assignable.
     */
    protected $fillable = [];

    /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = ['product'];

    /**
     * This method define the relationship b/w option & its values.
     *
     * @return array
     *---------------------------------------------------------------- */
    public function optionValues()
    {
        return $this->hasMany(ProductOptionValue::class, 'product_option_labels_id', 'id')
                    ->select('product_option_labels_id', 'id', 'name', 'addon_price');
    }

     /**
      * This method define for scope products.
      *
      * @return array
      *---------------------------------------------------------------- */
     public function scopeProductID($query, $productID)
     {
         return $query->where('products_id', $productID);
     }

    /**
     * Fetch option set exist for this product.
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function product()
    {
        return $this->belongsTo(Product::class, 'products_id', 'id');
    }
}
