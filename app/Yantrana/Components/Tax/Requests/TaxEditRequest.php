<?php
/*
* TaxAddRequest .php - Request file
*
* This file is part of the coupon add component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Tax\Requests;

use Illuminate\Http\Request;
use App\Yantrana\Core\BaseRequest;

class TaxEditRequest  extends BaseRequest
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
               'label' => 'required',
               'country' => 'required',
               'type' => 'required',
        ];

        if (Request::input('type') == 1) {
            $rule['applicable_tax'] = 'required|numeric|min:0.1';
        } elseif (Request::input('type') == 2) {
            $rule['applicable_tax'] = 'required|numeric|min:0.1';
        }

        return $rule;
    }
}
