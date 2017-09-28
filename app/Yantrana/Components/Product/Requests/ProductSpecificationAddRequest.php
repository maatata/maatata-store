<?php
/*
* ProductSpecificationAddRequest.php - Request file
*
* This file is part of the Product component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Product\Requests;

use Illuminate\Http\Request;
use App\Yantrana\Core\BaseRequest;

class ProductSpecificationAddRequest extends BaseRequest
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
        $rules = [];

        $productID = Request::route()->getParameter('productID');
        $values = Request::all();

        if (is_array($values)) {
            foreach ($values as $key => $value) {
                $rules[$key.'.name'] = 'required|unique:product_specifications,name,NULL,_id,products_id,'.$productID;
                $rules[$key.'.value'] = 'required';
            }
        }

        return $rules;
    }

    /**
     * Generate custom messages for validation rules that apply to the add specification option 
     * post request.
     *
     * @return array
     *-----------------------------------------------------------------------*/
    public function messages()
    {
        $messages = [];

        foreach (Request::all() as $key => $val) {
            $messages[$key.'.name.required'] = __('The name field is required.');
            $messages[$key.'.name.unique'] = __('The name field has already been taken.');
            $messages[$key.'.value.required'] = __('The value field is required.');
        }

        return $messages;
    }
}
