<?php
/*
* ShoppingCart.php - Model file
*
* This file is part of the ShoppingCart component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\ShoppingCart\Models;

use App\Yantrana\Core\BaseModel;

class ShoppingCart extends BaseModel
{
    /**
     * @var string - The database table used by the model.
     */
    protected $table = 'shopping_carts';

    /**
     * @var array - The attributes that should be casted to native types..
     */
    protected $casts = [];

    /**
     * @var array - The attributes that are mass assignable.
     */
    protected $fillable = [];
}
