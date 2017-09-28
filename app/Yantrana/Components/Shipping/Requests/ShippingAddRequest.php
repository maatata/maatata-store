<?php
/*
* ShippingAddRequest .php - Request file
*
* This file is part of the coupon add component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Shipping\Requests;

use Illuminate\Http\Request;
use App\Yantrana\Core\BaseRequest;

class ShippingAddRequest  extends BaseRequest
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
        $rule = [
               'country' => 'required|unique:shipping',
               'type' => 'required',
               'free_after_amount' => 'numeric',
               'amount_cap' => 'numeric|min:0.1',
        ];

        if (Request::input('type') == 1 or Request::input('type') == 2) {
            $rule['charges'] = 'required|numeric|min:0.1';
        }

        return $rule;
    }
}
