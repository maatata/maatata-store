<?php
/*
* CartOrderCouponRequest.php - Request file
*
* This file is part of the ShoppingCart component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\ShoppingCart\Requests;

use Illuminate\Http\Request;
use App\Yantrana\Core\BaseRequest;

class CartOrderCouponRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     *-----------------------------------------------------------------------*/
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the add coupon in cart order post request.
     *
     * @return bool
     *-----------------------------------------------------------------------*/
    public function rules()
    {
        return ['code' => 'required|AlphaDash|min:3|max:10'];
    }
}
