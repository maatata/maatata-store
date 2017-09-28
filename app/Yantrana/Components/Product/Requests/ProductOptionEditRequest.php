<?php
/*
* ProductOptionEditRequest.php - Request file
*
* This file is part of the Product component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Product\Requests;

use Illuminate\Http\Request;
use App\Yantrana\Core\BaseRequest;

class ProductOptionEditRequest extends BaseRequest
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
     * Get the validation rules that apply to the edit product option 
     * post request.
     *
     * @return bool
     *-----------------------------------------------------------------------*/
    public function rules()
    {
        $productID = Request::route()->getParameter('productID');
        $optionID = Request::route()->getParameter('optionID');

        return [
            'name' => 'required
            |unique:product_option_labels,name,'.$optionID.',id,products_id,'
            .$productID,
        ];
    }
}
