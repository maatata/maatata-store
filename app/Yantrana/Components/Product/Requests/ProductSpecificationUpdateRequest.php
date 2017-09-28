<?php
/*
* ProductSpecificationUpdateRequest.php - Request file
*
* This file is part of the Product component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Product\Requests;

use App\Yantrana\Core\BaseRequest;
use Illuminate\Http\Request;

class ProductSpecificationUpdateRequest extends BaseRequest
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
        $productID = Request::route()->getParameter('productID');
        $specificationID = Request::route()->getParameter('specificationID');

        return  [
            'name' => 'required|unique:product_specifications,name,'.$specificationID.',_id,products_id,'.$productID,
            'value' => 'required',
        ];
    }
}
