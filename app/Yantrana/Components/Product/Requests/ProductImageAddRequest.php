<?php
/*
* ProductImageAddRequest.php - Request file
*
* This file is part of the Product component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Product\Requests;

use App\Yantrana\Core\BaseRequest;

class ProductImageAddRequest extends BaseRequest
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
     * Get the validation rules that apply to the add product image post request.
     *
     * @return bool
     *-----------------------------------------------------------------------*/
    public function rules()
    {
        return [
            'title' => 'required',
            'image' => 'required',
            //'description'   => 'required|min:6'
       ];
    }
}
