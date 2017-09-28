<?php
/*
* ShippingAddRequest .php - Request file
*
* This file is part of the coupon add component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Shipping\Requests;

use Illuminate\Http\Request;
use App\Yantrana\Core\BaseRequest;

class ShippingEditRequest  extends BaseRequest
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
               'country' => 'required',
               'type' => 'required',
        ];

        if (Request::input('type') == 1) {
            $rule['charges'] = 'required|numeric|min:0.1';
            $rule['free_after_amount'] = 'numeric';
        } elseif (Request::input('type') == 2) {
            $rule['charges'] = 'required|numeric|min:0.1';
            $rule['amount_cap'] = 'numeric|min:0.1';
        }

        return $rule;
    }
}
