<?php
/*
* ProductSpecification.php - Model file
*
* This file is part of the Product component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Product\Models;

use App\Yantrana\Core\BaseModel;

class ProductSpecification extends BaseModel
{
    /**
     * @var string - The database table used by the model.
     */
    protected $table = 'product_specifications';

    /**
     * @var array - The attributes that should be casted to native types..
     */
    protected $casts = [
        '_id' => 'integer',
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
     * Fetch option set exist for this product.
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function product()
    {
        return $this->belongsTo(Product::class, 'products_id', 'id');
    }
}
