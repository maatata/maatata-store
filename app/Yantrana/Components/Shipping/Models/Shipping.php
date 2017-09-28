<?php
/*
* Shipping.php - Model file
*
* This file is part of the Shipping component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Shipping\Models;

use App\Yantrana\Core\BaseModel;

class Shipping extends BaseModel
{
    /**
     * @var string - The database table used by the model.
     */
    protected $table = 'shipping';

    /**
     * @var array - The attributes that should be casted to native types.
     */
    protected $casts = [
        '_id' => 'integer',
        'status' => 'integer',
        'type' => 'integer',
        'charges' => 'float',
        'free_after_amount' => 'float',
        'amount_cap' => 'float',
    ];

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = '_id';

    /**
     * Scope a query to only include ids shipping.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    /*public function scopeFetchByContry($query, $country)
    {
        return $query->whereIn('country', $country);
    }*/

    /**
     * Scope a query to only include ids shipping.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFetchByCountry($country)
    {
        return $this->where('country', $country);
    }

    /**
     * Define float format of shipping table Charges attribute.
     *
     * @param $amount
     */
    public function setChargesAttribute($amount)
    {
        $this->attributes['charges'] = formatAmount($amount);
    }

    /**
     * Define float format of shipping table amount cap attribute.
     *
     * @param $amount
     */
    public function setAmountCapAttribute($amount)
    {
        $this->attributes['amount_cap'] = formatAmount($amount);
    }

    /**
     * Define float format of shipping table free after amount attribute.
     *
     * @param $amount
     */
    public function setFreeAfterAmountAttribute($amount)
    {
        $this->attributes['free_after_amount'] = formatAmount($amount);
    }

    /**
     * @var array - The attributes that are mass assignable.
     */
    protected $fillable = [];
}
