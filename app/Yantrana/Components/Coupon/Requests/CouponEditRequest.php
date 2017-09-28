<?php
/*
* CouponEditRequest .php - Request file
*
* This file is part of the coupon add component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Coupon\Requests;

use Illuminate\Http\Request;
use App\Yantrana\Core\BaseRequest;

class CouponEditRequest  extends BaseRequest
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
     * Get the validation rules that apply to the add author client post request.
     *
     * @return bool
     *-----------------------------------------------------------------------*/
    public function rules()
    {
        $id = Request::input('_id');

        $rule = [
               'code' => "required|alpha_num|min:3|max:10|unique:coupons,code,$id,_id",
               'title' => 'required|min:3',
               'start' => 'required',
               'end' => 'required|after:start',
               'discount_type' => 'required',
              'minimum_order_amount' => 'required|numeric|min:0.1',
        ];

        if (Request::input('discount_type') == 2) {
            $rule['max_discount'] = 'numeric|min:0.1';
            $rule['discount'] = 'required|numeric|min:0.1:max:99';
        } else {
            $rule['max_discount'] = 'required|numeric|min:0.1';
            $rule['discount'] = 'required|numeric|min:0.1';
        }

        return $rule;
    }
}
