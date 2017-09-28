<?php
/*
* order.php - Model file
*
* This file is part of the order component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\ShoppingCart\Models;

use App\Yantrana\Core\BaseModel;
use App\Yantrana\Components\User\Models\User;
use App\Yantrana\Components\User\Models\Address;
use App\Yantrana\Components\Coupon\Models\Coupon;

class Order extends BaseModel
{
    /**
     * @var string - The database table used by the model.
     */
    protected $table = 'orders';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = '_id';

    /**
     * All of the relationships to be touched.
     *
     * @var array
     */

    /**
     * @var array - The attributes that should be casted to native types..
     */
    protected $casts = [
        '_id' => 'integer',
        'status' => 'integer',
        'type' => 'integer',
        'users_id' => 'integer',
        'coupons__id' => 'integer',
        'addresses_id' => 'integer',
        'addresses_id1' => 'integer',
        'payment_method' => 'integer',
        'total_amount' => 'float',
        'discount_amount' => 'float',
        'shipping_amount' => 'float',
    ];

    /**
     * @var array - The attributes that are mass assignable.
     */
    protected $fillable = [];

    /**
     * Caching Ids related to this model which may need to clear on add/update/delete.
     *
     * @var string
     *----------------------------------------------------------------------- */
    protected $cacheIds = [
        'cache.order.all.new.active.count',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Get the user record associated with the order.
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'users_id')
                    ->select('id', 'fname', 'lname', 'email');
    }

    /**
     * Get the coupon record associated with the order.
     */
    public function coupon()
    {
        return $this->hasOne(Coupon::class, '_id', 'coupons__id')
                    ->where('status', 1)
                    ->select('_id', 'code', 'title', 'description');
    }

    /**
     * Get the order product that owns the order.
     */
    public function orderProduct()
    {
        return $this->hasMany(OrderProduct::class, 'orders__id')->with('productOption', 'product')
                            ->select(
                                'orders__id',
                                '_id',
                                'products_id',
                                'name',
                                'status',
                                'price',
                                'quantity',
                                'custom_product_id'
                            );
    }

    /**
     * Get the order taxes.
     */
    public function orderTaxes()
    {
        return $this->hasMany(OrderTax::class, 'orders__id')->with('taxes')
                            ->select(
                                'orders__id',
                                '_id',
                                'tax__id',
                                'amount'
                            );
    }

    /**
     * Get the order payment.
     */
    public function orderPayment()
    {
        return $this->hasOne(OrderPayments::class, 'orders__id')
                            ->select(
                                '_id',
                                'orders__id',
                                'type'
                            );
    }

    /**
     * Get the address that owns the order.
     */
    public function address()
    {
        return $this->hasMany(Address::class, 'id', 'addresses_id')
                    ->select('id', 'type', 'address_line_1', 'address_line_2', 'city', 'state', 'country', 'pin_code', 'countries__id');
    }

    /**
     * Get the address1 that owns the order.
     */
    public function address1()
    {
        return $this->hasMany(Address::class, 'id', 'addresses_id1')
                    ->select('id', 'type', 'address_line_1', 'address_line_2', 'city', 'state', 'country', 'pin_code', 'countries__id');
    }

    /**
     * Define float format for order table shipping amount attribute.
     *
     * @param $amount
     */
    public function setShippingAmountAttribute($amount)
    {
        $this->attributes['shipping_amount'] = formatAmount($amount);
    }

    /**
     * Define float format of order table discount amount attribute.
     *
     * @param $amount
     */
    public function setDiscountAmountAttribute($amount)
    {
        $this->attributes['discount_amount'] = formatAmount($amount);
    }

    /**
     * Define float format of order table total amount attribute.
     *
     * @param $amount
     */
    public function setTotalAmountAttribute($amount)
    {
        $this->attributes['total_amount'] = formatAmount($amount);
    }
}
