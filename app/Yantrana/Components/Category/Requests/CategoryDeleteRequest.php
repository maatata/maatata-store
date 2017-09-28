<?php
/*
* CategoryDeleteRequest.php - Request file
*
* This file is part of the Category component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Category\Requests;

use App\Yantrana\Core\BaseRequest;

class CategoryDeleteRequest extends BaseRequest
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
     * Get the validation rules that apply to the add author client post request.
     *
     * @return bool
     *-----------------------------------------------------------------------*/
    public function rules()
    {
        return [
              'password' => 'required|min:6|max:30',
        ];
    }
}
