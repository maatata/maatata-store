<?php
/*
* ManageProductAddRequest.php - Request file
*
* This file is part of the Product component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Product\Requests;

use App\Yantrana\Core\BaseRequest;

class ManageProductAddRequest extends BaseRequest
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
     * Get the validation rules that apply to the add product post request.
     *
     * @return bool
     *-----------------------------------------------------------------------*/
    public function rules()
    {
        return [
            'name' => 'required',
            'product_id' => 'required|alpha_dash|unique:products,product_id',
            'old_price' => 'numeric',
            'price' => 'required|numeric|min:0.1',
            'image' => 'required',
            'categories' => 'required',
            'description' => 'required|min:6',
       ];
    }
}
