<?php
/*
* ProductOptionValue.php - Model file
*
* This file is part of the Product component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Product\Models;

use App\Yantrana\Core\BaseModel;

class ProductOptionValue extends BaseModel
{
    /**
     * @var string - The database table used by the model.
     */
    protected $table = 'product_option_values';

    /**
     * @var array - The attributes that should be casted to native types..
     */
    protected $casts = [
        'id' => 'integer',
        'addon_price' => 'float',
        'product_option_labels_id' => 'integer',
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
    protected $touches = ['productOptionLabel'];

    /**
     * Fetch option set exist for this product option.
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function productOptionLabel()
    {
        return $this->belongsTo(ProductOptionLabel::class);
    }
}
