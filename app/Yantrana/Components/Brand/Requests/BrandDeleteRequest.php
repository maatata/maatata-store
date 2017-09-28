<?php
/*
* BrandDeleteRequest .php - Request file
*
* This file is part of the brand add component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Brand\Requests;

use Illuminate\Http\Request;
use App\Yantrana\Core\BaseRequest;

class BrandDeleteRequest  extends BaseRequest
{
    /**
     * Secure form.
     *------------------------------------------------------------------------ */
    protected $securedForm = true;

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
     * Get the validation rules that apply to the request.
     *
     * @return bool
     *-----------------------------------------------------------------------*/
    public function rules()
    {
        $rules = [];
        if (Request::has('delete_related_products') and Request::input('delete_related_products')) {
            $rules['current_password'] = 'required|min:6|max:30';
        }

        return $rules;
    }
}
