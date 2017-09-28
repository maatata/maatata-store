<?php
/*
* ManageProductEditRequest.php - Request file
*
* This file is part of the Product component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Product\Requests;

use Illuminate\Http\Request;
use App\Yantrana\Core\BaseRequest;

class ManageProductEditRequest extends BaseRequest
{
    protected $looseSanitizationFields = ['description' => ''];

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
     * Get the validation rules that apply to the edit product details
     * post request.
     *
     * @return bool
     *-----------------------------------------------------------------------*/
    public function rules()
    {
        $productID = Request::route()->getParameter('productID');

        return [
            'name' => 'required',
            'product_id' => 'required|alpha_dash|unique:products,product_id,'.$productID.',id',
            'old_price' => 'numeric|min:1',
            'price' => 'required|numeric|min:0.1',
            'categories' => 'required',
            'description' => 'required|min:10',
            'active' => 'required',
       ];
    }
}
