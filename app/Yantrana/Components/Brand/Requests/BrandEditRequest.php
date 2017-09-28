<?php
/*
* BrandEditRequest .php - Request file
*
* This file is part of the brand add component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Brand\Requests;

use Illuminate\Http\Request;
use App\Yantrana\Core\BaseRequest;

class BrandEditRequest  extends BaseRequest
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
        $id = Request::input('_id');

        return [
               'name' => "required|min:2|unique:brands,name,$id,_id",
        ];
    }
}
