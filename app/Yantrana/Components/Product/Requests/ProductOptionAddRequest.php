<?php
/*
* ProductOptionAddRequest.php - Request file
*
* This file is part of the Product component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Product\Requests;

use Illuminate\Http\Request;
use App\Yantrana\Core\BaseRequest;

class ProductOptionAddRequest extends BaseRequest
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
     * Get the validation rules that apply to the add product option 
     * post request.
     *
     * @return array
     *-----------------------------------------------------------------------*/
    public function rules()
    {
        $values = Request::input('values');
        $productID = Request::route()->getParameter('productID');

        $rules = [
            'name' => 'required
            |unique:product_option_labels,name,NULL,id,products_id,'.$productID,
        ];

        if (is_array($values) and !empty($values)) {
            foreach ($values as $key => $value) {
                $rules['values.'.$key.'.name'] = 'required';
                $rules['values.'.$key.'.addon_price'] = 'numeric|min:0';
            }
        }

        return $rules;
    }
}
