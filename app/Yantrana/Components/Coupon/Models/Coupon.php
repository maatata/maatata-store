<?php
/*
* Coupon.php - Model file
*
* This file is part of the Coupon component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Coupon\Models;

use App\Yantrana\Core\BaseModel;

class Coupon extends BaseModel
{
    /**
     * @var string - The database table used by the model.
     */
    protected $table = 'coupons';

    /**
     * @var array - The attributes that should be casted to native types.
     */
    protected $casts = [
        '_id' => 'integer',
        'status' => 'integer',
        'discount' => 'float',
        'discount_type' => 'integer',
        'max_discount' => 'integer',
        'minimum_order_amount' => 'float',
    ];

    /**
     * @var array - The attributes that are mass assignable.
     */
    protected $fillable = [];

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = '_id';

    /**
     * Scope a query to only include active brand.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Scope a query to only include active brand.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFetchByID($query, $id)
    {
        return $query->where('_id', $id);
    }

    /**
     * Scope a query to only include active brand.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCode($query, $code)
    {
        return $query->where('code', $code);
    }
}
