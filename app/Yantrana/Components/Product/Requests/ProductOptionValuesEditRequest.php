<?php
/*
* ProductOptionValuesEditRequest.php - Request file
*
* This file is part of the Product component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Product\Requests;

use App\Yantrana\Core\BaseRequest;
use Illuminate\Http\Request;

class ProductOptionValuesEditRequest extends BaseRequest
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
     * Get the validation rules that apply to the edit product option values 
     * post request.
     *
     * @return bool
     *-----------------------------------------------------------------------*/
    public function rules()
    {
        $rules = [];

        $optionID = Request::route()->getParameter('optionID');
        $values = Request::input('values');

        if (is_array($values)) {
            foreach ($values as $key => $value) {
                $rules['values.'.$key.'.name'] = 'required
                    |unique:product_option_values,name,'.!empty($value['id']) ? '' : $value['id'].',id,product_option_labels_id,'.$optionID;

                $rules['values.'.$key.'.addon_price'] = 'numeric|min:0';
            }
        }

        return $rules;
    }
}
